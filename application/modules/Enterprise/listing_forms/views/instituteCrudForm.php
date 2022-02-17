<script>
	function performActionForResponse(responseText) {
        responseText = eval("("+responseText +")");
		if(typeof responseText.Success != 'undefined' && responseText.Success != '') {
			window.location = responseText.Success;
		}else {
			document.getElementById('save_add_course').disabled = false;
			try{document.getElementById('save_preview').disabled = false;} catch(e) {}
			var errorContainer = 'common_error';
                        var set_focus = true;
			for(var failIssue in responseText.Fail) {
				switch(failIssue) {
					case 'logo' :
						errorContainer = 'c_institute_logo_error';
						break;
					case 'panel' :
						errorContainer = 'i_feat_panel_error';
						break;
					case 'photo' :
						errorContainer = 'photo_error';
						break;
                                        case "i_brochure_panel" :
						errorContainer = 'i_brochure_panel_error';	
						break;

                                        case "location_issue" :
						errorContainer = 'pin_code_error';
                                                set_focus = false;
                                                $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                $('correct_above_error').style.display = 'inline';
						break;

                                        case "subscription_issue" :
						errorContainer = 'subs_unselect_error';
                                                set_focus = false;
                                                $('correct_above_error').innerHTML  = "Please scroll up and correct the fields marked in Red!";
                                                $('correct_above_error').style.display = 'inline';						
						removeExpiredSubscription(responseText.subscription_id);						
						break;
						
				}
				document.getElementById(errorContainer).innerHTML = responseText.Fail[failIssue];
				document.getElementById(errorContainer).style.display = '';
				document.getElementById(errorContainer).parentNode.style.display = '';
			}
			try{
				if(set_focus) {
                                    document.getElementById(errorContainer.replace('_error','')).focus();
                                }
				document.getElementById(errorContainer).style.display = '';
				document.getElementById(errorContainer).parentNode.style.display = '';
				$('save_preview').disabled=false;
			} catch(e) {}
		}
	}
var otherLocalityShown = false;
var zoneList = <?php echo json_encode($city_list);?>;
var localityList = <?php echo json_encode($locality_list);?>
</script>
<div style="display:none" align="center">
	<div id="common_error" class="errorMsg bld fontSize_14p" style="display:none" align="center"></div>
	<div class="lineSpace_10">&nbsp;</div>
</div>
<form action="<?php echo $formPostUrl; ?>" name="instituteListingData" id="instituteListing" method="post" enctype="multipart/form-data" style="margin:0;padding:0">

	<input type="hidden" name="insituteType" value="<?php echo $instituteType;?>"/>
	<input type="hidden" name="onBehalfOf" value="<?php echo $onBehalfOf; ?>"/>
	<input type="hidden" name="clientId" value="<?php echo $clientId; ?>"/>
	<input type="hidden" id="h_flow_name" name="flow" value="<?php echo $flow; ?>"/>
	<input type="hidden" id="nextAction" name="nextAction" value=""/>
	<input type="hidden" id="h_institute_id" name="instituteId" value="<?php echo $institute_id; ?>"/>
	<input type="hidden" id="instituteSubmitDate" name="instituteSubmitDate" value="<?php echo $instituteSubmitDate; ?>"/>
	<input type="hidden" name="instituteViewCount" id="instituteViewCount" value="<?=$viewCount?>"/>
	<input type="hidden" name="no_Of_Past_Paid_Views" id="no_Of_Past_Paid_Views" value="<?=$no_Of_Past_Paid_Views?>"/>
	<input type="hidden" name="no_Of_Past_Free_Views" id="no_Of_Past_Free_Views" value="<?=$no_Of_Past_Free_Views?>"/>
	<div style="margin:0 10px">
        <?php if ($onBehalfOf =='true'){
            if ($clientId != '') { ?>
            <div class="spacer10 clearFix"></div>
            This institute is being <?php echo ($flow=='add') ? 'posted':'edited'; ?> for Client named: <b><?php echo $clientDetails['displayname']; ?></b> with email id: <b><?php echo $clientDetails['email']; ?> </b>
<div class="spacer10 clearFix"></div>
        <?php }
        }
        ?>
        <?php if((($usergroup != "cms" || $onBehalfOf=="true") && $flow=='add')|| $flow =='upgrade'){
                $this->load->view('listing_forms/packsInPage');
            }?>
        <div class="lineSpace_10">&nbsp;</div>
               <div>
               <div style="float:left; width:650px;">
		<div class="row">
			<span style="float:right;padding-top:3px">All field marked with <span class="redcolor fontSize_13p">*</span> are compulsory to fill in</span>
			<span class="formHeader"><a class="formHeader" name="main" style="text-decoration:none" >Institute / University Details</a></span>
			<div class="line_1"></div>
		</div>
		<div style="line-height:16px">&nbsp;</div>
		<div class="row">
			<div class="row1" style="width:255px; padding-right:5px;"><b>Institute / University Name<span class="redcolor fontSize_13p">*</span><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'c_institute_name_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
                       <div id="c_institute_name_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('c_institute_name_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Should not be less than 3 characters.</li>
					<li>Abbreviations (if any) can be suffixed to Institute name.</li>
					<li>Example: Indian Institute of Technology, Kanpur (IIT, Kanpur).</li>
					</ul>
			</div>
			<div class="row2" >
				<input type="text" profanity="true" blurMethod="check_Institute_name(this);" name="c_institute_name" id="c_institute_name" validate="validateStr" required="true" maxlength="100" minlength="3" style="width:350px" tip="college_name" caption="University/Institute Name" value="<?php echo $insttitle; ?>" />
				<div style="display:none"><div class="errorMsg" id="c_institute_name_error"></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>
        <div class="row">
			<div class="row1" style="width:255px; padding-right:5px;"><b>Also known as<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'c_abbreviation_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
                          <div id="c_abbreviation_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('c_abbreviation_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Should contain Abbreviations and/or if any other renowned addressable of the institute.</li>
					<li>Should not be greater than 20 characters.</li>
					<li>If there are more than one abbreviations available, separate it by comma (,) or a semicolon (;).</li>
					<li>Example:IIM, Lucknow.</li>
					</ul>
			</div>
			<div class="row2" >
				<input type="text" profanity="true" name="c_abbreviation" id="c_abbreviation" validate="validateStr" maxlength="20" style="width:350px" tip="abbreviation" caption="Also know as" value="<?php echo $abbreviation; ?>" />
				<div style="display:none"><div class="errorMsg" id="c_abbreviation_error"></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>
