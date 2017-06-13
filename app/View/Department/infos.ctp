<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 部门详情 </title>
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
        <?php echo $this->element('top'); ?>

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
                        <a href="#">党政部门</a>
                    </li>
                    <li class="active"><?php echo $depInfo['Department']['name']; ?></li>
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

                        <div class="space-6"></div>

                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="widget-box transparent invoice-box">
                                    <div class="widget-header widget-header-large">
                                        <h3 class="grey lighter pull-left position-relative">
                                            <?php
                                                if($depInfo['Department']['type'] == 1){
                                                    echo '<i class="icon-pencil blue"></i>';
                                                }else{
                                                    echo '<i class="icon-leaf green"></i>';
                                                }
                                             echo $depInfo['Department']['name'];
                                             ?>
                                        </h3>

                                        <div class="widget-toolbar no-border invoice-info">
                                            <br />
                                            <span class="invoice-info-label blue">创建时间：</span>
                                            <span class="blue"><?php echo date('Y-m-d',$depInfo['Department']['ctime']);?></span>
                                        </div>

                                        <!--div class="widget-toolbar hidden-480">
                                            <a href="#">
                                                <i class="icon-print"></i>
                                            </a>
                                        </div-->
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-24">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <!--div class="row">
                                                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                            <b>详情</b>
                                                        </div>
                                                    </div-->

                                                    <div class="row">
                                                        <ul class="list-unstyled spaced">
                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                总人数：<?php echo count($depMember);?>
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                类型: <?php switch($depInfo['Department']['type']){   
                                                                case 1: echo '行政';break;
                                                                case 2: echo '科研';break;
                                                                }
                                                                ?>
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                负责人：<b class="blue"> <?php  echo isset($depInfo['Department']['user_id']) ? '无':$depMember[$depInfo['Department']['user_id']]['User']['name']; ?> </b>
                                                            </li>

                                                            <li class="divider"></li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                说明：<?php echo $depInfo['Department']['description'];?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div><!-- /span -->

                                                <!--div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                            <b>Customer Info</b>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <ul class="list-unstyled  spaced">
                                                            <li>
                                                                <i class="icon-caret-right green"></i>
                                                                Street, City
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div--><!-- /span -->
                                            </div><!-- row -->

                                            <div class="space"></div>

                                            <div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">编号</th>
                                                            <th>成员名</th>
                                                            <th class="hidden-xs">职务</th>
                                                            <th class="hidden-480">电话</th>
                                                            <th class="hidden-480">邮箱</th>
                                                            <th>使用状态</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php foreach($depMember as $dk => $dv){ ?>
                                                        <tr>
                                                            <td class="center"><?php  echo $dv['User']['id'] ; ?></td>

                                                            <td>
                                                                <a href="#"><?php  echo $dv['User']['name'];  ?></a>
                                                            </td>
                                                            <td class="hidden-xs">
                                                                <?php  echo isset($dv['User']['position_id']) ? '':$posArr[$dv['User']['position_id']];  ?>
                                                            </td>
                                                            <td class="hidden-480">  <?php  echo $dv['User']['tel'];  ?> </td>
                                                            <td class="hidden-480">  <?php  echo $dv['User']['email'] ; ?> </td>
                                                            <td>  <?php  echo $dv['User']['status'] == 1 ? '启用':'<font class="red">停用</font>';  ?> </td>
                                                        </tr>
                                                        <?php  }  ?>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="hr hr8 hr-double hr-dotted"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div><!-- /.main-content -->

        <?php echo $this->element('acebox'); ?>
    </div><!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/js/jquery-2.0.3.min.js"></script>
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
<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<!-- inline scripts related to this page -->

</body>
</html>
<script type="text/javascript">
    var f_class = 'government';
    var s_class = '';
    var t_class = '';
    <?php if (@$depInfo['Department']['type'] == 1) {?>
        //行政
        s_class = 'administration';
        t_class = s_class + "<?php echo '_'.$d_id;?>";
    <?php } else if(@$depInfo['Department']['type'] == 2) {?>
        //科研
        s_class = 'research';
        t_class = s_class + "<?php echo '_'.$d_id;?>";
    <?php } else {?>
            //有问题，暂时不处理
            window.location = '/homes/index';
    <?php }?>
    show_left_select(f_class,s_class,t_class );
</script>