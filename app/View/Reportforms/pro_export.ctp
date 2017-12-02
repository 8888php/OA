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
                            <tr style='font-size:12pt;text-align:center;height:25.00pt;' class="blue">
                                <?php foreach($xls_head['cols'] as $v){  ?>
                                <td  align="center"><b> <?php echo $v; ?> </b></td>
                                <?php } ?>
                            </tr>

                            <tr style="text-align:center;height:20.00pt;">
                                <td  rowspan="<?php echo count($applyList[1])+1; ?>" style="vertical-align:middle;font-size:12pt;font-weight:600;"><b> 零余额 </b></td>
                                <td style="text-align:center;">  -- </td>
                                <td> <?php echo $fromArr['one']['amount']; ?> </td>
                                <td> <?php echo $fromArr['one']['pay']; ?> </td>
                                <td> <?php echo $fromArr['one']['amount'] - $fromArr['one']['pay']; ?> </td>
                            </tr>
                            <?php  foreach($applyList[1] as $k => $v){  ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td style="text-indent:2rem;text-align:left;"> <?php  echo $v; ?> </td>
                                <td> <?php echo $fromArr[1][$k]['amount']; ?> </td>
                                <td> <?php echo $fromArr[1][$k]['pay']; ?> </td>
                                <td> <?php echo $fromArr[1][$k]['amount'] - $fromArr[1][$k]['pay']; ?> </td>
                            </tr>
                            <?php } ?>

                            <tr style="text-align:center;height:20.00pt;">
                                <td   rowspan="<?php echo count($applyList[2])+1; ?>" style="vertical-align:middle;font-size:12pt;font-weight:600;"><b> 基本户 </b></td>
                                <td style="text-align:center;">  -- </td>
                                <td> <?php echo $fromArr['two']['amount']; ?> </td>
                                <td> <?php echo $fromArr['two']['pay']; ?> </td>
                                <td> <?php echo $fromArr['two']['amount'] - $fromArr['two']['pay']; ?> </td>
                            </tr>

                            <?php  foreach($applyList[2] as $k => $v){  ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td style="text-indent:2rem;text-align:left;"> <?php  echo $v; ?> </td>
                                <td> <?php echo $fromArr[2][$k]['amount']; ?> </td>
                                <td> <?php echo $fromArr[2][$k]['pay']; ?> </td>
                                <td> <?php echo $fromArr[2][$k]['amount'] - $fromArr[2][$k]['pay']; ?> </td>
                            </tr>
                            <?php } ?>

                            <tr style="text-align:center;">
                                <td style="vertical-align:middle;font-size:12pt;font-weight:600;height:25.00pt;"  ><b> 总合计 </b></td>
                                <td style="text-align:center;">  -- </td>
                                <td><b> <?php echo $fromArr['one']['amount'] + $fromArr['two']['amount']; ?> </b></td>
                                <td><b> <?php echo $fromArr['one']['pay'] + $fromArr['two']['pay']; ?> </b></td>
                                <td><b> <?php echo $fromArr['one']['amount'] + $fromArr['two']['amount'] - $fromArr['one']['pay'] - $fromArr['two']['pay']; ?> </b></td>
                            </tr>  

                        </table>
                    </body>
                    </html>

