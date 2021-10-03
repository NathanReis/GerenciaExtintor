<?php

namespace App\Controllers;

use App\Helpers\HttpRequestHelper;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Slim\Views\Twig;

class ExtinguisherController
{
    /**
     * @return array
     */
    private function fetchExtinguisher(int $id): ?object
    {
        $extinguisher = null;

        $responseApi = HttpRequestHelper::get("/extinguishers");

        if ($responseApi?->success) {
            $extinguisher = $responseApi->data;
        }

        return $extinguisher;
    }

    /**
     * @return array
     */
    private function fetchLocations(): array
    {
        $locations = [];

        $responseApi = HttpRequestHelper::get("/locations");

        if ($responseApi?->success) {
            $locations = $responseApi->data;
        }

        return $locations;
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function index(IRequest $request, IResponse $response): IResponse
    {
        $extinguishers = [];

        $responseApi = HttpRequestHelper::get("/extinguishers");

        if ($responseApi) {
            $extinguishers = $responseApi->data;
        }

        return Twig::fromRequest($request)
            ->render($response, "extinguishers.twig", [
                "extinguishers" => $extinguishers,
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
            ->render($response, "extinguisher.twig", [
                "extinguisher" => null,
                "locations" => $this->fetchLocations(),
                "idErrors" => [],
                "locationErrors" => [],
                "validateErrors" => [],
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
        $idLocation = (int)$bodyRequest["idLocation"];
        $validate = $bodyRequest["validate"];

        $isUpdate = $bodyRequest["isUpdate"] === "yes";

        $dataToSend = [
            "json" => [
                "idLocation" => $idLocation,
                "validate" => $validate
            ]
        ];
        $responseApi = null;
        $uri = "/extinguishers";

        if ($isUpdate) {
            $responseApi = HttpRequestHelper::put($uri . "/{$id}", $dataToSend);
        } else {
            $responseApi = HttpRequestHelper::post($uri, $dataToSend);
        }

        if ($responseApi?->success) {
            return $this->index($request, $response);
        }

        $extinguisher = new \stdClass();
        $extinguisher->id = $id;
        $extinguisher->location = new \stdClass();
        $extinguisher->location->id = $idLocation;
        $extinguisher->validate = $validate;

        $idErrors = [];
        $locationErrors = [];
        $validateErrors = [];

        if ($responseApi?->data) {
            $dataApi = $responseApi->data;

            if (isset($dataApi->id)) {
                $idErrors = $dataApi->id;
            }

            if (isset($dataApi->location)) {
                $locationErrors = $dataApi->location;
            }

            if (isset($dataApi->validate)) {
                $validateErrors = $dataApi->validate;
            }
        }

        return Twig::fromRequest($request)
            ->render($response, "extinguisher.twig", [
                "extinguisher" => $extinguisher,
                "locations" => $this->fetchLocations(),
                "idErrors" => $idErrors,
                "locationErrors" => $locationErrors,
                "validateErrors" => $validateErrors,
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

        return Twig::fromRequest($request)
            ->render($response, "extinguisher.twig", [
                "extinguisher" => $this->fetchExtinguisher($id),
                "locations" => $this->fetchLocations(),
                "idErrors" => [],
                "locationErrors" => [],
                "validateErrors" => [],
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

        $responseApi = HttpRequestHelper::delete("/extinguishers/{$id}");

        if ($responseApi?->success) {
            return $this->index($request, $response);
        }

        $idErrors = [];

        if ($responseApi?->data) {
            $errorsApi = $responseApi->data;

            if (isset($errorsApi->id)) {
                $idErrors = $errorsApi->id;
            }
        }

        return Twig::fromRequest($request)
            ->render($response, "extinguisher.twig", [
                "extinguisher" => $this->fetchExtinguisher($id),
                "locations" => $this->fetchLocations(),
                "idErrors" => $idErrors,
                "locationErrors" => [],
                "validateErrors" => [],
                "isUpdate" => "yes",
                "rootURL" => getenv("ROOT_URL")
            ]);
    }
}
