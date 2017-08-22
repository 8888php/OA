<?php //echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <!--p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 审核申请</span> <a onclick="window.parent.wait_close();" class="close" data-dismiss="modal" >×</a></p-->

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div class="tab-content no-border ">
        <div id="faq-tab-1" class="tab-pane fade in active">
            <form class="form-horizontal" role="form">
                <table class="table  table-condensed" style="text-align: center;border-color:#000;" >
                    <tbody>
                        <tr>
                            <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  报销汇总单 </td>
                        </tr>
                        <tr>
                            <td colspan='2'>填表日期</td>
                            <td colspan='2'>
                                <?php echo $main_arr['ctime']; ?>
                            </td>
                            <td colspan='2'>原始凭证页数</td>
                            <td>  <?php echo $attr_arr['page_number']; ?>  </td>
                        </tr>
                        <tr>
                            <td>部门或项目</td>
                            <td colspan='6'> 
                                <?php echo $kemuStr; ?>   
                            </td>
                        </tr>
                        <tr>
                            <td>科目</td>
                            <td colspan='6'> <?php 
                                if($main_arr['department_id'] > 0 && $main_arr['project_id'] <= 0){ // 部门
                                $bumenArr = Configure::read('xizhenglist'); 
                                }else if($main_arr['department_id'] > 0 && $main_arr['project_id'] > 0){ // 项目
                                $bumenArr = Configure::read('keyanlist');
                                }
                                foreach($bumenArr as $bv){
                                foreach($bv as $bk => $bvv){
                                $bumenArrs[$bk] =  $bvv;
                                }
                                }

                                foreach($main_arr['subject'] as $k => $v){
                                echo @$bumenArrs[$k].':'.$v.'、';
                                }  

                                ?>  </td>
                        </tr>
                        <tr>
                            <td>金额</td>
                            <td>人民币大写</td>
                            <td colspan='2'>  <?php echo $attr_arr['rmb_capital']; ?>   </td>
                            <td>￥</td>
                            <td colspan='2'> <?php echo $attr_arr['amount']; ?>     </td>
                        </tr>
                        <tr>
                            <td class='bx_jysm'>报销人<br/>简要说明</td>
                            <td colspan='6'>  <?php echo $attr_arr['description']; ?>   </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:90px;">报销人</td>
                            <td style="width:100px;">项目负责人</td>
                            <td style="width:90px;">科室负责人</td>
                            <td style="width:90px;">分管所领导</td>
                            <td style="width:100px;">所长</td>
                            <td style="width:130px;">分管财务所长</td>
                            <td style="width:100px;">财务科长</td>
                        </tr>
                        <tr >
                            <td style="height:40px;line-height: 40px;"> 
                                <?php 
                                echo $createName ; 
                                echo '<br />';
                                echo $attr_arr['ctime'];
                                ?>
                            </td>
                            <td style="width:100px;"> 
                                <?php 
                                echo @$applyArr['11']['name']; 
                                echo '<br />';
                                echo @$applyArr['11']['ctime'];
				echo '<br />';
                                echo @$applyArr['11']['remarks'];
                                ?>   
                            </td>
                            <td style="width:100px;">
                                <?php 
                                echo @$applyArr['ksfzr']['name']; 
                                echo '<br />';
                                echo @$applyArr['ksfzr']['ctime'];
				echo '<br />';
                                echo @$applyArr['ksfzr']['remarks'];
                                ?> 
                            </td>
                            <td style="width:100px;">
                                <?php 
                                echo @$applyArr['5']['name']; 
                                echo '<br />';
                                echo @$applyArr['5']['ctime'];
				echo '<br />';
                                echo @$applyArr['5']['remarks'];
                                ?> 
                            </td>
                            <td style="width:100px;">
                                <?php 
                                echo @$applyArr['6']['name']; 
                                echo '<br />';
                                echo @$applyArr['6']['ctime'];
				echo '<br />';
                                echo @$applyArr['6']['remarks'];
                                ?> 
                            </td>
                            <td style="width:100px;">
                                <?php 
                                echo @$applyArr['13']['name']; 
                                echo '<br />';
                                echo @$applyArr['13']['ctime'];
				echo '<br />';
                                echo @$applyArr['13']['remarks'];
                                ?> 
                            </td>
                            <td style="width:100px;">
                                <?php 
                                echo @$applyArr['14']['name']; 
                                echo '<br />';
                                echo @$applyArr['14']['ctime'];
				echo '<br />';
                                echo @$applyArr['14']['remarks'];
                                ?> 
                            </td>
                        </tr>
                        <?php 
                        if(!empty($main_arr['attachment']) && false){
                        $fileurlArr = explode('|',$main_arr['attachment']);
                        echo '<tr ><td> 附件内容 </td><td colspan="6" >'; 
                        foreach($fileurlArr as $filevs){
                        echo "<a href='/files/$filevs' target='$filevs'>".$filevs.'</a> &nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        echo '</td>';
                        }                            
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

 <?php if($seecode == 'apply'){  ?>
    <div class="modal-body" style="padding:0 20px;">
        <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['id'];?>" />
        <textarea id="remarks" placeholder="审批意见" rows='2' cols='90' ></textarea>
    </div>
 <?php } ?>
 <hr class="hr" style="display: none; border: 1px solid #666666;" />
    <div class="modal-footer" style='background-color: #fff;'>
        <?php if($seecode == 'apply'){  ?>
        <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
        <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
        <?php } ?>
        <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
        <button type="button"  class="btn btn-primary " data-dismiss="modal"><i class="glyphicon glyphicon-remove bigger-110"></i>关闭</button>
    </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    $('.modal-footer').css('display', 'none');
    $('#dropzone').css('display', 'none');
    //隐藏下拉框
    $('.' + class_name).css('display', 'none');
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content').css('display', 'none');
        $('.table-striped').css('display', 'none');
        $('.right_list').css('display', 'none');
        $('.hr').css('display', '');
        //<td class='bx_jysm'>报销人<br/>简要说明</td>
        $('.bx_jysm').text('简要说明');
    }
    window.print();//打印刚才新建的网页
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', '');
        $('#sidebar').css('display', '');
        $('.breadcrumbs').css('display', '');
        $('.ace-settings-container').css('display', '');
        $('#btn-scroll-up').css('display', '');
        $('.right_content').css('display', '');
        $('.table-striped').css('display', '');
        $('.right_list').css('display', '');
        $('.hr').css('display', 'none');
        //<td class='bx_jysm'>报销人<br/>简要说明</td>
        $('.bx_jysm').html("报销人<br />简要说明");
    }
    $('.modal-footer').css('display', '');
    $('#dropzone').css('display', '');
    $('.' + class_name).css('display', '');
    return false;
}
</script>
</div>
</div><!-- /.row -->
</div>




<script type="text/javascript">
    function approve(type) {
        var text = '拒绝';
        if (type == 1) {
            text = '同意';
        } else {
            type = 2;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
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
    //添加错误信息
    function show_error(obj, msg) {
        obj.parent().find('.middle').addClass('text-danger').text(msg);
    }
    //去掉错误信息
    function hide_error(obj) {
        obj.parent().find('.middle').removeClass('text-danger').text('');
    }
    //为input框加事件
    $('input.col-xs-10').keyup(function () {
        if ($(this).val() != '') {
            hide_error($(this));
        }
    });
</script>

<?php echo $this->element('foot_frame'); ?>