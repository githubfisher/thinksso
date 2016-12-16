<?php
namespace Home\Controller;

use Think\Controller;

class CodeController extends Controller
{
	public function get_mobile_code()
	{
		$request = I();
		$mobile = $request['mobile'];
		if($mobile){
			$result = $this->send_mobile_code($mobile);
			if($result){
				$data = array(
					'status' => 1,
					'info' => '验证码发送成功！',
					'data' => $result
				);
			}else{
				$data = array(
					'status' => 0,
					'info' => '验证码发送失败！'
				);
			}
		}else{
			$data = array(
				'status' => 2,
				'info' => '手机号不能为空！'
			);
		}
		$this->ajaxReturn($data);
	}
	private function send_mobile_code($mobile)
	{
		return '123456';
	}
	public function get_email_code()
	{
		$request = I();
		$email = $request['email'];
		if($email){
			$result = $this->send_email_code($email);
			if($result){
				$data = array(
					'status' => 1,
					'info' => '验证码邮件发送成功！',
					'data' => $result
				);
			}else{
				$data = array(
					'status' => 0,
					'info' => '验证码邮件发送失败！'
				);
			}
		}else{
			$data = array(
				'status' => 2,
				'info' => '邮箱不能为空！'
			);
		}
		$this->ajaxReturn($data);
	}
	private function send_email_code($email)
	{
		return '123456';
	}
}