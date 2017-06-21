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

                                <li>
                                    <a data-toggle="tab" href="#faq-tab-2">
                                        <i class="green icon-user bigger-120"></i>
                                        项目预算
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#faq-tab-3">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        项目资产
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#faq-tab-4">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        费用申报
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#faq-tab-5">
                                        <i class="green icon-list-alt bigger-120"></i>
                                        报&nbsp;&nbsp;表
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#faq-tab-6">
                                        <i class="orange icon-credit-card bigger-120"></i>
                                        档&nbsp;&nbsp;案
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#faq-tab-7">
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
                                                <td>任务书</td>
                                                <td colspan="3"> 
                                                    <?php 
                                                    $filearr = explode('|',$pinfos['filename']);
                                                    foreach($filearr as $fv){
                                                    echo "<a href='/files/$fv' > $fv </a> <br/>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>项目成员 &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <i class="icon-plus arrow blue"></i> </td>
                                                <td colspan='3'> 
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>NO.</th>
                                                                <th>名称</th>
                                                                <th>邮箱</th>
                                                                <th>状态</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  foreach($source as $sk => $sv){  ?>
                                                            <tr>
                                                                <td><?php echo $sk+1;  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['source_channel'];  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['file_number'];  ?></td>
                                                                <td><?php echo $sv['ResearchSource']['amount'];  ?></td>
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

                                <div id="faq-tab-2" class="tab-pane fade">
                                    <table class="table table-bordered table-striped" style='width:40%;float:left;margin-right: 15px;'>
                                        <thead>
                                            <tr>
                                                <th colspan="4" class='blue'> 预算 </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                            foreach(Configure::read('keyanlist') as $ysk => $ysv){  ?>
                                            <tr>
                                                <?php foreach($ysv as $k => $v){ ?>
                                                <td><?php echo $v;  ?></td>
                                                <td><?php echo $cost[$k] ? $cost[$k] : '0.00';  ?></td>
                                                <?php   } ?>
                                            </tr>
                                            <?php   } ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered table-striped" style='width:40%;float:left;margin-left:15px;'>
                                        <thead>
                                            <tr>
                                                <th colspan='4' class='red'> 预算剩余 </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                            foreach(Configure::read('keyanlist') as $ysk => $ysv){  ?>
                                            <tr>
                                                <?php foreach($ysv as $k => $v){ ?>
                                                <td><?php echo $v;  ?></td>
                                                <td><?php echo $cost[$k] ? $cost[$k] : '0.00';  ?></td>
                                                <?php   } ?>
                                            </tr>
                                            <?php   } ?>
                                        </tbody>
                                    </table>

                                    <div style="clear:both;"> </div>
                                </div>

                                <div id="faq-tab-3" class="tab-pane fade">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="10" class='blue' style='border-right:0px;'> 项目资产 </th>

                                                <th colspan="4" style='border-left:0px;' >
                                                    <select  name="assets" class="type input-width" style="width:145px;">
                                                        <option value="1">固定资产减少</option>
                                                        <option value="2">固定资产增加</option>
                                                    </select>  
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <i class="icon-plus arrow blue"></i>
                                                </th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-weight:600;' class="blue">
                                                <td>序号</td>
                                                <td>项目</td>
                                                <td>资产名称</td>
                                                <td>编号</td>
                                                <td>购买日期</td>
                                                <td>数量</td>
                                                <td>单价</td>
                                                <td>金额</td>
                                                <td>分类</td>
                                                <td>现况</td>
                                                <td>管理人</td>
                                                <td>政采</td>
                                                <td>当前节点</td>
                                                <td>操作</td>
                                            </tr>

                                            <?php  
                                            foreach(Configure::read('keyanlist') as $ysk => $ysv){  ?>
                                            <tr>
                                                <td><?php echo $v;  ?></td>
                                                <td><?php echo  '0.00';  ?></td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 删除 </td>
                                            </tr>
                                            <?php   } ?>
                                        </tbody>
                                    </table>

                                </div>

                                <div id="faq-tab-4" class="tab-pane fade">
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

                                            <?php  
                                            foreach(Configure::read('keyanlist') as $ysk => $ysv){  ?>
                                            <tr>
                                                <td><?php echo $v;  ?></td>
                                                <td> <i class='glyphicon glyphicon-print blue'></i> </td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 删除 </td>
                                            </tr>
                                            <?php   } ?>
                                        </tbody>
                                    </table>

                                </div>

                                <div id="faq-tab-5" class="tab-pane fade">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="9" class='blue' style='border-right:0px;'> 报表 </th>

                                                <th colspan="3" style='border-left:0px;' >
                                                    <!--select  name="assets" class="type input-width" style="width:145px;">
                                                        <option value="1">财务报销单</option>
                                                    </select>  
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <i class="icon-plus arrow blue"></i-->
                                                </th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-weight:600;' class="blue">
                                                <td> 日期 </td>
                                                <td>摘要</td>
                                                <td>现金来源</td>
                                                <td>文号</td>
                                                <td>合计</td>
                                                <td>资料费</td>
                                                <td>设备费1</td>
                                                <td>设备费2</td>
                                                <td>设备费3</td>
                                                <td>材料费1</td>
                                                <td>材料费2</td>
                                                <td>材料费3</td>
                                            </tr>

                                            <?php  
                                            foreach(Configure::read('keyanlist') as $ysk => $ysv){  ?>
                                            <tr>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00 </td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 0.00</td>
                                                <td><?php echo $v;  ?></td>
                                                <td> 删除 </td>
                                            </tr>
                                            <?php   } ?>
                                        </tbody>
                                    </table>

                                </div>

                                <div id="faq-tab-6" class="tab-pane fade">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="5" class='blue' style='border-right:0px;'> 档案信息 </th>

                                                <th colspan="1" style='border-left:0px;' >

                                                    <i class="icon-plus arrow blue"></i>
                                                </th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-weight:600;' class="blue">
                                                <td> 内容 </td>
                                                <td>填写人</td>
                                                <td>填写时间</td>
                                                <td>上传状态</td>
                                                <td>审批状态</td>
                                                <td>操作</td>
                                            </tr>


                                            <tr>
                                                <td>任务书</td>
                                                <td> 赵琳</td>
                                                <td> 2017-04-26 11:30:32 </td>
                                                <td> 已上传</td>
                                                <td> 待审核 </td>
                                                <td>  <a class="badge badge-info">上传</a>  
                                                    <a class="badge badge-success">查看</a> </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                                <div id="faq-tab-7" class="tab-pane fade">
                                    <table class="table table-bordered table-striped" style=''>
                                        <thead>
                                            <tr>
                                                <th colspan="6" class='blue' style='border-right:0px;'> 出入库 </th>

                                                <th colspan="2" style='border-left:0px;text-align: center;' >
                                                    <select  name="assets" class="type input-width" style="width:145px;">
                                                        <option value="1">物资出库单</option>
                                                    </select>  
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <i class="icon-plus arrow blue"></i>
                                                </th>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style='font-weight:600;' class="blue">
                                                <td> NO. </td>
                                                <td>日期</td>
                                                <td>摘要</td>
                                                <td>规格</td>
                                                <td>数量</td>
                                                <td>金额</td>
                                                <td>状态</td>
                                                <td>操作</td>
                                            </tr>


                                            <tr>
                                                <td>1</td>
                                                <td> 2017-05-25</td>
                                                <td> 摘要…… </td>
                                                <td> xxl </td>
                                                <td> 3 </td>
                                                <td> 60 </td>
                                                <td> 待审核 </td>
                                                <td>  <a class="badge badge-info"> 修改 </a> 
                                                    <a class="badge badge-danger"> 删除 </a> </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td> 2017-05-26</td>
                                                <td> 摘要…… </td>
                                                <td> xxl </td>
                                                <td> 3 </td>
                                                <td> 30 </td>
                                                <td> 已通过 </td>
                                                <td>  <a class="badge badge-success"> 出库 </a> </td>
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
