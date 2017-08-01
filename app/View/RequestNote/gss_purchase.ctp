<?php echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:750px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所采购申请单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所采购申请单 </td>
                            </tr>
                            
                             <tr>
                                <td > 申报部门 </td>
                                <td colspan='6'>  <input  type="text" class="department" name="department"  style='height:25px;width:580px;'> </td>
                            </tr>
                             <tr>
                                <td > 支出项目 </td>
                                <td colspan='6'>  <input  type="text" class="project" name="project"  style='height:25px;width:580px;'> </td>
                            </tr>
                            <tr>
                                <td >申报日期</td>
                                <td colspan='2'> 
                                    <input readonly="readonly" type="text" class="ctime" name="ctime" value="<?php echo date('Y-m-d'); ?>" style='height:25px;width:180px;'>
                                <td >预算指标文号</td>
                                <td colspan='3'> 
                                    <input type="text" class="file_number" name="file_number"  style='height:25px;width:300px;'>
                             </tr>
                             <tr>
                                <td> 资金来源渠道 </td>
                                <td colspan='6'>  
                                   <label> <input type='radio' name="type" class="type" value='1' > 财政拨款公用经费 </label> &nbsp;&nbsp;
                                   <label> <input type='radio' name="type" class="type" value='2' > 财政拨款专项资金 </label> &nbsp;&nbsp;
                                   <label> <input type='radio' name="type" class="type" value='3' > 发展基金 </label>
                                </td>
                             </tr>
                             <tr>
                                <td> 采购物资名称 </td>
                                <td colspan='6'> <input  type="text" class="material_name" name="material_name"  style='height:25px;width:590px;'> </td>
                             </tr>
                             <tr>
                                <td> 规格型号及详细参数 </td>
                                <td colspan='6' >  <input  type="file" class="descripttion" name="descripttion" > </td>
                             </tr> 
                              <tr>
                                <td colspan='1'> 单位：<input  type="text" class="unit" name="unit"  style='height:25px;width:50px;'> </td>
                                <td colspan='2'> 数量：<input  type="text" class="nums" name="nums"  style='height:25px;width:90px;'> </td>
                                <td colspan='2'> 单价：<input  type="text" class="price" name="price"  style='height:25px;width:90px;'> </td>
                                <td colspan='2'> 合计金额：<input  type="text" class="total" name="total"  style='height:25px;width:90px;'> </td>
                             </tr>
                              <tr>
                                <td> 采购理由 </td>
                                <td colspan='6' >  <input  type="text" class="reason" name="reason"  style='height:25px;width:600px;'> </td>
                             </tr>
                             
                            <tr>
                                <td > 采购需求审核</td>
                                <td colspan='3'>
                                    需求部门负责人审核 <br /> &nbsp;&nbsp;
                                    
                                    </td>
                                    <td  colspan='3'>
                                        需求部门分管领导审核 <br /> &nbsp;&nbsp;
                                     
                                </td>
                            </tr>
                             <tr>
                                <td > 财务及采购审核 </td>
                                <td colspan='3'>
                                    财务科审核 <br /> &nbsp;&nbsp;
                                    
                                    </td>
                                    <td  colspan='3'>
                                        采购内容核对 <br /> &nbsp;&nbsp;
                                     
                                </td>
                            </tr>
                           
                            <tr>
                                <td colspan='2'> 采购中心审核 </td>
                                <td colspan='5'>  </td>
                            </tr>
                            <tr>
                                <td colspan='2'> 财务及采购分管领导审核 </td>
                                <td  colspan='5' >    </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 所长审核 </td>
                                <td colspan='5'>    </td>
                            </tr>
                            <tr >
                                <td colspan='2'> 备注 </td>
                                <td colspan='5' >  </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var ctime = $('.ctime').val();
        var department = $('.department').val();
        var project = $('.project').val();
        var type = $('.type radio:checked').val();
        var file_number = $('.file_number').val();
        var material_name = $('.material_name').val();
        var descripttion = $('.descripttion').val();
        var unit = $('.unit').val();
        var nums = $('.nums').val();
        var price = $('.price').val();
        var total = $('.total').val();
        var reason = $('.reason').val();
        var declarename = $('.declarename').val();
        
        if (department == '') {
            $('.department').focus();
            return;
        }
        if (project == '') {
            $('.project').focus();
            return;
        }
        if (file_number == '') {
            $('.file_number').focus();
            return;
        }
        if (material_name == '') {
            $('.material_name').focus();
            return;
        }
        if (descripttion == '') {
            $('.descripttion').focus();
            return;
        }
        if (unit == '') {
            $('.unit').focus();
            return;
        }
        if (nums == '') {
            $('.nums').focus();
            return;
        }
        if (price == '') {
            $('.price').focus();
            return;
        }
        if (total == '') {
            $('.total').focus();
            return;
        }
        var data = {};
        data.ctime = ctime;
        data.department = department;
        data.project = project;
        data.type = type;
        data.file_number = file_number;
        data.material_name = material_name;
        data.descripttion = descripttion;
        data.unit = unit;
        data.nums = nums;
        data.price = price;
        data.total = total;
        data.reason = reason;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_purchase',
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
                    window.parent.declares_close();
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

