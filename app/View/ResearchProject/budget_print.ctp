<?php echo $this->element('head_frame'); ?>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:750px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tabbable">


            <div class="tab-content no-border ">


                <div id="faq-tab-1" class="tab-pane fade in active">
                    <table class="table table-striped " style='width:100%;float:left;margin-right: 10px;border:1px solid #ccc;font-size:11px;'>
                        <thead>
                            <tr>
                                <th colspan="4" class='blue' style="text-align:center;"> 项目费用 </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            foreach($costList as $ysk => $ysv){  ?>
                            <tr>
                                <?php foreach($ysv as $k => $v){ ?>
                                <td style="text-align:right;"><?php echo $v;  ?></td>
                                    <?php if(!empty($subject)){
                                        $money = '0.00';
                                        foreach($subject as $sk=>$sv){
                                            if ($k == $sk) {
                                                $money = $sv;
                                                break;
                                            }
                                        }
                                    ?>
                                    <td><?php echo $money;?></td>    
                                    <?php }else{?>
                                        <td>0.00</td>
                                    <?php }?>
                                <?php   } ?>
                            </tr>
                            <?php   } ?>
                        </tbody>
                    </table>


                    <div style="clear:both;"> </div>
                </div>

            </div>
        </div>
    </div><!-- /.row -->
</div>
<script type="text/javascript">
    window.onload=function(){
        window.print();
    }
</script>
<?php echo $this->element('foot_frame'); ?>