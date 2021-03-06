<?php

namespace Tests\Framework\Http\Pipeline;

use Framework\Http\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class PipelineTest extends TestCase {
    
    public function testPipe(): void {
        $pipeline = new Pipeline();
        
        $pipeline->pipe(new Middleware1());
        $pipeline->pipe(new Middleware2());
        
        //В последнюю очередь в трубу добавляем сгенерированный пустой реквест и финальный Action
        $response = $pipeline(new ServerRequest(), new Response(), new Last());
        
        $this->assertJsonStringEqualsJsonString(
            json_encode(['middleware-1' => 1, 'middleware-2' => 2]),
            $response->getBody()->getContents()
        );
    }
}

class Middleware1
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $next($request->withAttribute('middleware-1', 1));
    }
}

class Middleware2
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $next($request->withAttribute('middleware-2', 2));
    }
}

class Last
{
    public function __invoke(ServerRequestInterface $request)
    {
        //Если дойдем до Last, то в атрибутах должны получить аттрибуты, добавленные в Посредниках 1 и 2
        
        return new JsonResponse($request->getAttributes());
    }
} 