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
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所领款单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所领款单 </td>
                            </tr>
                            <tr>
                                <td >填表日期</td>
                                <td colspan='4'>
                                    <input readonly="readonly" type="text" class=" ctime" name="ctime"  value="<?php echo date('Y-m-d'); ?>" style='height:25px;width:290px;'>
                                    <script type="text/javascript">
                                        $(".ctime").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                                <td > 附单据张数 </td>
                                <td > <input type="text" name='sheets_num' class="sheets_num" style='width:80px;height:25px;' value="<?php echo $attrInfo['page_number'] ? $attrInfo['page_number'] : ''; ?>"  /> </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='6'>  <select style="width:220px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_filenumber();" >
                                        <?php if ($is_department){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php 
                                        foreach($projectInfo as $pk=>$pv) {
                                        $selectedstr = ($mainInfo['project_id'] == $pk) ? 'selected' : '';
                                        echo "<option value='".$pk ."'". $selectedstr . '>' . $pv . "</option>";
                                       }?>
                                    </select>
                                    <select style="width:155px;height:25px;" name="filenumber" class="filenumber"  >
                                        <option></option>
                                    </select>

                                    <select style="width:120px;height:25px;" name='xzsubject' class="xzsubject" >     <option value='0'> 请选择科目 </option>
                                        <?php 
                                            foreach(Configure::read('xizhenglist') as $kyk=>$kyv) {
                                                foreach($kyv as $key=>$val) {
                                                 $selectedstr = ($mainInfo['type'] == 2 && isset($mainInfo['subject'][$key])) ? 'selected' : '';
                                                    echo "<option value='$key'  $selectedstr > $val </option>" ;
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
                            </tr>
                            <script type="text/javascript">
                                 //撤销用
                                    function chexiao() {
                                       <?php if (!empty($mainInfo)) {?>
                                               change_filenumber();
                                               $('.filenumber option').each(function(){
                                                    if (this.value == '<?php echo $mainInfo['source_id'];?>') {
                                                        $(this).attr('selected', true);
                                                    }
                                                });
                                       <?php }?>
                                    }
                                    
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
                                                async: false,
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
                            <tr>
                                <td colspan="2">项目</td>
                                <td>计算单位</td>
                                <td>数量</td>
                                <td>单价</td>
                                <td>金额</td>
                                <td>备注</td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[0]['pro']" class="pro0" style='width:175px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['pro'];   ?>" /></td>
                                <td> <input type="text" name="dp[0]['unit']" class="unit0" style='width:80px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['unit'];   ?>"  /> </td>
                                <td> <input type="text" name="dp[0]['nums']" class="nums0" style='width:80px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['nums'];   ?>"  /> </td>
                                <td> <input type="text" name="dp[0]['unit_price']" class="unit_price0 jisuan" style='width:80px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['unit_price'];   ?>" /> </td>
                                <td> <input type="text" name="dp[0]['amount']" class="amount0" readonly="readonly"  style='width:80px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['amount'];   ?>"   /> </td>
                                <td> <input type="text" name="dp[0]['remarks']" class="remarks0" style='width:80px;height:25px;' value="<?php echo $attrInfo['json_str'][0]['remarks'];   ?>" /> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[1]['pro']" class="pro1" style='width:175px;height:25px;'   /></td>
                                <td> <input type="text" name="dp[1]['unit']" class="unit1" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[1]['nums']" class="nums1" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[1]['unit_price']" class="unit_price1 jisuan" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[1]['amount']" class="amount1"   style='width:80px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[1]['remarks']" class="remarks1" style='width:80px;height:25px;'  /> </td>
                            </tr>
                            <tr class="dp">
                                <td colspan="2"><input type="text" name="dp[2]['pro']" class="pro2" style='width:175px;height:25px;'  /></td>
                                <td> <input type="text" name="dp[2]['unit']" class="unit2" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[2]['nums']" class="nums2" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[2]['unit_price']" class="unit_price2 jisuan" style='width:80px;height:25px;'  /> </td>
                                <td> <input type="text" name="dp[2]['amount']" class="amount2"   style='width:80px;height:25px;'/> </td>
                                <td> <input type="text" name="dp[2]['remarks']" class="remarks2" style='width:80px;height:25px;'  /> </td>
                            </tr>
                            
                            <tr>
                                <td>合计</td>
                                <td colspan='4'> <input type="text" name='big_total' class="big_total" readonly="readonly" style='width:345px;height:25px;' value="<?php echo $attrInfo['big_total'];   ?>"  /> </td>
                                <td colspan='2'> ￥ <input type="text" name='small_total' class="small_total" readonly="readonly"  style='width:158px;height:25px;' value="<?php echo $attrInfo['small_total'];   ?>"  /> </td>
                            </tr>
                
                            <tr>
                                <td >领款人</td>
                                <td >团队/科室<br/>负责人审核</td>
                                <td >分管领导审核</td>
                                <td >分管财务<br/>领导审核</td>
                                <td >财务审核</td>
                                <td colspan="2">所长审核</td>
                            </tr>
                            <tr style="min-height:60px;line-height: 20px;">
                                <td > 
                                    <!--<input style="width: 60px;" type='text' class="applicant" name="applicant" value="<?php echo $userInfo->name;?>" />-->
                                    <textarea title="回车换行分割" placeholder="回车换行分割" style="width: 75px; height: 63px;min-width: 75px;max-height: 63px;max-width: 75px;min-height: 63px;" class="applicant" name="applicant"><?php echo $attrInfo['applicant'] ? $attrInfo['applicant'] : trim($userInfo->name);?></textarea>
                                </td>
                                <td > </td>
                                <td > </td>
                                <td >  </td>
                                <td > </td>
                                <td colspan="2">  </td>
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
                                 maxFilesize: 5.0, // MB

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
        //相乘
        function ride_decimal(decimal_a, decimal_b) {
            if (!$.isNumeric(decimal_a)) {
                decimal_a = 0;
            }
            if (!$.isNumeric(decimal_b))
            {
                decimal_b = 0;
            }
            decimal_a = parseFloat(decimal_a);
            decimal_b = parseFloat(decimal_b);
            if (decimal_a == 0 || decimal_b == 0) {
                return 0;
            }
            //判断他们小数据点位数，谁的大取谁的
            var len_a = decimal_a.toString().indexOf('.') == -1 ? 0 : decimal_a.toString().split(".")[1].length;
            var len_b = decimal_b.toString().indexOf('.') == -1 ? 0 : decimal_b.toString().split(".")[1].length;
            var max = len_a;//最大的小数位数
            if (len_b > len_a) 
            {
                max = len_b;
                decimal_b = parseFloat(decimal_b.toString().split(".")[0] + decimal_b.toString().split(".")[1]);
                if (len_a == 0) {
                    //说明是整数
                    for(var i=0; i < len_b; i++) {
                        decimal_a = decimal_a.toString() + '0';
                    }
                } else {
                    //说明是小数
                    decimal_a = parseFloat(decimal_a.toString().split(".")[0] + decimal_a.toString().split(".")[1]);
                    for(var i=0; i < len_b - len_a; i++) {
                        decimal_a = decimal_a.toString() + '0';
                    }
                }
                decimal_a = parseFloat(decimal_a);
            } else {
                decimal_a = parseFloat(decimal_a.toString().split(".")[0] + decimal_a.toString().split(".")[1]);
               if (len_b == 0) {
                    //说明是整数
                    for(var i=0; i < len_a; i++) {
                        decimal_b = decimal_b.toString() + '0';
                    }

                } else {
                    //说明是小数
                    decimal_b = parseFloat(decimal_b.toString().split(".")[0] + decimal_b.toString().split(".")[1]);
                    for(var i=0; i < len_a - len_b; i++) {
                        decimal_b = decimal_b.toString() + '0';
                    }
                } 
                 decimal_b = parseFloat(decimal_b);
            }
            
            var tmp_sum = decimal_a  * decimal_b;
            tmp_sum = tmp_sum / (Math.pow(10, max) * Math.pow(10, max));
            return tmp_sum;
        }
    
        var dp = $("input[name='dp']").val();  console.log(dp);
        $('.dp input').blur(function(){
            var total = 0;//总钱数
            $('.dp').each(function(i){
                var pro = $(this).find('.pro' + i).val();
                var unit = $(this).find('.unit' + i).val();
                var nums = $(this).find('.nums' + i).val();
                var unit_price = $(this).find('.unit_price' + i).val();
                var amount = $(this).find('.amount' + i).val();
                var remarks = $(this).find('.remarks' + i).val();
                if (pro != '' && !isNaN(nums) && nums != '' && !isNaN(unit_price) && unit_price != '') {
                    var tmp_total = ride_decimal(nums, unit_price);
                    $(this).find('.amount' + i).val(tmp_total);
                    total = add_decimal(total, tmp_total);
                }
            });
            var big_total = '';
            if (total < 0) {
                big_total = '负';
            }
            big_total += convertCurrency(Math.abs(total));
            $('.big_total').val(big_total);
            $('.small_total').val(total);
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
        var dep_pro = $('.dep_pro').val();
        var filenumber = $('.filenumber').val();
        var sheets_num = $('.sheets_num').val();
        var small_total = $('.small_total').val();
        var big_total = $('.big_total').val();
        var declarename = $('.declarename').val();
        var dp = $("input[name='dp']").val(); 
        var applicant = $('.applicant').val();//
        var subject = (dep_pro == 0) ? $('.xzsubject').val() : $('.kysubject').val(); 
        if (ctime == '') {
            $('.ctime').focus();
            return;
        }
        if(subject == '' || subject == '0'){
           $('.xzsubject').focus();
           $('.kysubject').focus();
            return;
        }
        var reg = /^[1-9]\d*$/;
        if (!reg.test(sheets_num)) {
            $('.sheets_num').focus();
            return;
        }
        if (filenumber == '') {
            $('.filenumber').focus();
            return;
        }
        var dp_json_str = [{}];
        var mast_one = false;//必须有一个项目
        var error_flag = false;//错误标记
       $('.dp').each(function(i){
           var tmp_str = {};
           var pro = $(this).find('.pro' + i).val();
           if (pro == '') {
               //跳过
               return true;
           } else {
               mast_one = true;//标记这行有记录
           }
           var unit = $(this).find('.unit' + i).val();
           if (unit == '') {
               $(this).find('.unit' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var nums = $(this).find('.nums' + i).val();
           if (nums == '' || isNaN(nums)) {
               $(this).find('.nums' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var unit_price = $(this).find('.unit_price' + i).val();
           if (unit_price == '' || isNaN(unit_price)) {
               $(this).find('.unit_price' + i).focus();
               error_flag = true;
               return false;//中止
           }
           var amount = $(this).find('.amount' + i).val();
           var remarks = $(this).find('.remarks' + i).val();
           tmp_str.pro = pro;
           tmp_str.unit = unit;
           tmp_str.nums = nums;
           tmp_str.unit_price = unit_price;
           tmp_str.amount = amount;
           tmp_str.remarks = remarks;
           dp_json_str[i] = tmp_str;
       });
       if (!mast_one) {
           //说明他一行也没有写
           $('.dp').find('.pro' + 0).focus();
           return;
       }
       if (error_flag) {
           //说明所选有错误
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
       var attachment = $('#file_upload').val();
       var old_main_id = 0;
       <?php if (isset($mainInfo)) {?>
               old_main_id = "<?php echo $mainInfo['id'];?>";
       <?php }?>
        var data = {old_main_id: old_main_id, attachment: attachment,declarename: declarename, applicant: applicant, filenumber: filenumber ,dp_json_str:dp_json_str,ctime: ctime, dep_pro: dep_pro, sheets_num: sheets_num, small_total: small_total, big_total: big_total,declarename: declarename,subject: subject};
        $.ajax({
            url: '/RequestNote/gss_draw_money',
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

