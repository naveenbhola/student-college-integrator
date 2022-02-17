
        <div class="OrgangeFont fontSize_14p bld"><strong>View Client(s) Summary</strong></div>
        <div class="grayLine"></div>
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong> </td>
                    <td width="17%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE / INSTITUTE / UNIVERSITY NAME</strong></td>
                    <td width="33%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>LOGIN EMAIL ID</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISPLAY NAME</strong></td>
                    <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT NAME</strong></td>
                    <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT NO</strong></td>
                </tr>
            </table>
        
            <div style="overflow:auto; height:60px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">                        
                    <tr>
                        <td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="17%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="33%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="13%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    </tr>
        <?php $i=1; ?> 
        
        <?php foreach ($users as $key=>$val) { ?>
                    <tr>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $key; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['collegeName']; ?></td>
                        <?php if(strlen(trim($val['email'])) > 43){
                                $EMAIL = substr(trim($val['email']),0,40)."...";
                            }else{
                                $EMAIL = trim($val['email']);
                            }
                        ?>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $EMAIL; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['displayname']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['contactName']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['contactNumber']; ?></td>
                    </tr>
	<?php $i++; 
        } ?>

	
                </table>
            </div>        
	</div>
	
