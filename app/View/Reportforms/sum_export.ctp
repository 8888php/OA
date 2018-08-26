<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Excel.Sheet>
                <meta name=Generator content="Microsoft Excel 11">
                    </head>
                    <body>
                        <table border=1 cellpadding=0 cellspacing=0 width="100%" >
                            <tr>
                                <td colspan="25" align="center">
                                    <h2><?php echo $xls_head['title']; ?></h2>
                                </td>
                            </tr>

                            <tr style='text-align: center;font-size: 14px;' height="50" class="blue">
                                <b>
                                    <td > 团队 </td>
                                    <td >汇总项</td>
                                    <?php 
                                    foreach($keyanlist as $lv){  
                                    echo  "<td width='120'>" . $lv . '</td>'; 
                                    }
                                    ?>
                                </b>
                            </tr> 
                            <?php  foreach($teamlist as $k => $v){ ?>  
                            <tr>
                                <td rowspan="4"> <br/><br/><br/> <?php echo $v;  ?></td>
                                <td width='100'>预算</td>
                                <?php  
                                foreach($keyanlist as $lk => $lv){  
                                $money = $proCountSum[$k][$lk] ? $proCountSum[$k][$lk] : 0 ;
                                echo '<td>'.$money .'</td>';
                                }
                                ?>
                            </tr>
                            <tr>
                                <td width='100'>支出合计</td>
                                <?php  
                                foreach($keyanlist as $lk => $lv){  
                                $money = $expendSum[$k][$lk] ? $expendSum[$k][$lk] : 0 ;
                                echo '<td>'.$money .'</td>';
                                }
                                ?>
                            </tr>
                            <tr>
                                <td width='100'>结余</td>
                                <?php  
                                foreach($keyanlist as $lk => $lv){ 
                                $money = $surplusSum[$k][$lk] ? $surplusSum[$k][$lk] : 0 ;
                                echo '<td>'.$money .'</td>';
                                }
                                ?>
                            </tr>
                            <tr>
                                <td width='100'>进度</td>
                                <?php  
                                foreach($keyanlist as $lk => $lv){ 
                                $money = $percentage[$k][$lk] ? $percentage[$k][$lk] : 0 ;
                                echo '<td>'.$money .' % </td>';
                                }
                                ?>
                            </tr>
                            <?php }?>
                        </table>
                    </body>
                    </html>

