<style type='text/css'>
    .with-20{width:20%};
    .with-25{width:25%};
</style>
<p class="btn btn-info btn-block" width='50px'> <span style="font-size:16px;">项目经费</span> <a class="close" data-dismiss="modal" id='closemodel'>×</a></p>
        <div class="container" style='background-color:#fff;border-radius:4px;'>
           
        <div class="row" style='padding:20px 0;'>
            <div class="col-xs-12">
                <form class="form-horizontal" role="form">
                   
                    <?php  foreach($list as $lk => $lv){ ?>
                    <div class="form-group">
                        <?php  foreach($lv as $k => $v){ ?>
                        <label class="col-sm-3 control-label no-padding-right with-25" for="form-field-1" > <?php echo $v; ?> </label >
                        <div class="col-sm-9 with-20" > 
                            <input type="text" id="form-field-1 <?php echo $k; ?> " value='0.00' class="col-xs-10 col-sm-5 " style='width:100px;'/>
                        </div>  
                        <?php } ?>  
                    </div>  
                    <?php } ?>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right with-25" for="form-field-1" > 合计 </label >
                        <div class="col-sm-9 with-20" > 
                            <input type="text" id="form-field-1  " value='0.00' class="col-xs-10 col-sm-5 " style='width:100px;'/>
                        </div>  
                    </div>  
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right with-25" for="form-field-1" > 备注 </label >
                        <div class="col-sm-9 with-20" > 
                            <textarea style="float: left;" cols="40" rows="5" id='remarks' class="desc"></textarea>
                        </div>  
                    </div>  

                    
                    <div class="clearfix " style="text-align: right;" >
                        <div class=" col-md-9">
                            <button class="btn btn-primary" type="reset" onclick='upstep();' >
                                <i class="icon-undo bigger-110"></i>
                                上一步
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                <i class="icon-ok bigger-110"></i>
                                提交
                            </button>
                            
                        </div>
                    </div>


                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div>

    <script type="text/javascript">
        function upstep(){
            console.log($('#movestep2'));
            $('#closemodel').click();
           /* $("#modal_left").modal({
                remote: "/ResearchProject/step2"
            });*/
        $("#modal_left").on("hidden.bs.modal", function() {
    $(this).removeData("bs.modal");
});
            $('#movestep2').click();
        }
        //提交内容
        function ajax_submit() {
            var user_id = $('#user_id').val();
            var username = $('.username').val();
            var password = $('.pwd').val();
            var name = $('.nname').val();
            var pid = $('.pid option:selected').val();
            var position = $('.position option:selected').val();
            var tel = $('.tel').val();
            var sex = $('.sex option:selected').val();
            var email = $('.email').val();
            var status = $('.status option:selected').val();
           
            if (!username) {
                show_error($('.username'), '用户名为空');
                $('.username').focus();
                return;
            }
            if (!password) {
                show_error($('.pwd'), '密码为空');
                $('.pwd').focus();
                return;
            }
            if (!name) {
                show_error($('.nname'), '昵称为空');
                $('.nname').focus();
                return;
            }
            if (!pid) {
                show_error($('.pid'), '部门未选择');
                return;
            }
            if (!position) {
                show_error($('.position'), '职务未选择');
                return;
            }
            var data = {user_id: user_id, username: username, password: password, name: name, pid: pid, position: position, tel: tel, sex: sex, email: email, status: status};
            $.ajax({
                url: '/user/ajax_edit',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res.code == -1) {
                        //登录过期
                        window.location.href = '/homes/index';
                        return;
                    }
                    if (res.code == -2) {
                        //权限不足
                        alert('权限不足');
                        return;
                    }
                    if (res.code == 1) {
                        //说明有错误
                        alert(res.msg);
                        //清空之前的错误提示
                        $('.middle').removeClass('text-danger').text('');
                        show_error($(res.class), res.msg);
                        return;
                    }
                    if (res.code == 0) {
                        //说明添加或修改成功
                        location.href = '/user/index';
                        return;
                    }
                    if (res.code == 2) {
                        //失败
                        alert(res.msg);
                        return;
                    }
                }
            });
        }
        //添加错误信息
        function show_error(obj, msg) {
            obj.parent().find('.middle').addClass('text-danger').text(msg);
        }
        //去掉错误信息
        function hide_error(obj) {
            obj.parent().find('.middle').removeClass('text-danger').text('');
        }
        //为input框加事件
        $('input.col-xs-10').keyup(function () {
            if ($(this).val() != '') {
                hide_error($(this));
            }
        });
    </script>

