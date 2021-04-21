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


                            <?php  foreach($selfTeamList as $tk => $tv){  ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td  rowspan="<?php echo count($applyList[$tk])+1; ?>" style="vertical-align:middle;font-size:12pt;font-weight:600;"> <?php echo $tv; ?> </td>
                                <td style="text-align:center;">  -- </td>
                                <td> <?php echo $sumArr[$tk]['amount']; ?> </td>
                                <td> <?php echo $sumArr[$tk]['pay']; ?> </td>
                                <td> <?php echo $sumArr[$tk]['amount'] - $sumArr[$tk]['pay']; ?> </td>
                            </tr>

                            <?php  foreach($applyList[$tk] as $k => $v){  ?>
                            <tr style="text-align:center;height:20.00pt;">
                                <td style="text-indent:2rem;text-align:left;"> <?php  echo $v; ?> </td>
                                <td> <?php echo $fromArr[$tk][$k]['amount']; ?> </td>
                                <td> <?php echo $fromArr[$tk][$k]['pay']; ?> </td>
                                <td> <?php echo $fromArr[$tk][$k]['amount'] - $fromArr[$tk][$k]['pay']; ?> </td>
                            </tr>
                            <?php } } ?>

                            <tr style="text-align:center;">
                                <td style="vertical-align:middle;font-size:12pt;font-weight:600;height:25.00pt;"> 总合计 </td>
                                <td style="text-align:center;">  -- </td>
                                <td> <?php echo $totalArr['amount']; ?> </td>
                                <td> <?php echo $totalArr['pay']; ?> </td>
                                <td> <?php echo $totalArr['amount'] - $totalArr['pay']; ?> </td>
                            </tr>

                        </table>
                    </body>
                    </html>

