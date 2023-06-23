<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GoogleNlp\Controller\EntityController;
use GoogleNlp\Controller\IndexController;
use GoogleNlp\Controller\SentimentController;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extension\DebugExtension;

AppFactory::setSlimHttpDecoratorsAutomaticDetection(false);
ServerRequestCreatorFactory::setSlimHttpDecoratorsAutomaticDetection(false);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$twig = Twig::create(__DIR__ . '/../template', ['debug' => true]);
$twig->addExtension(new DebugExtension());
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/dashboard', IndexController::class . ':index');
$app->post('/urlToContent', IndexController::class . ':urlToContent');

$app->get('/sentiment', SentimentController::class . ':index');
$app->post('/sentiment', SentimentController::class . ':analyzeSentiment');
$app->post('/sentimentUrlToContent', SentimentController::class . ':sentimentUrlToContent');


$app->get('/entity', EntityController::class . ':index');
$app->post('/entity', EntityController::class . ':analyzeEntities');
$app->post('/entityUrlToContent', EntityController::class . ':entityUrlToContent');


$app->run();