<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 起草申请 </title>
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
                        <li class="active"> 起草申请 </li>
                    </ul><!-- .breadcrumb -->

                </div>

                <div class="page-content">						
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="row right_content">
                                <div class="col-xs-12">

                                    <div class="table-responsive" style="font-size: 10px;">
                                        <?php  foreach(Configure::read('applylist') as $k => $v){  ?>
                                        <table  class="table  table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th> <?php echo $k;  ?> </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr> <td >
                                                        <ul>
                                                            <?php  foreach($v as $ak => $av){  
                                                             if($av == '#' || empty($av)){  ?> 

                                                            <li style="float:left;width:24%;list-style:none;line-height: 30px;height:30px;"> <i class="glyphicon glyphicon-list-alt blue"></i> <a href="<?php echo $av;  ?>" style="text-decoration:none;"> <?php echo $ak;  ?> </a></li>
                                                            <?php }else{  

                                                             if($k == '财务科申请单下载'){ ?> 
                                                             <li style="float:left;width:24%;list-style:none;line-height: 30px;height:30px;"> <i class="glyphicon glyphicon-download-alt " style="color: #87b87f;"></i> <a href="<?php echo $av;  ?>" style="text-decoration:none; color: #87b87f;" > <?php echo $ak;  ?> </a></li>

                                                             <?php  }else{  ?>

                                                            <li style="float:left;width:24%;list-style:none;line-height: 30px;height:30px;"> <i class="glyphicon glyphicon-list-alt blue"></i> <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="<?php echo $av;  ?>" style="text-decoration:none;" onclick="$('#modal-body').load('<?php echo $av;  ?>');"  > <?php echo $ak;  ?> </a></li>
                                                            <?php  } } } ?>
                                                        </ul>
                                                    </td></tr>
                                            </tbody>
                                        </table>
                                        <?php   } ?>
                                    </div>

                                </div>
                            </div><!-- /.modal-content -->
                                  <!-- /.modal_storage -->
                                
                                    <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
         <div class="modal-body" id="modal-body"> （-_-)抱歉，申请单加载不出来  </div>
    </div><!-- /.modal -->
</div>
                            
                            
                        </div><!-- /.modal-dialog -->
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
    $('#modal_wait').on('shown.bs.modal', function () {
        // 执行一些动作...
        if (typeof bumeng_change == 'function' ) {
            bumeng_change(); 
        }
        
      })
    function declares_close() {
        $('#modal_wait').click();
    }
    
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
    function wait_close() {
        $('#wait_close').click();
    }
    $('#modal').on('hidden.bs.modal', function () {
        //关闭模态框时，清除数据，防止下次加雷有，缓存
        $(this).removeData("bs.modal");
    });
    show_left_select('office', 'draf');
</script>

</body>
</html>