<!--
        <div class="row">
            <div class="row1"><b>Affiliation Status:</b></div>
            <?php
                $deemedFlag;
                $affiliatedFlag;
                $affiliatedText;
                $affiliatedFlag_style = 'style="display:none"';
                if($certification !=''){
                    if(!strcasecmp($certification,"Deemed University")){
                        $deemedFlag = 'checked="checked"';
                    }else{
                        $affiliatedFlag = 'checked="checked"';
                        $affiliatedText = $certification;
                        $affiliatedFlag_style = '';
                    }
                }
                //$deemedFlag = 'checked="checked"';
            ?>
			<div class="row2">
                    <input tip="affiliated_to" leftPosition="330px" type="radio" onClick="Affilation_Status('deemed');" id ="deemedUniversity" value="deemedUniversity" name="deemedUniversity" <?php echo $deemedFlag; ?> /> Deemed University
                    <input name="deemedUniversity" id="affiliated_to_check" tip="affiliated_to" leftPosition="350" value="affiliated_to_check" onClick="Affilation_Status('affiliated_to');"  type="radio" <?php echo $affiliatedFlag; ?> /> Affiliated to
                    <input profanity="true" type="text" blurMethod="affiliated_to_blur();" name="affiliated_to"  <?=$affiliatedFlag_style?> id="affiliated_to" class="inputBorder" style="width:180px" tip="affiliated_to" value="<?php echo $affiliatedText; ?>" />&nbsp;
                    <a title="Click here to clear affiliation status." href="javascript:void(0);" id="clear_selection"  onClick="clear_selection();"> Clear selection</a>
                    <input type="hidden" name="deemedUniversity_clear_selection" value="" id="deemedUniversity_clear_selection">
					<div style="display:none"><div class="errorMsg" id="affiliated_to_error"></div></div>
			</div>
		</div>
        <div style="line-height:9px;clear:both">&nbsp;</div>
