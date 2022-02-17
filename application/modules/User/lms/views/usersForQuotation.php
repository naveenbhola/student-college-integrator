<?php if ($users==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong class="mar_left_10p">Select Client</strong>
		 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp; 
			<span><a href="javascript:void(0);" onclick="showDiv('clientDetail')">Show</a></span> <span style="color:#000000">/</span> <span><a href="javascript:void(0);" onclick="hideDiv('clientDetail')">Hide</a></span>
		</div>
        <div class="grayLine"></div>
		<div id="clientDetail">
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong> </td>
                    <td width="17%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE / INSTITUTE / UNIVERSITY NAME</strong></td>
                    <td width="33%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>LOGIN EMAIL ID</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISPLAY NAME</strong></td>
                    <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT NAME</strong></td>
                    <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT NO</strong></td>
                </tr>
            </table>
        
            <div style="overflow:auto; height:115px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">                        
                    <tr>
                        <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
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
                        <td height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="radio" value="<?php echo $key; ?>" name="selectedUserId" onclick="getClientListing('<?php echo $key; ?>')"/></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $key; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['collegeName']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['email']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['displayname']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['contactName']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['contactNumber']; ?></td>
                    </tr>
	<?php $i++; 
        } ?>

                </table>
            </div>
        <input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">
                      <!--  <button class="btn-submit19" onclick="getLeadsForClient();return false;" type="button" value="" style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Get Leads</p></div>
                        </button>-->
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
		</div>
        <?php } ?>
		
