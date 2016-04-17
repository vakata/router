# vakata\router\Router
A minimal routing class.

## Methods

| Name | Description |
|------|-------------|
|[__construct](#vakata\router\router__construct)|Create an instance.|
|[group](#vakata\router\routergroup)|Group a few routes together (when sharing a common prefix)|
|[add](#vakata\router\routeradd)|Add a route. All params are optional and each of them can be omitted independently.|
|[get](#vakata\router\routerget)|Shortcut for add('GET', $url, $handler)|
|[report](#vakata\router\routerreport)|Shortcut for add('REPORT', $url, $handler)|
|[post](#vakata\router\routerpost)|Shortcut for add('POST', $url, $handler)|
|[head](#vakata\router\routerhead)|Shortcut for add('HEAD', $url, $handler)|
|[put](#vakata\router\routerput)|Shortcut for add('PUT', $url, $handler)|
|[patch](#vakata\router\routerpatch)|Shortcut for add('PATCH', $url, $handler)|
|[delete](#vakata\router\routerdelete)|Shortcut for add('DELETE', $url, $handler)|
|[options](#vakata\router\routeroptions)|Shortcut for add('OPTIONS', $url, $handler)|
|[isEmpty](#vakata\router\routerisempty)|Are there any routes registered in the instances|
|[base](#vakata\router\routerbase)|return the base part of the URL (that is not evaluated by the router)|
|[url](#vakata\router\routerurl)|convert a router-relative path to a server absolute path|
|[exists](#vakata\router\routerexists)|check if a URL would be matched by any routes in the router|
|[path](#vakata\router\routerpath)|Return the path of a given request with the base stripped off.|
|[segments](#vakata\router\routersegments)|Get all the relevant segments from a path string.|
|[segment](#vakata\router\routersegment)|Get a relevant path segment by index.|
|[run](#vakata\router\routerrun)|Runs the router with the specified input, invokes the registered callbacks (if a match is found)|

---



### vakata\router\Router::__construct
Create an instance.  
You can specify the optional base parameter, that will be stripped if found at the begining of any URL.  
If you set $base to `true` the router will try to autodetect its base.

```php
public function __construct (  
    string|boolean $base  
)   
```

|  | Type | Description |
|-----|-----|-----|
| `$base` | `string`, `boolean` | optional parameter indicating a common part of all the URLs that will be run |

---


### vakata\router\Router::group
Group a few routes together (when sharing a common prefix)  


```php
public function group (  
    string $prefix,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$prefix` | `string` | the common prefix |
| `$handler` | `callable` | a function to add the actual routes from, receives the router object as parameter |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::add
Add a route. All params are optional and each of them can be omitted independently.  


```php
public function add (  
    array|string $method,  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$method` | `array`, `string` | HTTP verbs for which this route is valid |
| `$url` | `string` | the route URL (check the usage docs for information on supported formats) |
| `$handler` | `callable` | the handler to execute when the route is matched |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::get
Shortcut for add('GET', $url, $handler)  


```php
public function get (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::report
Shortcut for add('REPORT', $url, $handler)  


```php
public function report (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::post
Shortcut for add('POST', $url, $handler)  


```php
public function post (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::head
Shortcut for add('HEAD', $url, $handler)  


```php
public function head (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::put
Shortcut for add('PUT', $url, $handler)  


```php
public function put (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::patch
Shortcut for add('PATCH', $url, $handler)  


```php
public function patch (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::delete
Shortcut for add('DELETE', $url, $handler)  


```php
public function delete (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::options
Shortcut for add('OPTIONS', $url, $handler)  


```php
public function options (  
    string $url,  
    callable $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$url` | `string` |  |
| `$handler` | `callable` |  |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::isEmpty
Are there any routes registered in the instances  


```php
public function isEmpty () : boolean    
```

|  | Type | Description |
|-----|-----|-----|
|  |  |  |
| `return` | `boolean` | `true` if there are no routes registered |

---


### vakata\router\Router::base
return the base part of the URL (that is not evaluated by the router)  


```php
public function base () : string    
```

|  | Type | Description |
|-----|-----|-----|
|  |  |  |
| `return` | `string` | the base URL |

---


### vakata\router\Router::url
convert a router-relative path to a server absolute path  


```php
public function url (  
    string $path,  
    array $params  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$path` | `string` | the path to convert (defaults to an empty string) |
| `$params` | `array` | optional GET parameters to append |
|  |  |  |
| `return` | `string` | the server path |

---


### vakata\router\Router::exists
check if a URL would be matched by any routes in the router  


```php
public function exists (  
    string $request,  
    string $method  
) : boolean    
```

|  | Type | Description |
|-----|-----|-----|
| `$request` | `string` | the URL to check |
| `$method` | `string` | for which method to check (defaults to "GET") |
|  |  |  |
| `return` | `boolean` | would the URL match if it is ran |

---


### vakata\router\Router::path
Return the path of a given request with the base stripped off.  


```php
public function path (  
    string $request  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$request` | `string` | the request path to parse (optional, defaults to the current run, if router was run) |
|  |  |  |
| `return` | `string` | the parsed request path |

---


### vakata\router\Router::segments
Get all the relevant segments from a path string.  


```php
public function segments (  
    string $request  
) : array    
```

|  | Type | Description |
|-----|-----|-----|
| `$request` | `string` | the full path (optional, defaults to the current run, if router was run) |
|  |  |  |
| `return` | `array` | the parsed segments |

---


### vakata\router\Router::segment
Get a relevant path segment by index.  


```php
public function segment (  
    int $i,  
    string $request  
) : string    
```

|  | Type | Description |
|-----|-----|-----|
| `$i` | `int` | the desired index |
| `$request` | `string` | a full path (optional, defaults to the current run, if router was run) |
|  |  |  |
| `return` | `string` | the segment at that index or null |

---


### vakata\router\Router::run
Runs the router with the specified input, invokes the registered callbacks (if a match is found)  


```php
public function run (  
    string $request,  
    string $verb,  
    array $args  
) : mixed    
```

|  | Type | Description |
|-----|-----|-----|
| `$request` | `string` | the path to check |
| `$verb` | `string` | the HTTP verb to check (defaults to GET) |
| `$args` | `array` | additional parameters to pass to all handlers |
|  |  |  |
| `return` | `mixed` | if a match is found the result of the callback is returned |

---

