<?php
// 日志函数
function logger($log_content){ 
	//$folder = "./Log/";
	$folder = './Log/'.date('Y',time()).'/'.date('m',time()).'/'.date('d',time()).'D/';
	if(!file_exists($folder)){
		mkdir($folder,0777,TRUE);
	}
	for($i=80;$i--;$i>0){
		$max_size = 100000;   
    	$log_filename = $folder.'/'.date('Ymd',time()).'-'.$i.'.txt';
		if(file_exists($log_filename) && (abs(filesize($log_filename)) >= $max_size)){
			$next = $i + 1;
			$log_filename = $folder.'/'.date('Ymd',time()).'-'.$next.'.txt';
			//新内容写入日志，内容前加上时间， 后面加上换行， 以追加的方式写入
    		file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content." \r\n", FILE_APPEND);  
		}elseif(file_exists($log_filename) && (abs(filesize($log_filename)) < $max_size)){
			//写入日志，内容前加上时间， 后面加上换行， 以追加的方式写入
			file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content." \r\n", FILE_APPEND); 
		}else{
			if($i == 1){
				//写入日志，内容前加上时间， 后面加上换行， 以追加的方式写入
				file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content." \r\n", FILE_APPEND); 
			}
		}
	} 
}
// 随机字符串
function getRand($num = 4){
	$string = '';
	$source = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_';
	$length = strlen($source);
	while(strlen($string) < $num){
		$string .= substr($source,rand(0,$num),1);
	}
	return $string;
}
// 加密
function encrypt($data, $key)
{
    $key    =   md5($key);
    $x      =   0;
    $len    =   strlen($data);
    $l      =   strlen($key);
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l) 
        {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}
// 解密
function decrypt($data, $key)
{
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l) 
        {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
        {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }
        else
        {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}
function chanslate_add_to_empty($str)
{
	$str = str_replace('=','-',$str);
	return str_replace('+','_',$str);
}
//转译数据库中读取的JSON字符串
function chanslate_json_to_array($json){
	$json = str_replace('&quot;','"',$json);
	// logger('替换转译符号后,字符串:'.$json); //debug
	$array = json_decode($json,TRUE);
	// logger('转译后数组:'.var_export($array,TRUE)); //debug
	return $array;
}