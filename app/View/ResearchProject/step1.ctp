
<p class="btn btn-info btn-block" > <span style="font-size:16px;"><?php echo !empty($user) ? '修改':'添加';?>项目</span> <a class="close" data-dismiss="modal">×</a></p>
        <div class="container" style='background-color:#fff;border-radius:4px;'>
           
        <div class="row" style='padding:20px 0;'>
            <div class="col-xs-12">
                <form class="form-horizontal" role="form">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo @$user['id'];?>" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">简称</label>
                        <input type="text" id="form-field-1" placeholder="简称" class="col-xs-10 col-sm-4 name" value="<?php echo @$user['user'];?>" />  
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-2">全称</label>
                        <input type="text" id="form-field-2" placeholder="全称" class="col-xs-10 col-sm-4 alias" value="<?php echo @$user['password'];?>" />           
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">资金性质</label>
                        <select style="float: left;" name="pid" class="col-sm-4 type" id="form-field-1">
                                <option value="1">零余额</option>
                                <option value="1">基本户</option>
                            </select>
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-2">金额</label>
                        <input type="text" id="form-field-2" readonly="" placeholder="金额" class="col-xs-10 col-sm-4 amount" value="<?php echo @$user['password'];?>" />           
                    </div>
                    <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">开始</label>
                        <input type="text" class="col-sm-4 form_datetime1 start_date">
                        <script type="text/javascript">
                            $(".form_datetime1").datetimepicker({
                                format: 'yyyy-mm-dd',
                                minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>
<!--                        <div class="input-append date" id="datetimepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                            <input class="span2" size="16" type="text" value="12-02-2012">
                            <span class="add-on"><i class="icon-th"></i></span>
                         </div> -->
                        <!--<input type="text" id="form-field-1" placeholder="Username" class="col-xs-10 col-sm-4 username" value="<?php echo @$user['user'];?>" />-->  
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-2">结束</label>
                        <input type="text" class="col-sm-4 form_datetime2 end_date">
                        <script type="text/javascript">
                            $(".form_datetime2").datetimepicker({
                                format: 'yyyy-mm-dd',
                                 minView: "month", //选择日期后，不会再跳转去选择时分秒 
                            });
                        </script>
<!--                        <div class="input-append date" id="datetimepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                            <input class="span2" size="16" type="text" value="12-02-2012">
                            <span class="add-on"><i class="icon-th"></i></span>
                         </div> -->
                        <!--<input type="password" id="form-field-2" placeholder="Password" class="col-xs-10 col-sm-4 pwd" value="<?php echo @$user['password'];?>" />-->           
                    </div>
                    <div class="form-group qdly">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">资金来源</label>
                        <select style="float: left;" name="pid" class="col-sm-4 pid"  id="form-field-1">
                                <option value="0">来源渠道</option>
                        </select>
                        <input type="text" readonly="readonly" id="form-field-2" placeholder="文号" class="col-xs-10 col-sm-2 file_number" value="文号" />           
                        <input type="text" readonly="readonly" id="form-field-2" placeholder="金额" class="col-xs-10 col-sm-2 amount_2" value="金额" />           
                        <span title="添加" class="glyphicon glyphicon-plus" aria-hidden="true" onclick="add_qdly();"></span>
                    </div>
                    <script type="text/javascript">
                        //添加渠道来源
                        function add_qdly() {
                            $('.qdly').last().after($('.demo_hide').clone().removeClass('demo_hide').css('display',''));
                        }
                        //删除当前接点
                        function del_qbly(obj) {
                            $(obj).parent().remove();
                        }
                    </script>
                    <div class="form-group qdly demo_hide" style="display:none;">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"></label>
                        <select style="float: left;" name="pid" class="col-sm-4 pid" id="form-field-1">
                                <option value="0">来源渠道</option>
                        </select>
                        <input type="text" id="form-field-2" placeholder="文号" class="col-xs-10 col-sm-2 file_number" value="<?php echo @$user['password'];?>" />           
                        <input type="text" id="form-field-2" placeholder="金额" class="col-xs-10 col-sm-2 amount_2" value="<?php echo @$user['password'];?>" />
                        <span title="删除" class="icon-trash bigger-130" onclick="del_qbly(this);"></span>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">项目概述</label>
                        <textarea class="col-sm-9 overview" placeholder="项目概述" ></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">备注</label>
                        <textarea class="col-sm-9 remark" placeholder="项目概述"></textarea>
                    </div>
                    
                    <div class="space-4"></div>


                    <div class="clearfix " style="text-align: right;">
                        <div class=" /*col-md-9*/">
                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                <i class="icon-ok bigger-110"></i>
                                下一步
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="icon-undo bigger-110"></i>
                                取消
                            </button>
                        </div>
                    </div>


                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div>


    <script type="text/javascript">
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

