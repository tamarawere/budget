<?php

declare(strict_types=1);

namespace Budget\Core;

use GuzzleHttp\Psr7\Response;

class AppResponse
{

    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response(
            200,
            $this->setAppHeaders()
        );
    }

    private function setAppHeaders()
    {
        return $GLOBALS['config']['app_headers'];
    }
}
