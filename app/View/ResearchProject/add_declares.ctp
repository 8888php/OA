<?php echo $this->element('head_frame'); ?>
  <script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:600px;'>

    <!--p class="btn btn-info btn-block"  style="border-radius:4px 4px 0 0;padding:0 12px;"> <span style="font-size:16px;"> 费用申报 </span> <a onclick="window.parent.declares_close();" class="close" data-dismiss="modal" >×</a></p-->


    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table table-striped table-bordered table-condensed" style="text-align: center;" >
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;">  报销汇总单 </td>
                            </tr>
                            <tr>
                                <td colspan='2'>填表日期</td>
                                <td colspan='2'>
                                    <input readonly="readonly" type="text" class=" form_datetime1 start_date" name="add_date"  style='height:25px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime1").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                                <td colspan='2'>原始凭证页数</td>
                                <td>  <input type="text" name='pagenums' class="pagenums" style='width:60px;height:25px;'/>  </td>
                            </tr>
                            <tr>
                                <td>部门或项目</td>
                                <td colspan='6'> 
                                <select style="width:235px;height:25px;" name="projectname" class="projectname"  >
                            <?php $qd_arr = array('省级','中央','同级','企业','非本级','本级横向');
                            foreach($qd_arr as $qd){?>
                            <option value="<?php  echo $qd;?>"><?php  echo $qd;?></option>
                            <?php }?>
                        </select>
                                    <select style="width:155px;height:25px;" name="filenumber" class="filenumber"  >
                            <?php $qd_arr = array('省级','中央','同级','企业','非本级','本级横向');
                            foreach($qd_arr as $qd){?>
                            <option value="<?php  echo $qd;?>"><?php  echo $qd;?></option>
                            <?php }?>
                        </select>
                                </td>
                            </tr>
                            <tr>
                                <td>科目</td>
                                <td colspan='6'> <input type="text" name='subject' class="subject" style='width:380px;height:25px;'/>  </td>
                            </tr>
                            <tr>
                                <td>金额</td>
                                <td>人民币大写</td>
                                <td colspan='2'>  <input type="text" name='bigrmb' class="bigrmb" style='width:150px;height:25px;'/>   </td>
                                <td>￥</td>
                                <td colspan='2'> <input type="text" name='amount' class="amount"   style='width:100px;height:25px;'/>   </td>
                            </tr>
                            <tr>
                                <td>报销人<br/>简要说明</td>
                                <td colspan='6'> <textarea  name="remark" class="remark"  style="width:430px;" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:80px;">报销人</td>
                                <td style="width:100px;">项目负责人</td>
                                <td style="width:90px;">科室负责人</td>
                                <td style="width:90px;">分管所领导</td>
                                <td style="width:100px;">所长</td>
                                <td style="width:130px;">分管财务所长</td>
                                <td style="width:100px;">财务科长</td>
                            </tr>
                            <tr >
                                <td style="height:40px;line-height: 40px;"> <?php echo $userInfo->name; ?> </td>
                                <td > </td>
                                <td style="width:100px;"> </td>
                                <td style="width:100px;"> </td>
                                <td style="width:100px;"> </td>
                                <td style="width:100px;"> </td>
                                <td style="width:100px;"> </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                 <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();"> <i class="icon-ok bigger-110"></i> 关闭</button>
                
                <button type="button" class="btn btn-primary" onclick=""> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="icon-undo bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>




<script type="text/javascript">
    function approve(type) {
        var remarks = $('#remarks').val();//备注
        if (remarks == '') {
            $('#remarks').focus();
            return;
        }
        var text = '拒绝';
        if (type == 2) {
            text = '同意';
        } else {
            type = 1;
        }
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {p_id: $('#p_id').val(), remarks: remarks, type: type};
        $.ajax({
            url: '/Office/ajax_approve',
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
                    window.location.reload();
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