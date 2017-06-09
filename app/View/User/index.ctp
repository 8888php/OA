<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 管理员列表 </title>
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
                            <a href="#"> 系统设置 </a>
                        </li>
                        <li class="active"> 用户管理 </li>
                    </ul><!-- .breadcrumb -->

                    <div class="nav-search" id="nav-search">
                        <form class="form-search">
                            <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                <i class="icon-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div><!-- #nav-search -->
                </div>

                <div class="page-content">						
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="table-header">
                                        成员列表信息
                                    </div>

                                    <div class="table-responsive">
                                        <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="center">
                                                        <label>
                                                            <input type="checkbox" class="ace" />
                                                            <span class="lbl"></span>
                                                        </label>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>用户名</th>
                                                    <th class="hidden-480">部门</th>

                                                    <th>姓名</th>
                                                    <th class="hidden-480">职务</th>
                                                    <th class="hidden-480">电话</th>
                                                    <th class="hidden-480">状态</th>
                                                    <th class="hidden-480"><i class="icon-time bigger-110 hidden-480"></i>创建时间</th>
                                                    <th class="hidden-480"> 操作 </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php  foreach($userArr as $v){  ?>
                                                <tr>
                                                    <td class="center">
                                                        <label>
                                                            <input type="checkbox" class="ace" value="<?php echo $v['User']['id']; ?>" />
                                                            <span class="lbl"></span>
                                                        </label>
                                                    </td>

                                                    <td>
                                                        <a href="#"> <?php  echo $v['User']['id']; ?> </a>
                                                    </td>
                                                    <td><?php  echo $v['User']['user']; ?></td>
                                                    <td class="hidden-480"><?php  echo $v['User']['position_id']; ?></td>
                                                    <td><?php  echo $v['User']['name']; ?></td>
                                                    <td><?php  echo $v['User']['department_id']; ?></td>

                                                    <td class="hidden-480"><?php  echo $v['User']['tel']; ?> </td>
                                                    <td><?php  echo $v['User']['status'] == 0 ? '启用':' <span class="label label-sm label-warning">禁用</span>'; ?></td>
                                                    <td><?php  echo date('Y-m-d H:i',$v['User']['ctime']); ?></td>

                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="blue" href="#">
                                                                <i class="icon-zoom-in bigger-130"></i>
                                                            </a>

                                                            <a class="green" href="#">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>

                                                            <a class="red" href="#">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a>
                                                        </div>

                                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                            <div class="inline position-relative">
                                                                <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="icon-caret-down icon-only bigger-120"></i>
                                                                </button>

                                                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                                    <li>
                                                                        <a href="#" class="tooltip-info" data-rel="tooltip" title="View">
                                                                            <span class="blue">
                                                                                <i class="icon-zoom-in bigger-120"></i>
                                                                            </span>
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                                            <span class="green">
                                                                                <i class="icon-edit bigger-120"></i>
                                                                            </span>
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                                            <span class="red">
                                                                                <i class="icon-trash bigger-120"></i>
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <?php } ?>

                                        </table>
                                    </div>

                                    <div class="modal-footer no-margin-top">
                                        <button class="btn btn-sm btn-info pull-left" data-toggle="modal" href="/user/add" data-target="#modal" >
                                            <i class="icon-plus"></i>
                                            添加成员
                                        </button>

                                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" style='width:500px;overflow:hidden; margin:10% auto 0px; border-radius:4px;'>
                                            <div class='modal-hader' > <button class='close' type='button' data-dismiss='modal'><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> 
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        //remote.html内容会被加载到这里
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   


                                        <ul class="pagination pull-right no-margin">
                                            <li class="prev disabled">
                                                <a href="#">
                                                    <i class="icon-double-angle-left"></i>
                                                </a>
                                            </li>

                                            <li class="active">
                                                <a href="#">1</a>
                                            </li>

                                            <li>
                                                <a href="#">2</a>
                                            </li>

                                            <li>
                                                <a href="#">3</a>
                                            </li>

                                            <li class="next">
                                                <a href="#">
                                                    <i class="icon-double-angle-right"></i>
                                                </a>
                                            </li>
                                        </ul>

                                        <?php echo $this->Page->show($limit, $total, $curpage, $all_page, "/user/index/",2 ); ?>
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
</script>

</body>
</html>
