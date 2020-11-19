define(["jquery", "jquery-cookie"], function ($) {
    function loginSend() {
        $("#login-button").click(function () {
            $.ajax({
                type: "post",
                url: "../php/login.php",
                data: {
                    username: $(".item_account").eq(0).val(),
                    password: $(".item_account").eq(1).val()
                },
                success: function (result) {
                    var obj = JSON.parse(result);
                    
                    if(obj.code){// 如果存在code则登陆失败 显示登陆失败的信息
                        $(".err_tip").find("em").attr("class", "icon_error")
                    }else{ // 登陆成功
                        $(".err_tip").find("em").attr("class", "icon_select icon_true");
                        //将已经登录的用户名存储在cookie中
                        $.cookie("username", obj.username, {
                            expires: 7
                        })
                    }
                    $(".err_tip").show().find("span").html(obj.message);
                },
                error: function (msg) {
                    console.log(msg);
                }
            })
        })
    }



    return {
        loginSend: loginSend
    }


})