-->
		<div class="row">
			<div class="row1" style="width:255px; padding-right:5px;"><b>Year of establishment<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'i_establish_year_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
                        <div id="i_establish_year_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('i_establish_year_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Year in which institute has been established.</li>
					<li>Example:2005.</li>				
					</ul>
			</div>
			<div class="row2" >
				<input profanity="true" blurMethod="check_year_establishment(this);" type="text" name="i_establish_year" id="i_establish_year" style="width:80px" minlength="4" maxlength="4" tip="college_year" caption="Year Of Establishment" value="<?php echo $establish_year; ?>" />
				<div style="display:none"><div class="errorMsg" id="i_establish_year_error" ></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>

		<div class="row">
			<div class="row1" style="width:255px; padding-right:5px;"><b>AIMA Rating<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'i_aima_rating_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
                       <div id="i_aima_rating_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('i_aima_rating_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Basically for Management institutes, but if available need to select from the dropdown.</li>									
					</ul>
			</div>
			<div class="row2" >
				<select name="i_aima_rating" id="i_aima_rating" tip="aima_rating">
					<option value="0">Select</option>
					<?php foreach($aimaRatings AS $aimaRating):?>
					<option value="<?php echo $aimaRating;?>" <?php if($aimaRating == $aima_rating)echo "selected='selected'"?>><?php echo $aimaRating;?></option>
					<?php endforeach?>
				</select>
				<div style="display:none"><div class="errorMsg" id="i_aima_rating_error" ></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>
		
		
		
		
		
		<div class="row">
			<div class="row1" style="width:255px; padding-right:5px;"><b>USP<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'i_usp_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</b></div>
                        <div id="i_usp_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('i_usp_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Enter any unique selling point of the institute if available.</li>	
					<li>Can find it in the punch line of institute’s advertisements.</li>
					<li>Get published only for/in category page.</li>
					<li>Example:For IIPM – “Dare to think beyond IIMs” or “Largest B school on earth and Globally Best 
					Networked”.</li>
					</ul>
			</div>
			<div class="row2" >
				<input type="text" profanity="true" name="i_usp" id="i_usp" validate="validateStr" maxlength="100" style="width:350px" tip="usp" caption="USP" value="<?php echo $usp; ?>" />
				<div style="display:none"><div class="errorMsg" id="i_usp_error"></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>

		<div class="row">
			<div style="float:left;width:255px;padding-right:5px;line-height:20px"><div class="txt_align_r bld"><a id="collLogo" href="javascript:void(0);" onClick="return  replaceInnerHTML(this,'hideInstitueLogo','Logo of the Institute');">+ Logo of the Institute</a><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'collLogo_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div></div>
                        <div id="collLogo_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('collLogo_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Get the photograph resized by the designated team and upload it.</li>	
					<li>Send it by the name of logo.</li>
					<li>Maximum size allowed 340px *65px; Minimum size allowed 60px*40px.</li>
					</ul>
			</div>
			<div style="float:left;width:350px">
				<div style="display:none;" id="hideInstitueLogo">
					<?php $logo_style='';
						if($institute_logo !=''){
							$logo_style = 'display:none;';
					?>
						<div><img id="insti_logo_fetched" width="119" height="78" border="0" src="<?php echo $institute_logo; ?>"/></div>
						<div id="logo_anchor"><a onclick="removeInstiLogo('<?php echo $institute_id; ?>');" href="javascript:void(0);" >Remove</a></div>
					<?php } ?>
					<div style="position:relative">
						<div><input type="file" name="i_insti_logo[]" tip="institute_upload_logo"  leftPosition="125" id="c_institute_logo" value="" size="14" style="<?php echo $logo_style; ?>" /></div>
						 <!-- <div id="logoInfoBar" style="position:absolute;top:5px;left:5px;z-index:10;width:110px;<?php echo $logo_style; ?>">&nbsp;</div>-->
					</div>
                    <div style="<?php echo $logo_style; ?>font-size:10px;">
                        maximum size allowed <b>340x65</b>&nbsp;minimum size allowed <b>60x40</b>
                    </div>
				</div>
				<div><div class="errorMsg" style="display:none" id="c_institute_logo_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>

		<div class="spacer10 clearFix"></div>

                <input type="hidden" id="logo_removed" name="logoRemoved"  value="0"/>
		<?php
			if($institute_logo !=''){ ?>
			<script>
				replaceInnerHTML($('collLogo'),'hideInstitueLogo','Logo of the Institute');
			</script>
			<?php }
		?>

		<div class="row">
			<div style="float:left;width:255px;padding-right:5px;line-height:20px"><div class="txt_align_r bld"><a id="collFeatPanel" href="javascript:void(0);" onClick="return  replaceInnerHTML(this,'FeaturedPanel','Featured Panel');" >+ Featured Panel</a><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'collFeatPanel_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div></div>
                       <div id="collFeatPanel_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('collFeatPanel_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Get the photograph resized by the designated team and upload it.</li>	
					<li>Send it by the name of Featured Logo.</li>
					<li>Tags: Not for our use. Need not to update it.</li>
					<li>Basically available only for paid clients.</li>
					</ul>
			</div> 
			<div style="float:left;width:350px">
				<div style="display:none;" id="FeaturedPanel">
					<?php
						$featured_style='';
						if($featured_panel != ''){
						$featured_style = 'display:none;';
					?>
						<div><img id="insti_panel_fetched" width="119" height="78" border="0" src="<?php echo $featured_panel; ?>" /></div>
						<div id="panel_anchor"><a onclick="removeFeaturedPanelLogo('<?php echo $institute_id; ?>');" href="javascript:void(0);" >Remove</a></div>
					<?php } ?>
					<div style="position:relative">
						<div><input type="file" name="i_feat_panel[]" id="i_feat_panel" tip="institute_featured_panel"  leftPosition="75" value="" size="14" style="<?php echo $featured_style; ?>" /></div>
						<!-- <div id="featuredPanelInfoBar" style="position:absolute;top:5px;left:5px;z-index:10;width:110px;<?php echo $featured_style; ?>">&nbsp;</div> -->
					</div>
				</div>
				<div><div class="errorMsg" style="display:none" id="i_feat_panel_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>

		<div class="spacer10 clearFix"></div>

		<input type="hidden" id="panel_removed" name="panelRemoved" value="0"/>
		<?php
			if($featured_panel !=''){?>
			<script>
				replaceInnerHTML($('collFeatPanel'),'FeaturedPanel','Featured Panel');
			</script>
			<?php }
		?>

		<?php if($usergroup == "cms" ){ ?>
		<div class="row">
			<div style="float:left;width:265px;padding-right:10px;line-height:20px"><div class="txt_align_r bld"><a id="collTags" href="javascript:void(0);" onClick="return  replaceInnerHTML(this,'AddTags','Tags');">+Tags</a></div></div>
			<div style="float:left;width:350px">
				<div style="display:none;" id="AddTags">
					<div><textarea style="width:180px;height:80px" name="i_tags" id="i_tags" validate="validateStr" maxlength="100" tip="listing_add_tag" caption="Institute Tags"><?php echo $hiddenTags; ?></textarea></div>
					<div style="display:none"><div class="errorMsg" id="i_tags_error"></div></div>
					<?php
						if($hiddenTags!= ''){ ?>
						 <script>
							replaceInnerHTML($('collTags'),'AddTags','Tags');
						 </script>
					<?php } ?>
				</div>
				<div><div class="errorMsg" style="display:none" id="i_feat_panel_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>
		<?php } ?>
      
		<div class="spacer10 clearFix"></div>
		<?php if(!empty($institute_request_brochure_link)):?>
		<div class="row" style="position:relative;left:279px;bottom:5px;"> 
			<a onclick="removeEbrochure(this);" href="javascript:void(0);" >[Remove brochure and admission year]</a>
			<input type="hidden" value="" id="request_brochure_link_delete" name="request_brochure_link_delete"/>
	 	</div>
		<?php endif;?>
                <div class="row" id="BrochurePanel_row">
			<div style="float:left;width:255px;padding-right:10px;line-height:20px"><div class="txt_align_r bld"><span id="collBrochurePanel">Upload Brochure (PDF/Jpeg/MS word file): </span><br/><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'institute_request_brochure_link_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div></div>
                        <div id="institute_request_brochure_link_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('institute_request_brochure_link_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Very important from all perspective.</li>	
					<li>Upload a brochure if available.</li>
					<li>If not available try all your resources like contacting institute or web to get the same.</li>
					</ul>
			</div> 
			<div style="float:left;width:350px">
				<div id="BrochurePanel">
				       <?php if(!empty($institute_request_brochure_link)):?>
						<div><a href="<?php echo $institute_request_brochure_link;?>" target="blank">View uploaded file</a></div>
						<?php endif;?>
					<div style="position:relative">
						<div><input type="file" name="i_brochure_panel[]" id="i_brochure_panel" leftPosition="75" value="" size="14" /></div>
					</div>
				</div>
				<div><div class="errorMsg" style="display:none" id="i_brochure_panel_error"></div></div>
				<div style="padding-top: 10px;">
						<select name="c_brochure_panel_year" id="c_brochure_panel_year">
							<option value="0">Select admission year</option>
							<?php 
							$current_year = date("Y");
							$prev_year = $current_year-1;
							$next_year = $current_year+1;							
							?>
							<option value="<?php echo $prev_year;?>" <?php if($prev_year == $institute_request_brochure_year): ?> selected <?php endif;?>>Previous year</option>
							<option value="<?php echo $current_year;?>" <?php if($current_year == $institute_request_brochure_year): ?> selected <?php endif;?>>Current year</option>
							<option value="<?php echo $next_year;?>" <?php if($next_year == $institute_request_brochure_year): ?> selected <?php endif;?>>Next year</option>
						</select>
						</div>
						<div><div class="errorMsg" style="display:none" id="c_brochure_panel_year_error">Please select year of admission to upload brochure.</div></div>
			</div>
			<div class="clearFix"></div>
		</div>
                </div>
                <?php if(count($score_array) >0):?>
                <div class="completion-box" style="float:right;">
		<div class="completion-bar">
    		<span style="width:<?php echo $score_array['percentage_completion'];?>%"></span>
    		</div>
    		<strong class="completion-title">Institute Details are <?php echo $score_array['percentage_completion'];?>% complete</strong>
                <?php if($score_array['percentage_completion'] != 100): ?>
    		<p>Top 5 fields to increase % completeness:</p>
    		<ul style="list-style-type: disc;">
                        <?php foreach($score_array['fields_list'] as $key=>$value): 
                        $split_array= explode('%**===################&&&&&&@@@@@',$key);
						$label = $value['label'];	
                        if(is_array($split_array) && count($split_array)>0 && !empty($split_array[1])) {
						$label = $label." - "."<strong>".$split_array[1]."</strong>";
                        }
                        ?>
    			<li style="list-style-type: disc;"><p><?php echo $label;?></p></li>
                        <?php endforeach;?>
    		</ul>
                <?php endif;?>
		</div>
               <?php endif;?>
                </div>
                <div class="spacer10 clearFix"></div>
		<?php $this->load->view('listing_forms/instituteLocationContactForm');?>
                
