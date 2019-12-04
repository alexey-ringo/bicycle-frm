<?php

namespace App\Http\Action;

use Framework\Http\Message\Response;

/**
 * Description of AboutAction
 *
 * @author alexringo
 */
class AboutAction {
    public function __invoke() {
        return new Response('I am a simple site');
    }
}