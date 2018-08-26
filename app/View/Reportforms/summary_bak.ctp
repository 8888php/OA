<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 项目汇总报表</title>		
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
                    <li class="active">项目汇总报表</li>
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
                            </ul>

                            <div class="tab-content no-border ">
                                <div id="faq-tab-1" class="tab-pane fade in active" >
                                    <div class="table-header">
                                        <div style="float:right;">
                                            <a href="/reportforms/sum_export" style="color:#fff;"><i class="glyphicon glyphicon-cloud-download"></i>导出 </a>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                        科研项目经费汇总  
                                    </div>
                                    <div class="table-responsive" style='width:100%;overflow:auto;'>
                                        <table class="table table-bordered table-striped" style="font-size:12px;text-align: center;table-layout: fixed;" style="margin-left:60%;">
                                            <tbody>
                                                <tr style='font-weight:600;' class="blue">
                                                    <td width="15%" >科目</td>
                                                    <td width="15%"> 预算 </td>
                                                    <td width="15%"> 支出 </td>
                                                    <td width="15%"> 结余 </td>
                                                    <td> 进度 </td>
                                                </tr> 
                                                <?php foreach($keyanlist['key'] as $key => $val){ ?>
                                                <tr >
                                                        <td> <?php echo $keyanlist['val'][$key]; ?> </td>
                                                        <td> <?php echo isset($proCountSum[$val]) ? $proCountSum[$val] : 0; ?> </td>
                                                        <td> <?php echo isset($expendSum[$val]) ? $expendSum[$val] : 0; ?>  </td>
                                                        <td> <?php echo isset($surplusSum[$val]) ? $surplusSum[$val] : 0; ?>  </td>
                                                        <td> 
                                                            <div class="progress progress-striped active" style="margin:3px auto;border-radius:8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                                         style="width: <?php echo isset($percentage[$val]) ? $percentage[$val] : 0; ?>%;">
                                                                </div>
                                                            </div>
                                                            <span style="position:absolute;margin-top:-20px;"><?php echo isset($percentage[$val]) ? $percentage[$val] : 0; ?> % </span>
                                                        </td>
                                                    </tr>
                                                <?php }  ?>
                                            </tbody>
                                            
                                            <?php if(false){    // 旧样式   ?>
                                            <tbody>
                                                <tr style='font-weight:600;' class="blue">
                                                    <td width='100px'>科目</td>
                                                    <?php 
                                                        foreach($keyanlist['val'] as $lv){ 
                                                            echo  "<td width='120'>" . $lv . '</td>'; 
                                                        }
                                                    ?>  
                                                </tr>
                                                <tr style='background-color:#ADFEDC;'>
                                                    <td> 预算 </td>
                                                    <?php  
                                                    foreach($keyanlist['key'] as $k) {
                                                            echo  '<td>';
                                                            echo isset($proCountSum[$k]) ? $proCountSum[$k] : 0;
                                                            echo '</td>';
                                                    }
                                                    ?>
                                                </tr>
                                                
                                               <tr style='background-color:#fdf59a;'>
                                                    <td> 支出 </td>
                                                    <?php  
                                                    foreach($keyanlist['key'] as $k) {
                                                            echo  '<td>';
                                                            echo isset($expendSum[$k]) ? $expendSum[$k] : 0;
                                                            echo '</td>';
                                                    }
                                                    ?>
                                                </tr>
                                                
                                                <tr style='background-color:#fdf59a;'>
                                                    <td> 结余 </td>
                                                    <?php  
                                                    foreach($keyanlist['key'] as $k) {
                                                            echo  '<td>';
                                                            echo isset($surplusSum[$k]) ? $surplusSum[$k] : 0;
                                                            echo '</td>';
                                                    }
                                                    ?>
                                                </tr>
                                                
                                                <tr style='background-color:#ADFEDC;'>
                                                    <td> 进度 </td>
                                                    <?php  
                                                    foreach($keyanlist['key'] as $k) {
                                                            echo  '<td>';
                                                            echo isset($percentage[$k]) ? $percentage[$k] : 0;
                                                            echo ' % ';
                                                            echo '</td>';
                                                    }
                                                    ?>
                                                </tr>
                                                
                                            </tbody>
                                            <?php }  ?>
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
    show_left_select('reportforms', 'sum_baobiao');

</script>

</body>
</html>
