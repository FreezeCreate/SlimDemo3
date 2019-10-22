<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;

class MyController {
    protected $ci;
    //Constructor
    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }

    public function method1($request, $response, $args) {
        return json_encode($args);
        //your code
        //to access items in the container... $this->ci->get('');
    }

    public function method2($request, $response, $args) {
        //your code
        //to access items in the container... $this->ci->get('');
    }

    public function method3($request, $response, $args) {
        //your code
        //to access items in the container... $this->ci->get('');
    }

    public function getUser($request, $response, $args)
    {
        $capsule  = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'four_six',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();
        $conn  = $capsule;
        $users = $conn::select('SELECT * FROM x2_user limit 10');
        return $response->withJson($users, 200);
    }
}
