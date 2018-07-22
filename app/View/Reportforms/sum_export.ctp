<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Excel.Sheet>
                <meta name=Generator content="Microsoft Excel 11">
                    </head>
                    <body>
                        <table border=1 cellpadding=0 cellspacing=0 width="100%" >
                            <tr>
                                <td colspan="5" align="center">
                                    <h2><?php echo $title; ?></h2>
                                </td>
                            </tr>
                            
                                <tr style='text-align: center;font-size: 14px;' height="30" class="blue">
                                    <b>
                                    <td >科目</td>
                                    <td > 预算 </td>
                                    <td > 支出 </td>
                                    <td > 结余 </td>
                                    <td> 进度 </td>
                                    </b>
                                </tr> 
                                <?php foreach($keyanlist['key'] as $key => $val){ ?>
                                <tr height="25" style='padding-left:10px;' >
                                   <td > <?php echo $keyanlist['val'][$key]; ?> </td>
                                   <td > <?php echo isset($proCountSum[$val]) ? $proCountSum[$val] : 0; ?> </td>
                                   <td > <?php echo isset($expendSum[$val]) ? $expendSum[$val] : 0; ?>  </td>
                                   <td > <?php echo isset($surplusSum[$val]) ? $surplusSum[$val] : 0; ?>  </td>
                                   <td > <?php echo isset($percentage[$val]) ? $percentage[$val] : 0; ?> % </td>
                                </tr>
                                <?php }  ?>
                            
                            
                            <?php if(false){  ?>
                            <tr>
                                <td colspan="<?php echo count($keyanlist['val'])+1;?>" align="center">
                                    <h2><?php echo $title; ?></h2>
                                </td>
                            </tr>
                            
                                <tr style='font-weight:600;' class="blue">
                                    <td width='100px'>科目</td>
                                        <?php 
                                            foreach($keyanlist['val'] as $lv){ 
                                                echo  "<td width='120'>" . $lv . '</td>'; 
                                            }
                                        ?>  
                                </tr>
                                <tr >
                                    <td> 预算 </td>
                                        <?php  
                                            foreach($keyanlist['key'] as $k) {
                                                echo  "<td style='background-color:#ADFEDC;'>";
                                                echo isset($proCountSum[$k]) ? $proCountSum[$k] : 0;
                                                echo '</td>';
                                            }
                                        ?>
                                </tr>

                                <tr >
                                    <td> 支出 </td>
                                        <?php  
                                            foreach($keyanlist['key'] as $k) {
                                                echo  "<td style='background-color:#fdf59a;'>";
                                                echo isset($expendSum[$k]) ? $expendSum[$k] : 0;
                                                echo '</td>';
                                            }
                                        ?>
                                </tr>

                                <tr >
                                    <td> 结余 </td>
                                        <?php  
                                            foreach($keyanlist['key'] as $k) {
                                                echo  "<td style='background-color:#fdf59a;'>";
                                                echo isset($surplusSum[$k]) ? $surplusSum[$k] : 0;
                                                echo '</td>';
                                            }
                                        ?>
                                </tr>

                                <tr >
                                    <td> 进度 </td>
                                        <?php  
                                            foreach($keyanlist['key'] as $k) {
                                                echo  "<td style='background-color:#ADFEDC;'>";
                                                echo isset($percentage[$k]) ? $percentage[$k] : 0;
                                                echo ' % ';
                                                echo '</td>';
                                            }
                                        ?>
                                </tr>
                            
                            <?php } ?>
                        </table>
                    </body>
                    </html>

