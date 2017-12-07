<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 汇总报表 — 人事报表</title>		
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
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="error-container">
                            <div class="well">
                                <h3 class="grey lighter smaller">
                                    <span class="blue bigger-125">
                                        <i class="icon-sitemap"></i>
                                        人事
                                    </span>
                                    申请单汇总导出
                                </h3>
                                <hr />
                                <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script> 
                                    <div class="space"></div>
                                    <h4 class="smaller"> 根据筛选导出汇总表：</h4>

                                    <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                        <li>
                                            <i class="icon-hand-right blue"></i>
                                            <label class=" control-label no-padding-right" for="form-field-1"> 选择审批单 </label>
                                                <select  name="sheettype"  id="form-field-1" style="width:215px;">
                                                    <option value=""> 请选择 </option>
                                                    <option value="chuchai"> 出差审批单 </option>
                                                    <option value="leave"> 请假单 </option>
                                                    <option value="baogong"> 田间作业包工申请单 </option>
                                                    <option value="paidleave"> 职工带薪年休假审批单 </option>
                                                </select>  
                                        </li>
                                        
                                        <li>
                                            <i class="icon-hand-right blue"></i>
                                            <label class="control-label no-padding-right" for="form-field-1"> 选择日期 </label> 
                                            <input readonly="readonly" type="text" class=" form_datetime1 start_date" name="start_date" value="<?php echo date('Y-m-d',strtotime('-1 Month'));?>">  
                                                <script type="text/javascript">
                                                    $(".form_datetime1").datetimepicker({
                                                        format: 'yyyy-mm-dd',
                                                        minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                                    });
                                                </script>
                                                
                                                <input readonly="readonly" type="text"  class="form_datetime2 end_date" name="end_date"  value="<?php echo date('Y-m-d');?>"> 
                                                <script type="text/javascript">
                                                    $(".form_datetime2").datetimepicker({
                                                        format: 'yyyy-mm-dd',
                                                        minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                                    });
                                                </script> 
                                        </li>
                                    </ul>
                                </div>

                                <hr />
                                <div class="space"></div>

                                <div class="center">
                                    <a href="#" class="btn btn-primary">
                                        <i class="icon-dashboard"></i>
                                        导出汇总表
                                    </a>
                                </div>
                            </div>
                        </div><!-- PAGE CONTENT ENDS -->
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
    //show_left_select('research_project', '无效');

</script>
<script type="text/javascript">
    $('#modal').on('hidden.bs.modal', function () {
        //关闭模态框时，清除数据，防止下次加雷有，缓存
        $(this).removeData("bs.modal");
    });
    show_left_select('reportforms', 'rsh_baobiao');
</script>
</body>
</html>
