<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 项目详情 — 报表</title>		
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
                        <a href="#">汇总报表</a>
                    </li>
                    <li class="active">人事汇总报表</li>
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
                                <li style="min-width:150px;text-align: center;"  <?php echo $fromtype == 'leave' ? 'class="active"' : ''; ?> >
                                    <a  href="/Reportforms/report_form/leave">
                                        <i class="blue  icon-list-alt bigger-120"></i>
                                        请假单
                                    </a>
                                </li>

                                <li style="min-width:150px;text-align: center;"  <?php echo $fromtype == 'chuchai' ? 'class="active"' : ''; ?> >
                                    <a href="/Reportforms/report_form/chuchai">
                                        <i class="green icon-user bigger-120"></i>
                                        出差审批单
                                    </a>
                                </li>

                                <li style="min-width:150px;text-align: center;"  <?php echo $fromtype == 'baogong' ? 'class="active"' : ''; ?> >
                                    <a  href="/Reportforms/report_form/baogong">
                                        <i class="orange  icon-list-alt bigger-120"></i>
                                        田间作业包工申请单
                                    </a>
                                </li>
                                
                                <li style="min-width:150px;text-align: center;"  <?php echo $fromtype == 'paidleave' ? 'class="active"' : ''; ?> >
                                    <a href="/Reportforms/report_form/paidleave">
                                        <i class="green icon-list-alt bigger-120"></i>
                                        职工带薪年休假审批单
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content no-border ">
                                <div id="faq-tab-1" class="tab-pane fade in active" >
                                    <div class="table-header">
                                        <div style="float:right;">
                                            <a href="/ResearchProject/personnel_export/<?php echo $fromtype; ?>" style="color:#fff;"><i class="glyphicon glyphicon-cloud-download"></i>导出 </a>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                       <?php echo $xls_head['title']; ?>
                                    </div>
                                    <div class="table-responsive" style='width:100%;overflow:auto;'>
                                        <table class="table table-bordered " style="font-size:12px;text-align: center;table-layout: fixed;" style="margin-left:60%;">
                                            <tbody>
                                                <tr style='font-weight:700;' class="blue">
                                                    <?php foreach($xls_head['cols'] as $v){  ?>
                                                    <td width='130px'> <?php echo $v; ?> </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr style='background-color:#ADFEDC;'>
                                                    <td>  </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                                
                                              
                                                <tr style='background-color:#fdf59a;'>
                                                    <td>  </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td>  </td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
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
