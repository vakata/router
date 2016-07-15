# vakata\router\Router
A minimal routing class.

## Methods

| Name | Description |
|------|-------------|
|[setBase](#vakata\router\routersetbase)|Set the router base (string that will be stripped if found at the beggining of the URL when running the router)|
|[detectBase](#vakata\router\routerdetectbase)|Auteodetects the router's base (string that will be stripped if found at the beggining of any processed URL)|
|[getBase](#vakata\router\routergetbase)|return the base part of the URL (that is not evaluated by the router)|
|[getPrefix](#vakata\router\routergetprefix)|Get the current prefix|
|[setPrefix](#vakata\router\routersetprefix)|Set the prefix for all future URLs, used mainly internally.|
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
|[run](#vakata\router\routerrun)|Runs the router with the specified input, invokes the registered callbacks (if a match is found)|

---



### vakata\router\Router::setBase
Set the router base (string that will be stripped if found at the beggining of the URL when running the router)  


```php
public function setBase (  
    string $base  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$base` | `string` | the string to strip |
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::detectBase
Auteodetects the router's base (string that will be stripped if found at the beggining of any processed URL)  


```php
public function detectBase () : self    
```

|  | Type | Description |
|-----|-----|-----|
|  |  |  |
| `return` | `self` |  |

---


### vakata\router\Router::getBase
return the base part of the URL (that is not evaluated by the router)  


```php
public function getBase () : string    
```

|  | Type | Description |
|-----|-----|-----|
|  |  |  |
| `return` | `string` | the base URL |

---


### vakata\router\Router::getPrefix
Get the current prefix  


```php
public function getPrefix () : string    
```

|  | Type | Description |
|-----|-----|-----|
|  |  |  |
| `return` | `string` | $prefix the prefix |

---


### vakata\router\Router::setPrefix
Set the prefix for all future URLs, used mainly internally.  


```php
public function setPrefix (  
    string $prefix  
) : self    
```

|  | Type | Description |
|-----|-----|-----|
| `$prefix` | `string` | the prefix to prepend |
|  |  |  |
| `return` | `self` |  |

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

