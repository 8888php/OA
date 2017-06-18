<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> <?php echo empty($title) ? '控制台 - 管理系统' : $title ;  ?> </title>
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
        <!-- basic scripts -->
        <!--[if !IE]> -->
        <script src="/js/jquery-2.0.3.min.js"></script>
        <!-- <![endif]-->

        <!--[if IE]>
        <script src="/js/jquery-1.10.2.min.js"></script>
        <![endif]-->

        <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>" + "<" + "script>");
        </script>
        <!-- <![endif]-->

        <!--[if IE]>
        <script type="text/javascript">
        window.jQuery || document.write("<script src='/js/jquery-1.10.2.min.js'>"+"<"+"script>");
        </script>
        <![endif]-->

        <script type="text/javascript">
            if ("ontouchend" in document)
                document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "script>");
        </script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/typeahead-bs2.min.js"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="/assets/js/excanvas.min.js"></script>
        <![endif]-->

        <script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
        <script src="/assets/js/jquery.slimscroll.min.js"></script>
        <script src="/assets/js/jquery.easy-pie-chart.min.js"></script>
        <script src="/assets/js/jquery.sparkline.min.js"></script>
        <script src="/assets/js/flot/jquery.flot.min.js"></script>
        <script src="/assets/js/flot/jquery.flot.pie.min.js"></script>
        <script src="/assets/js/flot/jquery.flot.resize.min.js"></script>

        <!-- ace scripts -->
        <script src="/assets/js/ace-elements.min.js"></script>
        <script src="/assets/js/ace.min.js"></script>
        <!-- inline scripts related to this page --> 

    </head>

    <body >
