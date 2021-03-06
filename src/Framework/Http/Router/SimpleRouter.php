<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class SimpleRouter implements RouterInterface 
{    
    private $routes;
    
    public function __construct(RouteCollection $routes) {
        $this->routes = $routes;
    }
    
    //Принимает реквест, обходит все имеющиеся маршруты, матчит на соответствие со всеми правилами
    //и возврящает распарсенный результат в Result
    public function match(ServerRequestInterface $request): Result 
    {
        //Обходим все имеющиеся маршруты
        foreach ($this->routes->getRoutes() as $route) {
            /** @var RegexpRoute $route */
            //Каждый маршрут отправляем на проматчивание в вынесенную в Route match()
            //если у маршрута совпали данные в request с условиями данного маршрута
            if ($result = $route->match($request)) {
                //Возвращяем объект Result 
                return $result;
            }
        }
        throw new RequestNotMatchedException($request);
    }

    public function generate($name, array $params = []): string 
    {
        foreach ($this->routes->getRoutes() as $route) {
            if (null !== $url = $route->generate($name, array_filter($params))) {
                return $url;
            }
        }
        throw new RouteNotFoundException($name, $params);
    }
}