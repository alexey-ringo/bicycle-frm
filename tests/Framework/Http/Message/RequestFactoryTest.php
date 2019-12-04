<?php

namespace Tests\Framework\Http\Message;

use Framework\Http\Message\Request;
use Framework\Http\Message\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    public function testEmpty(): void
    {
        $request = RequestFactory::fromGlobals(
            $queryParams = ['name' => 'Vasya'],
            $parsedBody = ['age' => 23]
        );
        self::assertInstanceOf(Request::class, $request);
        self::assertEquals($queryParams, $request->getQueryParams());
        self::assertEquals($parsedBody, $request->getParsedBody());
    }
}