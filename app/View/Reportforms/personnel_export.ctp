<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
            <meta name=ProgId content=Excel.Sheet>
                <meta name=Generator content="Microsoft Excel 11">
                    </head>
                    <body>
                        <table border=1 cellpadding=0 cellspacing=0 width="100%" >
                            <tr>
                                <td colspan="<?php echo $colscount; ?>" align="center">
                                    <h2><?php echo $xls_head['title']; ?></h2>
                                </td>
                            </tr>
                            <tr style='font-size:12pt;font-weight:700;text-align:center;height:25.00pt;' class="blue">
                                <?php foreach($xls_head['cols'] as $v){  ?>
                                <td  width='130px' align="center"> <?php echo $v; ?> </td>
                                <?php } ?>
                            </tr>             
                            
                            <?php foreach($sheetList as $k => $v){  ?>
                            <tr style='height:23pt;'>
                                <?php 
                                    $wkey = 0;
                                    while( $wkey < $colscount ){ 
                                    echo '<td>'.$v[$wkey].'</td>';
                                    $wkey++;
                                }
                                ?>
                            </tr>
                            <?php } ?>

                        </table>
                    </body>
                    </html>

