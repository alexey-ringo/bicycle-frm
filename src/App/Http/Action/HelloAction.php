<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Message\Response;

/**
 * Description of HelloAction
 *
 * @author alexringo
 */
class HelloAction {
    public function __invoke(ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new Response('Hello, ' . $name . '!');
    }
}