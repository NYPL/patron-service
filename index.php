<?php
require __DIR__ . '/vendor/autoload.php';

use NYPL\Services\Controller\PatronController;
use NYPL\Services\ServiceContainer;
use NYPL\Services\Swagger;
use NYPL\Starter\Service;
use NYPL\Starter\Config;
use NYPL\Starter\ErrorHandler;

try {
    Config::initialize(__DIR__);

    $container = new ServiceContainer();

    $service = new Service($container);

    $service->get('/docs/patron-service', Swagger::class);

    $service->get(
        '/api/v0.1/patrons',
        PatronController::class . ':getPatrons'
    );

    $service->get(
        '/api/v0.1/patrons/{id}',
        PatronController::class . ':getPatron'
    );

    $service->run();
} catch (Exception $exception) {
    ErrorHandler::processShutdownError($exception->getMessage(), $exception);
}
