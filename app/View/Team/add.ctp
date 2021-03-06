
        <p class="btn btn-info btn-block" > <span style="font-size:16px;"><?php echo !empty($teamArr) ? '修改':'添加'?>团队</span> <a class="close" data-dismiss="modal">×</a></p>
        <div class="container" style='background-color:#fff;border-radius:4px;'>

            <div class="row" style='padding:20px 0;'>
                <div class="col-xs-12">
                    <form class="form-horizontal" role="form">
                        <input type="hidden" id="pid" name="pid" value="<?php echo @$teamArr['Team']['id'];?>" />
                    <div class="form-group">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">团队名</label>

                            <div class="col-sm-9">
                                <input type="text" id="form-field-1" placeholder="" class="col-xs-10 col-sm-5 name" value="<?php echo @$teamArr['Team']['name'];?>" />
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <span class="middle"></span>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">团队简介</label>

                            <div class="col-sm-9">
                                <textarea style="float: left;" cols="40" rows="5" placeholder="团队简介" class="desc"><?php echo @$teamArr['Team']['description'];?></textarea>
                                <span class="help-inline col-xs-12 col-sm-7">
                                    <span class="middle"></span>
                                </span>
                            </div>
                        </div>

                        <div class="space-4"></div>


                        <div class="clearfix " >
                            <div class=" col-md-7">
                                <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                    <i class="icon-ok bigger-110"></i>
                                    <?php echo !empty($teamArr) ? '修改':'添加'?>
                                </button>
                                &nbsp; &nbsp; &nbsp;
                            </div>
                        </div>


                    </form>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>

    <script type="text/javascript">
        //提交内容
        function ajax_submit() {
            var id = $('#pid').val();
            var name = $('.name').val();
            var desc = $('.desc').val();

            if (!name) {
                show_error($('.name'), '团队名为空');
                $('.name').focus();
                return;
            }
            var data = {id: id,name: name, desc: desc};
            $.ajax({
                url: '/Team/ajax_edit',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res.code == -1) {
                        //登录过期
                        window.location.href = '/Team/index';
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
                       location.href = '/Team/index';
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

