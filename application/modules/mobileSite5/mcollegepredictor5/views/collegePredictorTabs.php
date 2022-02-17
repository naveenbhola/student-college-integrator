<?php 
$baseUrl = SHIKSHA_HOME.$this->settingArray['CPEXAMS'][strtoupper($examName)]['directoryName'];
$name = strtolower($examName);
?>
<input type="hidden" name="noStateDropDown" id ="noStateDropDown" value= "<?php echo $noStateDropDown ?>" />
<input type="hidden" name="stateName" id ="stateName" value= "<?php echo $stateName ?>" />

<section id="tab" class="clearfix college-predictor-tab">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr>
            <td <?php if($tab=='1'){ echo 'class="active"'; } ?> >
            	<a href="<?php if($tab!='1'){ echo $baseUrl."/".$name.'-college-predictor';} else {echo "javascript:void(0)";}?>" <?php if($tab=='1'){echo "style='cursor:default'";} ?> ><h2>Find College & Branch</h2></a>
            </td>
            <td <?php if($tab=='2'){ echo 'class="active"'; } ?>>
            	<a href="<?php if($tab!='2'){ echo $baseUrl."/".$name.'-cut-off-predictor';} else {echo "javascript:void(0)";}?>" <?php if($tab=='2'){echo "style='cursor:default'";} ?> ><h2>Know College Cut-offs</h2></a>
            </td>
            <td <?php if($tab=='3'){ echo 'class="active"'; } ?>>
            	<a href="<?php if($tab!='3'){ echo $baseUrl."/".$name.'-branch-predictor';} else {echo "javascript:void(0)";}?>" <?php if($tab=='3'){echo "style='cursor:default'";} ?> ><h2>Find College for a Branch</h2></a>
            </td>
        </tr>
    </table>
</section>