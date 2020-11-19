<?php
header("Content-type:text/html;charset=utf-8");

// 定义一个统一的返回格式
$responseData = array("code" => 0, "message" => "");

// 得到用户post提交的值
$username = $_POST['username'];
$password = $_POST['password'];

// 简单表单验证
if(!$username){
    $responseData['code'] = 1;
    $responseData['message'] = "用户名不能为空";
    echo json_encode($responseData);
    exit;
}
if(!$password){
    $responseData['code'] = 2;
    $responseData['message'] = "密码不能为空";
    echo json_encode($responseData);
    exit;
}

// 链接数据库
$link = mysqli_connect("", "root", "root","xiaomi");

// 链接失败
if(!$link){
    $responseData['code'] = 3;
    $responseData['message'] = "数据库连接失败";
    echo json_encode($responseData); // 返回数据给前端
    exit;
}
// 设置字符集
mysqli_set_charset($link, "utf8");
// 密码m5加密
$str = md5(md5(md5($password).'beijing').'zhongguo'); 

// 查询用户提交的数据是否存在数据库中
$sql = "select * from users where username='{$username}' and password='{$str}'";
// 查询并返回查询结果
$res = mysqli_query($link, $sql);
// 查询语句的结果影响数据库中数据的条数
$row = mysqli_fetch_assoc($res);

if(!$row){ // 没有查询到数据
    $responseData['code'] = 4;
    $responseData['message'] = "用户名或密码错误";
    echo json_encode($responseData);
    exit;
}else{ // 查询到数据
    $responseData['message'] = "登录成功";
    $responseData["username"] = $row['username'];
    echo json_encode($responseData);
}

// 关闭数据库链接
mysqli_close($link);
?>