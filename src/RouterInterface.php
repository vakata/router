<?php

namespace vakata\router;

interface RouterInterface
{
    public function setBase(string $base) : RouterInterface;
    public function detectBase() : RouterInterface;
    public function getBase() : string;
    public function getPrefix() : string;
    public function setPrefix(string $prefix) : RouterInterface;
    public function group(string $prefix, callable $handler) : RouterInterface;
    public function add($method, $url = null, $handler = null) : RouterInterface;
    public function get(string $url, callable $handler) : RouterInterface;
    public function report(string $url, callable $handler) : RouterInterface;
    public function post(string $url, callable $handler) : RouterInterface;
    public function head(string $url, callable $handler) : RouterInterface;
    public function put(string $url, callable $handler) : RouterInterface;
    public function patch(string $url, callable $handler) : RouterInterface;
    public function delete(string $url, callable $handler) : RouterInterface;
    public function options(string $url, callable $handler) : RouterInterface;
    public function run(string $request, string $verb = 'GET');
}
