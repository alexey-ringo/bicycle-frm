<?php

namespace App\Http\Action;

use Zend\Diactoros\Response\HtmlResponse;

/**
 * Description of AboutAction
 *
 * @author alexringo
 */
class AboutAction {
    public function __invoke() {
        return new HtmlResponse('I am a simple site');
    }
}