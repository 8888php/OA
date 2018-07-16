<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Excel.Sheet>
                <meta name=Generator content="Microsoft Excel 11">
                    </head>
                    <body>
                        <table border=1 cellpadding=0 cellspacing=0 width="100%" >
                            <tr>
                                <td colspan="<?php echo count($xls_head['cols'])+1;?>" align="center">
                                    <h2><?php echo $xls_head['title']; ?></h2>
                                </td>
                            </tr>
                            <tr style='font-size:12pt;font-weight:700;height:22pt;' class="blue">
                                <?php foreach($xls_head['cols'] as $hv){ ?>
                                <td  align="center"> <?php echo $hv; ?> </td> 
                                <?php } ?>
                                <td width='120'>审批进度</td>
                            </tr>
                            <tr style='text-align:center;height:20pt;font-weight:700;'>
                                <td style='background-color:#ADFEDC;'> 预算 </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> <?php echo $pcost['total']; ?>  </td>
                                <?php 
                                foreach($keyanlist as $k) {
                                foreach($k as $kk=>$kv) {
                                echo "<td style='background-color:#ADFEDC;'>";
                                echo isset($pcost[$kk]) ? $pcost[$kk] : 0;
                                echo '</td>';
                                }
                                }
                                ?>
                                <td style='background-color:#ADFEDC;'> </td>
                            </tr>


                            <?php 
                            foreach($declares_arr as $d){  
                            $json_data = json_decode($d['m']['subject'],true);
                            ?>        
                            <tr style='text-align:center;height:20pt;'>
                                <td><?php echo $d['m']['ctime'];  ?></td>
                                <td><?php echo $d['u']['name']; ?> </td>
                                <td><?php echo '否';  ?></td>
                                <td> <?php echo $attr_arr[$d['m']['id']]['s']['source_channel'];  ?> </td>
                                <td> <?php echo $attr_arr[$d['m']['id']]['s']['file_number'];  ?> </td>
                                <td style='text-align:left;'> <?php echo $attr_arr[$d['m']['id']]['b']['description']; ?> </td>
                                <td> <?php echo $attr_arr[$d['m']['id']]['b']['amount'];  ?>  </td>
                                <?php 
                                foreach($keyanlist as $k) {
                                if($d['m']['table_name'] == 'apply_jiekuandan'){
                                foreach($k as $kk=>$kv) {
                                echo  '<td>';
                                echo isset($json_data[$kk]) ?  $attr_arr[$d['m']['id']]['b']['amount'] : 0;
                                echo '</td>';
                                }
                                }else{
                                foreach($k as $kk=>$kv) {
                                echo  '<td>';
                                echo isset($json_data[$kk]) ?  $json_data[$kk]: 0;
                                echo '</td>';
                                }
                                }
                                }
                                ?>
                                <td> <?php $code_bxd_arr = Configure::read('code_bxd_arr');echo $code_bxd_arr[$d['m']['code']];  ?> </td>
                            </tr>
                            <?php }?>

                            <tr style='text-align:center;height:20pt;font-weight:700;'>
                                <td style='background-color:#fdf59a;'> 支出合计 </td>
                                <td style='background-color:#fdf59a;'> </td>
                                <td style='background-color:#fdf59a;'> </td>
                                <td style='background-color:#fdf59a;'> </td>
                                <td style='background-color:#fdf59a;'> </td>
                                <td style='background-color:#fdf59a;'> </td>
                                <td style='background-color:#fdf59a;'> <?php echo array_sum($expent); ?>  </td>
                                <?php 
                                foreach($keyanlist as $k) {
                                foreach($k as $kk=>$kv) {
                                echo  "<td style='background-color:#fdf59a;'>";
                                echo isset($expent[$kk]) ? $expent[$kk] : 0;
                                echo '</td>';
                                }
                                }
                                ?>
                                <td style='background-color:#fdf59a;'> </td>
                            </tr>

                            <tr style='text-align:center;height:20pt;font-weight:700;'>
                                <td style='background-color:#ADFEDC;'> 结余 </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> </td>
                                <td style='background-color:#ADFEDC;'> <?php echo $pcost['total'] - array_sum($expent); ?>  </td>
                                <?php 
                                foreach($keyanlist as $k) {
                                foreach($k as $kk=>$kv) {
                                echo  "<td style='background-color:#ADFEDC;'>";
                                if(isset($expent[$kk])){
                                echo ($pcost[$kk] >= $expent[$kk]) ? ($pcost[$kk] - $expent[$kk]) : '<a style="color:red;">'.($pcost[$kk] - $expent[$kk]).'</a>'; 
                                }else{
                                echo isset($pcost[$kk]) ? $pcost[$kk] : 0;
                                }
                                echo '</td>';
                                }
                                }
                                ?>
                                <td style='background-color:#ADFEDC;'> </td>
                            </tr>
                        </table>
                    </body>
                    </html>

