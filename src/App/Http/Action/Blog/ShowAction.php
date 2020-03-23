<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Description of ShowAction
 *
 * @author alexringo
 */
class ShowAction {
    
    public function __invoke(ServerRequestInterface $request, callable $notFound) {
        $id = $request->getAttribute('id');
        if ($id > 2) {
            return $notFound($request);
        }
        return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
    }
}