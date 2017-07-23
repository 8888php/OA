
<p class="btn btn-info btn-block" > <span style="font-size:16px;"><?php echo !empty($user) ? '修改':'添加';?>成员</span> <a class="close" data-dismiss="modal">×</a></p>
        <div class="container" style='background-color:#fff;border-radius:4px;'>
           
        <div class="row" style='padding:20px 0;'>
            <div class="col-xs-12">
                <form class="form-horizontal" role="form">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo @$user['id'];?>" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">用户名</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" placeholder="Username" class="col-xs-10 col-sm-5 username" value="<?php echo @$user['user'];?>" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">密码</label>

                        <div class="col-sm-9">
                            <input type="password" id="form-field-2" placeholder="Password" class="col-xs-10 col-sm-5 pwd" value="<?php echo @$user['password'];?>" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">昵称</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" placeholder="Name" class="col-xs-10 col-sm-5 nname" value="<?php echo @$user['name'];?>" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门</label>

                        <div class="col-sm-9">
                            <!--<input type="text" id="form-field-1" placeholder="Name" class="col-xs-10 col-sm-5 position" />-->
                            <select style="float: left;" name="pid" class="pid" id="form-field-1" onchange='change_position(this.value);'>
                                <option value="0">请选择</option>
                                <?php foreach($department as $d) {?>
                                    <option value="<?php echo $d['Department']['id'];?>" <?php echo @$user['department_id'] == $d['Department']['id'] ? 'selected' : '';?> ><?php echo $d['Department']['name'];?></option>
                                <?php }?>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <script type="text/javascript">
                        //切换职务根据部门id
                        function change_position(d_id) {
                            if (d_id == 5) {
                                //财务部门 0,2
                                $('select.position').html(cw_option);
                            } else {
                                //其它部门 0,1
                                $('select.position').html(ot_option);
                            }
                        }
                        //定义 账务部门所对应的职务，其它部门所对应的职务
                        var cw_option = '<option value="0">请选择</option>';
                        var ot_option = '<option value="0">请选择</option>';
                        <?php foreach($position as $pk=>$pv) {?>
                                <?php if ($pv['Position']['type'] == 0){?>
                                    cw_option +='<option value="<?php echo $pv['Position']['id'];?>"><?php echo htmlspecialchars($pv['Position']['name'], ENT_QUOTES);?></option>';
                                    ot_option +='<option value="<?php echo $pv['Position']['id'];?>"><?php echo htmlspecialchars($pv['Position']['name'], ENT_QUOTES);?></option>';
                                <?php }else if($pv['Position']['type'] == 1) {?>
                                    ot_option +='<option value="<?php echo $pv['Position']['id'];?>"><?php echo htmlspecialchars($pv['Position']['name'], ENT_QUOTES);?></option>';
                                <?php }else if ($pv['Position']['type'] == 2){?>
                                    cw_option +='<option value="<?php echo $pv['Position']['id'];?>"><?php echo htmlspecialchars($pv['Position']['name'], ENT_QUOTES);?></option>';
                                <?php }?>
                        <?php }?>
                    </script>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">职务</label>

                        <div class="col-sm-9">
                            <!--<input type="text" id="form-field-1" placeholder="Name" class="col-xs-10 col-sm-5 position" />-->
                            <select style="float: left;" name="position" class="position" id="form-field-1">
                                <option value="0">请选择</option>
                                <?php if (!empty($user['position_id'])){?>
                                    <?php foreach($position as $p) {?>
                                    <option value="<?php echo $p['Position']['id'];?>" <?php echo @$user['position_id'] == $p['Position']['id'] ? 'selected' : '';?> ><?php echo $p['Position']['name'];?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">电话</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" placeholder="Tel" class="col-xs-10 col-sm-5 tel" value="<?php echo @$user['tel'];?>" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">性别</label>

                        <div class="col-sm-9">
                            <!--<input type="text" id="form-field-1" placeholder="Sex" class="col-xs-10 col-sm-5 sex" />-->
                            <select style="float: left;" name="sex" class="sex" id="form-field-1">
                                <option value="1" <?php echo @(int)$user['sex'] == 1 ? 'selected' : '';?> >男</option>
                                <option value="2" <?php echo @(int)$user['sex'] == 2 ? 'selected' : '';?>>女</option>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">邮箱</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" placeholder="Email" class="col-xs-10 col-sm-5 email" value="<?php echo @$user['email'];?>" />
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">有无审核权</label>
                        <div class="col-sm-9">
                            <select style="float: left;" name="approval" class="approval" id="form-field-1">         
                                <option value="1" <?php echo @$user['can_approval'] == 1 ? 'selected' : '';?> >无</option>
                                <option value="2" <?php echo @$user['can_approval'] == 2 ? 'selected' : '';?> >有</option>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">状态</label>
                        <div class="col-sm-9">
                            <select style="float: left;" name="status" class="status" id="form-field-1">         
                                <option value="0" <?php echo @$user['status'] == 0 ? 'selected' : '';?> >启用</option>
                                <option value="1" <?php echo @$user['status'] == 1 ? 'selected' : '';?> >停用</option>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>


                    <div class="clearfix " >
                        <div class=" col-md-9">
                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                <i class="icon-ok bigger-110"></i>
                                <?php echo !empty($user) ? '修改':'添加';?>
                            </button>
                             &nbsp; &nbsp; &nbsp;
                            <?php  if(!isset($user)){  ?>
                            <button class="btn" type="reset">
                                <i class="icon-undo bigger-110"></i>
                                重置
                            </button>
                            <?php } ?>
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
            var approval = $('.approval option:selected').val();
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
            var data = {user_id: user_id, username: username, password: password, name: name, pid: pid, position: position, tel: tel, sex: sex, email: email,approval:approval, status: status};
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

