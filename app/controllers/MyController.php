<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \interop\Container\ContainerInterface;


class MyController extends BaseController {
    protected $ci;
    //Constructor
    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        parent::__construct();
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

    /**
     * 获取用户信息
     * @Author: Ferre
     * @create: 2019/10/23 17:47
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getUser($request, $response, $args)
    {
        $user_all = UserModel::all();
        $capsule  = $this->capsule;
        $user_id  = $capsule::table('x2_user')->select('id')->get();
//        $a = \Illuminate\Support\Facades\DB::table('x2_user')->select('id')->get();
//        $conn  = $capsule;
//        $users = $conn::select('SELECT * FROM x2_user limit 10');
        return $response->withJson($user_all, 200);
        return $response->withJson($user_id, 200);
    }
}
