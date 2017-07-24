<?php echo $this->element('head_frame'); ?>


<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:750px;'>

    <p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 审核项目</span> <a onclick="window.parent.wait_close();" class="close" data-dismiss="modal" >×</a></p>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  报销汇总单 </td>
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
                                <td>报销人<br/>简要说明</td>
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
                                echo @$apply_log['11']; 
                                echo '<br />';
                                echo @$apply_log_time['11'];
                                ?>   
                                </td>
                                <td style="width:100px;">
                                <?php 
                                echo @$apply_log['4']; 
                                echo '<br />';
                                echo @$apply_log_time['4'];
                                ?> 
                                 </td>
                                <td style="width:100px;">
                                <?php 
                                echo @$apply_log['5']; 
                                echo '<br />';
                                echo @$apply_log_time['5'];
                                ?> 
                                 </td>
                                <td style="width:100px;">
                                <?php 
                                echo @$apply_log['6']; 
                                echo '<br />';
                                echo @$apply_log_time['6'];
                                ?> 
                                 </td>
                                <td style="width:100px;">
                                <?php 
                                echo @$apply_log['13']; 
                                echo '<br />';
                                echo @$apply_log_time['13'];
                                ?> 
                                 </td>
                                 <td style="width:100px;">
                                <?php 
                                echo @$apply_log['14']; 
                                echo '<br />';
                                echo @$apply_log_time['14'];
                                ?> 
                                 </td>
                            </tr>
                            <?php 
                                if(!empty($main_arr['attachment'])){
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
     
            
            <div class="modal-body" style="padding:0 20px;">
                <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['id'];?>" />
                <textarea id="remarks" placeholder="审批意见" rows='2' cols='90' ></textarea>
            </div>
            <div class="modal-footer" style='background-color: #fff;'>
                <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
            </div>
        </div>
    </div><!-- /.row -->
</div>




<script type="text/javascript">
    function approve(type) {
        var text = '拒绝';
        if (type == 1) {
            text = '同意';
        } else {
            type =2;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type};
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