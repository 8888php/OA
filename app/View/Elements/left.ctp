<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="main-container-inner">
        <a class="menu-toggler " id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>

        <div class="sidebar display" id="sidebar" style='margin-top:0;' >
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="icon-signal"></i>
                    </button>

                    <button class="btn btn-info">
                        <i class="icon-pencil"></i>
                    </button>

                    <button class="btn btn-warning">
                        <i class="icon-group"></i>
                    </button>

                    <button class="btn btn-danger">
                        <i class="icon-cogs"></i>
                    </button>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>
            </div><!-- #sidebar-shortcuts -->

            <ul class="nav nav-list">
                <li class="active">
                    <a href="/homes/index">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text"> 控制台 </span>
                    </a>
                </li>

                <li class="office">
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-text-width"></i>
                        <i class="icon-list"></i>
                        <span class="menu-text"> 行政办公 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="draf">
                            <a href="/office/draf">
                                <i class="icon-double-angle-right"></i>
                                起草申请
                            </a>
                        </li>

                        <li class="apply">
                            <a href="/office/apply">
                                <i class="icon-double-angle-right"></i>
                                我的申请
                            </a>
                        </li>

                        <li class="wait_approval">
                            <a href="/office/wait_approval">
                                <i class="icon-double-angle-right"></i>
                                待我审批
                            </a>
                        </li>
                        <li class="wait_approval_reimbursement">
                            <a href="/office/reimbursement">
                                <i class="icon-double-angle-right"></i>
                                待我审批报销单
                            </a>
                        </li>
                        <li class="my_approval">
                            <a href="/office/my_approval">
                                <i class="icon-double-angle-right"></i>
                                经我审批
                            </a>
                        </li>

                        <li class="system_message">
                            <a href="/office/system_message">
                                <i class="icon-double-angle-right"></i>
                                系统消息
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="government">
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 党政部门 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="administration">
                            <a href="/department/index" class="dropdown-toggle">
                                <i class="icon-double-angle-right"></i>
                                行政部门
                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <?php 
                                if(isset($deplist[1])){
                                foreach($deplist[1] as $depk => $depv){ 
                                ?>
                                <li class="administration_<?php echo  $depk;?>">
                                    <a href="/department/infos/<?php echo $depk; ?>">
                                        <i class="icon-pencil"></i>
                                        <?php echo $depv; ?>
                                    </a>
                                </li>
                                <?php  }  } ?>
                            </ul>

                        </li>

                        <li class="research">
                            <a href="/department/index" class="dropdown-toggle"  >
                                <i class="icon-double-angle-right"></i>
                                科研部门
                                <b class="arrow icon-angle-down"></b>
                            </a>

                            <ul class="submenu">
                                <?php 
                                if(isset($deplist[1])){
                                foreach($deplist[2] as $depk => $depv){ 
                                ?>
                                <li class="research_<?php echo $depk; ?>">
                                    <a href="/department/infos/<?php echo $depk; ?>">
                                        <i class="icon-leaf"></i>
                                        <?php echo $depv; ?>
                                    </a>
                                </li>
                                <?php }  } ?>

                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="research_project" >
                    <a href="#" class="dropdown-toggle" >
                        <i class="icon-edit"></i>
                        <span class="menu-text"> 科研项目 </span>
                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="">
                            <a  data-toggle="modal" href="#" data-target="#modal_left" class="step1_js" >
                                <i class="icon-double-angle-right"></i>
                                <i class="icon-plus arrow blue"></i>
                                添加项目
                            </a>

                            <div class="modal fade" id="modal_left" tabindex="-1" role="dialog" aria-labelledby="modal" style='width:570px;height:408px;margin:8% auto 0px; overflow: hidden;border-radius:4px; overflow-y:auto;'>
                                <button type="button" class="close" id="step_close" data-dismiss="modal" aria-hidden="true"></button>
                                <iframe id="iframe_1" src="/ResearchProject/step1" style="width:560px;min-height:400px;border-radius:4px; "  frameborder="0"> </iframe>
                            </div>
                        
                        </li>

                        <li class="lye">
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-double-angle-right"></i>
                                零余额项目
                                <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                <?php 
                                if(isset($applyList[1])){
                                foreach($applyList[1] as $depk => $depv){ 
                                    
                                ?>
                                <li class="lye_<?php echo $depk;?>">
                                    <a href="/ResearchProject/index/<?php echo $depk; ?>">
                                        <i class="icon-pencil"></i>
                                        <?php echo $depv; ?>
                                    </a>
                                </li>
                                <?php  }  } ?>
                            </ul>
                        </li>

                        <li class="jbh">
                            <a href="#" class="dropdown-toggle">
                                <i class="icon-double-angle-right"></i><!--i class="icon-eye-open"></i-->
                                基本户项目
                                <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                <?php 
                                if(isset($applyList[2])){
                                foreach($applyList[2] as $appk => $appv){ 
                                ?>
                                <li class="jbh_<?php echo $appk;?>">
                                    <a href="/ResearchProject/index/<?php echo $appk; ?>">
                                        <i class="icon-pencil"></i>
                                        <?php echo $appv; ?>
                                    </a>
                                </li>
                                <?php  }  } ?>
                            </ul>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-list-alt"></i>
                        <span class="menu-text"> 汇总报表 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/reportforms/index">
                                <i class="icon-double-angle-right"></i>
                                汇总报表
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="guding">
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-calendar"></i>
                        <span class="menu-text"> 固定资产 </span>
                        <span class="badge badge-transparent tooltip-error" title="2&nbsp;Important&nbsp;Events">
                            <i class="icon-warning-sign red bigger-130"></i>
                        </span>
                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="guding_index">
                            <a href="/fixedassets/index">
                                <i class="icon-double-angle-right"></i>
                                固定资产
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="system_set">
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-picture"></i>
                        <span class="menu-text"> 系统设置 </span>
                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li class="set_user">
                            <a href="/user/index">
                                <i class="icon-double-angle-right"></i>
                                成员管理
                            </a>
                        </li>

                        <li class="set_department">
                            <a href="/department/index">
                                <i class="icon-double-angle-right"></i>
                                部门管理
                            </a>
                        </li>

                        <li class="set_position">
                            <a href="/position/index">
                                <i class="icon-double-angle-right"></i>
                                职务管理
                            </a>
                        </li>

                        <li>
                            <a href="/user/info">
                                <i class="icon-double-angle-right"></i>
                                个人信息
                            </a>
                        </li>

                        <li>
                            <a href="/setting/index">
                                <i class="icon-double-angle-right"></i>
                                档案项管理
                            </a>
                        </li>

                    </ul>
                </li>

            </ul><!-- /.nav-list -->

            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
            </div>
            <script type="text/javascript" src="/js/jquery-2.0.3.min.js"></script>
            <script type="text/javascript">
                    try {
                        ace.settings.check('sidebar', 'collapsed')
                    } catch (e) {
                    }
                    //关闭添加项目的窗口
                    function step_close() {
                        $('.close').click();
                    }
                    /**
                     * 左侧栏选中
                     * f_li_class 左侧大栏的class 
                     * s_li_class 大栏下面li的class
                     * t_li_class 第三栏li的class
                     * @returns {undefined}
                     */
                    function show_left_select(f_li_class, s_li_class, t_li_class) {
                        if (f_li_class) {
                            $('.' + f_li_class).addClass('active').siblings().removeClass('active');
                        }
                        if (s_li_class) {
                            $('.' + f_li_class).addClass('open');
                            $('.' + s_li_class).addClass('active');
                        }
                        if (t_li_class) {
                            $('.' + f_li_class).addClass('open');
                            $('.' + s_li_class).addClass('open');
                            $('.' + t_li_class).addClass('active');
                        }
                    }
                    $('#modal_left').on('hidden.bs.modal', function () {
                        //关闭模态框时，清除数据，防止下次加雷有，缓存
                        $('#modal_left').removeData("bs.modal");
                        alert();
                    });
            </script>
            <script type="text/javascript">
                //用于项目的七个选项卡，公共部分
                <?php if (isset($left_show_arr) && !empty($left_show_arr)) {?>
                    function project_left_show() {
                        var f_class = 'research_project';
                        var s_class = '';
                        var t_class = '';
                        <?php if (@$left_show_arr[0]['ResearchProject']['type'] == 1) {?>
                            //行政
                            s_class = 'lye';
                            t_class = s_class + "<?php echo '_'.$left_show_arr[0]['ResearchProject']['id'];?>";
                        <?php } else if(@$left_show_arr[0]['ResearchProject']['type'] == 2) {?>
                            //科研
                            s_class = 'jbh';
                            t_class = s_class + "<?php echo '_'.$left_show_arr[0]['ResearchProject']['id'];?>";
                        <?php } else {?>
                                //有问题，暂时不处理

                        <?php }?>
                        show_left_select(f_class,s_class,t_class );
                    }
                    project_left_show();
                <?php }?>
            </script>
        </div>
