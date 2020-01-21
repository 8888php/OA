<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:700px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
        .tab-content{z-index:0;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="/*table-layout: fixed;*/text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所借款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;"> 
                                <span style='position:absolute;left:20px;top:25px;font-size:14px;font-weight: 400;'>
                                 <?php  echo $main_arr['ApplyMain']['code'] == 10000 ? '已付款': ($main_arr['ApplyMain']['code'] % 2 != 0 ? '已拒绝' : ''); ?>
                                </span>
                                <span style='font-size:14px;font-weight: 400;'> ID:<?php echo $main_arr['ApplyMain']['id']; ?> &nbsp;&nbsp;</span>
                                    果树所借款单 </td>
                            </tr>
                            <tr>
                                <td style="width: 14%;">填表日期</td>
                                <td colspan='6'>  <?php echo $attr_arr[0][$table_name]['ctime'];?>  </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='4'>  
                                    <?php if ($attr_arr[0][$table_name]['project_id']) {?>
                                        <?php echo $project_arr['ResearchProject']['name'];?>  
                                        
                                    <?php }else {?>
                                        <?php  echo $attr_arr[0][$table_name]['department_name'];?>
                                    <?php }?>
                                    <?php if(!empty($source_arr)){
                                            echo '| 【'.$source_arr['ResearchSource']['source_channel'].' （'.$source_arr['ResearchSource']['file_number'].'） '.$source_arr['ResearchSource']['year'].'】';
                                    }?>
                                    <?php if(!empty($feiyong)) {
                                        echo '| ' . $feiyong;
                                    }?>
                                </td>
                                <td style="width: 13%;">借款人姓名</td>
                                <td > <?php echo $user_arr['User']['name'];?> </td>
                            </tr>

                            <tr>
                                <td>借款事由</td>
                                <td colspan='6'> <?php echo $attr_arr[0][$table_name]['reason'];?> </td>
                            </tr>
                            
                            <tr>
                                <td>申请借款金额</td>
                                <td>金额大写</td>
                                <td colspan='3'> <?php echo $attr_arr[0][$table_name]['apply_money_capital'];?> </td>
                                <td colspan='2'> ￥ <?php echo $attr_arr[0][$table_name]['apply_money'];?> </td>
                            </tr>
                            
                            <tr>
                                <td>批准金额</td>
                                <td>金额大写</td>
                                <?php if ($apply == 'apply' && $caiwukezhang_flag) {?>
                                    <td colspan='3'> <input readonly="readonly" type="text" name='big_approval_amount' class="big_approval_amount" style='width:280px;height:25px;' value="<?php echo $attrInfo['approve_money_capital']; ?>" /> </td>
                                    <td colspan='2'> ￥ <input type="text" name='small_approval_amount' class="small_approval_amount" style='width:123px;height:25px;' value="<?php echo $attrInfo['approve_money']; ?>" /> </td> 
                                <?php }else{?>
                                    <td colspan='3'> <?php echo $attr_arr[0][$table_name]['approve_money_capital'];?> </td>
                                    <td colspan='2'> ￥ <?php echo $attr_arr[0][$table_name]['approve_money'];?> </td>
                                <?php }?>
                            </tr>
                            
                            
                            <tr>
                                <td  >还款计划</td>
                                <td colspan='6'>  <?php echo $attr_arr[0][$table_name]['repayment'];?></td>
                            </tr>
          
                            <tr>
                                <td style="width: 15%;" colspan="2">项目/科室负责人</td>
                                <td style="width: 15%;">分管领导</td>
                                <td style="width: 16%;">分管财务领导</td>
                                <td style="width: 16%;">财务审核</td>
                                <td colspan='2' >所长</td>
                            </tr>
                            <tr style="height:60px;line-height: 20px;">
                                <td colspan="2"> 
                                    <?php 
                                    if($applyArr[11]){
                                        //如果没有省去一下br
                                        if (!empty($applyArr['11']['remarks']))
                                        {
                                            echo @$applyArr['11']['remarks'];
                                            echo '<br />';
                                        }
                                        
                                        echo @$applyArr['11']['name']; 
                                        echo '<br />';
                                        //两个同时存在，取时间的前 10位
                                        if (!empty($applyArr['11']['ctime']) && !empty($applyArr['12']['ctime'])) {
                                            echo substr($applyArr['11']['ctime'], 0, 10);
                                        } else {
                                            echo @$applyArr['11']['ctime'];
                                        }
                                        
                                    }
                                    if ($applyArr['12']) {
                                        //如果没有省去一下br
                                        if (!empty($applyArr['12']['remarks']))
                                        {
                                            echo '<br />';
                                            echo @$applyArr['12']['remarks'];
                                        }
                                        echo '<br />';
                                        echo @$applyArr['12']['name']; 
                                        echo '<br />';
                                        //两个同时存在，取时间的前 10位
                                        if (!empty($applyArr['11']['ctime']) && !empty($applyArr['12']['ctime'])) {
                                            echo substr($applyArr['12']['ctime'], 0, 10);
                                        } else {
                                            echo @$applyArr['12']['ctime'];
                                        }
                                        
                                    }
                                    if ($applyArr['ksfzr']) {
                                        echo @$applyArr['ksfzr']['remarks'];
                                        echo '<br />';
                                        echo @$applyArr['ksfzr']['name']; 
                                        echo '<br />';
                                        echo @$applyArr['ksfzr']['ctime'];
                                    }
                                    echo @$jiaqian['11'] ;
                                    echo @$jiaqian['12'] ;
                                    echo @$jiaqian['ksfzr'] ;
                                    ?>   
                                </td>
                                <td >
                                    <?php 
                                    if($apply == 'apply'){
                                        if ($applyArr[5]) {
                                            echo @$applyArr['5']['remarks'];
                                            echo '<br />';
                                            echo @$applyArr['5']['name']; 
                                            echo '<br />';
                                            echo @$applyArr['5']['ctime']; 
                                        }
                                        echo @$jiaqian[5] ;
                                    }
                                    ?> 
                                </td>
                                <td >
                                    <?php 
                                    if($apply == 'apply'){
                                        if ($applyArr[13]) {
                                            echo @$applyArr['13']['remarks'];
                                            echo '<br />';
                                            echo @$applyArr['13']['name']; 
                                            echo '<br />';
                                            echo @$applyArr['13']['ctime'];
                                        }
                                        echo @$jiaqian[13] ;
                                    }
                                    ?> 
                                </td>
                                <td > 
                                    <?php 
                                    if($apply == 'apply'){
                                        if ($applyArr[14]) {
                                            echo @$applyArr['14']['remarks'];
                                            echo '<br />';
                                        }
                                        echo @$jiaqian[14] ;
                                    }
                                    ?> </td>
                                <td colspan='2'>
                                    <?php 
                                    if($apply == 'apply'){
                                        if ($applyArr[6]) {
                                            echo @$applyArr['6']['remarks'];
                                            echo '<br />';
                                            echo @$applyArr['6']['name']; 
                                            echo '<br />';
                                            echo @$applyArr['6']['ctime'];
                                        }
                                        echo @$jiaqian[6] ;
                                    }
                                    ?> 
                                </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </form>               
            </div>
            <?php if ($apply == 'apply') {?>
                <div class="modal-body" style="padding:0 20px;">
                    <input type="hidden" name="main_id" id="main_id" value="<?php echo $main_arr['ApplyMain']['id'];?>">
                    <textarea id="remarks" placeholder="审批意见" rows="2" cols="90"></textarea>
                </div>
            <?php }?>
            <!--<hr class="hr" style="display: none; border: 1px solid #666666;" />-->
            <div class="modal-footer" style='background-color: #fff;'>
                <?php if($feedback['code']){ ?>
                 <div class="alert alert-danger alert-dismissable center ">
                     <button type="button" class="close" data-dismiss="alert"
                             aria-hidden="true">
                         &times;
                     </button>
                     警告！
                     <?php  echo ($project_sum['code'] == 1) ? $project_sum['msg'] : $feedback['msg'];  ?>
                 </div>
                 <?php   } ?>
                        
                <?php if ($apply == 'apply') {?>
                 <?php if($project_sum['code'] == 0 && $feedback['code'] != -1){  ?>
                <button type="button" class="btn btn-primary" onclick="approve(1);"> <i class="icon-ok bigger-110"></i> 同意</button>
                <?php } ?>
                <button type="button" class="btn btn-primary" onclick="approve(2);"><i class="icon-undo bigger-110"></i> 拒绝</button>
                <?php } ?>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    var div_height = $('.container').height();
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
        //$('.hr').css('display', '');
        $('.container').css('border-bottom', '1px solid black');
        $('.container').css('height', '438px');
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
        //$('.hr').css('display', 'none');
        $('.container').css('border-bottom', '0px');
        $('.container').css('height', div_height + 'px');
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
        <?php if ($apply == 'apply' && $caiwukezhang_flag) {?>
            var small_approval_amount = $('.small_approval_amount').val();
            var big_approval_amount = $('.big_approval_amount').val();
            //判断没有值
            if (small_approval_amount == '' || isNaN(small_approval_amount)) {
                $('.small_approval_amount').focus();
                return;
            }
            if (type == 1) {
                text = '同意';
            } else {
                type = 2;
            }
        <?php } else {?>
            if (type == 1) {
                text = '同意';
            } else {
                type = 2;
            }
        <?php }?>
        if (!confirm('您确认 ' + text + ' 该项目？')) {
            //取消
            return;
        }
        var data = {main_id: $('#main_id').val(), type: type, remarks: $('#remarks').val()};
        <?php if ($apply == 'apply' && $caiwukezhang_flag) {?>
                data.small_approval_amount = small_approval_amount;
                data.big_approval_amount = big_approval_amount;
        <?php }?>
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

