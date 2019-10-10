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

//middleware
//$app->add(function ($request, $response, $next){
//    $response->getBody()->write('BEFORE ');
//    $response = $next($request, $response); //下一层中间件
//    $response->getBody()->write(' AFTER');
//    $response->getBody()->write(' the next do');
//    return $response;
//});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/', function () {
    return 'fake';
});

$app->group('/utils', function () use ($app) {  //路由组下课分配多个子级路由
    $app->get('/date', function ($request, $response) {
        return $response->getBody()->write(date('Y-m-d H:i:s'));
    });
    $app->get('/time', function ($request, $response) {
        return $response->getBody()->write(time());
    });
})->add(function ($request, $response, $next) { //middleware 追加在该路由组后，只对该路由组生效
    $response->getBody()->write('It is now ');
    $response = $next($request, $response);
    $response->getBody()->write('. Enjoy!');
    return $response;
});

$app->run();
