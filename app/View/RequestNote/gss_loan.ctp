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
                        <input type="hidden" name='declarename' class='declarename' value='果树所借款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所借款单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='6'>  <input readonly="readonly" type="text" class="ctime" name="ctime"  value="<?php echo date('Y-m-d'); ?>"   style='height:25px;width:590px;'>  </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='3'> <input type="text" name='dep_pro' class="dep_pro" style='width:290px;height:25px;'/>  </td>
                                <td>借款人姓名</td>
                                <td colspan='2'> <input type="text" class="borrower" name="borrower" style='width:190px;height:25px;'/> </td>
                            </tr>

                            <tr>
                                <td>借款事由</td>
                                <td colspan='6'> <input type="text" name='loan_reason' class="loan_reason" style='width:600px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td>申请借款金额</td>
                                <td>金额大写</td>
                                <td colspan='3'> <input type="text" name='big_amount' class="big_amount" style='width:280px;height:25px;'/> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_amount' class="small_amount" style='width:170px;height:25px;'/> </td>
                            </tr>
                            
                            <tr>
                                <td>批准金额</td>
                                <td>金额大写</td>
                                <td colspan='3'> <input type="text" name='big_approval_amount' class="big_approval_amount" style='width:280px;height:25px;'/> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_approval_amount' class="small_approval_amount" style='width:170px;height:25px;'/> </td>
                            </tr>
                            
                            
                            <tr>
                                <td  >还款计划</td>
                                <td colspan='6'> <input type="text" name='repayment_plan' class="repayment_plan" style='width:620px;height:25px;'/> </td>
                            </tr>
          
                            <tr>
                                <td >项目负责人</td>
                                <td >科室负责人</td>
                                <td >分管所领导</td>
                                <td >所长</td>
                                <td >分管财务所长</td>
                                <td colspan='2'>财务科长</td>
                            </tr>
                            <tr style="height:60px;line-height: 20px;">
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                                <td > </td>
                                <td > </td>
                                <td colspan='2'>  </td>
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

        var data = {ctime: ctime, dep_pro: dep_pro, borrower: borrower, loan_reason: loan_reason, big_amount: big_amount, small_amount: small_amount, big_approval_amount: big_approval_amount,small_approval_amount: small_approval_amount,repayment_plan: repayment_plan,declarename: declarename};
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

