<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
$headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
                                                                'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
                          					'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'tabName'	=>	'',
                                                                'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
							);
                                                        $this->load->view('enterprise/headerCMS', $headerComponents);
?>

</head>
<body>
<?php
global $categoryParentMap;
?>
<style>
#calendarDiv {background-color:#FFFFFF;border:1px solid #BDE862;font-family:arial;font-size:10px;padding:1px 1px 20px;position:absolute;visibility:hidden;width:205px;}
#calendarDiv span, #calendarDiv img {float:left;}
#calendarDiv .selectBox, #calendarDiv .selectBoxOver {cursor:pointer;line-height:12px;padding:1px 1px 1px 2px;}
#calendarDiv .selectBoxTime, #calendarDiv .selectBoxTimeOver {cursor:pointer;line-height:12px;padding:1px 1px 1px 2px;}
#calendarDiv td {font-size:10px;margin:0;padding:3px;}
#calendarDiv .selectBox {border:1px solid #FFFFFF;color:#000000;position:relative;}
#calendarDiv .selectBoxOver {background-color:#A2CE48;border:1px solid #FFFFFF;color:#FFFFFF;position:relative;}
#calendarDiv .selectBoxTime {border:1px solid #317082;color:#317082;position:relative;}
#calendarDiv .selectBoxTimeOver {border:1px solid #216072;color:#216072;position:relative;}
#calendarDiv .topBar {background-color:#A2CE48;height:16px;padding:2px;}
#calendarDiv .activeDay {color:#FF0000;}
#calendarDiv .todaysDate {background-color:#F0FFCD;bottom:0;height:17px;line-height:17px;padding:2px;position:absolute;text-align:center;width:201px;}
#calendarDiv .todaysDate div {float:left;}
#calendarDiv .timeBar {background-color:#E2EBED;color:#FFFFFF;height:17px;line-height:17px;position:absolute;right:0;width:72px;}
#calendarDiv .timeBar div {float:left;margin-right:1px;}
#calendarDiv .monthYearPicker {background-color:#A2CE48;border-color:-moz-use-text-color #FFFFFF #FFFFFF;border-style:none solid solid;border-width:medium 1px 1px;color:#317082;display:none;left:0;position:absolute;top:15px;z-index:1000;}
#calendarDiv #monthSelect {width:70px;}
#calendarDiv .monthYearPicker div {clear:both;cursor:pointer;float:none;margin:1px;padding:1px;}
#calendarDiv .monthYearActive {background-color:#D3F08F;color:#FFFFFF;}
#calendarDiv td {cursor:pointer;text-align:right;}
#calendarDiv .topBar img {cursor:pointer;}
#calendarDiv .topBar div {float:left;margin-right:1px;}
#calendarDiv .disableDay {color:GREY;}
#wrapper{margin:0 auto; width:948px;position:relative}
.stu_headerBrd_bg{border:1px solid #98b9e6; background:url(/public/images/study_headerBg.gif) repeat-x left bottom;height:82px;}
.study_headingTxt{font-size:24px;font-family:Trebuchet MS; background:url(/public/images/study_aero.gif) no-repeat right center;padding-right:54px;}
.study_bullets{background:url(/public/images/study_bullets.gif) no-repeat left top;padding-left:22px;padding-bottom:9px}
.study_subHead{line-height:26px;background:#edf5ff;padding-left:12px;font-size:16px;color:#464645;margin-bottom:5px}
.study_btnReady{border:0 none;margin:0;padding:0;background:url(/public/images/study_btnReady.gif) no-repeat left top;width:275px;height:29px;color:#FFF;font-size:18px;font-weight:700}
.study_btnOK{border:0 none;margin:0;padding:0;background:url(/public/images/study_btnOK.gif) no-repeat left top;width:51px;height:22px;color:#FFF;font-size:18px;font-weight:700}
</style>
</head>
<script>
    var cal = new CalendarPopup("calendardiv");
    var currentTime = new Date();
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate() - 1 ;
    var year = currentTime.getFullYear();
    var dateStr = month + '/' + day + '/' + year;
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>


<body>
<div class="mar_full_10p">
	<div class="raised_lgraynoBG">
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	<div class="boxcontent_lgraynoBG">
    	<div class="row">
    	<div class="mar_full_10p">
        	<!--Start_Heading-->
        	<div style="padding-bottom:10px">
            	<div class="OrgangeFont bld lineSpace_25">Consultant Profile</div>
            	<div class="grayLine" style="font-size:1px">&nbsp;</div>
            </div>
            <!--End_Heading-->
            <!--Start_Form-->
            <div>
            	<form id="consultantForm" name="consultantForm" method="post" action="/consultants/shikshaConsultants/addConsultant">
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Name:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">                    	 
                        <div><input type="text" style="width:200px;font-size:12px;height:17px" name="consultant_name" id="consultant_name" caption="name" required="true" minlength="3" maxlength="100" validate="validateDisplayName" value="<?php echo isset($consultantData['listing_title'])?$consultantData['listing_title']:''?>" /></div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_name_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Email Id:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div><input type="text" style="width:200px;font-size:12px;height:17px" name="consultant_email" id="consultant_email" caption="email" required="true" validate="validateEmail" minlength="5" maxlength="100" value="<?php echo isset($consultantData['consultant_email'])?$consultantData['consultant_email']:''?>"/></div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_email_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Mobile No:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div><input type="text" style="width:200px;font-size:12px;height:17px" name="consultant_mobile" id="consultant_mobile" minlength="10" maxlength1="10" maxlength="10" caption="mobile number" required="true" validate="validateMobileInteger" value="<?php echo isset($consultantData['consultant_mobile'])?$consultantData['consultant_mobile']:''?>"/></div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_mobile_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Address:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div><textarea style="width:200px;font-size:12px" name="consultant_address" id="consultant_address"  caption="address" required="true" minlength="3" maxlength="250" validate="validateStr"><?php echo isset($consultantData['consultant_address'])?$consultantData['consultant_address']:''?></textarea></div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_address_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Branch Office:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div>
				<select style="width:200px;font-size:12px;height:23px" name="consultant_city" id="consultant_city" caption="branch office" required="true" validate="validateSelect">
					<option value="">Select City</option>
					<?php
					if(isset($consultantData['consultant_branceOfficeCity']))
					{
						$consultant_city_selected = $consultantData['consultant_branceOfficeCity'];
					}
					else
					{
						$consultant_city_selected = -1;
					}
					foreach($cityTier1 as $key=>$value)
					{
						if($value['cityId'] == $consultant_city_selected)
						{
							echo "<option selected value=\"".$value['cityId']."\">".$value['cityName']."</option>";
						}
						else
						{
							echo "<option value=\"".$value['cityId']."\">".$value['cityName']."</option>";
						}
					}
					foreach($cityTier2 as $key=>$value)
					{
						if($value['cityId'] == $consultant_city_selected)
						{
							echo "<option selected value=\"".$value['cityId']."\">".$value['cityName']."</option>";
						}
						else
						{
							echo "<option value=\"".$value['cityId']."\">".$value['cityName']."</option>";
						}
					}
					?>
				</select>
			</div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_city_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Country Served:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div class="row">
                        	<div id="consultantContry">
				<?php
					$consultant_country_array = array();
					if(isset($consultantData['countries']))
					{
						$consultant_country_array = explode(',',$consultantData['countries']);
					}
					global $countries;
					foreach($countries as $key=>$value)
					{
						if($key != 'india')
						{
							if(in_array($value['id'],$consultant_country_array))
							{
								echo "<div style=\"float:left;width:150px\"><input type=\"checkbox\" value=\"".$value['id']."\" name=\"ctry[]\" id=\"consultantContry_\"".$value['id']."\" tag=\"".$value['name']."\" checked /> ".$value['name']."</div>";
							}
							else
							{
								echo "<div style=\"float:left;width:150px\"><input type=\"checkbox\" value=\"".$value['id']."\" name=\"ctry[]\" id=\"consultantContry_\"".$value['id']."\" tag=\"".$value['name']."\" /> ".$value['name']."</div>";
							}
						}
					}
				?>
                            </div>
                            <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                        </div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_country_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Categories Served:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div>
				<select style="width:200px;font-size:12px" name="consultant_category[]" id="consultant_category" multiple="multiple" size="10" caption="categories Served" required="true">
				<?php
				$consultant_category_array= array();
				if(isset($consultantData['categories']))
				{
					$consultant_category_array = explode(',',$consultantData['categories']);
				}
				foreach($categoryParentMap as $key=>$value)
				{
					if(in_array($value['id'],$consultant_category_array))
					{
						echo "<option selected value=\"".$value['id']."\">".$key."</option>";
					}
					else
					{
						echo "<option value=\"".$value['id']."\">".$key."</option>";
					}
				}
				?>
				</select>
			</div>

                        <div class="errorPlace"><div class="errorMsg" id="consultant_category_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Student source of funding:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
                        <div class="row">
                        	<div id="scFunding">
				<?php
				$fundSources=array();
				if(isset($consultantData['fundSources']))
				{
					$fundSources = explode(',',$consultantData['fundSources']);	
				} 
				?>
                            	<div style="float:left;width:150px"><input type="checkbox" value="Do not matter" name="sfund[]" id="funding_1" tag="Do not matter" <?php echo in_array('Do not matter',$fundSources)?'checked':''; ?>/> Don't matter</div>
                                <div style="float:left;width:150px"><input type="checkbox" value="Bank Loan" name="sfund[]" id="funding_2" tag="Bank loan" <?php echo in_array('Bank Loan',$fundSources)?'checked':''; ?>/> Bank Loan</div>
                                <div style="float:left;width:150px"><input type="checkbox" value="Own Funds" name="sfund[]" id="funding_3" tag="Own funds" <?php echo in_array('Own Funds',$fundSources)?'checked':'';?> /> Own funds</div>
                            </div>
                            <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                        </div>
                        <div class="errorPlace"><div class="errorMsg" id="consultant_source_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">Start Date:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
						<div><input caption="Start Date" required="true" type="text" name="start_date" id="start_date" onclick="cal.addDisabledDates(null,dateStr);cal.select($('start_date'),'dfs','yyyy-MM-dd');" minlength="0" maxlength="10" validate="validateStartDate" profanity="true" value="<?php echo isset($consultantData['leadStartDate'])?$consultantData['leadStartDate']:''; ?>" readonly=""/> <img align="absmiddle" onclick="cal.addDisabledDates(null,dateStr);cal.select($('start_date'),'dfs','yyyy-MM-dd');" id="dfs" style="cursor: pointer;" src="/public/images/eventIcon.gif"/></div>
                        <!--<div><input type="text" style="width:100px;font-size:12px;height:17px" name="consultant_startDate" id="consultant_startDate" /></div>-->
                        <div class="errorPlace"><div class="errorMsg" id="start_date_error"></div><div class="errorMsg" id="start_time_error"></div></div>					
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div style="padding-bottom:8px">
                    <div style="width:205px;float:left">
                        <div style="line-height:19px;text-align:right">End Date:<span class="redcolor">*</span>&nbsp;</div>
                    </div>
                    <div style="width:500px;float:left">
						<div><input caption="End Date" required="true" type="text" name="end_date" id="end_date" onclick="cal.addDisabledDates(null,dateStr);cal.select($('end_date'),'dfs','yyyy-MM-dd');" minlength="0" maxlength="10" validate="validateEndDate" profanity="true" readonly="" value="<?php echo isset($consultantData['leadEndDate'])?$consultantData['leadEndDate']:''; ?>"/> <img align="absmiddle" onclick="cal.addDisabledDates(null,dateStr);cal.select($('end_date'),'dfs','yyyy-MM-dd');" id="dfs" style="cursor: pointer;" src="/public/images/eventIcon.gif"/></div>                        
                        <div class="errorPlace"><div class="errorMsg" id="end_date_error"></div><div class="errorMsg" id="end_time_error"></div></div>
                    </div>
                    <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                </div>
                <div class="grayLine" style="font-size:1px">&nbsp;</div>
                <div>
		    <?php if(isset($consultantData['consultant_id'])) { ?>	
		    <input type="hidden" name="consultant_id" id="consultant_id" value="<?php echo $consultantData['consultant_id'];?>" />
		    <?php } ?>
		    <input type="hidden" name="mCountryList" id="mCountryList" value="" />
                    <input type="hidden" name="mCountryListName" id="mCountryListName" value="" />
					<input type="hidden" name="sFundingList" id="sFundingList" value="" />
                    <input type="hidden" name="sFundingListName" id="sFundingListName" value="" />
                </div>
                <div style="padding:5px 0 5px 205px"><input type="submit" class="submitGlobal" value="Submit" onClick="return validateConsultantForm(this.form)" /> <input type="button" class="cancelGlobal" value="Cancel" onClick="redirectMe('/enterprise/Enterprise/index/29');"/></div>
			</div>
            </form>
            <!--End_Form-->
		</div>
        </div>
	</div>
  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>
<script>
addOnBlurValidate(document.getElementById('consultantForm'));
</script>
