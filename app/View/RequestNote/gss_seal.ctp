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
                    <input type="hidden" name='declarename' class='declarename' value='印信使用签批单' /> 
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  印信使用签批单 </td>
                            </tr>
                            <tr>
                                <td >申请人</td>
                                <td colspan='2'>  <input  type="text" class="applyname" name="applyname"  style='height:25px;width:180px;'  value="<?php echo $userInfo->name;?>"> </td>
                                <td >流水单号</td>
                                <td colspan='3'> <input  type="text" class="oddnum" name="oddnum"  style='height:25px;width:180px;'  value="<?php echo date('Ym').$number;?>" readonly="readonly" >  </select> 
                                </td>
                            </tr>
                            
                             <tr>
                                <td>使用内容</td>
                                <td colspan='2'>   
                                    <input type="radio"  checked="checked" class="sealtype" name="sealtype" value='1' />  公章
                                    <input type="radio" class="sealtype" name="sealtype" value='2' />  名章
                                </td>
                                <td>使用单位</td>
                                <td colspan='3'>   
                                    <select style="height:25px;width:280px;" name="department" class="department" >
                                        <?php 
                                           foreach($dep_list as $k => $v){
                                            echo "<option value='$k' > $v </option>";
                                            }
                                         ?>
                                    </select>
                                </td>
                             </tr>
                            <tr>
                             <td>部门</td>
                                <td colspan='6'>   
                                    <select style="height:25px;width:580px;" name="dep_team" class="dep_team" >
                                        <?php foreach($department_arr as $v){?>
                                        <option value="0"><?php echo $v['name'];?></option>
                                        <?php }?>
                                        <?php foreach($team_arr as $v){?>
                                        <option value="<?php echo $v['team']['id'];?>"><?php echo $v['team']['name'];?></option>
                                        <?php }?>
                                    </select>
                                </td>
                             </tr>
                             <tr>
                                <td> 文件类型 </td>
                                <td colspan='6' >  
                                    <input type='checkbox'class="filetype" name='filetype' value='1' > 报表   
                                    <input type='checkbox'class="filetype" name='filetype' value='2' > 合同、协议  
                                    <input type='checkbox'class="filetype" name='filetype' value='3' > 证明  
                                    <input type='checkbox'class="filetype" name='filetype' value='4' > 申请  
                                    <input type='checkbox'class="filetype" name='filetype' value='5' > 介绍信  
                                    <input type='checkbox'class="filetype" name='filetype' value='6' > 外部函件  
                                    <input type='checkbox'class="filetype" name='filetype' value='7' > 其他  
                                </td>
                             </tr>                           
                            <tr>
                                <td >文号/编号</td>
                                <td colspan='6'>
                                    <input type="text" class='filenum'  name="filenum"  style='height:25px;width:500px;'>  
                                </td>
                            </tr>
                           
                            <tr>
                                <td style='height:50px;'> 部门负责人意见 </td>
                                <td  colspan='2' >   </td>
                                <td style='height:50px;'> 分管所领导意见 </td>
                                <td  colspan='3' >   </td>
                            </tr>
                            <tr>
                                <td style='height:50px;'> 科室主任意见 </td>
                                <td  colspan='6' >   </td>
                            </tr>
                            <tr >
                                <td style='height:50px;'> 所长意见  </td>
                                <td colspan='6'>   </td>
                            </tr>
                            <tr >
                                <td style='height:50px;'> 所办主任意见 </td>
                                <td colspan='6' >   </td>
                            </tr>
                            <tr >
                                <td style='height:50px;'> 律师意见 </td>
                                <td colspan='6' >   </td>
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
  
    function approve() {
        var applyname = $('.applyname').val();
        var oddnum = $('.oddnum').val();
        var department = $('.department option:selected').val();
        var department_name = $('.department option:selected').text();
        var dep_team = $('.dep_team option:selected').val();
        var dep_team_name = $('.dep_team option:selected').text();
        var sealtype = $('.sealtype:checked').val();
        var filetype = new Array();
        var filenum = $('.filenum').val()
        var declarename = $('.declarename').val();
        
        if (applyname == '') {
            $('.applyname').focus();
            return;
        }
        if (department == '') {
            $('.department').focus();
            return;
        }
        if (dep_team == '') {
            $('.dep_team').focus();
            return;
        }
        if (sealtype == '') {
            $('.sealtype').focus();
            return;
        }
        var i = 0; 
        $("input[name = 'filetype']:checkbox:checked").each(function(){filetype[i] = $(this).val();i++;});
        if (filetype == '') {
            alert('文件类型');
            $('.filetype').focus();
            return;
        }
        if (filenum == '') {
            $('.filenum').focus();
            return;
        }
        
        var data = {};
        data.applyname = applyname;
        data.oddnum = oddnum;
        data.department = department;
        data.department_name = department_name;
        data.dep_team = dep_team;
        data.dep_team_name = dep_team_name;
        data.sealtype = sealtype;
        data.filetype = filetype;
        data.filenum = filenum;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_seal',
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

