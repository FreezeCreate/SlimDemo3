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
$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    };
};
//unset($app->getContainer()['errorHandler']);  //禁用slim的错误注解器
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

//自定义路由 http://slim3.cc/brahms/read/book
$app->map(['GET', 'POST'], '/brahms/{param}/{some}', function ($request, $response, $args){    //匹配GET/POST的方式
    return json_encode(['Brahms' => $args['param'], 'Mozart' => $args['some']]);
});

//可选路由 http://slim3.cc/grieg/read
$app->get('/grieg[/{do}]', function ($request, $response, $args){
    if (!empty($args['do'])){
        return json_encode(['Grieg' => $args['do']]);
    }
    return json_encode(['Grieg' => 'Default']);
});

//多个可选参数 http://slim3.cc/lang/Lnaglang/34
$app->get('/lang[/{name}[/{age}]]', function ($request, $response, $args){
    if (!empty($args['name'])){
        if (!empty($args['age'])){
            return json_encode(['name' => $args['name'], 'age' => $args['age']]);
        }
        return json_encode(['name' => $args['name']]);
    }
    return json_encode(['data' => 'Default']);
});

//数目不确定的可选参数 http://slim3.cc/download/1879/06/23
$app->get('/download[/{params:.*}]', function($request, $response, $args){
    $params = explode('/', $request->getAttribute('params'));
    return json_encode($params);
});

//正则表达式匹,只有符合规则才会启动路由 http://slim3.cc/lib/90
$app->get('/lib/{id:[0-9]+}', function ($request, $response, $args){
    return json_encode($args);
});

$app->get('/luck/{name}', function ($request, $response ,$args){
    echo "hello," . $args['name'];
})->setName('hello');
//echo $app->router->pathFor('luck', [
//    'name' => 'Rank',
//]);

//使用控制器 需在composer.json里指定控制器的位置"autoload": {
//        "classmap" : [
//            "app/controllers"
//        ]
//    }
//composer install即可
//http://slim3.cc/method1/asd
$app->get('/method1/{read}', '\MyController:method1');

//uri信息
$app->get('/url', function($request, $response, $args){
    $uri = $request->getUri();
    echo $uri.'<br>';
    echo $uri->getScheme().'<br>';
    echo $uri->getAuthority().'<br>';
    echo $uri->getUserInfo().'<br>';
    echo $uri->getHost().'<br>';
    echo $uri->getPort().'<br>';
    echo $uri->getPath().'<br>';
    echo $uri->getBasePath().'<br>';
    echo $uri->getQuery().'<br>';
    echo $uri->getFragment().'<br>';
    echo $uri->getBaseUrl().'<br>';
});

//获取所有请求头信息
$app->get('/getUri', function ($request, $response, $args){
    $headers = $request->getHeaders();
//    $headerValueArray = $request->getHeader('Accept');  //获取单个请求头
//    $headerValueString = $request->getHeaderLine('Accept'); //获取单个请求头的值，返回逗号分隔的字符串
//    echo $headerValueString;die;
    if ($request->hasHeader('Accept')){ //检测请求头
        return json_encode($headers);
    }
});

//获取 HTTP 请求体,详见slim文档
$app->get('/frank/{rank}', function ($request, $response, $args){
    $body = $request->getBody();
    var_dump($body->isSeekable());die;
});

//检测xhr请求
$app->get('/xhr/{name}', function ($request, $response, $args){
    if ($request->isXhr()){
        echo 'xhr'.'<br>';
    }elseif ($request->isGet()){
        echo 'get'.'<br>';
    }
//    $contentType = $request->getContentCharset();
//    var_dump($contentType);die;
});

//获取HTTP响应
$app->get('/res', function ($request, $response, $args){
//    $status = $response->getStatusCode();
//    $newResponse = $response->withStatus(302);
//    echo $status.'<br>';
//    echo $newResponse.'<br>';
//
//    $headers = $response->getHeaders(); //获取所有响应头
//    foreach ($headers as $name => $values) {
//        echo $name . ": " . implode(", ", $values).'<br>';
//    }
//    $headerValueArray = $response->getHeader('Vary');   //获取单个响应头
//    var_dump($headerValueArray);
//    if ($response->hasHeader('Vary')) { //检测响应头
//        // Do something
//    }
//    echo '<br>';
//    $newResponse = $response->withHeader('Content-type', 'application/json');    //设置响应头
//    echo $newResponse->getHeader('Content-type')[0];    //获取新的响应头
//    $newResponse->withAddedHeader('Allow', 'PUT');   //追加响应头，且不可修改此头内容
//    $newResponse->withoutHeader('Allow');   //移除响应头
//
//    $body = $response->getBody();
//    $body->write('Hello');

    $data = array('name' => 'Bob', 'age' => 40);
    $newResponse = $response->withJson($data, 200); //可以追加状态码
    return $newResponse;
});

$app->run();
