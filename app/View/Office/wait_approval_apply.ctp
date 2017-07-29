<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 待我审批项目列表 </title>
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
                        <li class="active"> 待我审批 </li>
                        <li class="active"> 待审申请 </li>
                    </ul><!-- .breadcrumb -->

                </div>

                <div class="page-content">						
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="table-header">
                                        待审费用信息
                                    </div>

                                    <div class="table-responsive">
                                        <table  class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>申请名</th>
                                                    <th>申请时间</th>
                                                    <th>类型</th>
                                                    <th>申请人</th>
                                                    <th>附件</th>
                                                    <th>审核进度</th>
                                                    <th>操作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  foreach($lists as $sk => $sv){  ?>
                                                <tr>
                                                    <td><?php echo $sv['ApplyMain']['id'];  ?></td>
                                                    <td> <a data-toggle="modal"  data-target="#modal_wait" href="#" onclick="$('#myFrame').attr('src', '/office/apply_project_reimbursement/<?php echo $sv['ApplyMain']['id'];?>/apply');"  > <?php echo $sv['ApplyMain']['name'];  ?> </a>  </td>

                                                    <td><?php echo $sv['ApplyMain']['ctime'];  ?></td>
                                                    <td><?php echo $sv['ApplyMain']['name']; ?></td>
                                                    <td><?php echo $all_user_arr[$sv['ApplyMain']['user_id']];  ?></td>
                                                    <td><?php 
                                                        if(!empty($sv['ApplyMain']['attachment'])){
                                                            $fileurlArr = explode('|',$sv['ApplyMain']['attachment']);
                                                            foreach($fileurlArr as $filev){
                                                                echo  "<a href='/files/$filev' target='$filev'>".$filev.'</a> &nbsp;&nbsp;&nbsp;&nbsp;';
                                                            } 
                                                        }   ?></td>
                                                    <td><?php $new_appprove_code_arr =  Configure::read('new_appprove_code_arr');
						    echo $new_appprove_code_arr[$sv['ApplyMain']['code']];  ?></td>
                                                    <td><a data-toggle="modal"  data-target="#modal_wait" href="#" onclick="$('#myFrame').attr('src', '/office/apply_project_reimbursement/<?php echo $sv['ApplyMain']['id'];?>/apply');"  > 审核 </a></td>
                                                </tr>
                                                <?php   } ?>
                                            </tbody>


                                        </table>
                                    </div>
                                    <!-- /.modal_storage -->
                                    <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="modal" style='width:760px;height:510px;margin:3% auto 0px; overflow: hidden;border-radius:4px; overflow-y:auto;'>
                                        <button type="button" class="close" id="wait_close" data-dismiss="modal" aria-hidden="true"> </button>
                                        <iframe  id="myFrame" frameborder="0" style="width:760px;min-height:510px;border-radius:4px; " src="/office/apply_project_reimbursement" > </iframe>
                                    </div>      

                               
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                        <div class="modal-footer no-margin-top">
                            <?php echo $this->Page->show($limit, $total, $curpage, 1, "/office/wait_approval_apply/",5 ); ?>                                        
                        </div>
                    </div>
                </div><!-- /.modal-content -->
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

    function wait_close() {
        $('#wait_close').click();
    }
    $('#modal').on('hidden.bs.modal', function () {
        //关闭模态框时，清除数据，防止下次加雷有，缓存
        $(this).removeData("bs.modal");
    });

</script>

</body>
</html>
<script type="text/javascript">
    show_left_select('office', 'wait_approval', 'wait_approval_apply');
</script>