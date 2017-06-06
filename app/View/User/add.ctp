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
        <div class="navbar navbar-default" id="navbar">
            <script type="text/javascript">
                try {
                    ace.settings.check('navbar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="navbar-container" id="navbar-container">
                <div class="navbar-header pull-left">
                    <a href="#" class="navbar-brand">
                        <small>
                            <i class="icon-leaf"></i>
                            Ace Admin
                        </small>
                    </a><!-- /.brand -->
                </div><!-- /.navbar-header -->

                <div class="navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        <li class="grey">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-tasks"></i>
                                <span class="badge badge-grey">4</span>
                            </a>

                            <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="icon-ok"></i>
                                    4 Tasks to complete
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Software Update</span>
                                            <span class="pull-right">65%</span>
                                        </div>

                                        <div class="progress progress-mini ">
                                            <div style="width:65%" class="progress-bar "></div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Hardware Upgrade</span>
                                            <span class="pull-right">35%</span>
                                        </div>

                                        <div class="progress progress-mini ">
                                            <div style="width:35%" class="progress-bar progress-bar-danger"></div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Unit Testing</span>
                                            <span class="pull-right">15%</span>
                                        </div>

                                        <div class="progress progress-mini ">
                                            <div style="width:15%" class="progress-bar progress-bar-warning"></div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Bug Fixes</span>
                                            <span class="pull-right">90%</span>
                                        </div>

                                        <div class="progress progress-mini progress-striped active">
                                            <div style="width:90%" class="progress-bar progress-bar-success"></div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        See tasks with details
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="purple">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-bell-alt icon-animated-bell"></i>
                                <span class="badge badge-important">8</span>
                            </a>

                            <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="icon-warning-sign"></i>
                                    8 Notifications
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">
                                                <i class="btn btn-xs no-hover btn-pink icon-comment"></i>
                                                New Comments
                                            </span>
                                            <span class="pull-right badge badge-info">+12</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="btn btn-xs btn-primary icon-user"></i>
                                        Bob just signed up as an editor ...
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">
                                                <i class="btn btn-xs no-hover btn-success icon-shopping-cart"></i>
                                                New Orders
                                            </span>
                                            <span class="pull-right badge badge-success">+8</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">
                                                <i class="btn btn-xs no-hover btn-info icon-twitter"></i>
                                                Followers
                                            </span>
                                            <span class="pull-right badge badge-info">+11</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        See all notifications
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="green">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-envelope icon-animated-vertical"></i>
                                <span class="badge badge-success">5</span>
                            </a>

                            <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                                <li class="dropdown-header">
                                    <i class="icon-envelope-alt"></i>
                                    5 Messages
                                </li>

                                <li>
                                    <a href="#">
                                        <img src="/assets/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Alex:</span>
                                                Ciao sociis natoque penatibus et auctor ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="icon-time"></i>
                                                <span>a moment ago</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <img src="/assets/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Susan:</span>
                                                Vestibulum id ligula porta felis euismod ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="icon-time"></i>
                                                <span>20 minutes ago</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <img src="/assets/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Bob:</span>
                                                Nullam quis risus eget urna mollis ornare ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="icon-time"></i>
                                                <span>3:15 pm</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="inbox.html">
                                        See all messages
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo" src="/assets/avatars/user.jpg" alt="Jason's Photo" />
                                <span class="user-info">
                                    <small>Welcome,</small>
                                    Jason
                                </span>

                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="#">
                                        <i class="icon-cog"></i>
                                        Settings
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="icon-user"></i>
                                        Profile
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="#">
                                        <i class="icon-off"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.ace-nav -->
                </div><!-- /.navbar-header -->
            </div><!-- /.container -->
        </div>

        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="main-container-inner">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>

                <div class="sidebar" id="sidebar">
                    <script type="text/javascript">
                        try {
                            ace.settings.check('sidebar', 'fixed')
                        } catch (e) {
                        }
                    </script>

                    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                            <button class="btn btn-success">
                                <i class="icon-signal"></i>
                            </button>

                            <button class="btn btn-info">
                                <i class="icon-pencil"></i>
                            </button>

                            <button class="btn btn-warning">
                                <i class="icon-group"></i>
                            </button>

                            <button class="btn btn-danger">
                                <i class="icon-cogs"></i>
                            </button>
                        </div>

                        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                            <span class="btn btn-success"></span>

                            <span class="btn btn-info"></span>

                            <span class="btn btn-warning"></span>

                            <span class="btn btn-danger"></span>
                        </div>
                    </div><!-- #sidebar-shortcuts -->

                    <ul class="nav nav-list">
                        <li class="active">
                            <a href="index.html">
                                <i class="icon-dashboard"></i>
                                <span class="menu-text"> 控制台 </span>
                            </a>
                        </li>

                        <li>
                            <a href="typography.html">
                                <i class="icon-text-width"></i>
                                <span class="menu-text"> 文字排版 </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-desktop"></i>
                                <span class="menu-text"> UI 组件 </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="elements.html">
                                        <i class="icon-double-angle-right"></i>
                                        组件
                                    </a>
                                </li>

                                <li>
                                    <a href="buttons.html">
                                        <i class="icon-double-angle-right"></i>
                                        按钮 &amp; 图表
                                    </a>
                                </li>

                                <li>
                                    <a href="treeview.html">
                                        <i class="icon-double-angle-right"></i>
                                        树菜单
                                    </a>
                                </li>

                                <li>
                                    <a href="jquery-ui.html">
                                        <i class="icon-double-angle-right"></i>
                                        jQuery UI
                                    </a>
                                </li>

                                <li>
                                    <a href="nestable-list.html">
                                        <i class="icon-double-angle-right"></i>
                                        可拖拽列表
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-toggle">
                                        <i class="icon-double-angle-right"></i>

                                        三级菜单
                                        <b class="arrow icon-angle-down"></b>
                                    </a>

                                    <ul class="submenu">
                                        <li>
                                            <a href="#">
                                                <i class="icon-leaf"></i>
                                                第一级
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="icon-pencil"></i>

                                                第四级
                                                <b class="arrow icon-angle-down"></b>
                                            </a>

                                            <ul class="submenu">
                                                <li>
                                                    <a href="#">
                                                        <i class="icon-plus"></i>
                                                        添加产品
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <i class="icon-eye-open"></i>
                                                        查看商品
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-list"></i>
                                <span class="menu-text"> 表格 </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="tables.html">
                                        <i class="icon-double-angle-right"></i>
                                        简单 &amp; 动态
                                    </a>
                                </li>

                                <li>
                                    <a href="jqgrid.html">
                                        <i class="icon-double-angle-right"></i>
                                        jqGrid plugin
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="active open">
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-edit"></i>
                                <span class="menu-text"> 表单 </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <li class="active">
                                    <a href="form-elements.html">
                                        <i class="icon-double-angle-right"></i>
                                        表单组件
                                    </a>
                                </li>

                                <li>
                                    <a href="form-wizard.html">
                                        <i class="icon-double-angle-right"></i>
                                        向导提示 &amp; 验证
                                    </a>
                                </li>

                                <li>
                                    <a href="wysiwyg.html">
                                        <i class="icon-double-angle-right"></i>
                                        编辑器
                                    </a>
                                </li>

                                <li>
                                    <a href="dropzone.html">
                                        <i class="icon-double-angle-right"></i>
                                        文件上传
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="widgets.html">
                                <i class="icon-list-alt"></i>
                                <span class="menu-text"> 插件 </span>
                            </a>
                        </li>

                        <li>
                            <a href="calendar.html">
                                <i class="icon-calendar"></i>

                                <span class="menu-text">
                                    日历
                                    <span class="badge badge-transparent tooltip-error" title="2&nbsp;Important&nbsp;Events">
                                        <i class="icon-warning-sign red bigger-130"></i>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="gallery.html">
                                <i class="icon-picture"></i>
                                <span class="menu-text"> 相册 </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-tag"></i>
                                <span class="menu-text"> 更多页面 </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="profile.html">
                                        <i class="icon-double-angle-right"></i>
                                        用户信息
                                    </a>
                                </li>

                                <li>
                                    <a href="inbox.html">
                                        <i class="icon-double-angle-right"></i>
                                        收件箱
                                    </a>
                                </li>

                                <li>
                                    <a href="pricing.html">
                                        <i class="icon-double-angle-right"></i>
                                        售价单
                                    </a>
                                </li>

                                <li>
                                    <a href="invoice.html">
                                        <i class="icon-double-angle-right"></i>
                                        购物车
                                    </a>
                                </li>

                                <li>
                                    <a href="timeline.html">
                                        <i class="icon-double-angle-right"></i>
                                        时间轴
                                    </a>
                                </li>

                                <li>
                                    <a href="login.html">
                                        <i class="icon-double-angle-right"></i>
                                        登录 &amp; 注册
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-file-alt"></i>

                                <span class="menu-text">
                                    其他页面
                                    <span class="badge badge-primary ">5</span>
                                </span>

                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="faq.html">
                                        <i class="icon-double-angle-right"></i>
                                        帮助
                                    </a>
                                </li>

                                <li>
                                    <a href="error-404.html">
                                        <i class="icon-double-angle-right"></i>
                                        404错误页面
                                    </a>
                                </li>

                                <li>
                                    <a href="error-500.html">
                                        <i class="icon-double-angle-right"></i>
                                        500错误页面
                                    </a>
                                </li>

                                <li>
                                    <a href="grid.html">
                                        <i class="icon-double-angle-right"></i>
                                        网格
                                    </a>
                                </li>

                                <li>
                                    <a href="blank.html">
                                        <i class="icon-double-angle-right"></i>
                                        空白页面
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.nav-list -->

                    <div class="sidebar-collapse" id="sidebar-collapse">
                        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                    </div>

                    <script type="text/javascript">
                        try {
                            ace.settings.check('sidebar', 'collapsed')
                        } catch (e) {
                        }
                    </script>
                </div>

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
                                <a href="#">Forms</a>
                            </li>
                            <li class="active">Form Elements</li>
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
                        <div class="page-header">
                            <h1>
                                Form Elements
                                <small>
                                    <i class="icon-double-angle-right"></i>
                                    Common form elements and layouts
                                </small>
                            </h1>
                        </div><!-- /.page-header -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->

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
                                                <option value="1" <?php echo @$user['del'] == 0 ? 'selected' : '';?> >启用</option>
                                                <option value="2" <?php echo @$user['del'] == 1 ? 'selected' : '';?> >停用</option>
                                            </select>
                                            <span class="help-inline col-xs-12 col-sm-7">
                                                <span class="middle"></span>
                                            </span>
                                        </div>
                                    </div>



                                    <div class="space-4"></div>
                                    <div class="space-4"></div>


                                    <div class="clearfix form-actions">
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
                                    <div class="hr hr-24"></div>
                                    <div class="space-24"></div>

                                </form>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div><!-- /.main-content -->

                <div class="ace-settings-container" id="ace-settings-container">
                    <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                        <i class="icon-cog bigger-150"></i>
                    </div>

                    <div class="ace-settings-box" id="ace-settings-box">
                        <div>
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-skin="default" value="#438EB9">#438EB9</option>
                                    <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                    <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                    <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                </select>
                            </div>
                            <span>&nbsp; Choose Skin</span>
                        </div>

                        <div>
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                            <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                            <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
                            <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                            <label class="lbl" for="ace-settings-add-container">
                                Inside
                                <b>.container</b>
                            </label>
                        </div>
                    </div>
                </div><!-- /#ace-settings-container -->
            </div><!-- /.main-container-inner -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="icon-double-angle-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->

        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->

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
            $('input.col-xs-10').keyup(function (){
                if ($(this).val() != '') {
                    hide_error($(this));
                }
            });
        </script>
    </body>
</html>
