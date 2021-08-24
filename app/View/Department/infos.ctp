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


            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="space-6"></div>

                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="widget-box transparent invoice-box">
                                    <div class="widget-header widget-header-large not_right_tmp_8888">
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
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-24">
                                            <div class="row">
                                                <div class="col-sm-6">
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
                                                                分管所领导：<b class="blue"> <?php
                                                                    foreach($depMember as $mk => $mv){
                                                                    if($depInfo['Department']['sld'] == $mv['User']['id']){
                                                                    echo $mv['User']['name'];
                                                                    }                                                                                                        } ?> </b>
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                负责人：<b class="blue"> <?php
                                                                    foreach($depMember as $mk => $mv){
                                                                    if($depInfo['Department']['user_id'] == $mv['User']['id']){
                                                                    echo $mv['User']['name'];
                                                                    }                                                                                                        } ?>  </b>
                                                            </li>



                                                            <li class="divider"></li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                说明：<?php echo $depInfo['Department']['description'];?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div><!-- /span -->
                                            </div><!-- row -->

                                            <div class="space"></div>

                                            <div class="tabbable">
                                                <ul class="nav nav-tabs padding-18 tab-size-bigger not_right_tmp_8888" id="myTab">
                                                    <li class="active">
                                                        <a  data-toggle="tab" href="#faq-tab-1">
                                                            <i class="green icon-user bigger-120"></i>
                                                            部门成员
                                                        </a>
                                                    </li>

                                                    <!--li >
                                                        <a data-toggle="tab" href="#faq-tab-2">
                                                            <i class="blue icon-question-sign bigger-120"></i>
                                                            部门预算
                                                        </a>
                                                    </li-->
                                                    <!--如果是茹爱玲,显示所有的党证总站,科研项目,汇总报销,id=44-->
                                                <?php if(in_array($userInfo->id, array(1 ,2, 4, 6, 44, $depInfo['Department']['sld'], $depInfo['Department']['user_id'] ))){ ;?>
                                                    <li >
                                                        <a data-toggle="tab" href="#faq-tab-3">
                                                            <i class="blue icon-question-sign bigger-120"></i>
                                                            申报费用
                                                        </a>
                                                    </li>


                                                <li >
                                                        <a  data-toggle="tab" href="#faq-tab-4">
                                                            <i class="orange icon-list-alt bigger-120"></i>
                                                            资金来源
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                                <div class="tab-content no-border ">

                                                    <div id="faq-tab-1" class="tab-pane fade in active">
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
                                                                        <?php  echo isset($dv['User']['position_id']) ? $posArr[$dv['User']['position_id']] : '';  ?>
                                                                    </td>
                                                                    <td class="hidden-480">  <?php  echo $dv['User']['tel'];  ?> </td>
                                                                    <td class="hidden-480">  <?php  echo $dv['User']['email'] ; ?> </td>
                                                                    <td>  <?php  echo $dv['User']['status'] == 0 ? '启用':'<font class="red">停用</font>';  ?> </td>
                                                                </tr>
                                                                <?php  }  ?>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div id="faq-tab-2" class="tab-pane fade  ">
                                                        <table class="table table-striped" style='width:80%;float:left;margin-left:15px;border:1px solid #ccc;font-size:11px;'>
                                                            <thead>
                                                                <!--tr>
                                                                    <th colspan='4' class='red' style="text-align:center;"> 预算剩余 </th>
                                                                </tr-->
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach($costList as $ysk => $ysv){  ?>
                                                                <tr>
                                                                    <?php foreach($ysv as $k => $v){ ?>
                                                                    <td style="text-align:right;"><?php echo $v;  ?></td>
                                                                    <td><?php echo $cost[$k] ? $cost[$k] : '0.00';  ?></td>
                                                                    <?php   } ?>
                                                                </tr>
                                                                <?php   } ?>
                                                            </tbody>
                                                        </table>

                                                        <div style="clear:both;"> </div>
                                                    </div>

                                                    <div id="faq-tab-3" class="tab-pane fade " style='width:100%;overflow:auto;'>
                                                        <table class="table table-bordered table-striped" style="table-layout: fixed;text-align:center;font-size: 12px;" >
                                                            <tbody>
                                                                <tr style='font-weight:600;' class="blue">
                                                                    <td width='50px'> · </td>
                                                                    <td width='50px'>打印</td>
                                                                    <td width='100px'>日期</td>
                                                                    <td width='100px'>报销人</td>
                                                                    <td width='100px'>核算</td>
                                                                    <td width='100px'>摘要</td>
                                                                    <td width='100'>申报总额</td>
                                                                    <?php foreach(Configure::read('xizhenglist') as $tdv){
                                                                    foreach($tdv as $lv){
                                                                    echo  "<td width='130'>" . $lv . '</td>';
                                                                    }
                                                                    }
                                                                    ?>
                                                                    <td width='100'>审批进度</td>
                                                                </tr>
                                                                <?php foreach($declares_arr as $d){?>
                                                                <tr>
                                                                    <td><?php echo $d['m']['id'];  ?></td>
                                                                    <td>
                                                                    <a data-toggle="modal" data-remote="true" data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('/office/<?php echo $d['m']['table_name'];?>_print/<?php echo $d['m']['id'];?>');"><i class='glyphicon glyphicon-print blue'></i> </a>
                                                                    </td>
                                                                    <td><?php echo $d['m']['ctime'];  ?></td>
                                                                    <td><?php echo $d['u']['name']; ?> </td>
                                                                    <td><?php echo  $d['m']['is_calculation'] == 1 ? '是' : '否';  ?></td>

                                                                    <td> <?php echo $attr_arr[$d['m']['id']]['b']['description']; ?> </td>
                                                                    <td> <?php echo $d['m']['total'];  ?> </td>
                                                                    <?php
                                                                    $json_data = json_decode($d['m']['subject'],true);
                                                                    foreach($xizhenglist as $k) {
                                                                    foreach($k as $kk=>$kv) {
                                                                    echo  '<td>';
                                                                    echo isset($json_data[$kk]) ? $json_data[$kk] : 0;
                                                                    echo '</td>';
                                                                    }
                                                                    }
                                                                    ?>
                                                                    <td> <?php $code_bxd_arr = Configure::read('new_appprove_code_arr');echo $code_bxd_arr[$d['m']['code']];  ?> </td>
                                                                </tr>
                                                                <?php }?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                   <div id="faq-tab-4" class="tab-pane fade  ">
                                                        <table class="table table-striped table-bordered" style='width:80%;float:left;margin-left:15px;border:1px solid #ccc;font-size:11px;'>

                                                        <thead>
                                                            <tr>
                                                                <th>NO.</th>
                                                                <th>来源渠道</th>
                                                                <th>文号</th>
                                                                <th>金额</th>
                                                                <th>年度</th>

                                                                <?php  //if($depInfo['Department']['user_id'] == $userInfo->id){  ?>
                                                                <?php  if($is_dailirong_yujing){  ?>
                                                    <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;height:32px;margin-left: 20px;" onclick="$('#modal-body').load('/Department/add_filenumber/<?php echo $d_id;?>');" > <i class="icon-plus arrow blue"></i> </a>
                                                            <?php  } ?>
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

                                                        <div style="clear:both;"> </div>
                                                    </div>

                                                </div>

                                                <div class="hr hr8 hr-double hr-dotted not_right_tmp_8888"></div>

                                            </div> <!--tabbable-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
         <div class="modal-body" id="modal-body"> （-_-)抱歉，申请单加载不出来  </div>
    </div><!-- /.modal -->
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
                    window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>" + "<" + "/script>");</script>
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
        <?php if (@$depInfo['Department']['type'] == 1){  ?>
            //行政
            s_class = 'administration';
    t_class = s_class + "<?php echo '_'.$d_id;?>";
        <?php } else if(@$depInfo['Department']['type'] == 2){   ?>
            //科研
            s_class = 'research';
    t_class = s_class + "<?php echo '_'.$d_id;?>";
        <?php } else {   ?>
            //有问题，暂时不处理
            window.location = '/homes/index';
        <?php }  ?>

        jQuery(function ($) {
            $('.accordion').on('hide', function (e) {
    $(e.target).prev().children(0).addClass('collapsed');
        })
        $('.accordion').on('show', function (e) {
            $(e.target).prev().children(0).removeClass('collapsed');
        })
        });

        show_left_select(f_class,s_class,t_class );
</script>
