<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 管理员添加 </title>
        <meta name="keywords" content="OA" />
        <meta name="description" content="OA" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- basic styles -->
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/assets/css/font-awesome.min.css" />
        <!--[if IE 7]>
          <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />
        <![endif]-->
        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="/assets/css/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" href="/assets/css/chosen.css" />
        <link rel="stylesheet" href="/assets/css/datepicker.css" />
        <link rel="stylesheet" href="/assets/css/bootstrap-timepicker.css" />
        <link rel="stylesheet" href="/assets/css/daterangepicker.css" />
        <link rel="stylesheet" href="/assets/css/colorpicker.css" />
        <!-- fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
        <!-- ace styles -->
        <link rel="stylesheet" href="/assets/css/ace.min.css" />
        <link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="/assets/css/ace-skins.min.css" />
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
        <![endif]-->
        <!-- inline styles related to this page -->
        <!-- ace settings handler -->
        <script src="/assets/js/ace-extra.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="/assets/js/html5shiv.js"></script>
        <script src="/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <p class="btn btn-info btn-block" > <span style="font-size:16px;">添加成员</span> </p>
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
                            <select style="float: left;" name="pid" class="pid" id="form-field-1">
                                <option value="0">请选择</option>
                                <option value="1" <?php echo @$user['department_id'] == 1 ? 'selected' : '';?> >办公室</option>
                                <option value="2" <?php echo @$user['department_id'] == 2 ? 'selected' : '';?>>党委办公室</option>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">职务</label>

                        <div class="col-sm-9">
                            <!--<input type="text" id="form-field-1" placeholder="Name" class="col-xs-10 col-sm-5 position" />-->
                            <select style="float: left;" name="position" class="position" id="form-field-1">
                                <option value="0">请选择</option>
                                <option value="1" <?php echo @$user['position_id'] == 1 ? 'selected' : '';?> >局长</option>
                                <option value="2" <?php echo @$user['position_id'] == 2 ? 'selected' : '';?> >项目负责人</option>
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
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">状态</label>

                        <div class="col-sm-9">
                            <!--<input type="text" id="form-field-1" placeholder="Sex" class="col-xs-10 col-sm-5 sex" />-->
                            <select style="float: left;" name="del" class="del" id="form-field-1">                                              
                                <option value="0" <?php echo @$user['del'] == 0 ? 'selected' : '';?> >启用</option>
                                <option value="1" <?php echo @$user['del'] == 1 ? 'selected' : '';?> >停用</option>
                            </select>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle"></span>
                            </span>
                        </div>
                    </div>



                    <div class="space-4"></div>
                    <div class="space-4"></div>


                    <div class="clearfix " >
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
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div>
    </body>

    <!-- basic scripts -->
    <!--[if !IE]> -->
    <!--<script src="/js/jquery-2.0.3.min.js"></script>-->
    <!-- <![endif]-->
    <!--[if IE]>
