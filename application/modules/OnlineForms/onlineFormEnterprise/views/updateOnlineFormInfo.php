<?php
				
$headerComponents = array(
   'css'	=>	array('online-styles','header','raised_all','mainStyle','cal_style'),
   'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','user','onlineFormEnterprise'),
   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
   'callShiksha'=>1,
   'notShowSearch' => true,
   'showBottomMargin' => false,
   'showApplicationFormHeader' => false
);

$this->load->view('common/header', $headerComponents);
$this->load->view('common/calendardiv');
$maximumNumberOfEnterpriseFields = 150;
$maximunNumberOfAddMoreOptions = 10;
   
?>   
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
var urlToRedirect = '<?php echo $urlToRedirect;?>';
</script>
<style>
#dashboard-tab{border-bottom:1px solid #cdcdcd; float:left; width:100%; position: relative}
#dashboard-tab ul{margin:0; padding:0; list-style:none; padding-left:15px}
#dashboard-tab ul li{float:left; padding:5px 20px; border:1px solid #cdcdcd; border-bottom:0 none; background:#ededed; font:normal 16px Arial, Helvetica, sans-serif; margin-right:10px; position:relative; margin-top:2px;}
#dashboard-tab ul li.active{background:#fff; top:1px}
#dashboard-tab ul li a{text-decoration:none !important}
</style>

<div id="dashboard-tab" style="margin-top:20px;">
  <ul>
    <li <?php if($tab == 'internal'){ ?> class="active" <?php } ?>><a href="/onlineFormEnterprise/OnlineFormEnterprise/updateOnlineFormInformation?tab=internal">Update Internal Forms</a></li>
    <!--<li <?php if($tab == 'external'){ ?> class="active" <?php } ?>><a href="/onlineFormEnterprise/OnlineFormEnterprise/updateOnlineFormInformation?tab=external">Add/Update External Forms</a></li>-->
  </ul>
</div>
<div class="spacer10 clearFix"></div>

<?php if($tab=='internal'){ ?>

<div id="content-child-wrap" style="width:948px;padding-top:12px;margin: 15px;border: solid 1px">
<style type="text/css">
input, select{font:normal 12px Arial, Helvetica, sans-serif; padding:3px; margin:0 5px 6px 0}
</style>
<span style="font-size:16px;padding-bottom:20px;">Select Institute to update its Online form Basic Information</span>
	<form method="post" onsubmit="return checkForFields();" action='/onlineFormEnterprise/OnlineFormEnterprise/updateOnlineFormInformation'>
		<div id="instituteName" style="margin-top: 20px;">
			<select onchange="window.location='/onlineFormEnterprise/OnlineFormEnterprise/updateOnlineFormInformation/'+this.value" name="instituteId">
				<option value="">Institute Name</option>
				<?php for($i=0;$i<count($instituteInfo);$i++){?>
				    <option value="<?php echo $instituteInfo[$i]['instituteId']; ?>" <?php if($instituteId==$instituteInfo[$i]['instituteId']){ echo 'selected="selected"';}?>><?php echo $instituteInfo[$i]['instituteName']; ?></option>
				<?php
				}?>
			</select>
			<?php if($instituteId!=''){ ?><div style='margin-left:50px;margin-top:20px;'><b>You can update following Information:</b></div><?php } ?>
			<div style='margin-left:50px;margin-top:20px;'>
			<?php
				$hideArray = array('id','instituteId','courseId','formIdMinRange','formIdMaxRange','creationDate','status','logoImage','departmentId','departmentName','sessionYear','externalURL');
				$mandatoryArray = array('fees','last_date','instituteEmailId','demandDraftInFavorOf','demandDraftPayableAt','instituteMobileNo');
				//_p($instituteBasicInfo[0]);
				foreach ($instituteBasicInfo[0] as $key=>$value){
					if($key=='externalURL' && $value==''){
						$value='NULL';
					}
					if(!in_array($key,$hideArray)){
						echo "<div class='float_L' style='width:150px;margin-top:10px;'>".$key;
						if(in_array($key,$mandatoryArray)){
							echo '*';
						}
						echo "</div>";
						echo "<div class='float_L' style='margin-top:10px;'><input id='$key' name='$key' value='$value' style='width:400px;'/></div>";
						echo "<div class='clear_B'>&nbsp;</div>";
					}
					if($key=='last_date'){
						echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Enter date in YYYY-MM-DD format</div>";
					}
					if($key=='otherCourses'){
						echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Comma separated courses on which Online form button is to be displayed.</div>";
					}
					/*if($key=='externalURL'){
						echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Add URL in case of External Online form. Else leave blank</div>";
					}*/
					if($key=='basicInformation'){
						echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Add text to be displayed in Bold under Notification icon</div>";
					}
					if($key=='instituteDisplayText'){
						echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Add text to be displayed in Heading in points. For eg: <br/>
						<b>4) Minimum qualification for eligibity to this course is: A graduate Degree with 50% overall aggregate marks from an AIU recognised university &lt;/li&gt;&lt;li&gt;5) Valid scores from any of these examination will be considered: &lt;b&gt;CAT/XAT/GMAT/GRE&lt;/b&gt; &lt;/li&gt;</b></div>";
					}
                    if($key=='discount'){
                        echo "<div style='margin-left:150px;font-size:11px;color:grey;'>Please enter the percentage of Discount. Only integer values are accepted.</div>";
                    }
				}
			?>
			</div>
			<?php if($instituteId!=''){ ?><div style='margin-top:20px;margin-left:50px;'><input type='submit' value='Update' style='width:200px;' /></div><?php } ?>
			
		</div>
		
		<div class="spacer10 clearFix"></div>
		
		<div id="messageDiv" style="color: red;"></div>
		<br/>
	</form>
</div>

<?php } ?>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 

<script>
function checkForFields(){
	if( $('fees').value=='' || isNaN($('fees').value) ){
		alert("Fees is Mandatory and only Integer values are allowed.");
		$('fees').focus();
		return false;
	}
    if( $('discount').value!=''){
        if(Math.round($('discount').value) != $('discount').value){
            alert("Only Integer values are allowed for Discount.");
            $('discount').focus();
            return false;
        }
    }
	if( $('last_date').value=='' ){
		alert("Last Date is Mandatory and format is YYYY-MM-DD");
		$('last_date').focus();
		return false;
	}
	if( $('instituteEmailId').value=='' ){
		alert("EmailId is Mandatory");
		$('instituteEmailId').focus();
		return false;
	}
	if( $('instituteAddress').value=='' ){
		alert("Address is Mandatory");
		$('instituteAddress').focus();
		return false;
	}
	if( $('demandDraftInFavorOf').value=='' ){
		alert("DD details are Mandatory");
		$('demandDraftInFavorOf').focus();
		return false;
	}
	if( $('demandDraftPayableAt').value=='' ){
		alert("DD Payable at details are Mandatory");
		$('demandDraftPayableAt').focus();
		return false;
	}
	if( $('instituteMobileNo').value=='' || isNaN($('instituteMobileNo').value) ){
		alert("Mobile number is Mandatory and only Integer values are allowed.");
		$('instituteMobileNo').focus();
		return false;
	}
    
	return true;
}
</script>
