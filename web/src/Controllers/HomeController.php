<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Slim\Views\Twig;

class HomeController
{
    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function show(IRequest $request, IResponse $response): IResponse
    {
        return Twig::fromRequest($request)
            ->render($response, "home.twig", ["rootURL" => getenv("ROOT_URL")]);
    }
}
