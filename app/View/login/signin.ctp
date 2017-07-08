<!DOCTYPE html>
<html class="screen-desktop device-desktop">
<head profile="http://www.w3.org/2005/10/profile">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="icon" href="#" type="image/x-icon">
<link rel="shortcut icon" href="#" type="image/x-icon">
  <title>登录 - OA系统</title>
<script language="Javascript">
var config={"webRoot":"\/","appName":"sys","cookieLife":30,"requestType":"GET","requestFix":"-","moduleVar":"m","methodVar":"f","viewVar":"t","defaultView":"html","themeRoot":"\/theme\/","currentModule":"user","currentMethod":"login","clientLang":"zh-cn","requiredFields":"","save":"\u4fdd\u5b58","router":"\/sys\/index.php","runMode":"front","timeout":30000,"pingInterval":60}
</script>
<link rel="stylesheet" href="/css/rzlogin.css" type="text/css" media="screen">
<link rel="stylesheet" href="/css/rzlogin.css" type="text/css" media="print">
<script src="/js/rzlogin.js" type="text/javascript"></script>
<style>.user-control-nav{margin-bottom: 20px;}
html{height:100%;}
body {padding-top: 0;width:100%;height:100%;background-color:#f6f5f5;background: url(/img/loginbg.jpg) no-repeat 0px 0px;font-family: 'Open Sans', sans-serif;background-size:cover;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:100% 100%;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='bg-login.png',sizingMethod='scale');border:1px solid #fff;}
.page-content{padding: 0;}
.text-bold{font-weight: bold;}
.container {margin: 10% auto 0 auto}

#login {margin: 0 auto; width: 500px; min-height: 230px; background-color: #fff; border: 1px solid #dfdfdf; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; -moz-box-shadow: 0px 1px 15px rgba(0,0,0,0.15); -webkit-box-shadow: 0px 1px 15px rgba(0, 0, 0, 0.15); box-shadow: 0px 1px 15px rgba(0, 0, 0, 0.15)}
#login .panel-head {min-height: 55px; background-color: #edf3fe; border-bottom: 1px solid #dfdfdf; position: relative}
#login .panel-head h4 {margin: 0 0 0 20px; padding: 0; line-height: 55px; font-size: 14px}
#login .panel-actions {float: right; position: absolute; right: 15px; top: 12px; padding: 0}
#login .panel-actions .dropdown {display: inline-block; margin-right: 2px}
#login #submit {min-width: 100px;}
#loginForm {padding: 20px 20px;}
.table-form th, .table-form td {padding: 8px 5px}
.checkbox {display: inline-block; padding-left: 80px;}

.notice {padding: 10px;}
</style><!--[if lt IE 9]>
<script src='/js/html5shiv/min.js?v=pro2.2.2' type='text/javascript'></script>
<script src='/js/respond/min.js?v=pro2.2.2' type='text/javascript'></script>
<![endif]-->
<!--[if lt IE 10]>
<script src='/js/jquery/placeholder/min.js?v=pro2.2.2' type='text/javascript'></script>
<![endif]-->
<script language="Javascript">
if(typeof(v) != "object") v = {};v.lang = {"confirmDelete":"\u60a8\u786e\u5b9a\u8981\u6267\u884c\u5220\u9664\u64cd\u4f5c\u5417\uff1f","deleteing":"\u5220\u9664\u4e2d","doing":"\u5904\u7406\u4e2d","timeout":"\u7f51\u7edc\u8d85\u65f6,\u8bf7\u91cd\u8bd5","confirmDiscardChanges":"\u8868\u5355\u5df2\u66f4\u6539\uff0c\u786e\u5b9a\u5173\u95ed\uff1f","yes":"\u662f","no":"\u5426"};
</script>
</head>
<body class="m-user-login" style="zoom: 1;">
<script src="/js/md5.js" type="text/javascript"></script>
<script language="Javascript">
    v.scriptName = "\/sys\/index.php";
    v.random = "f1ae84c5f98fb8910a449ccce875955e";
    v.notEncryptedPwd = false;
</script>

<div class="container">
  <div id="login" style='opacity:0.9;'>
    <div class="panel-head">
      <h4>OA管理系统</h4>
      <div class="panel-actions">
        <div class="dropdown" id="langs">
            
        </div>
      </div>
    </div>
    <div class="panel-body" id="loginForm">
        <form action="/login/signin" id="LoginSigninForm" method="post" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>  
        <div id="responser" class="text-center"></div>
        <div class="row">
          <div class="col-xs-4 text-center">
          <img src="/img/login-logo.png" alt='OA'>
          </div>
          <div class="col-xs-8">
            <table class="table table-form">
              <tbody><tr>
                <th>用户名</th>
                <td><input type="text" class="form-control" placeholder="请输入用户名" name="data[user]" id="user"></td>
              </tr>
              <tr>
                <th>密码</th>
                <td><input type="password" class="form-control" placeholder="请输入密码" name="data[password]" id="password">
</td>
              </tr>
              <tr>
                <th></th>
                <td>
                   <button type="submit" id="submit" class="btn btn-primary" data-loading="稍候...">登录</button>
                  <!--label class="checkbox-inline"><input type="checkbox" name="keepLogin[]" value="on" id="keepLoginon"> 保持登录</label-->                
                </td>
              </tr>
            </tbody></table>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="notice text-center">
  </div>
</div>
<script language="Javascript">$(document).ready(function()
{
    $('.LoginSigninForm').submit(function()
    {
        var inputValue = $(".user").val();
        if(inputValue == '')
        {
            alert('请输入用户名');
            return false;
        }
    });
    
    if(v.deptID) $('#category' + v.deptID).addClass('text-bold');
})
$(document).ready(function()
{
    $('#account').focus();

    setInterval('ping()', 1000 * config.pingInterval);

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    /* show update notice. */
    if(typeof(latest) != 'undefined')
    {
        if(typeof(v.ignoreNotice) == 'undefined' || $.inArray('update' + latest.version, v.ignoreNotice) == -1)
        {
            var content = 'NOTE: <a href=' + latest.url + ' target=\'_blank\'>' + latest.note + '(' + latest.releaseDate + ')</a>';
            content += "&nbsp;&nbsp;&nbsp;<a class='ignore' href=" + createLink('misc', 'ignoreNotice', 'version=update' + latest.version) + ">" + v.ignore + "</a>";
            content = "<p>" + content + "</p>";
            $('.notice').append(content); 
        }
    }
    if(typeof(notice) != 'undefined')
    {
        if(typeof(v.ignoreNotice) == 'undefined' || $.inArray('notice' + notice.id, v.ignoreNotice) == -1)
        {
            var content = 'NOTE: <a href=' + notice.url + ' target=\'_blank\'>' + notice.note + '(' + notice.date + ')</a>';
            content += "&nbsp;&nbsp;&nbsp;<a class='ignore' href=" + createLink('misc', 'ignoreNotice', 'version=notice' + notice.id) + ">" + v.ignore + "</a>";
            content = "<p>" + content + "</p>";
            $('.notice').append(content); 
        }
    }
    $('.ignore').click(function()
    {
        $.get($(this).prop('href'));
        $(this).prop('href', '###');
        $('.notice').html('');
        return false;
    });
})

/* Keep session random valid. */
$('#submit').click(function()
{
    var inputUser = $("#user").val();
        if(inputUser == '')
        {
             bootbox.alert('请输入用户名');
             return false;
        }
     var inputPass = $("#password").val();
        if(inputPass == '')
        {
             bootbox.alert('请输入密码');
             return false;
        }
     
    var password = v.notEncryptedPwd ? inputPass : md5(inputPass);
    var rawPassword = md5(inputPass);
    
    loginURL = createLink('login', 'signin');
    $.ajax(
    {
        contentType: 'application/x-www-form-urlencoded',
        type: "POST",
        data:"user=" + inputUser + '&password=' + password + '&referer=' + encodeURIComponent($('#referer').val()) + '&rawPassword=' + rawPassword + '&keepLogin=' + $('#keepLoginon').is(':checked'),
        url:$('#LoginSigninForm').attr('action'),
        dataType:'json',
        success:function(data)
        {
            if(data.result == 'fail') return  bootbox.alert(data.message);
            if(data.result == 'success') return location.href=data.locate;
            if(typeof(data) != 'object') return bootbox.alert(data);
        },
        error:function(data){bootbox.alert(data.responseText)}
    })
    return false;
})

</script>
<script language="Javascript">v.ignoreNotice = [];</script>
<script language="Javascript">v.ignore = "\u5ffd\u7565";</script>

</body></html>