<div class="spacer20 clearFix"></div>

	<div class="formHeader" style="margin:0 10px"><a class="formHeader" name="wikicontent" style="text-decoration:none">Why should students join this institute</a></div>
    <div class="spacer5 clearFix"></div>
	<div class="line_1"></div>
	<div class="spacer10 clearFix"></div>

	<div class="row">
		<div style="float:left;width:200px;line-height:20px; padding-right:5px"><div class="txt_align_r bld">Upload Photo <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'upload_join_photo_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</div></div>
                <div id="upload_join_photo_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('upload_join_photo_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>It is a mandate</li>	
					<li>Get the photograph resized by the designated team and upload it.</li>
					<li>Send it by the name of Why Join.</li>
					<li>Maximum size allowed 252px *103px.</li>					
					</ul>
			</div> 
		<div style="float:left;width:600px;">
		<?php if($joinreason[0]['photoUrl'] !=''){ ?>
			<div><img id="photo" width="252" height="103" border="0" src="<?php echo $joinreason[0]['photoUrl']; ?>"/></div>
			<!-- <div id="logo_anchor"><a onclick="removeInstiLogo('<?php echo $institute_id; ?>');" href="javascript:void(0);" >Remove</a></div> -->
                        <input type="hidden" name="is_photo_exist" id="is_photo_exist" value="1" >
		<?php } else { ?>
                    <input type="hidden" name="is_photo_exist" id="is_photo_exist" value="0" >
               <?php  } ?>
			<div style="height:35px">
				<input type="hidden" name="photo_title" maxlength="100" id="photo_title"/>
				<input type="file" name="photo[]" id="photo"/>&nbsp;&nbsp;
				size allowed <b>252x103</b>&nbsp;
				<!-- <input type="submit" name="uploadPhoto" value="Upload"/> -->
			</div><div style="display:none; margin-bottom:10px;">
			<div class="errorMsg" id="photo_error"></div>
	</div>
		</div>
	</div>
	
    <div class="spacer10 clearFix"></div>
	<div class="row">
		<div style="float:left;width:200px;line-height:20px; padding-right:5px"><div class="txt_align_r bld">Enter Details <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'upload_join_details_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span>:</div></div>
                <div id="upload_join_details_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('upload_join_details_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>Formatting should be like 1 line of header and then highlights should be in bullet points.</li>	
					<li>It is a unique selling point and the most viewed and important field, hence should be relevant.</li>
					<li>Example:Points here can but not necessarily consist of: Few renowned associations, global rankings, accomplishments regarding placements, travelling abroad during summer trainings etc.
					<ul>
					<li>Honorable President of India, Smt. Pratibha Patil is the visitor of Central University of Jharkhand - Irrelevant.</li>
                                        <li>CUJ offers 5 year integrated courses in diverse range of discipline - Irrelevant</li>
                                        <li>Top notch faculty members with immense experience in their respective field of expertise - Relevant</li>
                                        <li>Tie – ups with conglomerates like Reliance, Infosys for corporate trainings – Relevant</li>
					</ul>
					</li>
					</ul>
			</div> 
		<div style="float:left;width:160px">
			<div><textarea tip="enter_details_field" validate="validateStr" maxlength="1500" id="details" minlength="0" name="details" class='mceEditor' style="width:500px;height:100px"><?php echo $joinreason[0]['details']; ?></textarea></div>
			<div style="display:none"><div class="errorMsg" id="details_error"></div></div>
			<!-- <textarea name="details" cols="60" rows="4" tip="enter_details_field"></textarea>  -->
		</div>
	</div>

	<div class="spacer20 clearFix"></div>
	
	<div class="formHeader" style="margin:0 10px"><a class="formHeader" name="wikicontent" style="text-decoration:none">Additional Institute/ University Details</a></div>
	<div class="spacer5 clearFix"></div>
    <div class="line_1"></div>
	<div class="spacer10 clearFix"></div>
		<?php $this->load->view('listing_forms/WikkiContentDetails',array('type_of_check'=>'institute')); ?>
        <div class="spacer20 clearFix"></div>
	<div style="margin-left:100px">
            <div><b>- Create a custom section</b><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'custom_section_details_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div>
                <div id="custom_section_details_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('custom_section_details_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>If anything extra from the sections above is required, then we can create a “Custom Session”.</li>	
					<li>Point to Remember –<br/> 
					<p style="text-decoration:underline;">Ordering Rule of Wikis : “Institute Description” will come first and then the “Custom Wikis” if any, otherwise they will appear in the order they are listed on the server page.</p>
					</li>					
					</ul>
			</div>
		<div class="spacer5 clearFix"></div>
		<div>
            <div id="wikki_parant_CYO">
                <div id="main_container_0">
                    <div><input profanity="true" type="text" caption="Title" onFocus="clearText('wikkicontent_main_0')" validate="validateStr" minlength="10" maxlength="100"  name="wikkicontent_main[]" id="wikkicontent_main_0" style="width:450px" value="<?php echo (($userFieldsArr[0]['caption'] == '')?'Enter Title':$userFieldsArr[0]['caption']); ?>"/><span><a onclick="removewikkicontent(0);" href="javascript:void(0);" style="font-size:12px;margin-left:10px;" > Remove</a></span></div>
                    <div style="display:none"><div class="errorMsg" id="wikkicontent_main_0_error"></div></div>
                    <div class="spacer10 clearFix"></div>
                    <div><textarea profanity="true" class='mceEditor' caption="Description"	 validate="validateStr" maxlength="10000" minlength="0" name="wikkicontent_detail[]" id="wikkicontent_detail_0" style="width:500px;height:100px;"  class="w62_per" ><?php echo (($userFieldsArr[0]['attributeValue'] == '')?'Enter Description':$userFieldsArr[0]['attributeValue']); ?></textarea></div>
                    <div style="display:none"><div class="errorMsg" id="wikkicontent_detail_0_error" ></div></div>
                </div>
            </div>
			<div id="add_multiple_wikki_content"></div>
			<?php
				if(count($userFieldsArr)>1){
					foreach($userFieldsArr as $key => $val){
						if($key != 0){
				?>
				<script>
					addwikkicontent(false);
				</script>
				<?php
				}
                            }
                        }
                    ?>
			<div class="spacer5 clearFix"></div>
            <div><b><a id="addwikkicontent_flag" onclick="addwikkicontent(true);" href="javascript:void(0);" >+ Add more</a></b></div>
		</div>
	</div>	

	<div class="spacer20 clearFix"></div>
	
	<div class="formHeader" style="margin:0 10px">
		<a class="formHeader" name="wikicontent" style="text-decoration:none">Add college features/facilities</a>
		<span>[ <a href="javascript:void(0);" onclick="resetFacilities()">Reset All</a> ]</span>
	</div>
	<div class="spacer5 clearFix"></div>
    <div class="line_1"></div>
	<div class="spacer10 clearFix"></div>
	<div class="row">
       <div class="row-ul">
         <ul>
         	<?php 
         	if(isset($facilitiesfields)){
         		foreach($facilitiesfields as $id => $attrtitle){
         			$checked='';
         			if(array_key_exists($id, $facilitiesfieldvalues)){
         				$checked = 'checked=""';
         			}
         			?>
         			<li>
         				<input type='checkbox' class='c_facility' id='c_facility_input_<?= $id ?>' onClick='togglefacilitydiv(<?php echo $id; ?>)' <?= $checked?>> <label><p><?= $attrtitle ?></p></label>
         				<?php 
         				if(array_key_exists($id, $facilitiesfieldvalues)){
         					echo "<div id='c_facility_div_{$id}'>";
         					echo "<textarea maxlength='200' class='mceNoEditor' id='c_facility_value_{$id}' name='c_facility_value_{$id}'>";
         					echo $facilitiesfieldvalues[$id].'</textarea>';
         				}
         				else{
         					echo "<div id='c_facility_div_{$id}' style='display:none'>";
         					echo "<textarea disabled maxlength='200' class='mceNoEditor' id='c_facility_value_{$id}' name='c_facility_value_{$id}'></textarea>";
         				}
         				?>
         				<small style="margin-left: 25px;">(Maximum of 200 characters allowed)</small>
         				</div>
         				</li>
         				<?php 
         		}
         		echo "<input type='hidden' id='nooffacilities' value='".count($facilitiesfields)."'>";
         	}
         	?>
         </ul>
       </div>
       
	</div>
	

