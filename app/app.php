<?php

use Silex\Provider\AssetServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\DAO\UserDAO;
use WebLinks\DAO\LinkDAO;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new ValidatorServiceProvider());
$app->register(new AssetServiceProvider(), array(
    'assets.version' => "v1"
));
$app->register(new SessionServiceProvider());
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check',
            ),
            'users' => function () use ($app) {
                return new UserDAO($app['db']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/submit', 'ROLE_USER'),
        array('^/admin', 'ROLE_ADMIN'),
    ),
));
$app->register(new FormServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/weblinks.log',
    'monolog.name' => 'WebLinks',
    'monolog.level' => $app['monolog.level']
));

// Register services
$app['dao.user'] = function ($app) {
    return new UserDAO($app['db']);
};
$app['dao.link'] = function ($app) {
    $linkDAO = new LinkDAO($app['db']);
    $linkDAO->setUserDAO($app['dao.user']);
    return $linkDAO;
};

// Register JSON data decoder for JSON requests
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Register error handler
$app->error(function (Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Access denied.';
            break;
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = 'Something went wrong.';
    }
    return $app['twig']->render('error.html.twig', array(
        'message' => $message
    ));
});
