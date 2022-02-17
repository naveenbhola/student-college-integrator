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
$maximumNumberOfEnterpriseFields = 250;
$maximunNumberOfAddMoreOptions = 10;
   
?>   
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
var urlToRedirect = '<?php echo $urlToRedirect;?>';
</script>
<div id="content-child-wrap" style="width:948px;padding-top:12px;margin: 15px;border: solid 1px">
<style type="text/css">
input, select{font:normal 12px Arial, Helvetica, sans-serif; padding:3px; margin:0 5px 6px 0}
</style>
<span style="font-size:16px;padding-bottom:20px;">Enterprise field mapping tool</span>
<form method="post" onsubmit="postMappingValue(this,'<?php echo $maximumNumberOfEnterpriseFields;?>','<?php echo $maximunNumberOfAddMoreOptions;?>');return false;" >
<input type="hidden" value="<?php echo $maximumNumberOfEnterpriseFields;?>" name="maximumNumberOfEnterpriseFields"/>
<input type="hidden" value="<?php echo $maximunNumberOfAddMoreOptions;?>" name="maximunNumberOfAddMoreOptions" />
<div id="instituteName" style="margin-top: 20px;">
	<select onchange="window.location='/onlineFormEnterprise/OnlineFormEnterprise/getMappingInterface/'+this.value" name="instituteId">
		<option value="">Institute Name</option>
		<?php for($i=0;$i<count($instituteInfo);$i++){?>
		    <option value="<?php echo $instituteInfo[$i]['instituteId']; ?>" <?php if($instituteId==$instituteInfo[$i]['instituteId']){ echo 'selected="selected"';}?>><?php echo $instituteInfo[$i]['instituteName']; ?></option>
		<?php
		}?>

	</select>
</div>

<div class="spacer10 clearFix"></div>


