<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2019/10/10
 * Time: 16:49
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
//middware
$app->add(function ($request, $response, $next){
    $response->getBody()->write('BEFORE ');
    $response = $next($request, $response); //下一层中间件
    $response->getBody()->write(' AFTER');
    $response->getBody()->write(' the next do');

    return $response;
});
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});
$app->get('/', function () {
    return 'fake';
});
$app->run();