<div class="spacer20 clearFix"></div>
	<?php if($usergroup=='cms'):?>
	<div class="formHeader"><a class="formHeader" name="wikicontent" style="text-decoration:none">SEO Specifications</a><span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'seo_section_details_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></div>
   <div id="seo_section_details_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('seo_section_details_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>
					<li>We won’t have to touch this section. Once a listing is created, we will send it to SEO team and they will finalize it via this section.</li>									
					</ul>
	</div>
    <div class="spacer5 clearFix"></div>
	<div class="line_1"></div>
	<div class="spacer10 clearFix"></div>
	<div class="row">
			<div style="float:left;width:200px;line-height:18px; padding-right:5px"><div class="txt_align_r bld">SEO listing url:</div></div>
			<div style="float:left;width:405px"><div>
				<textarea <?php if($flow=='edit' || $flow == 'upgrade'){ echo "style='background-color: #FFF;'";echo "disabled='true'";}?> caption="seo url" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_url" name="listing_seo_url<?php if($flow=='edit'  || $flow=='upgrade'){echo "_disabled";}?>"><?php echo $seoListingUrl; ?></textarea>
					<?php if($flow=='edit'  || $flow == 'upgrade'):?><input type="hidden" name="listing_seo_url" value="<?php echo $seoListingUrl; ?>"/><?php endif?>
			</div>
			<div style="display:none"><div class="errorMsg" id="listing_seo_url_error"></div></div>
			</div>
			<div class="clearFix"></div>
		</div>
	<div class="spacer10 clearFix"></div>
	<div class="row">
		<div style="float:left;width:200px;line-height:18px; padding-right:5px"><div class="txt_align_r bld">SEO listing title:</div></div>
		<div style="float:left;width:405px"><div>
			<textarea caption="seo title" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_title" name="listing_seo_title"><?php echo $seoListingTitle; ?></textarea>
		</div>
		<div style="display:none"><div class="errorMsg" id="listing_seo_title_error"></div></div>
		</div>
		<div class="clearFix"></div>
	</div>
	<div class="spacer10 clearFix"></div>
	<div class="row">
		<div style="float:left;width:200px;line-height:18px; padding-right:5px"><div class="txt_align_r bld">SEO listing description:</div></div>
		<div style="float:left;width:405px"><div>
			<textarea caption="seo description" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_description" name="listing_seo_description"><?php echo $listingSeoDescription; ?></textarea>
		</div>
		<div style="display:none"><div class="errorMsg" id="listing_seo_description_error"></div></div>
		</div>
		<div class="clearFix"></div>
	</div>
