<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;

class BaseController {
    protected $capsule;

    public function __construct() {
        //基类链接db
        $this->capsule  = new \Illuminate\Database\Capsule\Manager();
        $this->capsule->addConnection([ // 创建链接
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'four_six',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $this->capsule->setAsGlobal();  // 设置全局静态可访问
        $this->capsule->bootEloquent(); // 启动Eloquent
    }

}
