<?php

namespace vakata\router;

/**
 * A minimal routing class.
 */
class Router
{
    protected $preprocessors = [];
    protected $routes = [];
    protected $prefix = '';
    protected $base = '';
    protected $current = null;

    /**
     * Create an instance.
     * You can specify the optional base parameter, that will be stripped if found at the begining of any URL.
     * If you set $base to `true` the router will try to autodetect its base.
     * @method __construct
     * @param  string|boolean      $base optional parameter indicating a common part of all the URLs that will be run
     */
    public function __construct($base = '')
    {
        if ($base === true) {
            $this->base = trim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
        } else {
            $this->base = urldecode(trim((string)parse_url($base, PHP_URL_PATH), '/'));
        }
    }

    public function compile($url, $full = true)
    {
        $url = array_filter(
            explode('/', trim($url, '/')),
            function ($v) {
                return $v !== '';
            }
        );
        if (!count($url)) {
            return $full ? '(^/+$)u' : '(^/+)u';
        }
        $url = '(^/'.implode('', array_map([$this, 'compileSegment'], $url)).($full ? '$' : '').')u';
        if (@preg_match($url, '') === false) {
            throw new RouterException('Could not compile route regex');
        }

        return $url;
    }
    protected function compileSegment($url)
    {
        $all = preg_match('(^\{[^\}]+\}$)', $url);
        if (!preg_match('(([^{]*)\{([^}]+)\}([^{]*))i', $url)) {
            return '(?:'.preg_quote($url).')/';
        }
        $url = preg_replace_callback(
            '(([^{]*)\{([^}]+)\}([^{]*))i',
            function ($matches) use ($all) {
                $optional = $matches[2][0] === '?';
                if ($optional) {
                    $matches[2] = substr($matches[2], 1);
                }
                $matches[2] = explode(':', $matches[2], 2);
                if (count($matches[2]) === 1) {
                    $matches[2] = (
                        in_array($matches[2][0], ['a', 'i', 'h', '*', '**']) ||
                        !preg_match('(^[a-z]+$)', $matches[2][0])
                    ) ?
                        [$matches[2][0], ''] :
                        ['*', $matches[2][0]];
                }
                list($regex, $group) = $matches[2];
                switch ($regex) {
                    case 'i':
                        $regex = '[0-9]+';
                        break;
                    case 'a':
                        $regex = '[A-Za-z]+';
                        break;
                    case 'h':
                        $regex = '[A-Za-z0-9]+';
                        break;
                    case '*':
                        $regex = '[^/]+';
                        break;
                    case '**':
                        $regex = '[^/]+';
                        $regex = '.*';
                        break;
                    default:
                        $regex = $regex;
                        break;
                }
                $regex = '('.(strlen($group) ? '?P<'.preg_quote($group).'>' : '?:').$regex.')';
                if (!$all) {
                    $regex = $optional ? $regex.'?' : $regex;
                } else {
                    $regex = $optional ? '(?:'.$regex.'/)?' : $regex.'/';
                }

                return preg_quote($matches[1]).$regex.preg_quote($matches[3]);
            },
            $url
        );

        return $url;
    }

    /**
     * Group a few routes together (when sharing a common prefix)
     * @method group
     * @param  string   $prefix  the common prefix
     * @param  callable $handler a function to add the actual routes from, receives the router object as parameter
     * @return self
     */
    public function group($prefix, callable $handler) {
        $prefix = trim($prefix, '/');
        $this->prefix = $prefix.(strlen($prefix) ? '/' : '');
        $handler($this);
        $this->prefix = '';
        return $this;
    }

