<!--StartTopHeaderWithNavigation-->
<div style="line-height:20px">&nbsp;</div>
<div style="margin:0 20px">
   <div class="welcomeIcon">
			<div>
				<span class="fontSize_13p bld">Welcome </span><span class="fontSize_13p bld OrgangeFont"><?php echo $displayname;?></span>
			</div>
			<div style="height:13px;"></div>	
		</div>
</div>
<?php if($clientType!='Abroad') $this->load->view('contactAndViewCountDetails.php');?>
<?php if($clientType == 'Abroad') $this->load->view('abroadContactAndViewCountDetails.php');?>
<div style="line-height:1px">&nbsp;</div>
<div class="mar_full_10p">
				<div class="txt_align_r" style="margin-right:10px; position:relative; top:17px"><img src="/public/images/clip.gif" /></div>
				<div class="raised_greenGradient">
					<b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
					<div class="boxcontent_greenGradient">
						<div class="mar_full_10p">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="OrgangeFont fontSize_12p bld">You can do the following using enterprise shiksha</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="arrowBullets mar_bottom_10p">Purchase/Upgrade Product packs</div>
							<div class="arrowBullets mar_bottom_10p">View/Edit your profile details</div>
                                                        <?php if($clientType!='Abroad'){?>
							    <div class="arrowBullets">Post, Edit and Delete Institute and Course Listings</div>
							<?php }?>
							<div class="lineSpace_10">&nbsp;</div>
						</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="mar_full_10p">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td width="35%"><a href="/enterprise/Enterprise/profile"><img src="/public/images/manageAccount.jpg" border="0" height="70"/></a></td>
    <?php /*if($clientType!='Abroad'){?>
      <td width="32%" align="center"><a href="javascript:void(0)" onclick="showInstituteTypeSelectionOverlay()"><img src="/public/images/addCollegeCourse.jpg"  border="0" height="70"/></a></td>
      <td width="33%" align="right"><a href="/enterprise/ShowForms/showCourseForm"><img src="/public/images/addCollegeCourse.jpg" border="0" height="70" /></a></td>
    <?php } */?>
  </tr>
  <tr>
    <td height="25"><a href="/enterprise/Enterprise/profile" class="fontSize_12p">Manage Account Profile</a> &nbsp;</td>
   <?php /* if($clientType!='Abroad'){?>
    <td align="center"><a onclick="showInstituteTypeSelectionOverlay()" href="javascript:void(0)" class="fontSize_12p">Add an Institute</a> &nbsp;</td>
    <td align="right"><a href="/enterprise/ShowForms/showCourseForm/" class="fontSize_12p">Add a Course</a></td>
   <?php } */?>
  </tr>
  <tr><td height="0" colspan="3">&nbsp;</td></tr>
</table>
</div>


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
				<div><input type="radio" name="instituteType[]" value="1" id="academicInstitute"> Academic institute</div>
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

<script>
function addInstituteByType(type){
	var type;
	if($('academicInstitute').checked==true){
		type=1;
	}else if($('testprepInstitute').checked==true){
		type=2;
	}else{
		return false;
	}
//	document.getElementById("showInstituteSelectionForm").style.display = "block";
	window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/-1/'; ?>'+type;
}
</script>
