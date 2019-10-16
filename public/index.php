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

//middleware 统配路由
//$app->add(function ($request, $response, $next){
//    $response->getBody()->write('BEFORE ');
//    $response = $next($request, $response); //下一层中间件
//    $response->getBody()->write(' AFTER');
//    $response->getBody()->write(' the next do');
//    return $response;
//});

//带变量路由
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});

//主域路由
$app->get('/', function () {
    return 'fake';
});

//路由组下课分配多个子级路由
$app->group('/utils', function () use ($app) {
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

//匹配所有路由方式
$app->any('/books/[{id}]', function ($request, $response, $args) {  //匹配所有类型的请求
    // Create new book or list all books
    return json_encode($args);
});
$app->any('/read', 'readBook');    //回调的方式
function readBook()
{
    return json_encode(['my_name' => 'Freeze']);
}

//自定义路由
$app->map(['GET', 'POST'], '/brahms/{param}', function ($request, $response, $args){    //匹配GET/POST的方式
    return json_encode(['Brahms' => $args]);
});



$app->run();
