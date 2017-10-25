
<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                        <input type="hidden" name='declarename' class='declarename' value='山西省农科院果树所来文批办单' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">秘密等级</td>
                                <td colspan='1'>  <?php echo $attr_arr[0][$table_name]['hierarchy'];?>  </td>  
                                <td style="border-bottom: 1px solid white;"></td>
                                <td><?php echo $attr_arr[0][$table_name]['text1'];?> </td>
                            </tr>
                            
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;">紧急程度</td>
                                <td colspan='1'>  <?php echo $attr_arr[0][$table_name]['urgency'];?>  </td>  
                                <td style="border-top-color: white;border-bottom-color: white;"></td>
                                <td>第【 <?php echo $attr_arr[0][$table_name]['num'];?> 】号 </td>
                            </tr>
                            <tr>
                                <td colspan='1' style="text-align: right;height:25px; line-height: 25px;border-right-color: white;">  
                                </td>
                                <td colspan='1' style="border-right-color: white;">  </td>  
                                <td style="border-top-color: white; border-left-color: white;"></td>
                                <td>  <?php echo $attr_arr[0][$table_name]['datestr'];?> </td>
                            </tr>
                            
                            <tr>
                                <td colspan="4" style="font-size:24px;font-weight: 600;border-color:#000;border-left-color: white;border-right-color: white;">  山西省农科院果树所来文批办单 </td>
                            </tr>
                             <tr>
                                <td colspan='1' style="text-align: right;height:50px; line-height: 50px; text-align: center;">来文单位</td>
                                <td colspan='1'style="line-height: 50px;" >  <?php echo $attr_arr[0][$table_name]['company'];?>  </td>  
                                <td style="text-align: right;height:50px; line-height: 50px; text-align: center;">文号</td>
                                <td style="line-height: 50px;">  <?php echo $attr_arr[0][$table_name]['document_number'];?> </td>
                                
                            </tr>
                            <tr>
                                <td  colspan='1'  style="text-align: right;height:50px; line-height: 50px; text-align: center;">文件标题</td>
                                <td colspan='3' style="line-height: 50px;">  <?php echo $attr_arr[0][$table_name]['file_title'];?>  </td>
                            </tr>
                            <tr>
                                <td colspan='1' rowspan="2" style='height:200px;line-height: 200px;'>领导同志批示</td>
                                <td colspan='3' >  <?php  echo $applyArr[5]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[5]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[5]['remarks'];  ?>    </td>
                            </tr>
                            <tr>
                                <td colspan='3'>  <?php  echo $applyArr[6]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[6]['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr[6]['remarks'];  ?>    </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:100px;line-height: 100px;'> 拟办意见</td>
                                <td colspan='3'>   </td>
                            </tr>
                            <tr >
                                <td colspan='1' style='height:100px;line-height: 100px;'> 业务科室意见 </td>
                                <td colspan='3'>  <?php  echo $applyArr['ksfzr']['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr['ksfzr']['ctime'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$applyArr['ksfzr']['remarks'];  ?>    </td>
                            </tr>
                            <tr style='height:100px;'>
                                <td colspan='1' style='line-height: 100px;' > 所长办公室承办人 </td>
                                <td colspan='1'> <br/> <?php echo $attr_arr[0][$table_name]['user_cbr'];?>  </td>
                                <td colspan='1' style='line-height: 100px;' > 联系电话 </td>
                                <td colspan='1'>  <br/>  <?php echo $attr_arr[0][$table_name]['tel'];?> </td>
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
        $('#received_type').css('display','none');
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
        if (!confirm('您确认 ' + text + ' 该来文批办单？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
        $.ajax({
            url: '/Office/ajax_approve_received',
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