<?php if(!empty($courseInfo)){
?>
<div id="courseName" >
	<select onchange="window.location='/onlineFormEnterprise/OnlineFormEnterprise/getMappingInterface/<?php echo $instituteId;?>/'+this.value" name="courseId">
		<option value="">Course Name</option>
		<?php for($i=0;$i<count($courseInfo);$i++){?>
		
		    <option value="<?php echo $courseInfo[$i]['courseId']; ?>" <?php if($courseId==$courseInfo[$i]['courseId']){ echo 'selected="selected"';}?>><?php echo $courseInfo[$i]['courseName']; ?></option>
		<?php
		}?>

	</select>
</div>
<?php if(!empty($shikshaFieldInfo) && $shikshaFieldInfo!='None'){ ?>
<div class="spacer10 clearFix"></div>
<div class='float_L' style="width:100px;font-size:14px;">Order</div>
<div class='float_L' style="width:100px;font-size:14px;">Type Of field</div>
<div class='float_L' style="width:180px;font-size:14px;">Enter Enterprise field</div>
<div class='float_L' style="width:238px;font-size:14px;">Select Shiksha field</div>
<div class='float_L' style="font-size:14px;">Select Seperator field</div>
<div class='clear_B'>&nbsp;</div>
<?php } ?>
<?php
}
//print_r($mappingFieldInfo);die;
if(!empty($shikshaFieldInfo) && $shikshaFieldInfo!='None'){
	for($i=0;$i<$maximumNumberOfEnterpriseFields;$i++){
			$multipleFieldOptionString = '';
			
			 /*We have divided the mapping elements into groups.Each group contains 5 mapping elements.After every 5th mapping there will be a more options link.
			 *We are checking here that next first mapping element of group is empty or not. 
			 */

			if(!empty($mappingFieldInfo['EnterpriseFieldId_'.$i]) && $i%5==0 && $i>4){
			    $numberOfFieldsContaingValue = $i+5;
			   // $mainFlag = true;
			}
			/*Match the value of $i with $numberOfFieldsContaingValue for $i>4
			 */
			?>
			<div  <?php if($i>4 && $i>=$numberOfFieldsContaingValue){?>style='display:none;'<?php }?> id="containerNumber<?php echo $i;?>"  >
			<?php
			$flag = false;
			?>
			<div class='float_L' style="width:100px;">
			<input type='hidden' name='EnterpriseFieldId_<?php echo $i;?>' id='EnterpriseFieldId_<?php echo $i;?>' value='<?php echo $mappingFieldInfo['EnterpriseFieldId_'.$i];?>'></input>
			<select id='orderOfEnterpriseFieldId_<?php echo $i;?>' name='orderOfEnterpriseFieldId_<?php echo $i;?>'>
			<?php
				for($order=1;$order<=$maximumNumberOfEnterpriseFields;$order++){
			?>
				<option value="<?=$order?>" <?=(($order-1)==$i)?'selected':''?>><?=$order?></option>
			<?php
				}
			?>
			</select>
			</div>
			<div class='float_L' style="width:100px;">
			<!--<input type='hidden' id='orderOfEnterpriseFieldId_<?php echo $i;?>' name='orderOfEnterpriseFieldId_<?php echo $i;?>' value='<?php echo $mappingFieldInfo['OrderOfEnterpriseField_'.$i];?>' ></input>-->
			<select style="width:75px;" name="typeOfField_<?php echo $i;?>" id="typeOfField_<?php echo $i;?>" onchange="checkTypeOfField(this,'<?php echo $maximumNumberOfEnterpriseFields;?>','<?php echo $i;?>')">
				<option value="text" <?php if($mappingFieldInfo['typeOfField_'.$i]=='text' || $mappingFieldInfo['typeOfField_'.$i]==''){ echo 'selected="selected"';}?>>Text</option>
				<option value="name" <?php if($mappingFieldInfo['typeOfField_'.$i]=='name'){ echo 'selected="selected"';}?>>Name</option>
				<option value="email" <?php if($mappingFieldInfo['typeOfField_'.$i]=='email'){ echo 'selected="selected"';}?>>Email</option>
				<option value="date" <?php if($mappingFieldInfo['typeOfField_'.$i]=='date'){ echo 'selected="selected"';}?>>Date</option>
			</select>
			</div>
			<div class='float_L' style="width:180px;">
			<input type='text' style="width: 145px;" name='EnterpriseField_<?php echo $i;?>' id='EnterpriseField_<?php echo $i;?>'  value="<?php echo $mappingFieldInfo['EnterpriseField_'.$i];?>" onfocus="javascript: validatePreviousEnterpriseFieldAndShikshaFields('<?php echo $i;?>','<?php echo $maximunNumberOfAddMoreOptions;?>');"  onkeypress=" if(this.value.length > 250){ this.value = this.value.substring(0,249); }" onChange ="validateEF('<?php echo $i;?>');return false;" ></input>
			</div>
			
			<div class='float_L'>
			<?php
			for($j=0;$j<$maximunNumberOfAddMoreOptions;$j++){
			    $k=$j+1;
			    
			    ?>
			
				<input type='hidden' id='orderOfFieldId<?php echo $i;?>_<?php echo $j;?>' name='orderOfFieldId<?php echo $i;?>_<?php echo $j;?>' value='<?php echo $mappingFieldInfo['OrderOfShikshaField'.$i.'_'.$j]?>' > </input>
			
			
			    <?php
			    if(empty($mappingFieldInfo['shikshaFieldName'.$i.'_'.$j]))
			    {
				if($flag==true){
				?>
				    <span id="addMoreSpan<?php echo $i;?>_<?php echo $j;?>"><a href="javascript:void(0);" id="addMore<?php echo $i;?>_<?php echo $j;?>" onclick="addMoreOption('shikshaFieldAndSeperator<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $k;?>','<?php echo $i;?>','<?php echo $j;?>');" )>Add Shiksha Field</a></span>
				<?php
				    $flag = false;
				}
				else{
				?>
				<span id="addMoreSpan<?php echo $i;?>_<?php echo $j;?>"><a href="javascript:void(0);" id="addMore<?php echo $i;?>_<?php echo $j;?>" onclick="addMoreOption('shikshaFieldAndSeperator<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $k;?>','<?php echo $i;?>','<?php echo $j;?>');" <?php if($j==0 || $j>1){?>style='display:none;'<?php } ?> );">Add Shiksha Field</a></span>
				<?php
				}
			    }else{
				    $flag = true;
				?>
				<span id="addMoreSpan<?php echo $i;?>_<?php echo $j;?>"><a style="display:none" href="javascript:void(0);" id="addMore<?php echo $i;?>_<?php echo $j;?>" onclick="addMoreOption('shikshaFieldAndSeperator<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $j;?>','addMore<?php echo $i;?>_<?php echo $k;?>','<?php echo $i;?>','<?php echo $j;?>');" )>Add Shiksha Field</a></span>
				<?php
			    }
			    ?>
			    <span <?php if($j>0 && empty($mappingFieldInfo['shikshaFieldName'.$i.'_'.$j])){?>style='display:none'<?php }?> id="shikshaFieldAndSeperator<?php echo $i;?>_<?php echo $j;?>">
			    <select name='ShikshaField<?php echo $i;?>_<?php echo $j;?>' id='ShikshaField<?php echo $i;?>_<?php echo $j;?>' onChange="validateEnterpriseField('<?php echo $i;?>','<?php echo $j;?>','<?php echo $maximunNumberOfAddMoreOptions; ?>',this.value);return false;">
			    <option value='0'>Select Field</option>
			    <?php
			    foreach($shikshaFieldInfo as $data){
			    ?>
				<option value='<?php echo $data->fieldId;?>' <?php if($mappingFieldInfo['shikshaFieldName'.$i.'_'.$j]==$data->name){?>selected="selected"<?php }?> ><?php echo $data->name;?></option>
			    <?php
			       
			    }
			    ?>
			    </select>
			    
			    <select name="Seperator<?php echo $i;?>_<?php echo $j;?>" id="Seperator<?php echo $i;?>_<?php echo $j;?>">
				<option value=''>Select Seperator</option>
			    <?php
			    foreach($separatorInfo as $key=>$value){
			    ?>
				<option value='<?php echo $value;?>' <?php if($mappingFieldInfo['Seperator'.$i.'_'.$j]==$value){?>selected="selected"<?php }?>><?php echo $key;?></option>
			    <?php 
			    }?>
			    </select>
			    <?php //if($j>0){?>
			    <?php $orderOfShikshaField =$mappingFieldInfo['OrderOfShikshaField'.$i.'_'.$j];?>
			    <?php if($j!=0){ ?>
			    <img id="cancelButton_<?php echo $j;?>" src="/public/images/adsLink_close.gif" style="vertical-align: middle;" onclick="deteleShikshaFieldFromMapping('<?php echo $i;?>','<?php echo $j;?>','<?php echo $orderOfShikshaField;?>','<?php echo $mappingFieldInfo["EnterpriseFieldId_".$i];?>','<?php echo $mappingFieldInfo["shikshaFieldName".$i."_".$j];?>','<?php echo $maximunNumberOfAddMoreOptions;?>');" />
			    <?php }else if( $j==0 && isset($mappingFieldInfo['shikshaFieldName'.$i.'_'.$j])){ ?>
			    <img id="cancelButton_<?php echo $j;?>" src="/public/images/adsLink_close.gif" style="vertical-align: middle;" onclick="deteleShikshaFieldFromMapping('<?php echo $i;?>','<?php echo $j;?>','<?php echo $orderOfShikshaField;?>','<?php echo $mappingFieldInfo["EnterpriseFieldId_".$i];?>','<?php echo $mappingFieldInfo["shikshaFieldName".$i."_".$j];?>','<?php echo $maximunNumberOfAddMoreOptions;?>');" />
			    <?php } ?>
			    </span>
			    
			    <div class="clear_B"></div>
			<?php    
			}
			echo "</div>";
			echo '<div class="spacer5 clearFix"></div></div>';
			echo "<div class=\"spacer10 clearFix\" ".(($i>4)?'style=\'display:none;\'':'style=\'\'' )." id=\"spaceSection$i\"></div>";
	}
	?>
	<div class='spacer10 clearFix'></div>
	<strong id="showMore5Options"></strong>
	<?php
	
	echo "<input type=\"submit\" name=\"submit\" value=\"Submit\" id=\"submitButton\"/>";
}else if(!empty($shikshaFieldInfo) && $shikshaFieldInfo=='None'){
	echo "<div style='margin-bottom:30px;font-size:18px; color:red'>No Shiksha Field Related to Course.</div>";
}
//if(!empty($courseInfo)){ ?>

