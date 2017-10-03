<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
        .tab-content{z-index:0;}
    </style>

    <div  style='padding:0;' >
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所借款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所借款单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='3'>  <input readonly="readonly" type="text" class="ctime" name="ctime"  value="<?php echo date('Y-m-d'); ?>"   style='height:25px;width:270px;'>  </td>
                                 <td>借款人姓名</td>
                                 <td  colspan='2'> <input type="text" class="applicant" name="applicant" style='width:163px;height:25px;' value="<?php echo $attrInfo['applicant'] ? $attrInfo['applicant'] : $userInfo->name; ?>" /> </td>
                            </tr>
             
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='6'>  <select style="width:220px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_filenumber();" >
                                        <?php if ($is_department == 1){  ?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php 
                                        foreach($projectInfo as $pk=>$pv) {
                                        $selectedstr = ($mainInfo['project_id'] == $pk) ? 'selected' : '';
                                        echo "<option value='".$pk ."'". $selectedstr . '>' . $pv . "</option>";
                                         }
                                         ?>
                                    </select>
                                    <select style="width:155px;height:25px;" name="filenumber" class="filenumber">
                                        <option></option>
                                    </select>
                                    
                                    <select style="width:120px;height:25px;" name='xzsubject' class="xzsubject" >     <option value='0'> 请选择科目 </option>
                                        <?php 
                                            foreach(Configure::read('xizhenglist') as $kyk=>$kyv) {
                                                foreach($kyv as $key=>$val) {
                                                    $selectedstr = ($mainInfo['type'] == 2 && isset($mainInfo['subject'][$key])) ? 'selected' : '';
                                                    echo "<option value='$key' $selectedstr> $val </option>" ;
                                                }
                                            }
                                        ?>
                                    </select>
                                    <select style="width:120px;height:25px;display:none;" name='kysubject' class="kysubject" >         <option value='0'> 请选择科目 </option>
                                         <?php 
                                            foreach(Configure::read('keyanlist') as $kyk=>$kyv) {
                                                foreach($kyv as $key=>$val) {
                                                    $selectedstr = ($mainInfo['type'] == 1 && isset($mainInfo['subject'][$key])) ? 'selected' : '';
                                                    echo "<option value='$key' $selectedstr> $val </option>" ;
                                                }
                                            }
                                        ?>
                                    </select>
                                    
                                </td>
                                <script type="text/javascript">
                                    function change_filenumber(sid = 0) {
                                        var depid = "<?php echo $department_arr['Department']['id']; ?>" ;
                                        var type = $('.dep_pro').val();
                                        if (type ==0) {
                                            //部门
                                            $('.xzsubject').css('display','inline-block');
                                            $('.kysubject').css('display','none');
                                        } else {
                                            $('.xzsubject').css('display','none');
                                            $('.kysubject').css('display','inline-block');
                                        }
                                            //项目 去取项目所对应的souce
                                            var data = {pid:type,sid:sid};
                                            data.depid = (type == 0) ? depid : 0 ;
                                            $.ajax({
                                                url:'/RequestNote/ajax_get_souce',
                                                type:'post',
                                                data:data,
                                                dataType:'json',
                                                success:function(res){
                                                    var html = res['html'];
                                                    $('.filenumber').html(html);
                                                }
                                            });
                                    }
                                    function bumeng_change(){
                                        $('.dep_pro').change();
                                    }
                                </script>
                               
                            </tr>

                            <tr>
                                <td>借款事由</td>
                                <td colspan='6'> <input type="text" name='loan_reason' class="loan_reason" style='width:567px;height:25px;' value="<?php echo $attrInfo['reason']; ?>" /> </td>
                            </tr>
                            
                            <tr>
                                <td>申请借款金额</td>
                                <td>金额大写</td>
                                <td colspan='3'> <input readonly="readonly" type="text" name='big_amount' class="big_amount" style='width:280px;height:25px;' value="<?php echo $attrInfo['apply_money_capital']; ?>" /> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_amount' class="small_amount" style='width:160px;height:25px;' value="<?php echo $attrInfo['apply_money']; ?>" /> </td>
                            </tr>
                            
                            <tr>
                                <td>批准金额</td>
                                <td>金额大写</td>
                                <td colspan='3'> <input readonly="readonly" type="text" name='big_approval_amount' class="big_approval_amount" style='width:280px;height:25px;' value="<?php echo $attrInfo['approve_money_capital']; ?>" /> </td>
                                <td colspan='2'> ￥ <input  readonly="readonly" type="text" name='small_approval_amount' class="small_approval_amount" style='width:160px;height:25px;' value="<?php echo $attrInfo['approve_money']; ?>" /> </td>
                            </tr>
                            
                            
                            <tr>
                                <td  >还款计划</td>
                                <td colspan='6'> <input type="text" name='repayment_plan' class="repayment_plan" style='width:567px;height:25px;'  value="<?php echo $attrInfo['repayment']; ?>"  /> </td>
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
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
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
  
    function approve() {
        var ctime = $('.ctime').val();
        var applicant = $('.applicant').val();//申报人或贷款人姓名
        var dep_pro = $('.dep_pro').val();
        var filenumber = $('.filenumber').val();
        var borrower = $('.borrower').val();
        var loan_reason = $('.loan_reason').val();
        var big_amount = $('.big_amount').val();
        var small_amount = $('.small_amount').val();
        var big_approval_amount = $('.big_approval_amount').val();
        var small_approval_amount = $('.small_approval_amount').val();
        var repayment_plan = $('.repayment_plan').val();
        var declarename = $('.declarename').val();
        var subject = (dep_pro == 0) ? $('.xzsubject').val() : $('.kysubject').val();
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if (applicant == '') {
            $('.applicant').focus();
            return;
        }
        if(subject == '' || subject == '0'){
           $('.xzsubject').focus();
           $('.kysubject').focus();
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
        if (filenumber == '') {
            $('.filenumber').focus();
            return;
        }
        if (small_amount == '' || isNaN(small_amount)) {
            $('.small_amount').focus();
            return;
        }
/*        if (big_approval_amount == '') {
            $('.big_approval_amount').focus();
            return;
        }
        if (small_approval_amount == '' || isNaN(small_approval_amount)) {
            $('.small_approval_amount').focus();
            return;
        }
 */       
        if (repayment_plan == '') {
            $('.repayment_plan').focus();
            return;
        }
        

        var data = {declarename: declarename, ctime: ctime, applicant: applicant,dep_pro: dep_pro, filenumber: filenumber, borrower: borrower, loan_reason: loan_reason, big_amount: big_amount, small_amount: small_amount, big_approval_amount: big_approval_amount,small_approval_amount: small_approval_amount,repayment_plan: repayment_plan,subject: subject};
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
    $('.small_amount').blur(function(){
        var total = $(this).val();
        var big_total = '';
        if (total < 0) {
            //负数
            big_total = '负';
        } 
        big_total += convertCurrency(Math.abs(total))
        $('.big_amount').val(big_total);
    });
    $('.small_approval_amount').blur(function(){
        var total = $(this).val();
        var big_total = '';
        if (total < 0) {
            //负数
            big_total = '负';
        } 
        big_total += convertCurrency(Math.abs(total))
        $('.big_approval_amount').val(big_total);
    });
    //钱小写转大写
    function convertCurrency(money) {
        //汉字的数字
        var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        //基本单位
        var cnIntRadice = new Array('', '拾', '佰', '仟');
        //对应整数部分扩展单位
        var cnIntUnits = new Array('', '万', '亿', '兆');
        //对应小数部分单位
        var cnDecUnits = new Array('角', '分', '毫', '厘');
        //整数金额时后面跟的字符
        var cnInteger = '整';
        //整型完以后的单位
        var cnIntLast = '元';
        //最大处理的数字
        var maxNum = 999999999999999.9999;
        //金额整数部分
        var integerNum;
        //金额小数部分
        var decimalNum;
        //输出的中文金额字符串
        var chineseStr = '';
        //分离金额后用的数组，预定义
        var parts;
        if (money == '') { return ''; }
        money = parseFloat(money);
        if (money >= maxNum) {
          //超出最大处理数字
          return '';
        }
        if (money == 0) {
          chineseStr = cnNums[0] + cnIntLast + cnInteger;
          return chineseStr;
        }
        //转换为字符串
        money = money.toString();
        if (money.indexOf('.') == -1) {
          integerNum = money;
          decimalNum = '';
        } else {
          parts = money.split('.');
          integerNum = parts[0];
          decimalNum = parts[1].substr(0, 4);
        }
        //获取整型部分转换
        if (parseInt(integerNum, 10) > 0) {
          var zeroCount = 0;
          var IntLen = integerNum.length;
          for (var i = 0; i < IntLen; i++) {
            var n = integerNum.substr(i, 1);
            var p = IntLen - i - 1;
            var q = p / 4;
            var m = p % 4;
            if (n == '0') {
              zeroCount++;
            } else {
              if (zeroCount > 0) {
                chineseStr += cnNums[0];
              }
              //归零
              zeroCount = 0;
              chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
            }
            if (m == 0 && zeroCount < 4) {
              chineseStr += cnIntUnits[q];
            }
          }
          chineseStr += cnIntLast;
        }
        //小数部分
        if (decimalNum != '') {
          var decLen = decimalNum.length;
          for (var i = 0; i < decLen; i++) {
            var n = decimalNum.substr(i, 1);
            if (n != '0') {
              chineseStr += cnNums[Number(n)] + cnDecUnits[i];
            }
          }
        }
        if (chineseStr == '') {
          chineseStr += cnNums[0] + cnIntLast + cnInteger;
        } else if (decimalNum == '') {
          chineseStr += cnInteger;
        }
        return chineseStr;
}
</script>

<?php echo $this->element('foot_frame'); ?>