    /**
     * Add a route. All params are optional and each of them can be omitted independently.
     * @method add
     * @param  array|string $method  HTTP verbs for which this route is valid
     * @param  string       $url     the route URL (check the usage docs for information on supported formats)
     * @param  callable     $handler the handler to execute when the route is matched
     * @return self
     */
    public function add($method, $url = null, $handler = null)
    {
        $args = func_get_args();
        $handler = array_pop($args);
        $url = array_pop($args);
        $method = array_pop($args);

        if (!$method &&
            (is_array($url) || in_array($url, ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE', 'PUT', 'OPTIONS', 'REPORT']))
        ) {
            $method = $url;
            $url = null;
        }

        if (!$url && $this->prefix) {
            $url = $this->prefix;
        } else {
            if (!$url) {
                $url = '{**}';
            }
            $url = $this->prefix.$url;
        }

        if (!$method) {
            $method = ['GET','POST'];
        }
        if (!is_array($method)) {
            $method = [$method];
        }
        if (!is_callable($handler)) {
            throw new RouterException('No valid handler found');
        }

        foreach ($method as $m) {
            if (!isset($this->routes[$m])) {
                $this->routes[$m] = [];
            }
            $this->routes[$m][$url] = $handler;
        }

        return $this;
    }
    /**
     * Shortcut for add('GET', $url, $handler)
     * @method get
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function get($url, callable $handler)
    {
        return $this->add('GET', $url, $handler);
    }
    /**
     * Shortcut for add('REPORT', $url, $handler)
     * @method report
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function report($url, callable $handler)
    {
        return $this->add('REPORT', $url, $handler);
    }
    /**
     * Shortcut for add('POST', $url, $handler)
     * @method post
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function post($url, callable $handler)
    {
        return $this->add('POST', $url, $handler);
    }
    /**
     * Shortcut for add('HEAD', $url, $handler)
     * @method head
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function head($url, callable $handler)
    {
        return $this->add('HEAD', $url, $handler);
    }
    /**
     * Shortcut for add('PUT', $url, $handler)
     * @method put
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function put($url, callable $handler)
    {
        return $this->add('PUT', $url, $handler);
    }
    /**
     * Shortcut for add('PATCH', $url, $handler)
     * @method patch
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function patch($url, callable $handler)
    {
        return $this->add('PATCH', $url, $handler);
    }
    /**
     * Shortcut for add('DELETE', $url, $handler)
     * @method delete
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function delete($url, callable $handler)
    {
        return $this->add('DELETE', $url, $handler);
    }
    /**
     * Shortcut for add('OPTIONS', $url, $handler)
     * @method options
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function options($url, callable $handler)
    {
        return $this->add('OPTIONS', $url, $handler);
    }
    /**
     * Are there any routes registered in the instances
     * @method isEmpty
     * @return boolean `true` if there are no routes registered
     */
    public function isEmpty()
    {
        return count($this->routes) === 0;
    }
    /**
     * return the base part of the URL (that is not evaluated by the router)
     * @method base
     * @return string the base URL
     */
    public function base()
    {
        return '/' . $this->base . '/';
    }
    /**
     * convert a router-relative path to a server absolute path
     * @method url
     * @param  string $path   the path to convert (defaults to an empty string)
     * @param  array  $params optional GET parameters to append
     * @return string         the server path
     */
    public function url($path = '', $params = [])
    {
        $path = $this->base() . ltrim($path, '/');
        if (count($params)) {
            $path .= '?' . http_build_query($params);
        }
        return $path;
    }
    /**
     * check if a URL would be matched by any routes in the router
     * @method exists
     * @param  string $request the URL to check
     * @param  string $method  for which method to check (defaults to "GET")
     * @return boolean         would the URL match if it is ran
     */
    public function exists($request, $method = 'GET') {
        $request = urldecode(trim(parse_url($request, PHP_URL_PATH), '/'));
        if ($this->base && strpos($request, $this->base) === 0) {
            $request = substr($request, strlen($this->base));
        }
        $request = str_replace('//', '/', '/'.$request.'/');

        if (!isset($this->routes[$verb])) {
            return false;
        }
        foreach ($this->routes[$verb] as $route => $handler) {
            if (preg_match($this->compile($route), $request)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Return the path of a given request with the base stripped off.
     * @method path
     * @param  string $request the request path to parse (optional, defaults to the current run, if router was run)
     * @return string          the parsed request path
     */
    public function path($request = null)
    {
        $request = $request ?: $this->current;
        $request = urldecode(trim($request, '/'));
        if ($this->base && strpos($request, $this->base) === 0) {
            $request = substr($request, strlen($this->base));
        }
        $request = str_replace('//', '/', '/'.$request.'/');
        return $request;
    }
    /**
     * Get all the relevant segments from a path string.
     * @method segments
     * @param  string   $request the full path (optional, defaults to the current run, if router was run)
     * @return array             the parsed segments
     */
    public function segments($request = null)
    {
        $request = $this->path($request);
        return explode('/', trim($request, '/'));
    }
    /**
     * Get a relevant path segment by index.
     * @method segment
     * @param  int     $i       the desired index
     * @param  string  $request a full path (optional, defaults to the current run, if router was run)
     * @return string           the segment at that index or null
     */
    public function segment($i, $request = null)
    {
        $request = $this->segments($request);
        $i = (int)$i;
        if ($i < 0) {
            $i = count($request) + $i;
        }
        return isset($request[$i]) ? $request[$i] : null;
    }
    /**
     * Runs the router with the specified input, invokes the registered callbacks (if a match is found)
     * @method run
     * @param  string $request the path to check
     * @param  string $verb    the HTTP verb to check (defaults to GET)
     * @param  array  $args    additional parameters to pass to all handlers
     * @return mixed           if a match is found the result of the callback is returned
     */
    public function run($request, $verb = 'GET', array $args = [])
    {
        if ($this->isEmpty()) {
            throw new RouterNotFoundException('No valid routes', 500);
        }
        $this->current = $request;
        $request = $this->path($request);
        $matches = [];
        if (isset($this->routes[$verb])) {
            foreach ($this->routes[$verb] as $route => $handler) {
                if (preg_match($this->compile($route), $request, $matches)) {
                    $arg = explode('/', trim($request, '/'));
                    $arg[-1] = $this->base();
                    foreach ($matches as $k => $v) {
                        if (!is_int($k)) {
                            $arg[$k] = trim($v, '/');
                        }
                    }
                    array_unshift($args, $arg);
                    return call_user_func_array($handler, $args);
                }
            }
        }
        throw new RouterNotFoundException('No matching route found', 404);
    }
}
