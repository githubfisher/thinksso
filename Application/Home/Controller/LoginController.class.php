<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login()
    {
    	$request = I();
    	$username = $request['username'];
    	$password = $request['password'];

    	if($info = $this->getUser($username)){
    		if($info['password'] == md5($password.$info['salt'])){
    			// 写入登录记录
    			$ticket = getRand(10);
    			if($this->addLoginInfo($info['id'],$ticket,time())){
	    			unset($info['password']);
	    			$data = array(
	    				'code' => 1,
	    				'message' => 'login successfully',
	    				'info' => $info,
	    				'ticket' => $ticket
	    			);
	    		}else{
	    			$data = array(
	    				'code' => 0,
	    				'message' => 'login faild'
	    			);
	    		}
    		}else{
    			$data = array(
    				'code' => 0,
    				'message' => 'username or password incorrect'
    			);
    		}
    	}
    	$this->ajaxReturn($data);
    }
    private function getUser($username)
    {
    	$user = D('user');

    	/* 使用会员名、邮箱或手机号登录*/
        if(preg_match('/^1[3|4|5|7|8|][0-9]{9}$/', $username)){
          $type="mobile";
        }elseif(preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/', $card)){
            $type="email";
        }else{$type="username";}

    	return $user->where(array($type=>$username))->field('username,password,salt,id,icon,nick')->cache(true,60)->find();
    }
    private function addLoginInfo($uid,$ticket,$timestamp)
    {
        $tickets = D('ticket');
        $data = array(
            'ticket' => $ticket,
            'uid' => $uid,
            'create_at' => $timestamp
        );
        if($tickets->add($data))
            return true;
        return false;
    }
}