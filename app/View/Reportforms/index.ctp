<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 汇总报表 — 项目报表</title>		
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
                    <li class="active">
                        <a href="#">汇总报表</a>
                    </li>
                    <li class="active">
                        项目报表
                    </li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">

                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="tabbable">
                            <div class="tab-content no-border ">
   
                                <div id="faq-tab-1" class="tab-pane fade in active">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="5" class='blue' style='font-size:16px;border-right:0px;'> 项目报表 </th>

                                                <th colspan="1" style='border-left:0px;text-align:right;' >
                                                    <a href='/Reportforms/pro_export'><i class="glyphicon glyphicon-cloud-download"></i>导出 </a>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-size:14px;font-weight:600;text-align:center;' class="blue">
                                                <td> 资金类型 </td>
                                                <td > 项目 </td>
                                                <td> 期初数 </td>
                                                <td> 支出累计 </td>
                                                <td> 期末数 </td>
                                                <td> 详细 </td>
                                            </tr>
                                            <?php  foreach($selfTeamList as $tk => $tv){  ?>
                                             <tr style="text-align:center;border-top:2px solid #478;">
                                                <td  rowspan="<?php echo count($applyList[$tk])+1; ?>" style="vertical-align:middle;font-size:14px;font-weight:600;"> <?php echo $tv; ?> </td>
                                                <td style="text-align:center;">  -- </td>
                                                <td> <?php echo $sumArr[$tk]['amount']; ?> </td>
                                                <td> <?php echo $sumArr[$tk]['pay']; ?> </td>
                                                <td> <?php echo $sumArr[$tk]['amount'] - $fromArr[$tk]['pay']; ?> </td>
                                                <td>  </td>
                                            </tr>
                                            
                                            <?php  foreach($applyList[$tk] as $k => $v){  ?>
                                            <tr style="text-align:center;">
                                                <td style="text-indent:2rem;text-align:left;"> <?php  echo $v; ?> </td>
                                                <td> <?php echo $fromArr[$tk][$k]['amount']; ?> </td>
                                                <td> <?php echo $fromArr[$tk][$k]['pay']; ?> </td>
                                                <td> <?php echo $fromArr[$tk][$k]['amount'] - $fromArr[$tk][$k]['pay']; ?> </td>
                                                <td>  <a href="/ResearchProject/report_form/<?php echo $k; ?>" > <i class='glyphicon glyphicon-list-alt'> </i> </a>  </td>
                                            </tr>
                                            <?php } } ?>
                                            
                                              <tr style="text-align:center;border-top:2px solid #478;">
                                                <td style="vertical-align:middle;font-size:14px;font-weight:600;"> 总合计 </td>
                                                <td style="text-align:center;">  -- </td>
                                                <td> <?php echo $totalArr['amount']; ?> </td>
                                                <td> <?php echo $totalArr['pay']; ?> </td>
                                                <td> <?php echo $totalArr['amount'] - $totalArr['pay']; ?> </td>
                                                <td> </td>
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
    //show_left_select('reportforms', '无效');
    
</script>
<script type="text/javascript">
    $('#modal').on('hidden.bs.modal', function(){
    //关闭模态框时，清除数据，防止下次加雷有，缓存
    $(this).removeData("bs.modal");
    });
            show_left_select('reportforms', 'xm_baobiao');
</script>
</body>
</html>