<div id="messageDiv" style="color: red;"></div>
<?php //} ?>
<br/>
</form>
</div>


<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 
<script>
	window.onload= function(){
			if($('showMore5Options')){
				var maximumNumberOfEnterpriseFields= '<?php echo $maximumNumberOfEnterpriseFields;?>';
				for(var i =0;i< maximumNumberOfEnterpriseFields;i++){
					if(i%5==0 && i>1 && $('EnterpriseFieldId_'+i).value==''){
						$('showMore5Options').innerHTML = '<a href="javascript:void(0);" onclick="showNextSection(\''+i+'\',\'<?php echo $maximumNumberOfEnterpriseFields;?>\');" id="showNextSection5">Add 5 More Options</a>';
						return;
					}
				}
	}
	
}

function checkTypeOfField(ref,maximumNumberOfEnterpriseFields,rowNum){
	var valueToSelect = ref.value;
	var count = 0;
	for(var i=0;i<maximumNumberOfEnterpriseFields;i++){
		if(valueToSelect == $('typeOfField_'+i).value && valueToSelect!='text' && valueToSelect!='date'){
			count++;
		}
	}
	if(count>1){
		alert('You have already selected this value.');
		$('typeOfField_'+rowNum).options.selectedIndex = 0;
		return false;
	}
	return true;
}

</script>
