
        <p class="btn btn-info btn-block" > <span style="font-size:16px;"></span><a class="close" data-dismiss="modal">×</a> </p>
        <div class="container" style='background-color:#fff;border-radius:4px;'>

            <div class="row" style='padding:10px 0;'>
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
                            <?php if(@$depArr['Department']['id']){  ?>
                                    <li>
                                        <a data-toggle="tab" href="#profile">
                                            添加部门成员
                                        </a>
                                    </li>
                            <?php }  ?>
                                </ul>

                                <div class="tab-content">
                                    <div id="home" class="tab-pane in active">
                                        <form class="form-horizontal" role="form">
                                            <input type="hidden" id="d_id" name="d_id" value="<?php echo @$depArr['Department']['id'];?>" />
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门名称</label>

                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" placeholder="部门名称" class="col-xs-10 col-sm-5 d_name" value="<?php echo @$depArr['Department']['name'];?>" />
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
                                                    <select style="float: left;" name="type" class="type" id="form-field-1">                                              
                                                        <option value="1" <?php echo @$depArr['Department']['type'] == 1 ? 'selected' : '';?> >行政</option>
                                                        <option value="2" <?php echo @$depArr['Department']['type'] == 2 ? 'selected' : '';?> >科研</option>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>
                                            <?php if(@$depArr['Department']['id']){  ?>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门负责人</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="fzr" class="fzr" id="form-field-1">
                                                        <option value="0" > 请选择 </option>
                                                        <?php foreach($fuzeren as $fk=>$fv){  ?>
                                                        <option value="<?php echo @$fv['User']['id'];?>" <?php echo @$fv['User']['id'] == @$depArr['Department']['user_id'] ? 'selected' : '';?> > <?php echo @$fv['User']['name'];?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php }?>
                                            
                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-11">分管所领导</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="sld" class="sld" id="form-field-11">
                                                        <option value="0" > 请选择 </option>
                                                        <?php foreach($suolingdao as $sk=>$sv){  ?>
                                                        <option value="<?php echo @$sk;?>" <?php echo @$sk == @$depArr['Department']['sld'] ? 'selected' : '';?> > <?php echo @$sv;?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="space-4"></div>
                                            <?php if(@$depArr['Department']['id'] == $caiwu_dep_id ){  ?>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">出纳</label>

                                                <div class="col-sm-9">
                                                    <select style="float: left;" name="chuna" class="chuna" id="form-field-1">
                                                        <option value="0" > 请选择 </option>
                                                        <?php foreach($fuzeren as $fk=>$fv){  ?>
                                                        <option value="<?php echo @$fv['User']['id'];?>" <?php echo @$fv['User']['id'] == @$chuna['id'] ? 'selected' : '';?> > <?php echo @$fv['User']['name'];?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php }?>
                                            
                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2">部门介绍</label>

                                                <div class="col-sm-9">
                                                    <textarea style="float: left;"  cols="30" rows="3"  placeholder="部门介绍" class="d_desc"><?php echo @$depArr['Department']['description'];?></textarea>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

              

                                            <div class="space-4"></div>

                                            <div class="hr hr-24"></div>
                                            <div class="clearfix ">
                                                <div class="col-md-7">
                                                    <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                        <i class="icon-ok bigger-110"></i>
                                                        <?php echo !empty($depArr['Department']['id']) ? '修改' : '添加';  ?>
                                                    </button>
                                                    &nbsp; &nbsp; &nbsp;
                                                    <!--button class="btn" type="reset">
                                                        <i class="icon-undo bigger-110"></i>
                                                        重置
                                                    </button-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                          <?php if(@$depArr['Department']['id']){  ?>
                                    <div id="profile" class="tab-pane">
                                        <form class="form-horizontal" role="form">
                                            <input type="hidden" id="dep_id" name="dep_id" value="<?php echo @$depArr['Department']['id']; ?>" />
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">部门</label>

                                                <div class="col-sm-9">
                                                    <input type="text" id="form-field-1" placeholder="部门名称" class="col-xs-10 col-sm-5 " value="<?php echo @$depArr['Department']['name'];?>" disabled  />
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2">选择成员</label>

                                                <div class="col-sm-9">
                                                      <select style="float: left;" name="members" class="members" id="form-field-1">
                                                        <option value="0" > 暂不添加 </option>
                                                        <?php foreach($members as $mk=>$mv){  ?>
                                                        <option value="<?php echo @$mk; ?>" id="mem<?php echo @$mk; ?>" > <?php echo @$mv; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="help-inline col-xs-12 col-sm-7">
                                                        <span class="middle"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-4 success">  </div>

                                            <div class="hr hr-24"></div>
                                            <div class="clearfix ">
                                                <div class=" col-md-7">
                                                    <button class="btn btn-info" type="button"  onclick="ajax_member();">
                                                        <i class="icon-ok bigger-110"></i>
                                                        添加
                                                    </button>
                                                    &nbsp; &nbsp; &nbsp;
                                                    <!--button class="btn" type="reset">
                                                        <i class="icon-undo bigger-110"></i>
                                                        重置
                                                    </button-->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                          <?php } ?>

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
            var did = $('#d_id').val();
            var dname = $('.d_name').val();
            var desc = $('.d_desc').val();
            var type = $('.type option:selected').val();
            var fzr = $('.fzr option:selected').val();
            var sld = $('.sld option:selected').val();
            var chuna = $('.chuna option:selected').val();

            if (!dname) {
                show_error($('.d_name'), '部门名为空');
                $('.d_name').focus();
                return;
            }
            if (!type) {
                show_error($('.type'), '请选择类型');
                $('.type').focus();
                return;
            }
            if (!sld) {
                show_error($('.sld'), '请选择所领导');
                $('.sld').focus();
                return;
            }
            
            var data = {id: did, name: dname, desc: desc, type: type, fzr: fzr,sld: sld, chuna: chuna};
            $.ajax({
                url: '/department/ajax_edit',
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
                       //location.href = '/department/index';
                       window.history.back();
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
        
        function ajax_member(){
            var depid = $('#dep_id').val();
            var members = $('.members option:selected').val(); 
            if (!members) {
                show_error($('.members'), '请选择成员');
                $('.members').focus();
                return;
            }
            if (!depid) {
                show_error($('#dep_id'), '添加失败');
                $('#dep_id').focus();
                return;
            }
             var data = {id: depid, member: members};
            $.ajax({
                url: '/department/ajax_member',
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
                        $('#mem'+depid).remove();
                        show_error($('.success'), res.msg);
                       //location.href = '/department/index';
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
        
    </script>

