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

                        <div class="space-6"></div>

                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="widget-box transparent invoice-box">
                                    <div class="widget-header widget-header-large">
                                        <h3 class="grey lighter pull-left position-relative">
                                            <i class="icon-leaf green"></i>
                                            <?php echo $depInfo['Department']['name']; ?>
                                        </h3>

                                        <div class="widget-toolbar no-border invoice-info">
                                            <span class="invoice-info-label">Invoice:</span>
                                            <span class="red">#121212</span>

                                            <br />
                                            <span class="invoice-info-label">Date:</span>
                                            <span class="blue">03/03/2013</span>
                                        </div>

                                        <div class="widget-toolbar hidden-480">
                                            <a href="#">
                                                <i class="icon-print"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main padding-24">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                                            <b>Company Info</b>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <ul class="list-unstyled spaced">
                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                Street, City
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                Zip Code
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                State, Country
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                Phone:
                                                                <b class="red">111-111-111</b>
                                                            </li>

                                                            <li class="divider"></li>

                                                            <li>
                                                                <i class="icon-caret-right blue"></i>
                                                                Paymant Info
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div><!-- /span -->

                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                                            <b>Customer Info</b>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <ul class="list-unstyled  spaced">
                                                            <li>
                                                                <i class="icon-caret-right green"></i>
                                                                Street, City
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right green"></i>
                                                                Zip Code
                                                            </li>

                                                            <li>
                                                                <i class="icon-caret-right green"></i>
                                                                State, Country
                                                            </li>

                                                            <li class="divider"></li>

                                                            <li>
                                                                <i class="icon-caret-right green"></i>
                                                                Contact Info
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div><!-- /span -->
                                            </div><!-- row -->

                                            <div class="space"></div>

                                            <div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">编号</th>
                                                            <th>成员名</th>
                                                            <th class="hidden-xs">职务</th>
                                                            <th class="hidden-480">使用状态</th>
                                                            <th>删除</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td class="center">1</td>

                                                            <td>
                                                                <a href="#">google.com</a>
                                                            </td>
                                                            <td class="hidden-xs">
                                                                1 year domain registration
                                                            </td>
                                                            <td class="hidden-480"> --- </td>
                                                            <td>$10</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="center">2</td>

                                                            <td>
                                                                <a href="#">yahoo.com</a>
                                                            </td>
                                                            <td class="hidden-xs">
                                                                5 year domain registration
                                                            </td>
                                                            <td class="hidden-480"> 5% </td>
                                                            <td>$45</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="center">3</td>
                                                            <td>Hosting</td>
                                                            <td class="hidden-xs">
                                                                1 year basic hosting
                                                            </td>
                                                            <td class="hidden-480"> 10% </td>
                                                            <td>$90</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="center">4</td>
                                                            <td>Design</td>
                                                            <td class="hidden-xs">
                                                                Theme customization
                                                            </td>
                                                            <td class="hidden-480"> 50% </td>
                                                            <td>$250</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="hr hr8 hr-double hr-dotted"></div>

                                        </div>
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
<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<!-- inline scripts related to this page -->

</body>
</html>