<div class="spacer10 clearFix"></div>
	<div class="row">
		<div style="float:left;width:200px;line-height:18px; padding-right:5px"><div class="txt_align_r bld">SEO listing keywords:</div></div>
		<div style="float:left;width:405px"><div>
			<textarea caption="seo keywords" maxlength="500" minlength="0" class="mceNoEditor" id="listing_seo_keywords" name="listing_seo_keywords"><?php echo $listingSeoKeywords; ?></textarea>
		</div>
		<div style="display:none"><div class="errorMsg" id="listing_seo_keywords_error"></div></div>
		</div>
		<div class="clearFix"></div>
	</div>

<div class="spacer10 clearFix"></div>
		<?php endif?>
		
		<div id="correct_above_error" style="display:none;color:red;"></div><br/>
		<div class="line_1"></div>
		<div class="spacer10 clearFix"></div>
		<?php if($clientDetails['usergroup']=='cms'){ ?>
		<div class="row">
			<div class="row1" style="width:200px; padding-right:5px;"><b>Source Type:</b></div>
			<div class="row2" >
				<select name="i_source_type" id="i_source_type" onchange = "checkSourceType();">
					<?php global $sourceList;?>
					<option value =''>Select</option>
					<?php foreach($sourceList AS $source):?>
					<option value="<?php echo $source;?>" <?php if($source == $source_type)echo "selected='selected'"?>><?php echo $source;?></option>
					<?php endforeach?>
				</select>
				<div style="display:none"><div class="errorMsg" id="i_source_type_error" ></div></div>
			</div>
		</div>
		<div style="line-height:9px;clear:both">&nbsp;</div>
		
		
		<div class="row">
			<div class="row1" style="width:200px; padding-right:5px;"><b>Source Name:</b></div>
			<div class="row2" >
				<input type="text" blurMethod="checkSourceType();" profanity="true" name="i_source_name" id="i_source_name" validate="validateStr" maxlength="100" style="width:350px"  caption="Source Name" value="<?php echo $source_name; ?>"  />
				<div style="display:none"><div class="errorMsg" id="i_source_name_error"></div></div>
			</div>
		</div>
		<?php } ?>
		<div style="line-height:9px;clear:both">&nbsp;</div>
		
		<!-- mandatory comment box section : starts-->
		<?php $this->load->view('listing_forms/mandatory_comments',array('userid'=>$userid,'listing_id'=>$institute_id,'tab'=>'institute')); ?>
		<!-- mandatory comment box section : ends-->
		
		<div align="center">
			<div class="r1_1 bld">&nbsp;</div>
			<div class="r2_2">
				<?php 
				if($status != 'deleted'){
					if($flow == 'edit'){
						?>
						<button class="btn-submit19" type="button" value="" id="save_preview" onclick="document.getElementById('nextAction').value = 1; $('save_preview').disabled = true;if(validate_institute_listing_form() == false){$('save_preview').disabled=false; return false;}" style="width:150px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save &amp; Preview</p></div>
						</button>
						<?php
					}
					?>
					<button class="btn-submit19" type="button" value="" id="save_add_course" onclick="$('save_add_course').disabled=true;if(validate_institute_listing_form()==false){$('save_add_course').disabled=false;return false;}" style="width:150px"><div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save &amp; Add Courses</p></div>
					</button>
					<?php
				}
				else{
					?>
					<p>This institute is already deleted. You cannot edit / publish it.</p>
					<?php
				}
				?>
                <button class="btn-submit39" value=""  type="button" onClick=" try{ ListingOnBeforeUnload.prompt = true;location.replace('/enterprise/Enterprise/index/6');} catch(err) {}"    style="width:72px"><div class="btn-submit39"><p style="padding: 15px 8px 15px 5px;color:#000; font-size:12px" class="btn-submit40">Cancel</p></div>
                </button>
			</div>
			<div class="clearFix"></div>
		</div>
