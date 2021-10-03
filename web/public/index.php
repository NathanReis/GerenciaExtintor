<?php

use App\Controllers\ExtinguisherController;
use App\Controllers\HomeController;
use App\Controllers\LocationController;
use App\Utils\PathUtil;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once implode(
    DIRECTORY_SEPARATOR,
    [__DIR__, "..", "vendor", "autoload.php"]
);

// *** WARNING - Using unsafe ***
$dotenv = Dotenv::createUnsafeImmutable(
    PathUtil::resolve(__DIR__, "..")
);
$dotenv->load();

/**
 * @var Slim\App $app
 */
$app = AppFactory::create();

$twig = Twig::create(
    PathUtil::resolve(__DIR__, "..", "src", "Templates"),
    [
        "cache" => getenv("ENV") === "prod"
            ? PathUtil::resolve(__DIR__, "..", "temp")
            : false,
        "strict_variables" => true
    ]
);

$app->add(TwigMiddleware::create($app, $twig));

if (getenv("ENV") === "prod") {
    $app->addErrorMiddleware(false, false, false);
} else {
    $app->addErrorMiddleware(true, true, true);
}

$app
    ->get("/", HomeController::class . ":show")
    ->setName("home");

$app->group("/locations", function (RouteCollectorProxy $group) {
    $group
        ->get("", LocationController::class . ":index")
        ->setName("list-locations");

    $group
        ->get("/new", LocationController::class . ":new")
        ->setName("new-location");

    $group
        ->post("/save", LocationController::class . ":save")
        ->setName("save-location");

    $group
        ->get("/{id:\d}", LocationController::class . ":view")
        ->setName("view-location");

    $group
        ->get("/delete/{id:\d}", LocationController::class . ":delete")
        ->setName("delete-location");
});

$app->group("/extinguishers", function (RouteCollectorProxy $group) {
    $group
        ->get("", ExtinguisherController::class . ":index")
        ->setName("list-extinguishers");

    $group
        ->get("/new", ExtinguisherController::class . ":new")
        ->setName("new-extinguisher");

    $group
        ->post("/save", ExtinguisherController::class . ":save")
        ->setName("save-extinguisher");

    $group
        ->get("/{id:\d}", ExtinguisherController::class . ":view")
        ->setName("view-extinguisher");

    $group
        ->get("/delete/{id:\d}", ExtinguisherController::class . ":delete")
        ->setName("delete-extinguisher");
});

$app->run();
