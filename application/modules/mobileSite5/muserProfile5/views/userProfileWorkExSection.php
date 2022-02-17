<?php  
	$privacyFields = array('0'=>'DepartmentworkExp'.$counter,'1' =>'EmployerworkExp'.$counter,'2'=>'DesignationworkExp'.$counter,'3'=>'CurrentJobworkExp'.$counter);
                    $privacyFields = serialize($privacyFields);
?>
<li style="word-wrap:break-word">
	<p class="flLt" style="width:80%"><?php if($CurrentJob != 'NO'){?>Currently at <?php } ?><?php echo $Employer;?><br/>
		<span class="bold-text">
		<?php echo $Designation;?>
		<?php if(!empty($Department)) { echo " (".$Department.")"; } ?>
		</span>
	</p>

<?php if(!$publicProfile){
	
	$publicFlag = false;
	if($privacyDetails['EmployerworkExp'.$counter] == 'public'){
		$publicFlag = true;
	}

	 $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));
 }?>	
 </li>