<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 项目详情 — 费用申报</title>		
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
        <style>
            table{font-size:12px;}
        </style>   
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
                    <li class="active">行政部门</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1></h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
                                <li >
                                    <a  href="/researchproject/index/<?php echo $pid;?>">
                                        <i class="blue icon-question-sign bigger-120"></i>
                                        项目信息
                                    </a>
                                </li>

                                <li >
                                    <a href="/researchproject/budget/<?php echo $pid;?>">
                                        <i class="green icon-user bigger-120"></i>
                                        项目预算
                                    </a>
                                </li>

                                <li>
                                    <a  href="/researchproject/assets/<?php echo $pid;?>">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        项目资产
                                    </a>
                                </li>

                                <li class="active">
                                    <a   data-toggle="tab" href="#faq-tab-1">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        费用申报
                                    </a>
                                </li>
                                <li>
                                    <a  href="/researchproject/report_form/<?php echo $pid;?>">
                                        <i class="green icon-list-alt bigger-120"></i>
                                        报&nbsp;&nbsp;表
                                    </a>
                                </li>
                                <li>
                                    <a  href="/researchproject/archives/<?php echo $pid;?>">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        档&nbsp;&nbsp;案
                                    </a>
                                </li>
                                <li>
                                    <a  href="/researchproject/storage/<?php echo $pid;?>">
                                        <i class="blue icon-list bigger-120"></i>
                                        出入库
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content no-border ">
                                

                                <div id="faq-tab-1" class="tab-pane fade in active">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="9" class='blue' style='border-right:0px;'> 项目经费 </th>

                                                <th colspan="3" style='border-left:0px;' >
                                                    <select  name="assets" class="type input-width" style="width:145px;">
                                                        <option value="1">财务报销单</option>
                                                    </select>  
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <i class="icon-plus arrow blue"></i>
                                                </th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-weight:600;' class="blue">
                                                <td> · </td>
                                                <td>打印</td>
                                                <td>日期</td>
                                                <td>报销人</td>
                                                <td>政府采购</td>
                                                <td>来源渠道</td>
                                                <td>文号</td>
                                                <td>摘要</td>
                                                <td>报销科目</td>
                                                <td>报销费用</td>
                                                <td>管理人</td>
                                                <td>操作</td>
                                            </tr>

                                            <tr>
                                                <td><?php echo 1;  ?></td>
                                                <td> <i class='glyphicon glyphicon-print blue'></i> </td>
                                                <td><?php echo 1;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo 1;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo 1;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo 1;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo 1;  ?></td>
                                                <td> 删除 </td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
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
<script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="/assets/js/jquery.slimscroll.min.js"></script>
<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<!-- inline scripts related to this page -->

<script type="text/javascript">
    //left页面用与判断
    function research_prject_flag() {
        //do noting
    }
    jQuery(function ($) {
        $('.accordion').on('hide', function (e) {
            $(e.target).prev().children(0).addClass('collapsed');
        })
        $('.accordion').on('show', function (e) {
            $(e.target).prev().children(0).removeClass('collapsed');
        })
    });
    show_left_select('research_project', '无效');
    
</script>

</body>
</html>
