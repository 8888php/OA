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
                        <input type="hidden" name='declarename' class='declarename' value='果树所差旅费报销单' /> 
                        <tbody>
                            <tr>
                                <td colspan="8" style="font-size:24px;font-weight: 600;border-color:#000;">  果树所差旅费报销单 </td>
                            </tr>
                            <tr>
                                <td >填表时间</td>
                                <td colspan='2'>  <input readonly="readonly" type="text" class="ctime" name="ctime"   value="<?php echo date('Y-m-d'); ?>"  style='height:25px;width:150px;'>  </td>
                                <td>附单据张数</td>
                                <td colspan='2'> <input type="text" name='sheets_num' class="sheets_num" style='width:140px;height:25px;' value="<?php echo $attrInfo['page_number'] ? $attrInfo['page_number'] : ''; ?>"  />  </td>
                                <td>核算</td>
                                <td > 
                                    <select  name='is_calculation' class="is_calculation" style='width:60px;height:25px;' >
                                        <option value="1" > 是 </option>
                                        <option value="0"  <?php echo $attrInfo['is_calculation'] === 0 ? 'selected' : ''; ?> > 否 </option>
                                    </select>
                                </td>
                            </tr>
                            
                             <tr>
                                <td>部门或项目</td>
                                <td colspan='7'>
                                    <select style="width:335px;height:25px;" name='dep_pro' class="dep_pro"  onchange="change_filenumber();" >
                                        <?php if ($is_department == 1){?>
                                        <option value="0"><?php echo $department_arr['Department']['name'];?></option>
                                        <?php }?>
                                        <?php 
                                        foreach($projectInfo as $pk=>$pv) {
                                        $selectedstr = ($mainInfo['project_id'] == $pk) ? 'selected' : '';
                                        echo "<option value='".$pk ."'". $selectedstr . '>' . $pv . "</option>";
                                         }?>
                                    </select>
                                    <select style="width:215px;height:25px;" name="filenumber" class="filenumber"  >
                                        <option></option>
                                    </select>
                                </td>
                                </tr>
                            <script type="text/javascript">
                                    function change_filenumber(sid = 0) {
                                        var depid = "<?php echo $department_arr['Department']['id']; ?>" ;
                                        var type = $('.dep_pro').val();
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
                             <tr>
                                <td > 出差人姓名 </td>
                                <td colspan='6'>
                                    <input type="text" class="personnel" name="personnel"  style='height:25px;width:465px;'  value="<?php echo $attrInfo['business_traveller_id']; ?>" >  </td>
                                <td >
                                   共 <input  type="text" class="sums" name="sums"  style='height:25px;width:40px;'  value="<?php echo $attrInfo['total_number'] ? $attrInfo['total_number'] : 0; ?>"> 人 </td>
                             </tr>
                             <tr>
                                <td>起止日期</td>
                                <td>起讫地点</td>
                                <td>车船费</td>
                                <td>差旅补助天数</td>
                                <td>差旅补助标准</td>
                                <td>差旅补助金额</td>
                                <td>住宿费</td>
                                <td>其他费用</td>
                            </tr>
                            
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day0" name="dp[0]['start_end_day']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][0]['start_end_day']; ?>" > </td>
                                <td> <input type="text" class="start_end_address0" name="dp[0]['start_day']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['start_end_address']; ?>" > </td>
                                <td> <input type="text" class="fare0" name="dp[0]['fare']"  style='height:25px;width:75px;'   value="<?php echo $attrInfo['json_str'][0]['fare'] ? $attrInfo['json_str'][0]['fare'] : 0; ?>"> </td>
                                <td> <input type="text" class="allowance_days0" name="dp[0]['allowance_days']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['allowance_days'] ? $attrInfo['json_str'][0]['allowance_days'] : 0; ?>" > </td>
                                <td> <input type="text" class="supply_needs0" name="dp[0]['supply_needs']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['supply_needs'] ? $attrInfo['json_str'][0]['supply_needs'] : 0; ?>"> </td>
                                <td> <input readonly="readonly" type="text" class="subsidy_amount0" name="dp[0]['subsidy_amount']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['subsidy_amount'] ? $attrInfo['json_str'][0]['subsidy_amount'] : 0; ?>"> </td>
                                <td> <input type="text" class="hotel_expense0" name="dp[0]['hotel_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['hotel_expense'] ? $attrInfo['json_str'][0]['hotel_expense'] : 0; ?>"> </td>
                                <td> <input type="text" class="other_expense0" name="dp[0]['other_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][0]['other_expense'] ? $attrInfo['json_str'][0]['other_expense'] : 0; ?>"> </td>
                                </tr>
                                
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day1" name="dp[1]['start_end_day']"  style='height:25px;width:75px;'value="<?php echo $attrInfo['json_str'][1]['start_end_day']; ?>"  > </td>
                                <td> <input type="text" class="start_end_address1" name="dp[1]['start_day']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][1]['start_end_address']; ?>" > </td>
                                <td> <input type="text" class="fare1" name="dp[1]['fare']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][1]['fare'] ? $attrInfo['json_str'][1]['fare'] : 0; ?>"> </td>
                                <td> <input type="text" class="allowance_days1" name="dp[1]['allowance_days']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][1]['allowance_days'] ? $attrInfo['json_str'][1]['allowance_days'] : 0; ?>"> </td>
                                <td> <input type="text" class="supply_needs1" name="dp[1]['supply_needs']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][1]['supply_needs'] ? $attrInfo['json_str'][1]['supply_needs'] : 0; ?>"> </td>
                                <td> <input readonly="readonly" type="text" class="subsidy_amount1" name="dp[1]['subsidy_amount']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][1]['subsidy_amount'] ? $attrInfo['json_str'][1]['subsidy_amount'] : 0; ?>"> </td>
                                <td> <input type="text" class="hotel_expense1" name="dp[1]['hotel_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][1]['hotel_expense'] ? $attrInfo['json_str'][1]['hotel_expense'] : 0; ?>"> </td>
                                <td> <input type="text" class="other_expense1" name="dp[1]['other_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][1]['other_expense'] ? $attrInfo['json_str'][1]['other_expense'] : 0; ?>"> </td>
                                </tr>
                                
                            <tr class="json_str">
                                <td > <input type="text" class="start_end_day2" name="dp[2]['start_end_day']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][2]['start_end_day']; ?>" > </td>
                                <td> <input type="text" class="start_end_address2" name="dp[2]['start_day']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][2]['start_end_address']; ?>" > </td>
                                <td> <input type="text" class="fare2" name="dp[2]['fare']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][2]['fare'] ? $attrInfo['json_str'][2]['fare'] : 0; ?>"> </td>
                                <td> <input type="text" class="allowance_days2" name="dp[2]['allowance_days']"  style='height:25px;width:75px;'value="<?php echo $attrInfo['json_str'][2]['allowance_days'] ? $attrInfo['json_str'][2]['allowance_days'] : 0; ?>"> </td>
                                <td> <input type="text" class="supply_needs2" name="dp[2]['supply_needs']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][2]['supply_needs'] ? $attrInfo['json_str'][2]['supply_needs'] : 0; ?>"> </td>
                                <td> <input readonly="readonly" type="text" class="subsidy_amount2" name="dp[2]['subsidy_amount']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][2]['subsidy_amount'] ? $attrInfo['json_str'][2]['subsidy_amount'] : 0; ?>"> </td>
                                <td> <input type="text" class="hotel_expense2" name="dp[2]['hotel_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][2]['hotel_expense'] ? $attrInfo['json_str'][2]['hotel_expense'] : 0; ?>"> </td>
                                <td> <input type="text" class="other_expense2" name="dp[2]['other_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][2]['other_expense'] ? $attrInfo['json_str'][2]['other_expense'] : 0; ?>"> </td>
                                </tr>
                                <tr class="json_str">
                                    <td colspan="2"> 小计</td>
                                    <td> <input readonly="readonly" type="text" class="fare3" name="dp[3]['fare']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][3]['fare'] ? $attrInfo['json_str'][3]['fare'] : 0; ?>"> </td>
                                    <td> <input readonly="readonly" type="text" class="allowance_days3" name="dp[3]['allowance_days']"  style='height:25px;width:75px;'value="<?php echo $attrInfo['json_str'][3]['allowance_days'] ? $attrInfo['json_str'][3]['allowance_days'] : 0; ?>"> </td>
                                    <td> <input readonly="readonly" type="text" class="supply_needs3" name="dp[3]['supply_needs']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][3]['supply_needs'] ? $attrInfo['json_str'][3]['supply_needs'] : 0; ?>"> </td>
                                    <td> <input readonly="readonly" readonly="readonly" type="text" class="subsidy_amount3" name="dp[3]['subsidy_amount']"  style='height:25px;width:75px;' value="<?php echo $attrInfo['json_str'][3]['subsidy_amount'] ? $attrInfo['json_str'][3]['subsidy_amount'] : 0; ?>"> </td>
                                    <td> <input readonly="readonly" type="text" class="hotel_expense3" name="dp[3]['hotel_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][3]['hotel_expense'] ? $attrInfo['json_str'][3]['hotel_expense'] : 0; ?>"> </td>
                                    <td> <input readonly="readonly" type="text" class="other_expense3" name="dp[3]['other_expense']"  style='height:25px;width:75px;'  value="<?php echo $attrInfo['json_str'][3]['other_expense'] ? $attrInfo['json_str'][3]['other_expense'] : 0; ?>"> </td>
                                </tr>
                           
                            <tr>
                                <td style="width:90px;">合计（大写）</td>
                                <td colspan='4'>  <input readonly="readonly" type="text" name='big_total' class='big_total'  value="<?php echo $attrInfo['total_capital']; ?>"   style="width:315px;" /> </td>
                                <td style="width:40px;"> ￥ </td>
                                <td colspan='2'>  <input readonly="readonly" type="text" name='small_total' class='small_total'  value="<?php echo $attrInfo['total']; ?>"   style="width:150px;" /> </td>
                            </tr>
                           
                            <tr>
                                <td>事由</td>
                                <td colspan='7'> <input type="text" name='reason' class="reason" style='width:570px;height:25px;'  value="<?php echo $attrInfo['reason']; ?>"  /> </td>
                            </tr>
                            
                            <tr>
                                <td style="width:260px;" >申报人</td>
                                <td  style="width:260px;">项目负责人</td>
                                <td >科室负责人</td>
                                <td  style="width:260px;" >分管所领导</td>
                                <td  style="width:260px;">所长</td>
                                <td >分管财务所长</td>
                                <td colspan='2' style="width:260px;" >财务科长</td>
                            </tr>
                            <tr style="height:60px;line-height: 20px;" >
                                <td > 
                                    <!--<input style="width: 60px;" type='text' class="applicant" name="applicant" value="<?php echo $userInfo->name;?>" />-->
                                    <textarea title="回车换行分割" placeholder="回车换行分割" style="width: 75px; height: 63px;min-width: 75px;max-height: 63px;max-width: 75px;min-height: 63px;" class="applicant" name="applicant"><?php echo $attrInfo['applicant'] ? $attrInfo['applicant'] : trim($userInfo->name);?></textarea>
                                </td>
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
                                 maxFilesize: 1.0, // MB

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

        </div>
    </div><!-- /.row -->
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
    function jisuan() {
        var mast_one = false;//必须有一个
        var error_flag = false;//记录错误标示
        var fare_xj = 0,
            allowance_days_xj = 0,
            supply_needs_xj = 0,
            subsidy_amount_xj = 0,
            hotel_expense_xj = 0,
            other_expense_xj = 0;//小计
            $('.json_str').each(function(i){
            var tmp_str = {};
            if (i == 3) {
                //计算小计
                if (!mast_one) {
                    return false;//中止掉
                }
                $(this).find('.fare' + i).val(fare_xj);
                tmp_str.fare = fare_xj;
                $(this).find('.allowance_days' + i).val(allowance_days_xj);
                tmp_str.allowance_days = allowance_days_xj;
                $(this).find('.supply_needs' + i).val(supply_needs_xj);
                tmp_str.supply_needs = supply_needs_xj;
                $(this).find('.subsidy_amount' + i).val(subsidy_amount_xj);
                tmp_str.subsidy_amount = subsidy_amount_xj;
                $(this).find('.hotel_expense' + i).val(hotel_expense_xj);
                tmp_str.hotel_expense = hotel_expense_xj;
                $(this).find('.other_expense' + i).val(other_expense_xj);
                tmp_str.other_expense = other_expense_xj;
//                json_str[i] = tmp_str;
                
                //合计
                var total = add_decimal(add_decimal(fare_xj + subsidy_amount_xj), add_decimal(hotel_expense_xj + other_expense_xj));
                $('.small_total').val(total);
                var big_total_str = '';
                if (total < 0) {
                    big_total_str = '负';
                }
                big_total_str += convertCurrency(Math.abs(total));
                $('.big_total').val(big_total_str);
                return false;//中止，已经到结束了
            }
            var start_end_day = $(this).find('.start_end_day' + i).val();
            if (start_end_day == '') {
                //没填写跳过
                return true;
            } else {
                mast_one = true;
            }
            tmp_str.start_end_day = start_end_day;
            var start_end_address = $(this).find('.start_end_address' + i).val();
            if (start_end_address == '') {
                $(this).find('.start_end_address' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.start_end_address = start_end_address;
            var fare = $(this).find('.fare' + i).val();
            if (fare == '' || isNaN(fare)) {
                $(this).find('.fare' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.fare = fare;
            fare_xj += parseFloat(fare);
            var allowance_days = $(this).find('.allowance_days' + i).val();
            if (allowance_days == '' || isNaN(allowance_days)) {
                $(this).find('.allowance_days' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.allowance_days = allowance_days;
            allowance_days_xj += parseInt(allowance_days);
            var supply_needs = $(this).find('.supply_needs' + i).val();
            if (supply_needs == '' || isNaN(supply_needs)) {
                $(this).find('.supply_needs' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.supply_needs = supply_needs;
            supply_needs_xj += parseFloat(supply_needs)
            var subsidy_amount = ride_decimal(allowance_days, supply_needs);
            $(this).find('.subsidy_amount' + i).val(subsidy_amount);
//            if (subsidy_amount == '' || isNaN(subsidy_amount)) {
//                $(this).find('.subsidy_amount' + i).focus();
//                error_flag = true;
//                return false;//中止
//            }
            tmp_str.subsidy_amount = subsidy_amount;
            subsidy_amount_xj += parseFloat(subsidy_amount);
            var hotel_expense = $(this).find('.hotel_expense' + i).val();
            if (hotel_expense == '' || isNaN(hotel_expense)) {
                $(this).find('.hotel_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.hotel_expense = hotel_expense;
            hotel_expense_xj += parseFloat(hotel_expense);
            var other_expense = $(this).find('.other_expense' + i).val();
            if (other_expense == '' || isNaN(other_expense)) {
                $(this).find('.other_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.other_expense = other_expense;
            other_expense_xj += parseFloat(other_expense);
        });
        if (!mast_one) {
            //说明没有写
            $('.json_str').find('.start_end_day' + 0).focus();
            //把合计和总计都清空
            $('.json_str').eq(3).find('input').val('');
            $('.big_total').val('');
            $('.small_total').val('');
            return;
        }
        if (error_flag) {
            //说明有没写的
            return;
        }
    }
    $('.json_str input').blur(function(){
        jisuan();
    });
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
        var sheets_num = $('.sheets_num').val();
        var is_calculation = $('.is_calculation').val();
        var dep_pro = $('.dep_pro').val();
        var filenumber = $('.filenumber').val();
        var personnel = $('.personnel').val();
        var sums = $('.sums').val();
        var big_total = $('.big_total').val();
        var small_total = $('.small_total').val();
        var reason = $('.reason').val();
        var payee = $('.payee').val();
        var declarename = $('.declarename').val();
        var applicant = $('.applicant').val();
        
        if (ctime == '') {
            $('.ctime').focus();
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
        if (personnel == '') {
            $('.personnel').focus();
            return;
        }
        if (sums == '') {
            $('.sums').focus();
            return;
        }
        
        var json_str = [{}];
        var mast_one = false;//必须有一个
        var error_flag = false;//记录错误标示
        var fare_xj = 0,
            allowance_days_xj = 0,
            supply_needs_xj = 0,
            subsidy_amount_xj = 0,
            hotel_expense_xj = 0,
            other_expense_xj = 0;//小计
        $('.json_str').each(function(i){
            var tmp_str = {};
            if (i == 3) {
                //计算小计
                if (!mast_one) {
                    return false;//中止掉
                }
                $(this).find('.fare' + i).val(fare_xj);
                tmp_str.fare = fare_xj;
                $(this).find('.allowance_days' + i).val(allowance_days_xj);
                tmp_str.allowance_days = allowance_days_xj;
                $(this).find('.supply_needs' + i).val(supply_needs_xj);
                tmp_str.supply_needs = supply_needs_xj;
                $(this).find('.subsidy_amount' + i).val(subsidy_amount_xj);
                tmp_str.subsidy_amount = subsidy_amount_xj;
                $(this).find('.hotel_expense' + i).val(hotel_expense_xj);
                tmp_str.hotel_expense = hotel_expense_xj;
                $(this).find('.other_expense' + i).val(other_expense_xj);
                tmp_str.other_expense = other_expense_xj;
                json_str[i] = tmp_str;
                
                //合计
                var total = add_decimal(add_decimal(fare_xj + subsidy_amount_xj), add_decimal(hotel_expense_xj + other_expense_xj));
                $('.small_total').val(total);
                $('.big_total').val(convertCurrency(total));
                return false;//中止，已经到结束了
            }
            var start_end_day = $(this).find('.start_end_day' + i).val();
            if (start_end_day == '') {
                //没填写跳过
                return true;
            } else {
                mast_one = true;
            }
            tmp_str.start_end_day = start_end_day;
            var start_end_address = $(this).find('.start_end_address' + i).val();
            if (start_end_address == '') {
                $(this).find('.start_end_address' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.start_end_address = start_end_address;
            var fare = $(this).find('.fare' + i).val();
            if (fare == '' || isNaN(fare)) {
                $(this).find('.fare' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.fare = fare;
            fare_xj += parseFloat(fare);
            var allowance_days = $(this).find('.allowance_days' + i).val();
            if (allowance_days == '' || isNaN(allowance_days)) {
                $(this).find('.allowance_days' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.allowance_days = allowance_days;
            allowance_days_xj += parseInt(allowance_days);
            var supply_needs = $(this).find('.supply_needs' + i).val();
            if (supply_needs == '' || isNaN(supply_needs)) {
                $(this).find('.supply_needs' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.supply_needs = supply_needs;
            supply_needs_xj += parseFloat(supply_needs);
            var subsidy_amount = parseFloat(allowance_days) * parseFloat(supply_needs);
            $(this).find('.subsidy_amount' + i).val(subsidy_amount);
//            if (subsidy_amount == '' || isNaN(subsidy_amount)) {
//                $(this).find('.subsidy_amount' + i).focus();
//                error_flag = true;
//                return false;//中止
//            }
            tmp_str.subsidy_amount = subsidy_amount;
            subsidy_amount_xj += parseFloat(subsidy_amount);
            var hotel_expense = $(this).find('.hotel_expense' + i).val();
            if (hotel_expense == '' || isNaN(hotel_expense)) {
                $(this).find('.hotel_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.hotel_expense = hotel_expense;
            hotel_expense_xj += parseFloat(hotel_expense);
            var other_expense = $(this).find('.other_expense' + i).val();
            if (other_expense == '' || isNaN(other_expense)) {
                $(this).find('.other_expense' + i).focus();
                error_flag = true;
                return false;//中止
            }
            tmp_str.other_expense = other_expense;
            other_expense_xj += parseFloat(other_expense)
            json_str[i] = tmp_str;
        });
        
        if (!mast_one) {
            //说明没有写
            $('.json_str').find('.start_end_day' + 0).focus();
            //把合计和总计都清空
            $('.json_str').eq(3).find('input').val('');
            $('.big_total').val('');
            $('.small_total').val('');
            return;
        }
        if (error_flag) {
            //说明有没写的
            return;
        }
        if (big_total == '') {
            $('.big_total').focus();
            return;
        }
        if (small_total == '') {
            $('.small_total').focus();
            return;
        }
        if (reason == '') {
            $('.reason').focus();
            return;
        }
        if (payee == '') {
            $('.payee').focus();
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
        var data = {attachment: attachment,filenumber: filenumber, declarename: declarename, applicant: applicant, json_str: json_str,ctime: ctime, reason: reason, sheets_num: sheets_num, dep_pro: dep_pro, personnel: personnel, sums: sums, big_total: big_total,small_total: small_total,payee: payee,declarename: declarename, is_calculation : is_calculation};
        $.ajax({
            url: '/RequestNote/gss_evection_expense',
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

