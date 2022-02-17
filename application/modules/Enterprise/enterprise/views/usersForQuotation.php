<?php if ($users==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong>View Client(s) Summary</strong></div>
        <div class="grayLine"></div>
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
                        <td height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="radio" value="<?php echo $key; ?>" name="selectedUserId" /></td>
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
        <input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">
                        <?php
                        if ($flag_listing_upgrade == '1') {
                        ?>
                        <input type="button" value="Upgrade Institute" onclick="$('frmSelectUser').action='/index.php/enterprise/ShowForms/upgradeInstituteForm';validateFormSums();">
                        <?php
                        }
			else if($flag_listing_upgrade == '2')
			{
			 ?>
                        <input type="button" value="Upgrade Course" onclick="$('frmSelectUser').action='/index.php/enterprise/ShowForms/upgradeCourseForm';validateFormSums();">
                        <?php
                        
			}
                        if(($forListingPost) && ($flag_listing_upgrade != '1') && ($flag_listing_upgrade!='2')){ ?>

                        <?php if(((empty($userData)) || ($usergroup == 'cms')) && ($validity_check == 'Y')) { ?>
			            <input type="button" onclick="$('frmSelectUser').action='/index.php/enterprise/Enterprise/setSponsored';validateFormSums();" value="Set Main Institute">
			            <input type="button" onclick="$('frmSelectUser').action='/index.php/enterprise/Enterprise/showMainInstituteForClient';validateFormSums();" value="Unset Main Institute"> 
                        <input type="button" value="Set Sponsored" onclick="$('frmSelectUser').action='/index.php/search/SearchEnterprise/setSponsoredListing';validateFormSums();">
                        <!--  
                        <input type="button" value="Post Institute" onclick="$('frmSelectUser').action='/index.php/enterprise/ShowForms/showInstituteForm';validateFormSums();">
                        -->
                        <input type="button" value="Post Course" onclick="$('frmSelectUser').action='/index.php/enterprise/ShowForms/chooseInstitute';validateFormSums();">
                        <input type="button" value="Post Institute" onclick="showInstituteTypeSelectionOverlay()">
                        <!--<input type="button" value="Post Course" onclick="showInstituteTypeSelectionOverlay()">-->
                        <?php } else { ?>                         
                        <input type="button" value="Send Mass-mails" onclick="$('frmSelectUser').action='/index.php/enterprise/Enterprise/massMailSubsChoose';validateFormSums();">
                        <?php } 
                        } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>

        
        <div class="w395" id="instituteSelectionForm" style="display:none">
			<div class="orblkBrd">
		        <!--<div class="otit">
		            <div class="float_L">Type of Institute</div>
		            <div class="float_R"><div class="cssSprite1 allShikCloseBtn">&nbsp;</div></div>
		            <div class="clear_B">&nbsp;</div>
		        </div> -->
		        <div class="pf10">
					<div style="width:100%">
						<div><strong>What type of institute do you want to add:</strong></div>
						<div><input type="radio" name="instituteType[]" value="1" id="academicInstitute" checked="true"> Academic institute</div>
						<div><input type="radio" name="instituteType[]" value="2" id="testprepInstitute"> Test preparatory institute</div>
						<div class="lineSpace_20">&nbsp;</div>							            
					</div>
		            <div align="center">
						<div class="float_L"><div style="margin-left:140px"><input type="button" class="entr_Allbtn ebtn_6" value="&nbsp;" onclick="return addInstituteByType()"> &nbsp; &nbsp; </div></div>
						<div class="float_L"><div style="padding-top:5px"><a class="Fnt16" href="javascript:void(0)" onclick="hideLoginOverlay()"><strong>Cancel</strong></a></div></div>
						<div class="clear_B">&nbsp;</div>
					</div>            
		            <div class="lineSpace_10">&nbsp;</div>
		        </div>
		    </div>
		</div>
