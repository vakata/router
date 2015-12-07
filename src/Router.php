<?php

namespace vakata\router;

class Router
{
    protected $preprocessors = [];
    protected $routes = [];
    protected $prefix = '';

    protected function compile($url, $full = true)
    {
        $url = array_filter(
            explode('/', trim($url, '/')),
            function ($v) {
                return $v !== '';
            }
        );
        if (!count($url)) {
            return $full ? '(^/+$)ui' : '(^/+)ui';
        }
        $url = '(^/'.implode('', array_map([$this, 'compileSegment'], $url)).($full ? '$' : '').')';
        if (@preg_match($url, '') === false) {
            throw new RouterException('Could not compile route regex');
        }

        return $url;
    }
    protected function compileSegment($url)
    {
        $all = preg_match('(^\{[^\}]+\}$)', $url);
        if (!preg_match('(([^{]*)\{([^}]+)\}([^{]*))i', $url)) {
            return '('.preg_quote($url).')/';
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
                    $matches[2] = in_array($matches[2][0], ['a', 'i', 'h', '*', '**']) || !preg_match('(^[a-z]+$)', $matches[2][0]) ?
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
                $regex = '('.(strlen($group) ? '?P<'.preg_quote($group).'>' : '').$regex.')';
                if (!$all) {
                    $regex = $optional ? $regex.'?' : $regex;
                } else {
                    $regex = $optional ? '('.$regex.'/)?' : $regex.'/';
                }

                return preg_quote($matches[1]).$regex.preg_quote($matches[3]);
            },
            $url
        );

        return $url;
    }

    public function with($prefix = '', callable $handler = null)
    {
        $prefix = trim($prefix, '/');
        $this->prefix = $prefix.(strlen($prefix) ? '/' : '');
        if (isset($handler)) {
            if (!isset($this->preprocessors[$prefix])) {
                $this->preprocessors[$prefix] = [];
            }
            $this->preprocessors[$prefix][] = $handler;
        }
        return $this;
    }
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
    public function get($url, callable $handler)
    {
        return $this->add('GET', $url, $handler);
    }
    public function report($url, callable $handler)
    {
        return $this->add('REPORT', $url, $handler);
    }
    public function post($url, callable $handler)
    {
        return $this->add('POST', $url, $handler);
    }
    public function head($url, callable $handler)
    {
        return $this->add('HEAD', $url, $handler);
    }
    public function put($url, callable $handler)
    {
        return $this->add('PUT', $url, $handler);
    }
    public function patch($url, callable $handler)
    {
        return $this->add('PATCH', $url, $handler);
    }
    public function delete($url, callable $handler)
    {
        return $this->add('DELETE', $url, $handler);
    }
    public function options($url, callable $handler)
    {
        return $this->add('OPTIONS', $url, $handler);
    }
    public function isEmpty()
    {
        return count($this->routes) === 0;
    }

    public function run($request, $verb = 'GET')
    {
        if ($this->isEmpty()) {
            throw new RouterException('No valid routes');
        }
        $request = str_replace('//', '/', '/'.urldecode(trim($request, '/')).'/');
        $matches = [];
        foreach ($this->preprocessors as $route => $handlers) {
            if (preg_match($this->compile($route, false), $request, $matches)) {
                $arg = explode('/', trim($request, '/'));
                foreach ($matches as $k => $v) {
                    if (!is_int($k)) {
                        $arg[$k] = trim($v, '/');
                    }
                }
                foreach ($handlers as $handler) {
                    if (call_user_func($handler, $arg) === false) {
                        return false;
                    }
                }
            }
        }
        if (isset($this->routes[$verb])) {
            foreach ($this->routes[$verb] as $route => $handler) {
                if (preg_match($this->compile($route), $request, $matches)) {
                    $arg = explode('/',trim($request, '/'));
                    foreach ($matches as $k => $v) {
                        if (!is_int($k)) {
                            $arg[$k] = trim($v, '/');
                        }
                    }

                    return call_user_func($handler, $arg);
                }
            }
        }
        throw new RouterException('No matching route found', 404);
    }
}
