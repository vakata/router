# vakata\router\Router
A minimal routing class.

## Methods

| Name | Description |
|------|-------------|
|[with](#vakata\router\routerwith)|Define a prefix for all routes added from now on. Optionally define a callback to execute if the request matches the prefix.|
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
|[run](#vakata\router\routerrun)|Runs the router with the specified input, invokes the registered callbacks (if a match is found)|

---



### vakata\router\Router::with
Define a prefix for all routes added from now on. Optionally define a callback to execute if the request matches the prefix.  


```php
public function with (  
    string $prefix,  
    callable|null $handler  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$prefix` | `string` | the prefix |
| `$handler` | `callable`, `null` | the callback to execute if the request matches the prefix |
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


### vakata\router\Router::run
Runs the router with the specified input, invokes the registered callbacks (if a match is found)  


```php
public function run (  
    string $request,  
    string $verb  
) : mixed    
```

|  | Type | Description |
|-----|-----|-----|
| `$request` | `string` | the path to check |
| `$verb` | `string` | the HTTP verb to check (defaults to GET) |
|  |  |  |
| `return` | `mixed` | if a match is found the result of the callback is returned |

---

