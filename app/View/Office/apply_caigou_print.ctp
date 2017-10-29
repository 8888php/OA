<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" enctype="multipart/form-data" action="/RequestNote/gss_purchase" method="post" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;table-layout: fixed;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所采购申请单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所采购申请单 </td>
                            </tr>
                            
                             <tr>
                                <td > 申报部门 </td>
                                <td colspan='6'> <?php echo $attr_arr[0][$table_name]['team_name'];?>  </td>
                             </tr>
                             <tr>
                                <td > 支出项目 </td>
                                <td colspan='6'> <?php echo $attr_arr[0][$table_name]['project'];?> </td>
                            </tr>
                            <tr>
                                <td >申报日期</td>
                                <td colspan='2'> <?php echo $attr_arr[0][$table_name]['ctime'];?>  </td>
                                <td >预算指标文号</td>
                                <td colspan='3'> <?php echo $attr_arr[0][$table_name]['file_number'];?>  </td>
                             </tr>
                             <tr>
                                <td> 资金来源渠道 </td>
                                <td colspan='6'>  <?php 
                                $listattr = Configure::read('apply_caigou_type');
                                echo $listattr[$attr_arr[0][$table_name]['channel_id']];
                                ?>  </td>
                             </tr>
                             <tr>
                                <td> 采购物资名称 </td>
                                <td colspan='6'> <?php echo $attr_arr[0][$table_name]['purchase_name'];?>  </td>
                             </tr>
                             <tr>
                                <td> 规格型号及详细参数 </td>
                                <td colspan='6' >  
                                <?php 
                                if($attr_arr[0][$table_name]['attachment']) {
                                    echo "<a href='/files/caigou/".$attr_arr[0][$table_name]['attachment']."' target='_blank' >规格型号及详细参数 </a>"; 
                                }else{
                                    echo '无';
                                }
                                ?>  </td>
                             </tr> 
                              <tr>
                                <td colspan='1'> 单位：<?php echo $attr_arr[0][$table_name]['company'];?>  </td>
                                <td colspan='2'> 数量：<?php echo $attr_arr[0][$table_name]['number'];?>  </td>
                                <td colspan='2'> 单价：<?php echo $attr_arr[0][$table_name]['price'];?>  </td>
                                <td colspan='2'> 合计金额：<?php echo $attr_arr[0][$table_name]['amount'];?>  </td>
                             </tr>
                              <tr>
                                <td> 采购理由 </td>
                                <td colspan='6' >  <?php echo $attr_arr[0][$table_name]['reason'];?>  </td>
                             </tr>
                             
                            <tr>
                                <td style='height:100px;line-height:100px;'> 采购需求审核</td>
                                <td colspan='3'>
                                    <?php  
                                            if($applyArr[20]['name']){
                                                echo $applyArr[20]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[20]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[20]['remarks']; 
                                            }else{
                                                echo '需求部门负责人审核 <br /> &nbsp;&nbsp;';
                                            }
                                        ?>                                    
                                    </td>
                                    <td  colspan='3'>
                                        <?php  
                                            if($applyArr[5]['name']){
                                                echo $applyArr[5]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[5]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[5]['remarks']; 
                                            }else{
                                                echo '需求部门分管领导审核 <br /> &nbsp;&nbsp;';
                                            }
                                        ?>                                      
                                </td>
                            </tr>
                             <tr>
                                <td style='height:100px;'> <br/><br/>财务及采购审核 </td>
                                <td colspan='3'>
                                        <?php  
                                            if($applyArr[14]['name']){
                                                echo $applyArr[14]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[14]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[14]['remarks']; 
                                            }else{
                                                echo '财务科审核 <br /> &nbsp;&nbsp;';
                                            }
                                        ?>                                     
                                    </td>
                                    <td  colspan='3'>
                                        <?php  
                                            if($applyArr[23]['name']){
                                                echo $applyArr[23]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[23]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[23]['remarks']; 
                                            }else{
                                                echo '采购内容核对 <br /> &nbsp;&nbsp;';
                                            }
                                        ?> 
                                </td>
                            </tr>
                           
                            <tr >
                                <td colspan='2' style='height:50px;line-height:50px;'> 采购中心审核 </td>
                                <td colspan='5'>  <?php  echo $applyArr[24]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[24]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[24]['remarks'];  ?>  </td>
                            </tr>
                            <tr>
                                <td colspan='2' style='height:50px;line-height:50px;'> 财务及采购分管领导审核 </td>
                                <td  colspan='5' >   <?php  echo $applyArr[13]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[13]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[13]['remarks'];  ?>    </td>
                            </tr>
                            <tr >
                                <td colspan='2' style='height:50px;line-height:50px;'> 所长审核 </td>
                                <td colspan='5'>  <?php  echo $applyArr[6]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[6]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[6]['remarks'];  ?>    </td>
                            </tr>
                            <tr >
                                <td colspan='2' style="line-height: 90px;"> 备注 </td>
                                <td colspan='5' style="text-align:left;"> 
                                    1、“采购物资名称”一栏，如物资不止一个，请填写右上角“编辑正文文件”；<br />
                                    2、“规格型号及详细参数”一栏，如参数内容超过三行，请在右上角“编辑正文文件”中填写；<br />
                                    3、科研项目填写此表时，支出项目名称必须准确填写，与计划任务书（或项目合同书）完全一致；预算指标文号为必填项。<br />
                                    4、购买农家肥时，在“采购理由”一栏注明施肥面积。<br />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

              <?php if ($apply == 'apply') {?>
                <div class="modal-body" style="padding:0 20px;">
                    <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['ApplyMain']['id'];?>">
                    <textarea id="remarks" placeholder="审批意见" rows="2" cols="85"></textarea>
                </div>
            <?php }?>
            
            <div class="modal-footer" style='background-color: #fff;'>
                <?php if ($apply == 'apply') {?>
                <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
                <?php }?>
                
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
                <button  type="button" class="btn btn-primary" onclick="/*window.parent.declares_close();*/" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    $('.modal-footer').css('display', 'none');
    $('#dropzone').css('display', 'none');
    //隐藏下拉框
    $('.' + class_name).css('display', 'none');
    {
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content,.right_list').css('display', 'none');
    }
    window.print();//打印刚才新建的网页
    {
        $('.navbar-default').css('display', '');
        $('#sidebar').css('display', '');
        $('.breadcrumbs').css('display', '');
        $('.ace-settings-container').css('display', '');
        $('#btn-scroll-up').css('display', '');
        $('.right_content,.right_list').css('display', '');
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
        if (!confirm('您确认 ' + text + ' 该采购申请单？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
        $.ajax({
            url: '/Office/ajax_approve_caigou',
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

