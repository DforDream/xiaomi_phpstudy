<?php
header('content-type:text/html;charset="utf-8"');

// 定义一个统一的返回格式
$responseDate = array('code' => 0,'message' => "");

// 获取提交过来的数据
$username = $_POST['username'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];
$createTime = $_POST["createTime"];

// 简单表单验证
if(!$username){
    $responseData['code'] = 1;
    $responseData["message"] = "用户名不能为空";
    //将数据按统一数据返回格式返回
    echo json_encode($responseData);
    exit;
}

if(!$password){
    $responseData['code'] = 2;
    $responseData["message"] = "密码不能为空";
    //将数据按统一数据返回格式返回
    echo json_encode($responseData);
    exit;
}

if($password != $repassword){
    $responseData['code'] = 3;
    $responseData["message"] = "两次输入密码不一致";
    //将数据按统一数据返回格式返回
    echo json_encode($responseData);
    exit;
}

$link = mysqli_connect("","root","root","xiaomi");

// 判断是否链接成功
if(!$link){
    $responseData['code'] = 4;
    $responseData["message"] = "服务器忙";
    //将数据按统一数据返回格式返回
    echo json_encode($responseData);
    exit;
}
mysqli_set_charset($link,'utf8');

// 数据库查找是否有用户注册的用户名
$sql = "select * from users where username='{$username}'";

$res = mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($res);

if(!$row){ // 不存在该用户
    // 密码加密
    $str = md5(md5(md5($password).'beijing').'zhongguo');

    // 数据添加到数据库中
    $sql2 = "insert into users(username,password,createtime) values('{$username}','{$str}','{$createTime}')";

    $res = mysqli_query($link, $sql2);
    if($res){
        $responseData['message'] = "注册成功";
        echo json_encode($responseData);
        exit;
    }else{
        $responseData['code'] = 5;
        $responseData['message'] = "注册失败";
        echo json_encode($responseData);
        exit;
    }
}else{
    $responseData['code'] = 6;
	$responseData['message'] = "用户名重名";
	echo json_encode($responseData);
	exit;
}

mysqli_close($link); // 关闭数据库链接
?>