<?php

namespace App\Controllers;

use App\Helpers\HttpRequestHelper;
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
        $responseApiExtinguisher = HttpRequestHelper::get("/attention-points/extinguisher");
        $responseApiLocation = HttpRequestHelper::get("/attention-points/location");

        return Twig::fromRequest($request)
            ->render($response, "home.twig", [
                "attentionPointsExtinguisher" => $responseApiExtinguisher->data,
                "attentionPointsLocation" => $responseApiLocation->data,
                "rootURL" => getenv("ROOT_URL")
            ]);
    }
}
