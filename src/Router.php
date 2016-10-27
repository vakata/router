<?php

namespace vakata\router;

/**
 * A minimal routing class.
 */
class Router implements RouterInterface
{
    protected $preprocessors = [];
    protected $routes = [];
    protected $prefix = '';
    protected $base = '';

    /**
     * Set the router base (string that will be stripped if found at the beggining of the URL when running the router)
     * @param  string  $base the string to strip
     * @return  self
     */
    public function setBase(string $base) : RouterInterface
    {
        $this->base = urldecode(trim((string)parse_url($base, PHP_URL_PATH), '/'));
        return $this;
    }
    /**
     * Auteodetects the router's base (string that will be stripped if found at the beggining of any processed URL)
     * @return  self
     */
    public function detectBase() : RouterInterface
    {
        $this->base = trim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
        return $this;
    }
    /**
     * return the base part of the URL (that is not evaluated by the router)
     * @return string the base URL
     */
    public function getBase() : string
    {
        return str_replace('//', '/', '/' . $this->base . '/');
    }
    protected function compile(string $url, bool $full = true) : string
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
    protected function compileSegment(string $url) : string
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
     * Get the current prefix
     * @return string    $prefix the prefix
     */
    public function getPrefix() : string {
        return $this->prefix;
    }
    /**
     * Set the prefix for all future URLs, used mainly internally.
     * @param  string    $prefix the prefix to prepend
     * @return self
     */
    public function setPrefix(string $prefix) : RouterInterface {
        $prefix = trim($prefix, '/');
        $this->prefix = $prefix.(strlen($prefix) ? '/' : '');
        return $this;
    }
    /**
     * Group a few routes together (when sharing a common prefix)
     * @param  string   $prefix  the common prefix
     * @param  callable $handler a function to add the actual routes from, receives the router object as parameter
     * @return self
     */
    public function group(string $prefix, callable $handler) : RouterInterface {
        $this->setPrefix($prefix);
        $handler($this);
        $this->setPrefix('');
        return $this;
    }

    /**
     * Add a route. All params are optional and each of them can be omitted independently.
     * @param  array|string $method  HTTP verbs for which this route is valid
     * @param  string       $url     the route URL (check the usage docs for information on supported formats)
     * @param  callable     $handler the handler to execute when the route is matched
     * @return self
     */
    public function add($method, $url = null, $handler = null) : RouterInterface
    {
        $temp = [ 'method' => [ 'GET', 'POST' ], 'url' => '', 'handler' => null ];
        foreach (func_get_args() as $arg) {
            if (is_callable($arg)) {
                $temp['handler'] = $arg;
            } else if (in_array($arg, ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE', 'PUT', 'OPTIONS', 'REPORT'])) {
                $temp['method'] = $arg;
            } else {
                $temp['url'] = $arg;
            }
        }
        list($method, $url, $handler) = array_values($temp);

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
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function get(string $url, callable $handler) : RouterInterface
    {
        return $this->add('GET', $url, $handler);
    }
    /**
     * Shortcut for add('REPORT', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function report(string $url, callable $handler) : RouterInterface
    {
        return $this->add('REPORT', $url, $handler);
    }
    /**
     * Shortcut for add('POST', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function post(string $url, callable $handler) : RouterInterface
    {
        return $this->add('POST', $url, $handler);
    }
    /**
     * Shortcut for add('HEAD', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function head(string $url, callable $handler) : RouterInterface
    {
        return $this->add('HEAD', $url, $handler);
    }
    /**
     * Shortcut for add('PUT', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function put(string $url, callable $handler) : RouterInterface
    {
        return $this->add('PUT', $url, $handler);
    }
    /**
     * Shortcut for add('PATCH', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function patch(string $url, callable $handler) : RouterInterface
    {
        return $this->add('PATCH', $url, $handler);
    }
    /**
     * Shortcut for add('DELETE', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function delete(string $url, callable $handler) : RouterInterface
    {
        return $this->add('DELETE', $url, $handler);
    }
    /**
     * Shortcut for add('OPTIONS', $url, $handler)
     * @param  string   $url
     * @param  callable $handler
     * @return self
     */
    public function options(string $url, callable $handler) : RouterInterface
    {
        return $this->add('OPTIONS', $url, $handler);
    }

    protected function path(string $request) : string
    {
        $request = $request ?: $this->current;
        $request = urldecode(trim($request, '/'));
        if ($this->base && strpos($request, $this->base) === 0) {
            $request = substr($request, strlen($this->base));
        }
        $request = str_replace('//', '/', '/'.$request.'/');
        return $request;
    }
    protected function segments(string $request) : array
    {
        $request = $this->path($request);
        $arg = array_filter(explode('/', trim($request, '/')));
        $cnt = count($arg) * -1;
        foreach (array_values($arg) as $k => $v) {
            $arg[$cnt + $k] = $v;
        }
        $arg['/'] = $this->getBase();
        $arg['.'] = trim($request, '/');
        return $arg;
    }
    /**
     * Runs the router with the specified input, invokes the registered callbacks (if a match is found)
     * @param  string $request the path to check
     * @param  string $verb    the HTTP verb to check (defaults to GET)
     * @return mixed           if a match is found the result of the callback is returned
     */
    public function run(string $request, string $verb = 'GET')
    {
        $request = $this->path($request);
        $matches = [];
        if (isset($this->routes[$verb])) {
            foreach ($this->routes[$verb] as $route => $handler) {
                if (preg_match($this->compile($route), $request, $matches)) {
                    $segments = $this->segments($request);
                    foreach ($matches as $k => $v) {
                        if (!is_int($k)) {
                            $segments[$k] = trim($v, '/');
                        }
                    }
                    return call_user_func($handler, $segments);
                }
            }
        }
        throw new RouterNotFoundException('No matching route found', 404);
    }
}
