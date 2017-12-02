<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Excel.Sheet>
                <meta name=Generator content="Microsoft Excel 11">
                    </head>
                    <body>
                        <table border=1 cellpadding=0 cellspacing=0 width="100%" >
                            <tr>
                                <td colspan="<?php echo count($xls_head['cols']);?>" align="center">
                                    <h2><?php echo $xls_head['title']; ?></h2>
                                </td>
                            </tr>
                            <tr style='font-size:12pt;font-weight:700;text-align:center;height:25.00pt;' class="blue">
                                <?php foreach($xls_head['cols'] as $v){  ?>
                                <td  align="center"> <?php echo $v; ?> </td>
                                <?php } ?>
                            </tr>
                            
                            <?php  foreach($deplist[1] as $k => $v){ ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td rowspan = "<?php echo isset($startAmount[$k]) ? count($startAmount[$k])+1 : 1;  ?>" style="text-align:left;vertical-align:middle;"> <?php  echo $v ; ?> </td>
                                <td> -- </td>
                                <td> <?php  echo $fromArr[$k]['amount'] ; ?> </td>
                                <td> <?php  echo $fromArr[$k]['pay'] ; ?> </td>
                                <td> <?php  echo sprintf('%.2f',$fromArr[$k]['amount'] - $fromArr[$k]['pay']); ?> </td>
                            </tr>                                           
                            <?php  
                            if(isset($startAmount[$k])){
                            foreach($startAmount[$k] as $kf => $vf){  ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td> <?php  echo $vf['file_number'] ; ?> </td>
                                <td> <?php  echo $vf['amount'] ; ?> </td>
                                <td> <?php  echo $vf['pay'] ; ?> </td>
                                <td> <?php  echo $vf['amount'] - $vf['pay'] ; ?> </td>
                            </tr>
                            <?php 
                            }
                            }
                            } 
                            ?>

                            <tr style="font-size:12pt;font-weight:700;text-align:center;height:25.00pt;">
                                <td style="vertical-align:middle;"> 总合计 </td>
                                <td> -- </td>
                                <td> <?php  echo sprintf('%.2f',$total['amount']) ; ?> </td>
                                <td> <?php  echo sprintf('%.2f',$total['pay']) ; ?> </td>
                                <td> <?php  echo sprintf('%.2f',$total['amount'] - $total['pay']) ; ?> </td>
                            </tr>  
                        </table>
                    </body>
                    </html>