</div>


<input type="hidden" name="locality_name" id="locality_name"/>
<input type="hidden" name="city_name" id="city_name"/>
</form>
<script>
var institute_request_brochure_link = "<?php echo $institute_request_brochure_link;?>";
<?php if($clientDetails['usergroup']=='cms'){ ?>		
function checkSourceType(){
	$('save_add_course').disabled=false;
                        if($('save_preview')){
                                $('save_preview').disabled = false;
                        }

	if($('i_source_name')!='' && $('i_source_type').value == 'Website'){
		if(validateURLNew($('i_source_name').value))
		{
			$('save_add_course').disabled=false;
			$('i_source_name_error').parentNode.style.display ='none';
			$('i_source_name_error').innerHTML = "";
			if($('save_preview')){
				$('save_preview').disabled = false;
			}				
			return true;
		}
		else{	
			$('i_source_name_error').parentNode.style.display ='block';
			$('i_source_name_error').innerHTML = "Please enter valid Website address";
			$('save_add_course').disabled=true;
			if($('save_preview')){
				$('save_preview').disabled = true;
			}
			return false;
		}
	}
	return true;
}
<?php } ?>
        function populate_seo_url(title_obj) {
            var seo_url = title_obj.value;
            seo_url = seo_url.replace(/[\-\(\)\ ]/g, '-');
            seo_url = seo_url.replace(/[\-]+/g, '-');
            seo_url = seo_url.replace(/\-$/, '');
            $('listing_seo_url').value = seo_url;
        }

	function updateFormElem() {
		AIM.submit(document.getElementById('instituteListing'), {'onStart' : startCallback, 'onComplete' : performActionForResponse});
	}
	if(document.all) {
		document.body.onload = updateFormElem;
	} else {
		updateFormElem();
	}
	addOnBlurValidate(document.getElementById('instituteListing'));
  	addOnFocusToopTip(document.getElementById('instituteListing'));
  	//document.getElementById('affiliated_to').setAttribute('tip','');
        getCitiesForCountry('<?php echo $contactAddress['city_id']; ?>',false,'',false);
