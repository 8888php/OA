<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/assets/css/dropzone.css" />
<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="text-align: center;border-color:#000;table-layout: fixed;" >
                        <input type="hidden" name='declarename' class='declarename' value='报销汇总单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  报销汇总单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='2'>
                                    <input readonly="readonly" type="text" class=" ctime" name="ctime" value="<?php echo date('Y-m-d'); ?>"   style='width:150px;height:25px;'>  
                                </td>
                                <td >原始凭证页数</td>
                                <td>  <input type="text" name='page_number' class="page_number" style='width:80px;height:25px;' value="<?php echo $attrInfo['page_number'] ? $attrInfo['page_number'] : ''; ?>"  />  </td>
                                <td>核算</td>
                                <td > 
                                    <select  name='is_calculation' class="is_calculation" style='width:60px;height:25px;' >
                                        <option value="1"> 是 </option>
                                        <option value="0"  <?php echo $attrInfo['is_calculation'] === 0 ? 'selected' : ''; ?> > 否 </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>部门或项目</td>
                                <td colspan='6'> 
                                    <select style="width:300px;height:25px;" name="projectname" class="projectname" onchange="change_filenumber();" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php foreach($projectInfo as $pk=>$pv) {
                                        $selectedstr = ($mainInfo['project_id'] == $pk) ? 'selected' : '';
                                        echo "<option value='".$pk ."'". $selectedstr . '>' . $pv . "</option>";
                                       }?>
                                    </select>
                                    <select style="width:255px;height:25px;" name="filenumber" class="filenumber"  >
                                        <?php  foreach($source as $qd){?>
                                        <option value="<?php  echo $qd['ResearchSource']['id'];?>"><?php  echo '【'.$qd['ResearchSource']['source_channel'].' （'.$qd['ResearchSource']['file_number'].'） '.$qd['ResearchSource']['year'].'】';?></option>
                                        <?php }?>
                                    </select>
                                </td>
                        <script type="text/javascript">
                            var depid = "<?php echo $department_arr['Department']['id']; ?>" ;
                            var class_name = 'multipleselect_bm';
                            if ($('#multipleselect_bm option').eq(0).val() != 0) {
                                class_name = 'multipleselect_ky';
                            }
                            function change_filenumber(sid = 0) {
                                var type = $('.projectname').val();
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
                                    if(type == 0){
                                        $('.multipleselect_ky').css('display', 'none');
                                        $('.multipleselect_bm').css('display', '');
                                        class_name = 'multipleselect_bm';
                                        //清空 之前所选
                                        clear_class_info('multipleselect_ky');
                                     }else{
                                        $('.multipleselect_bm').css('display', 'none');
                                        $('.multipleselect_ky').css('display', '');
                                        class_name = 'multipleselect_ky';
                                        //清空 之前所选
                                        clear_class_info('multipleselect_bm');
                                    }
                                $('.subject').val('');
                            }
                            //清空 checkbox,清空input
                            function clear_class_info(classname) {
                                $('#'+classname+' option').each(function(){
                                        $(this).removeAttr('selected')
                                });
                                $('.ms-choice span').text('');
                                $('.amount').val('');
                                $('.rmb_capital').val('');
                                $( '.'+ classname +' div.ms-drop li.multiple').each(function (i) {
                                    var li_item = $('li.multiple').eq(i);
                                    li_item.find('.first_inpuut').removeAttr('checked');
                                    li_item.find('input.je').val('0')
                                });
                            }
                            function bumeng_change(){
                                $('.projectname').change();
                            }
                        </script>
                        </tr>
                        <tr>
                            <td>科目</td>
                            <td colspan='6'>
                                <textarea style='width:555px;height:25px;' class="subject" disabled="disabled"></textarea>
                                <select id="multipleselect_ky" multiple="multiple">
                                    <?php 
                                        foreach($keyanlist as $lk=>$lv){
                                          foreach($lv as $k=>$v){
                                            $selectedstr = ($mainInfo['type'] == 1 && isset($mainInfo['subject'][$k])) ? 'selected' : '';
                                            $option_val = ($selectedstr == 'selected') ? $mainInfo['subject'][$k] : $v;
                                            echo "<option value='$k' $selectedstr> $v </option>" ;
                                            }
                                         }?>
                                </select>
                                <?php if($is_department == 1){?>
                                <select id="multipleselect_bm" multiple="multiple">
                                    <?php 
                                        foreach($xizhenglist as $lk=>$lv){
                                          foreach($lv as $k=>$v){
                                            $selectedstr = ($mainInfo['type'] == 2 && isset($mainInfo['subject'][$k])) ? 'selected' : '';
                                            $option_val = ($selectedstr == 'selected') ? $mainInfo['subject'][$k] : $v;
                                            echo "<option value='$k' $selectedstr> $v </option>" ;
                                            }
                                         }?>
                                </select>
                                <?php }?>
                                <script src="/assets/js/multiple-select_fy.js"></script>
                                <link href="/assets/js/multiple-select.css" rel="stylesheet">
                                <script>
                        $("#multipleselect_ky").multipleSelect({
                            class:'multipleselect_ky',
                            width: 480,
                            multiple: true,
                            multipleWidth: 220,
                            minimumCountSelected: 9
                        });
                                </script>
                                <?php if($is_department == 1){?>
                                <script>
                                    $("#multipleselect_bm").multipleSelect({
                                        class:'multipleselect_bm',
                                        width: 480,
                                        multiple: true,
                                        multipleWidth: 220,
                                        minimumCountSelected: 9
                                    });
                                </script>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td>金额</td>
                            <td>人民币大写</td>
                            <td colspan='2'>  <input type="text" name='rmb_capital' class="rmb_capital" disabled="disabled"  style='width:180px;height:25px;' />   </td>
                            <td>￥</td>
                            <td colspan='2'> <input type="text" name='amount' class="amount" disabled="disabled"  style='width:180px;height:25px;' />   </td>
                        </tr>
                        <tr>
                            <td>报销<br/>简要说明</td>
                            <td colspan='6'> <textarea  name="description" class="description"  style="width:570px;" ><?php echo $attrInfo['description'];?></textarea>
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
                            <td > 
                                    <!--<input style="width: 60px;" type='text' class="applicant" name="applicant" value="<?php echo $userInfo->name;?>" />-->
                                    <textarea title="回车换行分割" placeholder="回车换行分割" style="width: 75px; height: 63px;min-width: 75px;max-height: 63px;max-width: 75px;min-height: 63px;" class="applicant" name="applicant"><?php echo $attrInfo['applicant'] ? $attrInfo['applicant'] : trim($userInfo->name);?></textarea>
                                </td>
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

            <!-- PAGE CONTENT BEGINS -->
            <div id="dropzone">
                
                <?php if($mainInfo['id']){ ?>
                 <div class="alert alert-warning alert-dismissable center ">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                         &times;
                     </button>
                     <strong>请再次提交附件！ </strong>
                 </div>
                 <?php   } ?>
                 
                <span>点击添加上传附件</span>
                <form  class="dropzone" style='min-height:100px;' enctype="multipart/form-data" id="upfiles"  method="post" >
                    
                    <div class="fallback" >
                        <input name="file[]" type="file" multiple="" />
                    </div>

                 <input type="hidden" id="file_upload" name="file_upload[]" value="" />  
                </form>
            </div><!-- PAGE CONTENT ENDS -->
            <!-- basic scripts -->
            <script src="/js/jquery-2.0.3.min.js"></script>
            <script src="/assets/js/dropzone.min.js"></script>
            <script type="text/javascript">
                      jQuery(function ($) {
                         try {
                             $(".dropzone").dropzone({
                                 url: '/ResearchProject/upload_file',
                                 paramName: "file", // The name that will be used to transfer the file
                                 maxFilesize: 0.5, // MB

                                 addRemoveLinks: true,
                                 dictDefaultMessage:
                                         '<span class="bigger-150 bolder"><i class="icon-caret-right red"></i> Drop files</span> to upload \
                <span class="smaller-80 grey">(or click)</span> <br /> \
                <i class="upload-icon icon-cloud-upload blue icon-3x"></i>'
                                 ,
                                 dictResponseError: 'Error while uploading file!',

                                 //change the previewTemplate to use Bootstrap progress bars
                                 previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>",
                                 removedfile: function (file) {
                                     //删除文件
                                     var file_name = file.name;
                                     file.previewElement.remove();
                                     var file_all_arr = $('#file_upload').val().split('|');
                                     var index_ = $.inArray(file_name, file_all_arr);
                                     if (index_ != -1) {
                                         //去掉他
                                         file_all_arr.splice(index_, 1);
                                     }
                                     $('#file_upload').val(file_all_arr.join('|'));
                                 },
                                 success: function (file, res) {
                                     //把json转成Obj
                                     var res = JSON.parse(res);
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
                                         //重名
                                         (file.previewElement.classList.add("dz-error"));
                                         //file.previewElement.remove();
                                         alert(res.msg);

                                         return;
                                     }
                                     if (res.code == 0) {
                                         //上传成功什么也不干   
                                         (file.previewElement.classList.add("dz-success"));
                                         var file_all_str = $('#file_upload').val();
                                         if (file_all_str) {
                                             file_all_str += '|' + file.name;
                                         } else {
                                             file_all_str = file.name;
                                         }
                                         $('#file_upload').val(file_all_str)
                                         return;
                                     }
                                     if (res.code == 2) {
                                         //失败
                                         alert(res.msg);
                                         return;
                                     }
                                 }

                             });
                         } catch (e) {
                             alert('Dropzone.js does not support older browsers!');
                         }

                     });  
            </script>
            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal" > <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>
