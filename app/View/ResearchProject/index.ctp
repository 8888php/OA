<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 项目详情 </title>		
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
                                <li class="active">
                                    <a data-toggle="tab" href="#faq-tab-1">
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

                                <li>
                                    <a  href="/researchproject/declares/<?php echo $pid;?>">
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

                                    <table class="table table-striped table-bordered table-condensed" >
                                        <tbody>
                                            <tr>
                                                <td>全称</td>
                                                <td><?php echo $pinfos['name'];  ?></td>
                                                <td>简称</td>
                                                <td><?php echo $pinfos['alias'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td>资金性质</td>
                                                <td><?php 
                                                    switch($pinfos['type']){
                                                    case 1 : echo '零余额';break; 
                                                    case 2 : echo '基本户';break; 
                                                    }  ?> </td>
                                                <td>金额</td>
                                                <td><?php echo $pinfos['amount'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td>开始日期</td>
                                                <td> <?php echo $pinfos['start_date'];  ?> </td>
                                                <td>结束日期</td>
                                                <td> <?php echo $pinfos['end_date'];  ?> </td>
                                            </tr>
                                            <tr>
                                                <td>所属项目组</td>
                                                <td> <?php echo $pinfos['project_team_str'];  ?> </td>
                                                <td> 项目组负责人 </td>
                                                <td> <?php echo $pinfos['project_team_user'];  ?> </td>
                                            </tr>
                                            <tr>
                                                <td>任务书</td>
                                                <td colspan="3"> 
                                                    <?php 
                                                    $filearr = explode('|',$pinfos['filename']);
                                                    foreach($filearr as $fv){
                                                    echo "<a href='/files/$fv' target='_blank' > $fv </a> <br/>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>项目成员 &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('/ResearchProject/add_member/<?php echo $pid;?>');"  >
                                                    <i class="icon-plus arrow blue"></i> </a> 
                                                </td>

                                                <td colspan='3'> 
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>NO.</th>
                                                                <th>姓名</th>
                                                                <th>手机</th>
                                                                <th>权限</th>
                                                                <th>日期</th>
                                                                <th>账号</th>
                                                                <th>备注</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  foreach($members as $mk => $mv){  ?>
                                                            <tr>
                                                                <td><?php echo $mk+1;  ?></td>
                                                                <td><?php echo $mv['ProjectMember']['name'];  ?></td>
                                                                <td><?php echo $mv['ProjectMember']['tel'];  ?></td>
                                                                <td>
                                                                    <?php switch($mv['ProjectMember']['type']){
                                                                    case 1: echo '负责人';break;
                                                                    case 2: echo '职员';break;
                                                                    }   ?>
                                                                </td>
                                                                <td><?php echo $mv['ProjectMember']['ctime'];  ?></td>
                                                                <td><?php echo $mv['ProjectMember']['user_name'];  ?></td>
                                                                <td><?php echo $mv['ProjectMember']['remark'];  ?></td>
                                                            </tr>
                                                            <?php   } ?>
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>资金来源</td>
                                                <td colspan="3">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>NO.</th>
                                                                <th>来源渠道</th>
                                                                <th>文号</th>
                                                                <th>金额</th>
                                                                <th>年度</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  foreach($source as $sk => $sv){  ?>
                                                            <tr>
                                                                <td><?php echo $sk+1;  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['source_channel'];  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['file_number'];  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['amount'];  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['year'];  ?></td>
                                                            </tr>
                                                            <?php   } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>项目概述</td>
                                                <td colspan='3'> <?php echo $pinfos['overview'];  ?> </td>
                                            </tr>
                                            <tr>
                                                <td>备注</td>
                                                <td colspan='3'> <?php echo $pinfos['remark'];  ?> </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                          <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
         <div class="modal-body" id="modal-body"> （-_-)抱歉，申请单加载不出来  </div>
    </div><!-- /.modal -->
</div>

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
    //关闭添加成员的窗口
    function member_close() {
        $('#member_close').click();
    }
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