window.setTimeout('initTMCEEditor()',2000);
function myCustomInitInstance(ed) {
  if (tinymce.isIE || !tinymce.isGecko) {
        tinymce.dom.Event.add(ed.getWin(), 'focus', function(e) {
            try {
                if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                        tinyMCE.activeEditor.setContent('');
                        }
            } catch (ex) {
                // do it later
            }
        });
        tinymce.dom.Event.add(ed.getWin(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
 } else {
	   tinymce.dom.Event.add(ed.getDoc(),'focus',function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
		           tinyMCE.activeEditor.setContent('');
            }
        });
        tinymce.dom.Event.add(ed.getDoc(), 'blur', function(e) {
            if (stripHtml(tinyMCE.activeEditor.getContent()) == 'Enter Description' ) {
                    tinyMCE.activeEditor.setContent('');
                    }
        });
  }
}
var characterLimit = 350;
function initTMCEEditor(){
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	browser_spellcheck : true ,
	theme_advanced_resizing : true,
    plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
	theme_advanced_buttons1 : "bold,italic,underline,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,image|,preview",
    theme_advanced_buttons2 : "jbimages,tablecontrols,|,sub,sup,|,charmap",
	theme_advanced_toolbar_location : "top",
	            force_p_newlines : false,
                force_br_newlines : true,
                forced_root_block : '',
                editor_selector : "mceEditor",
                editor_deselector : "mceNoEditor",
                init_instance_callback: "myCustomInitInstance",
                setup : function(ed) {
                ed.onKeyUp.add(function(ed, e) {
					var strip = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"");

                    // This condition is placed by Amit Kuksal on 12th Jan 2010 as HTML Editor is taking SPACE as characters in IE (Bug ID: 41406)
                    if(document.all && (strip == "&nbsp;" || strip == "<p>&nbsp;</p>"))
                        strip = "";
                    /*
                    var text = strip.split(' ').length + " Words, " +  strip.length + " Characters. You have " +(characterLimit-strip.length)+" Chracter remaining.";
                    */

		// Placed by Amit Kuksal on 22 Dec 2010 against bug id: 41108
                   if(tinyMCE.activeEditor.id == "details")
                       characterLimit = 1500;
                   else
                       characterLimit = 10000;
					
                    var text = "<b>"+strip.length + "</b> out of <b>"+ characterLimit + "</b> characters."
               		if (strip.length > characterLimit) {
                        document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                        document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = "You have used <b>"+ strip.length + "</b> Characters. Please use a maximum of "+ characterLimit +" characters.";
                        tinyMCE.execCommand('mceFocus', false, tinyMCE.activeEditor.id);
                        return false;
               		} else {
                        document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                        document.getElementById(tinyMCE.activeEditor.id+'_error').innerHTML = text;
                    }
                    var textBoxContent = trim(tinyMCE.activeEditor.getContent());
                    textBoxContent = textBoxContent.replace(/[(\n)\r\t\"\']/g,' ');
                    textBoxContent = textBoxContent.replace(/[^\x20-\x7E]/g,'');
                    var profaneResponseWikki = isProfaneTinymce(stripHtml(textBoxContent));
                    if(profaneResponseWikki !== false) {
                        document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                        document.getElementById(tinyMCE.activeEditor.id +'_error').innerHTML = 'Please do not use objected words ('+ profaneResponseWikki +') for the Description';
                        return false;
                    }
                });
           }
             }
             );
}

        <?php
                            if(count($userFieldsArr)>1){
                                foreach($userFieldsArr as $key => $val){
                                    if($key != '0'){
                            ?>
                                document.getElementById('wikkicontent_main_<?php echo $key; ?>').value = '<?php echo jsspecialchars($val['caption']); ?>';
                                document.getElementById('wikkicontent_detail_<?php echo $key; ?>').value = '<?php echo jsspecialchars($val['attributeValue']); ?>';
                            <?php
                            }
                        }
                    }
                ?>
</script>
