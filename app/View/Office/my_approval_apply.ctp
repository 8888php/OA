<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 经我审批申请列表 </title>
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

        <div class="main-container-inner">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

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
                            <a href="#"> 行政办公 </a>
                        </li>
                        <li class="active"> 经我审批 </li>
                        <li class="active"> 经审申请 </li>
                    </ul><!-- .breadcrumb -->

                </div>

                <div class="page-content">						
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="row">
                                <div class="col-xs-12 right_list">

                                    <div class="table-header">
                                        经审费用信息  
                                        <select id="sheqtable" style="float: right; margin-right: 4%; height: 38px;font-size: 13px;" onchange="change_table();">
                                            <option value="0">请选择申请名...</option>
                                            <?php foreach(Configure::read('select_apply') as $k=>$v){?>
                                            <option value="<?php echo $k;?>" <?php echo $k==$table ? 'selected':'';?>><?php echo $v;?></option>
                                            <?php }?>
                                        </select>
                                        <input type="text" style="float: right; /*margin-right: 4%;*/ height: 38px;font-size: 13px; width: 88px;" onkeyup="change_shqren(this.value)" />
                                        <select id="shqren" style="float: right; /*margin-right: 4%;*/ height: 38px;font-size: 13px;" onchange="change_table();">
                                            <option value="0">请选择申请人...</option>
                                            <?php foreach($all_user_arr as $k=>$v){?>
                                            <option value="<?php echo $k;?>" <?php echo $k==$shqren ? 'selected':'';?>><?php echo $v;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <script type="text/javascript">
                                        //根据Input的值去搜索 申请人的
                                        function change_shqren(val) {
                                            if (val == '') {
                                                $('#shqren option').each(function(i){
                                                    $(this).css('display', 'block');
                                                });
                                                return;
                                            }
                                            $('#shqren option').each(function(i){
                                                if (i == 0) {
                                                    return true;
                                                }
                                                var text = $(this).text();
                                                if (text.indexOf(val) != -1) {
                                                    //说明包含
                                                    $(this).css('display', 'block');
                                                } else {
                                                    $(this).css('display', 'none');
                                                }
                                            });
                                            
                                        }
                                        function change_table() {
                                            var table = $('#sheqtable option:selected').val();
                                            var shqren = $('#shqren option:selected').val();
                                            var host = window.location.host;
                                            var url = 'http://'+host+'/office/my_approval_apply/1/' + table + '/' + shqren;
                                            window.location.href = url;
                                        }
                                    </script>
                                    
                                    <div class="table-responsive">
                                        <table  class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>申请名</th>
                                                    <th>申请时间</th>
						                            <th>审核时间</th>
                                                    <th>类型</th>
                                                    <th>申请人</th>
                                                    <th>附件</th>
                                                    <th>审核进度</th>
                                                    <!--th>操作</th-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  foreach($lists as $sk => $sv){  ?>
                                                <tr>
                                                    <td><?php echo $sv['ApplyMain']['id'];  ?></td>
                                                    <?php  if($sv['ApplyMain']['table_name']){  ?>
                                                        <td>  <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('/office/<?php echo $sv['ApplyMain']['table_name'].'_print/'.$sv['ApplyMain']['id'];?>');"  ><?php echo $sv['ApplyMain']['name'];  ?> </a> </td>
                                                    <?php  }else{   ?>
                                                        <td><?php echo $sv['ApplyMain']['name'];  ?></td>
                                                    <?php }?>
                                                    
                                                    <td><?php echo $sv['ApplyMain']['ctime'];  ?></td>
						                            <td><?php echo $sv['ApprovalInformation']['ctime'];  ?></td>
                                                    <td><?php echo $sv['ApplyMain']['name'];  ?></td>
                                                    <td><?php echo $all_user_arr[$sv['ApplyMain']['user_id']];  ?></td>
                                                    <td>
                                                         <?php 
                                                          if(!empty($sv['ApplyMain']['attachment'])){
                                                            $filearr = array();
                                                            $filearr['url'] = $sv['ApplyMain']['attachment'];
                                                            $filearr['name'] = $sv['ApplyMain']['name'];
                                                            $filearr['uname'] = $all_user_arr[$sv['ApplyMain']['user_id']];
                                                            $filearr['type'] =  $sv['ApplyMain']['table_name'] == 'apply_caigou' ? 'caigou/' : '';
                                                            $filebase = base64_encode( json_encode( $filearr ) );
                                                           ?>
                                                        <a  data-toggle="modal" data-target="#fileModal" onclick="$('#modal-content').load('/office/file_print/<?php echo $filebase;?>');" > 附件 </a>
                                                        <?php   } ?>
                                                        
                                                    </td>
                                                    <td><?php $new_appprove_code_arr =  Configure::read('new_appprove_code_arr');
                                                        echo $new_appprove_code_arr[$sv['ApplyMain']['code']];  ?>
                                                        <?php if($sv['ApplyMain']['code'] == 28 && $userInfo->position_id == 14 && $userInfo->department_id == 5){   ?>
                                                        <span style='color: #006dcc;margin-left: 10px;' onclick="approvetoo(<?php echo $sv['ApplyMain']['id']; ?>);" > 拒绝 </span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php   } ?>
                                            </tbody>


                                        </table>
                                    </div>

                                    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content" id='modal-content'> </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#modal').on('hidden.bs.modal', function () {
                                                //关闭模态框时，清除数据，防止下次加雷有，缓存
                                                $(this).removeData("bs.modal");
                                            })
                                        });
                                        //审批
                                        function approve(type) {
                                            var remarks = $('#remarks').val();//备注
                                            if (remarks == '') {
                                                $('#remarks').focus();
                                                return;
                                            }
                                            var text = '拒绝';
                                            if (type == 2) {
                                                text = '同意';
                                            } else {
                                                type = 1;
                                            }
                                            if (!confirm('您确认 ' + text + ' 该项目？')) {
                                                //取消
                                                return;
                                            }
                                            var data = {p_id: $('#p_id').val(), remarks: remarks, type: type};
                                            $.ajax({
                                                url: '/Office/ajax_approve',
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

                                                        return;
                                                    }
                                                    if (res.code == 0) {
                                                        //说明添加或修改成功
                                                        $('.close').click();
                                                        window.location.reload();
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
                                    </script>

                                    <div class="modal-footer no-margin-top">
                                        <?php echo $this->Page->show($limit, $total, $curpage, 1, "/office/my_approval_apply/",5, $table, $shqren ); ?>                                        
                                    </div>
                                </div>

                                <!-- /.modal_storage -->
                                <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-body" id="modal-body"> （-_-)抱歉，申请单加载不出来  </div>
                                    </div><!-- /.modal -->
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
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/jquery.dataTables.bootstrap.js"></script>
<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->

<script type="text/javascript">
    jQuery(function ($) {
        var oTable1 = $('#sample-table-2').dataTable({
            "aoColumns": [
                {"bSortable": false},
                null, null, null, null, null,
                {"bSortable": false}
            ]});


        $('table th input:checkbox').on('click', function () {
            var that = this;
            $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function () {
                        this.checked = that.checked;
                        $(this).closest('tr').toggleClass('selected');
                    });

        });


        $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
        function tooltip_placement(context, source) {
            var $source = $(source);
            var $parent = $source.closest('table')
            var off1 = $parent.offset();
            var w1 = $parent.width();

            var off2 = $source.offset();
            var w2 = $source.width();

            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                return 'right';
            return 'left';
        }
    })


    function ajax_del(did) {
        if (!did) {
            alert('删除失败');
            return;
        }

        var data = {did: did};
        $.ajax({
            url: '/user/ajax_del',
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
                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    location.href = '/user/index';
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
    function ajax_recovery(did) {
        if (!did) {
            alert('恢复失败');
            return;
        }

        var data = {did: did};
        $.ajax({
            url: '/user/ajax_recovery',
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
                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    location.href = '/user/index';
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
    $('#modal').on('hidden.bs.modal', function () {
        //关闭模态框时，清除数据，防止下次加雷有，缓存
        $(this).removeData("bs.modal");
    });
    function wait_close() {
        $('#wait_close').click();
    }
    
       function approvetoo(subid) {
        var text = '拒绝';
        if (!subid) {
           alert('提交信息有误');
           return ;
        }
        content = prompt('您确认拒绝该项目？可输入拒绝原因');
        if(content == null){
            return;
        }
        var data = {main_id: subid, type: 2, remarks: content};
        $.ajax({
            url: '/Office/ajax_approve_reimbursement',
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

                    return;
                }
                if (res.code == 0) {
                    //说明添加或修改成功
                    $('.close').click();
                    window.parent.location.reload();
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
    
</script>
<script type="text/javascript">
    show_left_select('office', 'my_approval', 'my_approval_apply');
</script>
</body>
</html>