<script src="/js/jquery-1.10.2.min.js"></script>
<![endif]-->
    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
    </script>
    <!-- <![endif]-->
    <!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

    <script type="text/javascript">
        if ("ontouchend" in document)
            document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/typeahead-bs2.min.js"></script>

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
      <script src="/assets/js/excanvas.min.js"></script>
    <![endif]-->

    <script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/js/chosen.jquery.min.js"></script>
    <script src="/assets/js/fuelux/fuelux.spinner.min.js"></script>
    <script src="/assets/js/date-time/bootstrap-datepicker.min.js"></script>
    <script src="/assets/js/date-time/bootstrap-timepicker.min.js"></script>
    <script src="/assets/js/date-time/moment.min.js"></script>
    <script src="/assets/js/date-time/daterangepicker.min.js"></script>
    <script src="/assets/js/bootstrap-colorpicker.min.js"></script>
    <script src="/assets/js/jquery.knob.min.js"></script>
    <script src="/assets/js/jquery.autosize.min.js"></script>
    <script src="/assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
    <script src="/assets/js/jquery.maskedinput.min.js"></script>
    <script src="/assets/js/bootstrap-tag.min.js"></script>

    <!-- ace scripts -->

    <script src="/assets/js/ace-elements.min.js"></script>
    <script src="/assets/js/ace.min.js"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        jQuery(function ($) {
            $('#id-disable-check').on('click', function () {
                var inp = $('#form-input-readonly').get(0);
                if (inp.hasAttribute('disabled')) {
                    inp.setAttribute('readonly', 'true');
                    inp.removeAttribute('disabled');
                    inp.value = "This text field is readonly!";
                } else {
                    inp.setAttribute('disabled', 'disabled');
                    inp.removeAttribute('readonly');
                    inp.value = "This text field is disabled!";
                }
            });


            $(".chosen-select").chosen();
            $('#chosen-multiple-style').on('click', function (e) {
                var target = $(e.target).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2)
                    $('#form-field-select-4').addClass('tag-input-style');
                else
                    $('#form-field-select-4').removeClass('tag-input-style');
            });


            $('[data-rel=tooltip]').tooltip({container: 'body'});
            $('[data-rel=popover]').popover({container: 'body'});

            $('textarea[class*=autosize]').autosize({append: "\n"});
            $('textarea.limited').inputlimiter({
                remText: '%n character%s remaining...',
                limitText: 'max allowed : %n.'
            });

            $.mask.definitions['~'] = '[+-]';
            $('.input-mask-date').mask('99/99/9999');
            $('.input-mask-phone').mask('(999) 999-9999');
            $('.input-mask-eyescript').mask('~9.99 ~9.99 999');
            $(".input-mask-product").mask("a*-999-a999", {placeholder: " ", completed: function () {
                    alert("You typed the following: " + this.val());
                }});



            $("#input-size-slider").css('width', '200px').slider({
                value: 1,
                range: "min",
                min: 1,
                max: 8,
                step: 1,
                slide: function (event, ui) {
                    var sizing = ['', 'input-sm', 'input-lg', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
                    var val = parseInt(ui.value);
                    $('#form-field-4').attr('class', sizing[val]).val('.' + sizing[val]);
                }
            });

            $("#input-span-slider").slider({
                value: 1,
                range: "min",
                min: 1,
                max: 12,
                step: 1,
                slide: function (event, ui) {
                    var val = parseInt(ui.value);
                    $('#form-field-5').attr('class', 'col-xs-' + val).val('.col-xs-' + val);
                }
            });


            $("#slider-range").css('height', '200px').slider({
                orientation: "vertical",
                range: true,
                min: 0,
                max: 100,
                values: [17, 67],
                slide: function (event, ui) {
                    var val = ui.values[$(ui.handle).index() - 1] + "";

                    if (!ui.handle.firstChild) {
                        $(ui.handle).append("<div class='tooltip right in' style='display:none;left:16px;top:-6px;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>");
                    }
                    $(ui.handle.firstChild).show().children().eq(1).text(val);
                }
            }).find('a').on('blur', function () {
                $(this.firstChild).hide();
            });

            $("#slider-range-max").slider({
                range: "max",
                min: 1,
                max: 10,
                value: 2
            });

            $("#eq > span").css({width: '90%', 'float': 'left', margin: '15px'}).each(function () {
                // read initial values from markup and remove that
                var value = parseInt($(this).text(), 10);
                $(this).empty().slider({
                    value: value,
                    range: "min",
                    animate: true

                });
            });


            $('#id-input-file-1 , #id-input-file-2').ace_file_input({
                no_file: 'No File ...',
                btn_choose: 'Choose',
                btn_change: 'Change',
                droppable: false,
                onchange: null,
                thumbnail: false //| true | large
                        //whitelist:'gif|png|jpg|jpeg'
                        //blacklist:'exe|php'
                        //onchange:''
                        //
            });

            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'icon-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
                        //,icon_remove:null//set null, to hide remove/reset button
                        /**,before_change:function(files, dropped) {
                         //Check an example below
                         //or examples/file-upload.html
                         return true;
                         }*/
                        /**,before_remove : function() {
                         return true;
                         }*/
                ,
                preview_error: function (filename, error_code) {
                    //name of the file that failed
                    //error_code values
                    //1 = 'FILE_LOAD_FAILED',
                    //2 = 'IMAGE_LOAD_FAILED',
                    //3 = 'THUMBNAIL_FAILED'
                    //alert(error_code);
                }

            }).on('change', function () {
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });


            //dynamically change allowed formats by changing before_change callback function
            $('#id-file-format').removeAttr('checked').on('change', function () {
                var before_change
                var btn_choose
                var no_icon
                if (this.checked) {
                    btn_choose = "Drop images here or click to choose";
                    no_icon = "icon-picture";
                    before_change = function (files, dropped) {
                        var allowed_files = [];
                        for (var i = 0; i < files.length; i++) {
                            var file = files[i];
                            if (typeof file === "string") {
                                //IE8 and browsers that don't support File Object
                                if (!(/\.(jpe?g|png|gif|bmp)$/i).test(file))
                                    return false;
                            } else {
                                var type = $.trim(file.type);
                                if ((type.length > 0 && !(/^image\/(jpe?g|png|gif|bmp)$/i).test(type))
                                        || (type.length == 0 && !(/\.(jpe?g|png|gif|bmp)$/i).test(file.name))//for android's default browser which gives an empty string for file.type
                                        )
                                    continue;//not an image so don't keep this file
                            }

                            allowed_files.push(file);
                        }
                        if (allowed_files.length == 0)
                            return false;

                        return allowed_files;
                    }
                } else {
                    btn_choose = "Drop files here or click to choose";
                    no_icon = "icon-cloud-upload";
                    before_change = function (files, dropped) {
                        return files;
                    }
                }
                var file_input = $('#id-input-file-3');
                file_input.ace_file_input('update_settings', {'before_change': before_change, 'btn_choose': btn_choose, 'no_icon': no_icon})
                file_input.ace_file_input('reset_input');
            });




            $('#spinner1').ace_spinner({value: 0, min: 0, max: 200, step: 10, btn_up_class: 'btn-info', btn_down_class: 'btn-info'})
                    .on('change', function () {
                        //alert(this.value)
                    });
            $('#spinner2').ace_spinner({value: 0, min: 0, max: 10000, step: 100, touch_spinner: true, icon_up: 'icon-caret-up', icon_down: 'icon-caret-down'});
            $('#spinner3').ace_spinner({value: 0, min: -100, max: 100, step: 10, on_sides: true, icon_up: 'icon-plus smaller-75', icon_down: 'icon-minus smaller-75', btn_up_class: 'btn-success', btn_down_class: 'btn-danger'});



            $('.date-picker').datepicker({autoclose: true}).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });
            $('input[name=date-range-picker]').daterangepicker().prev().on(ace.click_event, function () {
                $(this).next().focus();
            });

            $('#timepicker1').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            }).next().on(ace.click_event, function () {
                $(this).prev().focus();
            });

            $('#colorpicker1').colorpicker();
            $('#simple-colorpicker-1').ace_colorpicker();


            $(".knob").knob();


            //we could just set the data-provide="tag" of the element inside HTML, but IE8 fails!
            var tag_input = $('#form-field-tags');
            if (!(/msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())))
            {
                tag_input.tag(
                        {
                            placeholder: tag_input.attr('placeholder'),
                            //enable typeahead by specifying the source array
                            source: ace.variable_US_STATES, //defined in ace.js >> ace.enable_search_ahead
                        }
                );
            } else {
                //display a textarea for old IE, because it doesn't support this plugin or another one I tried!
                tag_input.after('<textarea id="' + tag_input.attr('id') + '" name="' + tag_input.attr('name') + '" rows="3">' + tag_input.val() + '</textarea>').remove();
                //$('#form-field-tags').autosize({append: "\n"});
            }




            /////////
            $('#modal-form input[type=file]').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'icon-cloud-upload',
                droppable: true,
                thumbnail: 'large'
            })

            //chosen plugin inside a modal will have a zero width because the select element is originally hidden
            //and its width cannot be determined.
            //so we set the width after modal is show
            $('#modal-form').on('shown.bs.modal', function () {
                $(this).find('.chosen-container').each(function () {
                    $(this).find('a:first-child').css('width', '210px');
                    $(this).find('.chosen-drop').css('width', '210px');
                    $(this).find('.chosen-search input').css('width', '200px');
                });
            })
            /**
             //or you can activate the chosen plugin after modal is shown
             //this way select element becomes visible with dimensions and chosen works as expected
             $('#modal-form').on('shown', function () {
             $(this).find('.modal-chosen').chosen();
             })
             */

        });
    </script>

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
</body>
</html>
