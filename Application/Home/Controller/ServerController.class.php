<?php
namespace Home\Controller;

use Think\Controller;

class ServerController extends Controller {
	/*
	 * Class Server Object
	 */
    protected $server;
    /*
     * initialize class server ,set $server
     * @return object
     */
	public function _initialize()
	{
		Vendor('EasyCenter.server');
		$config = C('server');
		$this->server = new \Server($config);
	}

	/*
	 * check if user is login
	 * @return redirect
	 */ 
	public function isLogin(){
		logger('用户请求验证是否登录？');
		$request = I();
		$this->server->isLogin($request);
	}

	/*
	 * Check User Input,login or reinput
	 * @return json OR redirect
	 */
	public function login(){
		$request = I();
		$username = $request['username'];
		$password = $request['password'];
		$callback = $request['callback'];
		$appId = $request['appId'];
		$users = D('user');
		$user = $users->where(array('username'=>$username,'password'=>$password))->field('id,username,nickname')->find();
		logger('查询SQL:'.$users->getLastsql()); // debug
		if($user){
			$this->server->login($user,$callback,$appId);
		}else{
			$this->server->toLogin($callback,$appId);
		}
	}

	/*
	 * User ask for Logout
	 * @return redirect
	 */
	public function logout(){
		$request = I();
		$this->server->logout($request);
	}


	/*
	 * login view
	 * @return display
	 */
	public function index(){
		$request = I();
		$this->assign('callback',$request['callback']);
		$this->assign('appId',$request['appId']);
		$this->display('login');
	}
	/*
	 * register view
	 * @return display
	 */
	public function register()
    {
        $this->display();
    }
    /*
	 * set_password view
	 * @return display
	 */
    public function set_password()
    {
        $this->display();
    }
    /*
	 * forgot_password view
	 * @return display
	 */
    public function forgot_password()
    {
        $this->display();
    }
    /*
	 * reset view
	 * @return display
	 */
    public function reset()
    {
        $this->display();
    }
    /*
	 * fast_login view
	 * @return display
	 */
    public function fast_login()
    {
        $this->display();
    }
}