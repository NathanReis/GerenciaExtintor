<?php

namespace App\Controllers;

use App\Helpers\HttpRequestHelper;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Slim\Views\Twig;

class LocationController
{
    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function index(IRequest $request, IResponse $response): IResponse
    {
        $locations = [];

        $responseApi = HttpRequestHelper::get("/locations");

        if ($responseApi) {
            $locations = $responseApi->data;
        }

        return Twig::fromRequest($request)
            ->render($response, "locations.twig", [
                "locations" => $locations,
                "rootURL" => getenv("ROOT_URL")
            ]);
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function new(IRequest $request, IResponse $response): IResponse
    {
        return Twig::fromRequest($request)
            ->render($response, "location.twig", [
                "location" => null,
                "idErrors" => [],
                "descriptionErrors" => [],
                "isUpdate" => "no",
                "rootURL" => getenv("ROOT_URL")
            ]);
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function save(IRequest $request, IResponse $response): IResponse
    {
        $bodyRequest = $request->getParsedBody();

        $id = isset($bodyRequest["id"]) ? (int)$bodyRequest["id"] : 0;
        $description = trim((string)$bodyRequest["description"]);

        $isUpdate = $bodyRequest["isUpdate"] === "yes";

        $dataToSend = [
            "json" => [
                "description" => $description
            ]
        ];
        $responseApi = null;
        $uri = "/locations";

        if ($isUpdate) {
            $responseApi = HttpRequestHelper::put($uri . "/{$id}", $dataToSend);
        } else {
            $responseApi = HttpRequestHelper::post($uri, $dataToSend);
        }

        if ($responseApi?->success) {
            return $this->index($request, $response);
        }

        $location = new \stdClass();
        $location->id = $id;
        $location->description = $description;

        $idErrors = [];
        $descriptionErrors = [];

        if ($responseApi?->data) {
            $dataApi = $responseApi->data;

            if (isset($dataApi->id)) {
                $idErrors = $dataApi->id;
            }

            if (isset($dataApi->description)) {
                $descriptionErrors = $dataApi->description;
            }
        }

        return Twig::fromRequest($request)
            ->render($response, "location.twig", [
                "location" => $location,
                "idErrors" => $idErrors,
                "descriptionErrors" => $descriptionErrors,
                "isUpdate" => $isUpdate ? "yes" : "no",
                "rootURL" => getenv("ROOT_URL")
            ]);
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function view(IRequest $request, IResponse $response, array $args): IResponse
    {
        $id = (int)$args["id"];

        $location = null;

        $responseApi = HttpRequestHelper::get("/locations/{$id}");

        if ($responseApi) {
            $location = $responseApi->data;
        }

        return Twig::fromRequest($request)
            ->render($response, "location.twig", [
                "location" => $location,
                "idErrors" => [],
                "descriptionErrors" => [],
                "isUpdate" => "yes",
                "rootURL" => getenv("ROOT_URL")
            ]);
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function delete(IRequest $request, IResponse $response, array $args): IResponse
    {
        $id = (int)$args["id"];

        $responseApiToDelete = HttpRequestHelper::delete("/locations/{$id}");

        if ($responseApiToDelete?->success) {
            return $this->index($request, $response);
        }

        $location = null;

        $responseApiToGet = HttpRequestHelper::get("/locations/{$id}");

        if ($responseApiToGet) {
            $location = $responseApiToGet->data;
        }

        $idErrors = [];

        if ($responseApiToDelete?->data) {
            $errorsApiToDelete = $responseApiToDelete->data;

            if (isset($errorsApiToDelete->id)) {
                $idErrors = $errorsApiToDelete->id;
            }
        }

        return Twig::fromRequest($request)
            ->render($response, "location.twig", [
                "location" => $location,
                "idErrors" => $idErrors,
                "descriptionErrors" => [],
                "isUpdate" => "yes",
                "rootURL" => getenv("ROOT_URL")
            ]);
    }
}
