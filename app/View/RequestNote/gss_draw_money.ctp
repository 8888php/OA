<?php echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:780px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所领款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所领款单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='4'>  <input readonly="readonly" type="text" class=" form_datetime3 ctime" name="ctime"  style='height:25px;width:290px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime3").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script> </td>
                                <td > 附单据张数 </td>
                                <td > <input type="text" name='sheets_num' class="sheets_num" style='width:90px;height:25px;'/> </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='6'> <input type="text" name='dep_pro' class="dep_pro" style='width:590px;height:25px;'/>  </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2">项目</td>
                                <td>计算单位</td>
                                <td>数量</td>
                                <td>单价</td>
                                <td>金额</td>
                                <td>备注</td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name='pro1' class="pro1" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name='unit1' class="unit1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='nums1' class="nums1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='unit_price1' class="unit_price1" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='amount1' class="amount1" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='remarks1' class="remarks1" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name='pro2' class="pro2" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name='unit2' class="unit2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='nums2' class="nums2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='unit_price2' class="unit_price2" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='amount2' class="amount2" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='remarks2' class="remarks2" style='width:100px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name='pro3' class="pro3" style='width:200px;height:25px;'/></td>
                                <td> <input type="text" name='unit3' class="unit3" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='nums3' class="nums3" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='unit_price3' class="unit_price3" style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='amount3' class="amount3" readonly="readonly"  style='width:100px;height:25px;'/> </td>
                                <td> <input type="text" name='remarks3' class="remarks3" style='width:100px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td>合计</td>
                                <td colspan='4'> <input type="text" name='big_total' class="big_total" readonly="readonly" style='width:380px;height:25px;'/> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_total' class="small_total" readonly="readonly"  style='width:170px;height:25px;'/> </td>
                            </tr>
                
                            <tr>
                                <td >领款人</td>
                                <td >项目负责人</td>
                                <td >科室负责人</td>
                                <td >分管所领导</td>
                                <td >所长</td>
                                <td >分管财务所长</td>
                                <td >财务科长</td>
                            </tr>
                            <tr style="min-height:60px;line-height: 20px;">
                                <td > <textarea  name="payee" class="payee"  style="width:100px;" ></textarea>  </td>
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var ctime = $('.ctime').val();
        var dep_pro = $('.dep_pro').val();
        var borrower = $('.borrower').val();
        var loan_reason = $('.loan_reason').val();
        var big_amount = $('.big_amount').val();
        var small_amount = $('.small_amount').val();
        var big_approval_amount = $('.big_approval_amount').val();
        var small_approval_amount = $('.small_approval_amount').val();
        var repayment_plan = $('.repayment_plan').val();
        var payee = $('.payee').val();
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if (dep_pro == '') {
            $('.dep_pro').focus();
            return;
        }
        if (borrower == '') {
            $('.borrower').focus();
            return;
        }
        if (loan_reason == '') {
            $('.loan_reason').focus();
            return;
        }
        if (big_amount == '') {
            $('.big_amount').focus();
            return;
        }
        if (small_amount == '') {
            $('.small_amount').focus();
            return;
        }
        if (big_approval_amount == '') {
            $('.big_approval_amount').focus();
            return;
        }
        if (small_approval_amount == '') {
            $('.small_approval_amount').focus();
            return;
        }
        if (repayment_plan == '') {
            $('.repayment_plan').focus();
            return;
        }
        if (payee == '') {
            $('.payee').focus();
            return;
        }

        var data = {ctime: ctime, dep_pro: dep_pro, borrower: borrower, loan_reason: loan_reason, big_amount: big_amount, small_amount: small_amount, big_approval_amount: big_approval_amount,small_approval_amount: small_approval_amount,repayment_plan: repayment_plan,payee: payee,declarename: declarename};
        $.ajax({
            url: '/RequestNote/gss_loan',
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

