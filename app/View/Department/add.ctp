
        <!--p class="btn btn-info btn-block" > <span style="font-size:16px;">添加成员</span> </p-->
        <div class="container" style='background-color:#fff;border-radius:4px;'>

            <div class="row" style='padding:20px 0;'>
                <div class="page-content">

                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="myTab">
                                    <li class="active">
                                        <a data-toggle="tab" href="#home">
                                            <i class="green icon-home bigger-110"></i>
                                            添加部门
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#profile">
                                            添加部门成员
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content">
                                    <div id="home" class="tab-pane in active">
                                        <form class="form-horizontal" role="form">
                                            <input type="hidden" id="d_id" name="d_id" value="<?php echo @$department['id'];?>" />
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门名称</label>

                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" placeholder="部门名称" class="col-xs-10 col-sm-5 d_name" value="<?php echo @$department['name'];?>" />
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">类型</label>

                                                <div class="col-sm-9">
                                                    <!--<input type="text" id="form-field-1" placeholder="Sex" class="col-xs-10 col-sm-5 sex" />-->
                                                    <select style="float: left;" name="del" class="del" id="form-field-1">                                              
                                                        <option value="0" <?php echo @$department['type'] == 1 ? 'selected' : '';?> >行政</option>
                                                        <option value="1" <?php echo @$department['type'] == 2 ? 'selected' : '';?> >科研</option>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门负责人</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="del" class="del" id="form-field-1">                                     
                                                        <?php foreach($fuzeren as $fk=>$fv){  ?>
                                                        <option value="<?php echo @$fv['User']['id'];?>" <?php echo @$fv['User']['id'] == @$department['user_id'] ? 'selected' : '';?> > <?php echo @$fv['User']['name'];?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2">部门介绍</label>

                                                <div class="col-sm-9">
                                                    <textarea style="float: left;"  cols="30" rows="5"  placeholder="部门介绍" class="d_desc"><?php echo @$department['description'];?></textarea>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">状态</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="del" class="del" id="form-field-1">                                              
                                                        <option value="0" <?php echo @$department['del'] == 0 ? 'selected' : '';?> >启用</option>
                                                        <option value="1" <?php echo @$department['del'] == 1 ? 'selected' : '';?> >停用</option>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="hr hr-24"></div>
                                            <div class="clearfix ">
                                                <div class="col-md-9">
                                                    <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                        <i class="icon-ok bigger-110"></i>
                                                        添加
                                                    </button>
                                                    &nbsp; &nbsp; &nbsp;
                                                    <button class="btn" type="reset">
                                                        <i class="icon-undo bigger-110"></i>
                                                        重置
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="profile" class="tab-pane">
                                        <form class="form-horizontal" role="form">
                                            <input type="hidden" id="d_id" name="d_id" value="<?php echo @$department['id'];?>" />
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="did" class="del" id="form-field-1">                                             <?php foreach($department as $dk => $dv){ ?>
                                                        <option value="<?php echo @$dv['type']; ?>" > <?php echo @$dv['type']; ?> </option>
                                           <?php } ?>
                                                    </select>
                                                   
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2">选择成员</label>

                                                <div class="col-sm-9">
                                                       <select style="float: left;" name="uid" class="del" id="form-field-1">                                             <?php foreach($user as $uk => $uv){ ?>
                                                        <option value="<?php echo @$uv['type']; ?>" > <?php echo @$uv['type']; ?> </option>
                                             <?php } ?>
                                                    </select>
                                                    
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="hr hr-24"></div>
                                            <div class="clearfix ">
                                                <div class=" col-md-9">
                                                    <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                        <i class="icon-ok bigger-110"></i>
                                                        添加
                                                    </button>
                                                    &nbsp; &nbsp; &nbsp;
                                                    <button class="btn" type="reset">
                                                        <i class="icon-undo bigger-110"></i>
                                                        重置
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>


                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
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
            var del = $('.del option:selected').val();

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
            var data = {user_id: user_id, username: username, password: password, name: name, pid: pid, position: position, tel: tel, sex: sex, email: email, del: del};
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
                        alert(res.msg);
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

