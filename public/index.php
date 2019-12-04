<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use App\Http\Action;
use Framework\Http\ActionResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\SimpleRouter;

use Framework\Http\Message\Factory\Psr17Factory;
use Framework\Http\Message\Psr7Server\ServerRequestCreator;
use Framework\Http\Message\Response;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$routes = new RouteCollection();

$routes->get('home', '/', Action\HelloAction::class);

$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);

$router = new SimpleRouter($routes);
$resolver = new ActionResolver();

### Running
$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

try {    
    $result = $router->match($request);
    
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());
    
    $response = $action($request);
} catch (RequestNotMatchedException $e){
    $response = new Response('Undefined page', 404);
}

## Postprocessing
$response = $response->withHeader('X-Developer', 'AlexRingo');
    
### Sending
header('HTTP/1.0 ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $name => $values) {
    header($name . ':' . implode(', ', $values));
}
echo $response->getBody();