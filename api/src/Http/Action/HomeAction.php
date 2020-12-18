<?php

declare(strict_types=1);

namespace Api\Http\Action;


use Slim\Http\Request;
use Slim\Http\Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = [
            'name'    => 'Api',
            'version' => '1.0',
        ];
        $payload = json_encode($data);

        return $response->withJson($payload);
    }
}