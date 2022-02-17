<?php if (count($List_Detail) <= 0 ) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { 
        $sumsDataArr = json_decode(base64_decode($sumsData),true);
?>

    <div class="OrgangeFont fontSize_14p bld"><strong>List Details(<?php echo  $numUsers. " Users Found"; ?>)</strong>&nbsp; 
    
    <?php if($sumsDataArr['BaseProdRemainingQuantity'] != ''){ ?> 
    <font color="red">Number Left in Subscription = <?php echo $sumsDataArr['BaseProdRemainingQuantity']?></font>
    <?php } ?>
    </div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>User Name</strong> </td>
                    <td width="50%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>User Profession</strong> </td>

                </tr>
            </table>

            <div style="overflow:auto; height:115px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                        <td width="48%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="48%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>

                    </tr>

        <?php //print_r($List_Detail);
        if (count(json_decode($List_Detail[0]['usersArr'])) > 0) {
        	foreach (json_decode($List_Detail[0]['usersArr']) as $val) {
        ?>
                    <tr>

                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val->displayname; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val->profession; ?></td>

                    </tr>
	<?php
		}
	}
        ?>
                </table>
            </div>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">

                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>