<script type="text/javascript">             
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
        $('.right_content').css('display', 'none');
    }
    window.print();//打印刚才新建的网页
    {
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
<script type="text/javascript">
    //相加
    function add_decimal(decimal_a, decimal_b) {
        if (!$.isNumeric(decimal_a)) {
            decimal_a = 0;
        }
        if (!$.isNumeric(decimal_b))
        {
            decimal_b = 0;
        }
        decimal_a = parseFloat(decimal_a);
        decimal_b = parseFloat(decimal_b);
        //判断他们小数据点位数，谁的大取谁的
        var len_a = decimal_a.toString().indexOf('.') == -1 ? 0 : decimal_a.toString().split(".")[1].length;
        var len_b = decimal_b.toString().indexOf('.') == -1 ? 0 : decimal_b.toString().split(".")[1].length;
        var max = len_a;//最大的小数位数
        if (len_b > len_a) 
        {
            max = len_b;
        }
        var tmp_sum = decimal_a * Math.pow(10, max) + decimal_b * Math.pow(10, max);
        tmp_sum = tmp_sum / Math.pow(10, max);
        return tmp_sum;
    }
    //计算科目的费用
    var total = 0;//总数
    var sub_str = '';//科目 
    function sub_fy() {
        total = 0;
        sub_str = ''
        $( '.'+ class_name +' div.ms-drop li.multiple').each(function (i) {
            var li_item = $('.'+class_name+' li.multiple').eq(i);
            if (li_item.find('.first_inpuut').get(0).checked) {
                //如果这个选中，则把他的金额取出，放到total里面
                if ($.isNumeric(li_item.find('input.je').val())) {
//                    total = parseFloat(total) + parseFloat(li_item.find('input.je').val());
                    total = add_decimal(total, parseFloat(li_item.find('input.je').val()));
                    var name = $('select#'+ class_name +' option').eq(i).text();
                    var money = li_item.find('input.je').val();
                    sub_str += name + ": " + money + ',';
                }
            }
        });
        $('.subject').val(sub_str + '总额: ' + total);
        $('input.amount').val(total);
        var big_total_str = '';
        if (total < 0) {
            big_total_str = '负';
        }
        big_total_str += convertCurrency(Math.abs(total));
        $('.rmb_capital').val(big_total_str);
    }
    //当输入框输入后，再改变一下总金额
    $('input.je').blur(function () {
        var reg = /^[-]?[1-9]+[0-9]*/;
        if (!reg.test(this.value)) {
            this.value = '';
        }
        sub_fy();
    });
    //获取下拉的，值和键
    var option_json_tmp = {};
    function option_josn() {
        $('.'+ class_name +' div.ms-drop li.multiple').each(function (i) {
            var li_item = $('.'+class_name+' li.multiple').eq(i);
            var index = $('select#'+ class_name +' option').eq(i).val();
            var is_select = 0;
            var money = li_item.find('input.je').val();
            if (li_item.find('.first_inpuut').get(0).checked) {
                is_select = 1;
                option_json_tmp[index] = money;
            }
//            var tmp = {};
//            tmp.is_select = is_select;
//            tmp.money = money;
//            option_json_tmp[index] = tmp;
        });
        return option_json_tmp;
    }
        //去左空格;
function ltrim(s){
    return s.replace(/(^\s*)/g, "");
}
//去右空格;
function rtrim(s){
    return s.replace(/(\s*$)/g, "");
}
//去左右空格;
function trim(s){
    return s.replace(/(^\s*)|(\s*$)/g, "");
}
    function approve() {
        var ctime = $('.ctime').val();
        var page_number = $('.page_number').val();
        var projectname = $('.projectname').val();
        var filenumber = $('.filenumber').val();
        var is_calculation = $('.is_calculation').val();
        var subject = option_josn();
  
        var rmb_capital = $('.rmb_capital').val();
        var amount = $('.amount').val();
        var description = $('.description').val();
        var declarename = $('.declarename').val();
        var attachment = $('#file_upload').val();
        var applicant = $('.applicant').val();
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        var reg = /^[1-9]\d*$/;
        if (!reg.test(page_number)) {
            $('.page_number').focus();
            return;
        }
        if (projectname == '') {
            $('.projectname').focus();
            return;
        }
        if (filenumber == '') {
            $('.filenumber').focus();
            return;
        }
        

        if (rmb_capital == '') {
            $('.ms-choice').focus();
            return;
        }
        if (amount == '') {
            $('.ms-choice').focus();
            return;
        }
        if (description == '') {
            $('.description').focus();
            return;
        }
        if (applicant == '') {
            $('.applicant').focus();
            return;
        }
        applicant = trim(applicant);
       if (applicant == '') {
            $('.applicant').focus();
            return;
       }
       var applicant_arr = applicant.split("\n");
       var applicant_str = '';
       for(var i in applicant_arr) {
            if (applicant_arr[i] == '') {
                continue;
            }
            applicant_str += applicant_arr[i] + ',';
       }
       applicant = applicant_str.substring(0, applicant_str.length - 1);
        var data = {declarename: declarename, ctime: ctime, applicant: applicant, page_number: page_number, projectname: projectname, filenumber: filenumber, subject: subject, rmb_capital: rmb_capital, amount: amount, description: description, attachment:attachment, is_calculation:is_calculation};

        $.ajax({
            url: '/ResearchProject/sub_declares',
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

<?php 
                            $subject_str = '';
                            foreach($keyanlist as $lk=>$lv){
                                foreach($lv as $k=>$v){
                                 $subject_str .= isset($mainInfo['subject'][$k]) ? $v.':'.$mainInfo['subject'][$k].',' : '';
                                } 
                            } 
                            if($subject_str != ''){
                                $subject_str .= '总额：'.$mainInfo['total'];
                        ?>
                        $('.subject').val("<?php echo $subject_str;?>");
                        $('.rmb_capital').val("<?php echo $attrInfo['rmb_capital'];?>");
                        $('.amount').val("<?php echo $attrInfo['amount'];?>");
                        <?php } ?>

</script>

<?php echo $this->element('foot_frame'); ?>