<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 部门添加 </title>
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
        <link rel="stylesheet" href="assets/css/jquery.gritter.css" />        
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
        <style>
            .spinner-preview {
                width:100px;
                height:100px;
                text-align:center;
                margin-top:60px;
            }

            .dropdown-preview {
                margin:0 5px;
                display:inline-block;
            }
            .dropdown-preview  > .dropdown-menu {
                display: block;
                position: static;
                margin-bottom: 5px;
            }
        </style>
        <!-- ace settings handler -->
        <script src="/assets/js/ace-extra.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="/assets/js/html5shiv.js"></script>
        <script src="/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <?php echo $this->element('top'); ?>

        <div class="main-container-inner">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            <?php echo $this->element('left'); ?>

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try {
                            ace.settings.check('breadcrumbs', 'fixed')
                        } catch (e) {
                        }
                    </script>

                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a href="#">Home</a>
                        </li>

                        <li>
                            <a href="#">系统设置</a>
                        </li>
                        <li class="active">部门职务添加</li>
                    </ul><!-- .breadcrumb -->

                    <div class="nav-search" id="nav-search">
                        <form class="form-search">
                            <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                <i class="icon-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div><!-- #nav-search -->
                </div>

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
                                            
                                            <li>
                                                <a data-toggle="tab" href="#dropdown1">
                                                    添加职务
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
                                                            <!--<input type="text" id="form-field-1" placeholder="Sex" class="col-xs-10 col-sm-5 sex" />-->
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
                                                            <!--<input type="t" id="form-field-2" placeholder="部门介绍" class="col-xs-10 col-sm-5 pwd" value="<?php echo @$user['password'];?>" />-->
                                                            <textarea style="float: left;" placeholder="部门介绍" class="d_desc"><?php echo @$department['description'];?></textarea>
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
                                                                <option value="0" <?php echo @$department['del'] == 0 ? 'selected' : '';?> >启用</option>
                                                                <option value="1" <?php echo @$department['del'] == 1 ? 'selected' : '';?> >停用</option>
                                                            </select>
                                                            <span class="help-inline col-xs-12 col-sm-7">
                                                                <span class="middle"></span>
                                                            </span>
                                                        </div>
                                                    </div>



                                                    <div class="space-4"></div>
                                                    <div class="space-4"></div>

                                                    <div class="hr hr-24"></div>
                                                    <div class="clearfix ">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                                <i class="icon-ok bigger-110"></i>
                                                                Submit
                                                            </button>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <button class="btn" type="reset">
                                                                <i class="icon-undo bigger-110"></i>
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="profile" class="tab-pane">
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
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">部门介绍</label>

                                                        <div class="col-sm-9">
                                                            <!--<input type="t" id="form-field-2" placeholder="部门介绍" class="col-xs-10 col-sm-5 pwd" value="<?php echo @$user['password'];?>" />-->
                                                            <textarea style="float: left;" placeholder="部门介绍" class="d_desc"><?php echo @$department['description'];?></textarea>
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
                                                                <option value="0" <?php echo @$department['del'] == 0 ? 'selected' : '';?> >启用</option>
                                                                <option value="1" <?php echo @$department['del'] == 1 ? 'selected' : '';?> >停用</option>
                                                            </select>
                                                            <span class="help-inline col-xs-12 col-sm-7">
                                                                <span class="middle"></span>
                                                            </span>
                                                        </div>
                                                    </div>



                                                    <div class="space-4"></div>
                                                    <div class="space-4"></div>

                                                    <div class="hr hr-24"></div>
                                                    <div class="clearfix ">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                                <i class="icon-ok bigger-110"></i>
                                                                Submit
                                                            </button>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <button class="btn" type="reset">
                                                                <i class="icon-undo bigger-110"></i>
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="dropdown1" class="tab-pane">
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
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">部门介绍</label>

                                                        <div class="col-sm-9">
                                                            <!--<input type="t" id="form-field-2" placeholder="部门介绍" class="col-xs-10 col-sm-5 pwd" value="<?php echo @$user['password'];?>" />-->
                                                            <textarea style="float: left;" placeholder="部门介绍" class="d_desc"><?php echo @$department['description'];?></textarea>
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
                                                                <option value="0" <?php echo @$department['del'] == 0 ? 'selected' : '';?> >启用</option>
                                                                <option value="1" <?php echo @$department['del'] == 1 ? 'selected' : '';?> >停用</option>
                                                            </select>
                                                            <span class="help-inline col-xs-12 col-sm-7">
                                                                <span class="middle"></span>
                                                            </span>
                                                        </div>
                                                    </div>



                                                    <div class="space-4"></div>
                                                    <div class="space-4"></div>

                                                    <div class="hr hr-24"></div>
                                                    <div class="clearfix ">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button class="btn btn-info" type="button"  onclick="ajax_submit();">
                                                                <i class="icon-ok bigger-110"></i>
                                                                Submit
                                                            </button>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <button class="btn" type="reset">
                                                                <i class="icon-undo bigger-110"></i>
                                                                Reset
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
            </div><!-- /.main-content -->

            <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="icon-cog bigger-150"></i>
                </div>

                <?php echo $this->element('acebox'); ?>
            </div><!-- /#ace-settings-container -->
        </div><!-- /.main-container-inner -->

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

    <!--[if !IE]> -->

    <script type="text/javascript">
                                                                window.jQuery || document.write("<script src='/assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
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

    <script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/jquery.easy-pie-chart.min.js"></script>
    <script src="assets/js/jquery.gritter.min.js"></script>
    <script src="assets/js/spin.min.js"></script>

    <!-- ace scripts -->

    <script src="/assets/js/ace-elements.min.js"></script>
    <script src="/assets/js/ace.min.js"></script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        jQuery(function ($) {
            /**
             $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
             console.log(e.target.getAttribute("href"));
             })
             */


            $('#accordion-style').on('click', function (ev) {
                var target = $('input', ev.target);
                var which = parseInt(target.val());
                if (which == 2)
                    $('#accordion').addClass('accordion-style2');
                else
                    $('#accordion').removeClass('accordion-style2');
            });


            var oldie = /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase());
            $('.easy-pie-chart.percentage').each(function () {
                $(this).easyPieChart({
                    barColor: $(this).data('color'),
                    trackColor: '#EEEEEE',
                    scaleColor: false,
                    lineCap: 'butt',
                    lineWidth: 8,
                    animate: oldie ? false : 1000,
                    size: 75
                }).css('color', $(this).data('color'));
            });

            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({html: true});


            $('#gritter-regular').on(ace.click_event, function () {
                $.gritter.add({
                    title: 'This is a regular notice!',
                    text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="blue">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                    image: $path_assets + '/avatars/avatar1.png',
                    sticky: false,
                    time: '',
                    class_name: (!$('#gritter-light').get(0).checked ? 'gritter-light' : '')
                });

                return false;
            });

            $('#gritter-sticky').on(ace.click_event, function () {
                var unique_id = $.gritter.add({
                    title: 'This is a sticky notice!',
                    text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="red">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                    image: $path_assets + '/avatars/avatar.png',
                    sticky: true,
                    time: '',
                    class_name: 'gritter-info' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                });

                return false;
            });


            $('#gritter-without-image').on(ace.click_event, function () {
                $.gritter.add({
                    // (string | mandatory) the heading of the notification
                    title: 'This is a notice without an image!',
                    // (string | mandatory) the text inside the notification
                    text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="orange">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                    class_name: 'gritter-success' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                });

                return false;
            });


            $('#gritter-max3').on(ace.click_event, function () {
                $.gritter.add({
                    title: 'This is a notice with a max of 3 on screen at one time!',
                    text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="green">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                    image: $path_assets + '/avatars/avatar3.png',
                    sticky: false,
                    before_open: function () {
                        if ($('.gritter-item-wrapper').length >= 3)
                        {
                            return false;
                        }
                    },
                    class_name: 'gritter-warning' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                });

                return false;
            });


            $('#gritter-center').on(ace.click_event, function () {
                $.gritter.add({
                    title: 'This is a centered notification',
                    text: 'Just add a "gritter-center" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                    class_name: 'gritter-info gritter-center' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                });

                return false;
            });

            $('#gritter-error').on(ace.click_event, function () {
                $.gritter.add({
                    title: 'This is a warning notification',
                    text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                    class_name: 'gritter-error' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                });

                return false;
            });


            $("#gritter-remove").on(ace.click_event, function () {
                $.gritter.removeAll();
                return false;
            });


            ///////


            $("#bootbox-regular").on(ace.click_event, function () {
                bootbox.prompt("What is your name?", function (result) {
                    if (result === null) {
                        //Example.show("Prompt dismissed");
                    } else {
                        //Example.show("Hi <b>"+result+"</b>");
                    }
                });
            });

            $("#bootbox-confirm").on(ace.click_event, function () {
                bootbox.confirm("Are you sure?", function (result) {
                    if (result) {
                        //
                    }
                });
            });

            $("#bootbox-options").on(ace.click_event, function () {
                bootbox.dialog({
                    message: "<span class='bigger-110'>I am a custom dialog with smaller buttons</span>",
                    buttons:
                            {
                                "success":
                                        {
                                            "label": "<i class='icon-ok'></i> Success!",
                                            "className": "btn-sm btn-success",
                                            "callback": function () {
                                                //Example.show("great success");
                                            }
                                        },
                                "danger":
                                        {
                                            "label": "Danger!",
                                            "className": "btn-sm btn-danger",
                                            "callback": function () {
                                                //Example.show("uh oh, look out!");
                                            }
                                        },
                                "click":
                                        {
                                            "label": "Click ME!",
                                            "className": "btn-sm btn-primary",
                                            "callback": function () {
                                                //Example.show("Primary button");
                                            }
                                        },
                                "button":
                                        {
                                            "label": "Just a button...",
                                            "className": "btn-sm"
                                        }
                            }
                });
            });



            $('#spinner-opts small').css({display: 'inline-block', width: '60px'})

            var slide_styles = ['', 'green', 'red', 'purple', 'orange', 'dark'];
            var ii = 0;
            $("#spinner-opts input[type=text]").each(function () {
                var $this = $(this);
                $this.hide().after('<span />');
                $this.next().addClass('ui-slider-small').
                        addClass("inline ui-slider-" + slide_styles[ii++ % slide_styles.length]).
                        css({'width': '125px'}).slider({
                    value: parseInt($this.val()),
                    range: "min",
                    animate: true,
                    min: parseInt($this.data('min')),
                    max: parseInt($this.data('max')),
                    step: parseFloat($this.data('step')),
                    slide: function (event, ui) {
                        $this.attr('value', ui.value);
                        spinner_update();
                    }
                });
            });





            $.fn.spin = function (opts) {
                this.each(function () {
                    var $this = $(this),
                            data = $this.data();

                    if (data.spinner) {
                        data.spinner.stop();
                        delete data.spinner;
                    }
                    if (opts !== false) {
                        data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
                    }
                });
                return this;
            };

            function spinner_update() {
                var opts = {};
                $('#spinner-opts input[type=text]').each(function () {
                    opts[this.name] = parseFloat(this.value);
                });
                $('#spinner-preview').spin(opts);
            }



            $('#id-pills-stacked').removeAttr('checked').on('click', function () {
                $('.nav-pills').toggleClass('nav-stacked');
            });


        });
    </script>

    <script type="text/javascript">
        //提交内容
        function ajax_submit() {
            var d_id = $('#d_id').val();
            var d_name = $('.d_name').val();
            var d_desc = $('.d_desc').val();
            var del = $('.del option:selected').val();

            if (!d_name) {
                show_error($('.d_name'), '部门名称为空');
                $('.d_name').focus();
                return;
            }
            if (!d_desc) {
                show_error($('.d_desc'), '部门介绍为空');
                $('.d_desc').focus();
                return;
            }
            var data = {d_id: d_id, d_name: d_name, d_desc: d_desc, del: del};
            $.ajax({
                url: '/user/ajax_bumen_edit',
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
