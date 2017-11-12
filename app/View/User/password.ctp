<script src="/js/md5.js" type="text/javascript"></script>
<style>
    .modal-dialog{margin: 10% auto 0;}  
    .btnstyle,#sub_pwd {background:#286090;color:#fff;width:100px;height:35px;}
</style>

<!-- 模态框（Modal） -->

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times; </button>
    <h4 class="modal-title" id="myModalLabel">
        修改密码
    </h4>
</div>

<form class="form-horizontal"  accept-charset="utf-8" >
<div class="modal-body">
        <div class="form-group">
            <label class="col-sm-4 control-label" >原密码</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" name="pwd1" id="pwd1" value = '' placeholder="原密码"  >
            </div> 
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="pwd2">新密码</label>  
            <div class="col-sm-5">
                <input type="password" class="form-control" name="pwd2" id="pwd2" value = ''  placeholder="新密码" >
            </div>  
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="pwd3" >确认密码</label> 
            <div class="col-sm-5">
                <input type="password" class="form-control" name="pwd3" id="pwd3" value = ''  placeholder="确认密码" >
            </div>  
        </div>
 
    
    <div class="alert alert-warning alert-dismissable" style="text-align: center;display:none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                &times;
            </button>
            警告！
        </div>
</div>
<div class="modal-footer">
    <button type="button" id='sub_pwd' style='border:2px solid #fff;border-radius: 4px; ' data-loading="稍候..."> 修改 </button>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button type="button " class='btnstyle' style='border:2px solid #fff;border-radius: 4px; ' data-dismiss="modal">关闭</button>
</div>
   </form>
<script>
        $("#sub_pwd").click(function(){ 
            $(".alert").css('display','none');
            var pwd1 = $("#pwd1").val(); 
            if(pwd1 == ''){
                $("#pwd1").focus();
                $(".alert").css('display','block');
                $(".alert").text('警告！请输入现有密码。');
                return false;
            }
            var pwd2 = $("#pwd2").val();
            if(pwd2 == ''){
                $("#pwd2").focus();
                $(".alert").css('display','block');
                $(".alert").text('警告！请输入新密码。');
                return false;
            }
            if( pwd2.length < 6 ){
                $("#pwd2").focus();
                $(".alert").css('display','block');
                $(".alert").text('警告！新密码至少要6个字符。');
                return false;
            }
            var pwd3 = $("#pwd3").val();
            pwd2 = md5(pwd2);
            pwd3 = md5(pwd3);
            if( pwd3 !== pwd2 ){
                $("#pwd3").focus();
                $(".alert").css('display','block');
                $(".alert").text('警告！确认密码与新密码不一致。');
                return false;
            }

            $.ajax({
                url:'/User/password',
                type: "post",
                data:{pwd1:md5(pwd1),pwd2:pwd2},
                dataType:'json',
                success:function(res)
                {
                    if(res.result == 'fail'){
                        $(".alert").css('display','block');
                        $(".alert").text('警告！'+ res.message);
                    } 
                    if(res.result == 'success'){
                        alert('修改成功');
                        $('#useModal').modal('hide')
                    } 
                    if(typeof(res) != 'object'){ 
                        $(".alert").css('display','block');
                        $(".alert").text('警告！'+ res);
                    }
                },
                error:function(res){ 
                        $(".alert").css('display','block');
                        $(".alert").text( res.responseText );
                    }
            });
            
            return false;
        });
   
</script>