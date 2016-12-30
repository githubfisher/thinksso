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
        $request = I();
        logger('子应用'.$request['appId'].': 请求验证是否登录？');
        $this->server->isLogin($request);
    }
    /*
     * Check User Input,login or reinput
     * @return redirect
     */
    public function login()
    {
        $request = I();
        $username = $request['username'];
        $password = $request['password'];
        $mobile = $request['mobile'];
        $code = $request['code'];
        $callback = $request['callback'];
        $appId = $request['appId'];
        if($username && $password){
            /* 使用会员名、邮箱或手机号登录*/
            $type = $this->inputType($username);
            $where = array($type=>$username);
            $user = $this->getUserInfo($where,'id,username,nickname,rand,password');
            if($user){
                if($user['password'] == md5($password.$user['rand'])){
                    $this->server->login($user,$callback,$appId);
                }else{
                    $this->server->toLogin($callback,$appId);
                }
            }else{
                $this->server->toLogin($callback,$appId);
            } 
        }else if($mobile && $code){
            $checkCode = $this->getCode();
            if($checkCode == $code){
                $where = array('mobile'=>$mobile);
                $user = $this->getUserInfo($where,'id,username,nickname');
                if($user){
                    $this->server->login($user,$callback,$appId);
                }else{
                    $this->server->toLogin($callback,$appId);
                }
            }else{
               $this->server->toLogin($callback,$appId);
            }
        }else{
            $this->server->toLogin($callback,$appId);
        }
    }
    /*
     * Check mobile and code
     * @return json
     */
    public function fast_login()
    {
        $request = I();
        $mobile = $request['mobile'];
        $code = $request['code'];
        $callback = $request['callback'];
        $appId = $request['appId'];
        if($mobile && $code && $callback && $appId){
            $checkCode = $this->getCode();
            if($checkCode == $code){
                $where = array('mobile'=>$mobile);
                $user = $this->getUserInfo($where,'id,username,nickname,status,email');
                if($user){
                    if($user['status'] == 0){
                        $data = array(
                            'status' => 5,
                            'info' => '账号未激活',
                            'data' => $user['email']
                        );
                        $this->ajaxReturn($data);
                    }
                    $signature = $this->server->makeSignature();
                    $ticket = $this->server->makeTicket($user);
                    $ticket = array_merge($signature,$ticket);
                    $ticket['url'] = $this->server->getClientLoginUrl($appId);
                    $this->server->setTicket(false,$ticket);
                    $data = array(
                        'status' => 1,
                        'info' => '登录成功',
                        'data' => $ticket
                    );
                }else{
                    $data = array(
                        'status' => 4,
                        'info' => '用户不存在'
                    );
                }
            }else{
                $data = array(
                    'status' => 3,
                    'info' => '验证码校验失败'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '参数不全'
            );
        }
        $this->ajaxReturn($data);
    }
    /*
     * Check username and password
     * @return json
     */
    public function common_login()
    {
        $request = I();
        $username = $request['username'];
        $password = $request['password'];
        $callback = $request['callback'];
        $appId = $request['appId'];
        if($username && $password && $callback && $appId){
            $type = $this->inputType($username);
            $where = array($type=>$username);
            $user = $this->getUserInfo($where,'id,username,nickname,rand,password,status,email');
            if($user){
                if($user['status'] == 0){
                    $data = array(
                        'status' => 5,
                        'info' => '账号未激活',
                        'data' => $user['email']
                    );
                    $this->ajaxReturn($data);
                }
                if($user['password'] == md5($password.$user['rand'])){
                    $signature = $this->server->makeSignature();
                    unset($user['rand']);
                    unset($user['password']);
                    $ticket = $this->server->makeTicket($user);
                    $ticket = array_merge($signature,$ticket);
                    $ticket['url'] = $this->server->getClientLoginUrl($appId);
                    $this->server->setTicket(false,$ticket);
                    $data = array(
                        'status' => 1,
                        'info' => '登录成功',
                        'data' => $ticket
                    );
                }else{
                    $data = array(
                        'status' => 3,
                        'info' => '用户名或密码错误'
                    );
                }
            }else{
                $data = array(
                    'status' => 4,
                    'info' => '用户不存在'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '参数不全'
            );
        }
        $this->ajaxReturn($data);
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
        $request = I();
        $this->assign('callback',$request['callback']);
        $this->assign('appId',$request['appId']);
        $this->display();
    }
    /*
     * set_password view
     * @return display
     */
    public function set_password()
    {
        $request = I();
        $this->assign('callback',$request['callback']);
        $this->assign('appId',$request['appId']);
        $this->display();
    }
    /*
     * forgot_password view
     * @return display
     */
    public function forgot_password()
    {
        $request = I();
        $this->assign('callback',$request['callback']);
        $this->assign('appId',$request['appId']);
        $this->display();
    }
    /*
     * reset view
     * @return display
     */
    public function reset()
    {
        $request = I();
        $name = $request['name'];
        $code = $request['code'];
        $checkCode = $this->getCode();
        if($code == md5($name.$checkCode)){
            $this->assign('callback',$request['callback']);
            $this->assign('appId',$request['appId']);
            $this->assign('name',$name);
            $this->display();
        }else{
            $this->redirect("Home/Server/forgot_password?appId=".$request['appId']."&callback=".$request['callback']);
        }
    }
    /*
     * fast_login view
     * @return display
     */
    public function ffast_login()
    {
        $this->display();
    }
    /*
     * send SMS
     * @return json
     */
    public function send_msg()
    {
        $request = I();
        $mobile = $request['mobile'];
        if($mobile){
            $code = getRand(6,false);
            $result = $this->sendMsg($mobile,'Uc4PF3',$code,'2分钟');
            if($result['status'] == 'success'){
                $this->saveCode($code);
                $data = array(
                    'status' => 1,
                    'info' => '短信发送成功！'
                );
            }else{
                $data = array(
                    'status' => 0,
                    'info' => '短信发送失败！'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '参数不全，短信发送失败！'
            );
        }
        $this->ajaxReturn($data);
    }
    /*
     * register new user
     * @return json
     */
    public function registe()
    {
        $request = I();
        $name = $request['mobile'];
        if(empty($name)){
            $name = $request['email'];
        }
        $password = $request['password'];
        $username = $request['username'];
        if($name && $password && $username){
            $type = $this->inputType($name);
            $where = array($type => $name);
            $result = $this->getUserInfo($where,'id');
            if($result){
                $data = array(
                    'status' => 3,
                    'info' => '账号已存在！'
                );
                $this->ajaxReturn($data);
            }
            $rand = getRand(6);
            $password = md5($password.$rand);
            $info = array(
                $type => $name,
                'username' => $username,
                'password' => $password,
                'rand' => $rand,
                'create_at' => time(),
                'status' => 1
            );
            $result = $this->addUser($info);
            if($result){
                $data = array(
                    'status' => 1,
                    'info' => '注册成功！'
                );
            }else{
                $data = array(
                    'status' => 0,
                    'info' => '注册失败！'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '参数不全！'
            );
        }
        $this->ajaxReturn($data);
    }
    /*
     * check input code
     * @return json
     */
    public function get_check_code()
    {
        $request = I();
        $name = $request['mobile'];
        if(empty($name)){
            $name = $request['email'];
        }
        $code = $request['code'];
        $result = $this->getCode();
        if($result == $code){
            $data = array(
                'status' => 1,
                'info' => '验证码匹配！'
            );
        }else{
            $data = array(
                'status' => 0,
                'info' => '验证码不匹配！'
            );
        }
        $this->ajaxReturn($data);
    }
    /*
     * judge user's input type
     * @return string
     */
    private function inputType($input)
    {
        /* 会员名、邮箱或手机号*/
        if(preg_match('/^1[3|4|5|7|8|][0-9]{9}$/', $input)){
            $type = "mobile";
        }elseif(preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/', $input)){
            $type = "email";
        }else{
            $type = "username";
        }
        return $type;
    }
    /*
     * send SMS
     * @return array
     */
    private function sendMsg($mobile,$templet,$code,$time)
    {
        Vendor("SubMail.lib.message");
        Vendor("SubMail.lib.messagexsend");
        Vendor("SubMail.Autoload");
        $config = C('SubMsg');
        $submsg=new \MESSAGEXsend($config);
        $submsg->SetTo($mobile);
        $submsg->SetProject($templet);
        $submsg->AddVar("code", $code);
        $submsg->AddVar("time", $time);
        $result = $submsg->xsend();
        return $result;
    }
    /*
     * save code
     * @return bool
     */
    private function saveCode($code,$expire=60)
    {
        $sendCode = D('sendcode');
        $sendCode->add(array('name'=>session_id(),'code'=>$code,'create_at'=>time()));
        // 缓存方式

    }
    /*
     * get stored code
     * @return string
     */
    private function getCode()
    {
        $sendCode = D('sendcode');
        $result = $sendCode->where(array('name'=>session_id()))->field('code')->order('create_at desc')->limit(1)->select();
        return $result[0]['code'];
        // 缓存方式

    }
    /*
     * get User Info
     * @return array
     */
    private function getUserInfo($where,$field)
    {
        $users = D('user');
        $result = $users->where($where)->field($field)->find();
        return $result;
    }
    /*
     * add User Info
     * @return string
     */
    private function addUser($info)
    {
        $users = D('user');
        $result = $users->add($info);
        return $result;
    }
    /*
     * check user input ,turn to reset controller
     * @return json
     */
    public function reseter()
    {
        $request = I();
        if(!empty($request['mobile'])){
            $name = $request['mobile'];
            $code = $request['code'];
            if($name && $code){
                $checkCode = $this->getCode();
                if($code == $checkCode){
                    $data = array(
                        'status' => 1,
                        'info' => '验证码校验成功',
                        'data' => md5($name.$code)
                    );
                }else{
                    $data = array(
                        'status' => 0,
                        'info' => '验证码校验失败'
                    );
                }
            }else{
                $data = array(
                    'status' => 2,
                    'info' => '校验参数不全'
                );
            }
        }else if(!empty($request['email'])){
            $result = $this->sendMail($request['email']);
            if($result){
                $data = array(
                    'status' => 1,
                    'info' => '邮件发送成功'
                );
            }else{
                $data = array(
                    'status' => 0,
                    'info' => '邮件发送失败'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '校验参数不全'
            );
        }
        $this->ajaxReturn($data);
    }
    public function reset_pwd()
    {
        $request = I();
        $name = $request['name'];
        $pwd = $request['pwd'];
        if($name && $pwd){
            $type = $this->inputType($name);
            $where = array($type=>$name);
            $rand = getRand(6);
            $password = md5($pwd.$rand);
            $info = array(
                'password' => $password,
                'rand' => $rand,
                'modify_at' => time()
            );
            $result = $this->updateUserInfo($where,$info);
            if($result){
                $data = array(
                    'status' => 1,
                    'info' => '密码重置成功'
                );
            }else{
                $data = array(
                    'status' => 0,
                    'info' => '密码重置失败'
                );
            }
        }else{
            $data = array(
                'status' => 2,
                'info' => '参数不全'
            );
        }
        $this->ajaxReturn($data);
    }
    private function updateUserInfo($where,$info)
    {
        $users = D('user');
        $result = $users->where($where)->save($info);
        return $result;
    }
    private function sendMail()
    {
        return true;
    }
    public function activate()
    {
        $data = array(
            'status' => 1,
            'info' => '邮件发送失败'
        );
        $this->ajaxReturn($data);
    }
}   