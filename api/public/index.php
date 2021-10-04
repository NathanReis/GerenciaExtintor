<?php

use App\Controllers\ExtinguisherController;
use App\Controllers\LocationController;
use App\Helpers\ResponseHelper;
use App\Utils\PathUtil;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once implode(
    DIRECTORY_SEPARATOR,
    [__DIR__, "..", "vendor", "autoload.php"]
);

Dotenv::createUnsafeImmutable(PathUtil::resolve(__DIR__, ".."))->load();

$app = AppFactory::create();

$customErrorHandler = function (
    IRequest $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app): IResponse {
    return ResponseHelper::getNewFailResponseWithJSON(
        response: $app
            ->getResponseFactory()
            ->createResponse()
            ->withHeader("Access-Control-Allow-Origin", "*")
            ->withHeader("Access-Control-Allow-Methods", "DELETE, GET, POST, PUT"),
        data: "Message: {$exception}",
        statusCode: 500
    );
};

$enableCORS = function (IRequest $request, IRequestHandler $handler): IResponse {
    return $handler
        ->handle($request)
        ->withHeader("Access-Control-Allow-Origin", "*")
        ->withHeader("Access-Control-Allow-Methods", "DELETE, GET, POST, PUT");
};

$app->add($enableCORS);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true)
    ->setDefaultErrorHandler($customErrorHandler);

$app->group("/locations", function (RouteCollectorProxy $group) {
    $group->delete("/{id:\d+}", LocationController::class . ":delete");
    $group->get("", LocationController::class . ":findAll");
    $group->get("/{id:\d+}", LocationController::class . ":findFirstById");
    $group->post("", LocationController::class . ":create");
    $group->put("/{id:\d+}", LocationController::class . ":update");
});

$app->group("/extinguishers", function (RouteCollectorProxy $group) {
    $group->delete("/{id:\d+}", ExtinguisherController::class . ":delete");
    $group->get("", ExtinguisherController::class . ":findAll");
    $group->get("/{id:\d+}", ExtinguisherController::class . ":findFirstById");
    $group->post("", ExtinguisherController::class . ":create");
    $group->put("/{id:\d+}", ExtinguisherController::class . ":update");
});

$app->group("/attention-points", function (RouteCollectorProxy $group) {
    $group->get("/location", LocationController::class . ":listAttentionPoints");
    $group->get("/extinguisher", ExtinguisherController::class . ":listAttentionPoints");
});

$app->run();
