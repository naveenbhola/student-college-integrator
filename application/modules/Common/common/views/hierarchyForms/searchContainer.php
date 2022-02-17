<div id="custom-slct-<?php echo $containerId;?>" num="<?php echo $countHierarchy;?>" class="findSrCnt customSelect multi-slct alignBox">
     <div class="input-wrapper custom-srch" style="position:static;height:30px;display:block;">
         <input type="text" id="<?php echo $containerId;?>-Key" placeholder=" Search/Select <?php echo $label;?>" class="drp-txt locations"/>
         <em class="pointerDown"></em>
         <div><div id="location-Key_error" class="errorMsg" style="display: none; padding-top:5px;">&nbsp;</div></div>	
     </div>
    
     <div class="option-wrapper">
     	<?php $hideTitleArr = array('location','otherAttributes');if(!in_array($containerId, $hideTitleArr)){?>
         <div class="loc-title">Select <?php echo $label;?></div>
        <?php }?>
         <ul id="<?php echo $containerId;?>-list-container" class="drpdwn-ul">
         <?php 
	        if($combinedView == 1){
				$index = 0;
			}
			else{
				$index = $existingData['hierarchyId'];
			}
			$fromContainer = $entity.'View';
			//this executes only when we get prefilled data on page load
			if(count($prefilledData[$fromContainer][$index][$entity]) > 0):
	        ?>
				<?php
				$countLoop = 1;
				foreach ($prefilledData[$fromContainer][$index][$entity] as $key => $value){
					if(!empty($value['name'])){
						if($countLoop == 1){
						?>
						<li>
						<div class="Customcheckbox">
						<?php } 
						$countLoop++; 
						$checked = '';
						foreach ($prefilledData[$fromContainer][$index]['selections'] as $k => $val) {
					 		if($val == $value['id']){
								$checked = "checked = 'checked'";
								$createtags[$value['id']] = $value['name'];
							}
						}
						?>
						<input id="<?php echo $containerId;?>-<?php echo $value['id'];?>" <?php echo $checked;?> type="checkbox" value= "<?php echo $value['id'];?>">
				        <label for="<?php echo $containerId;?>-<?php echo $value['id'];?>"><?php echo $value['name'];?></label>
			    <?php }} ?>
				</div>
			</li>
     		<?php endif; ?>
         </ul>
     </div>
 </div>
<?php 
$this->load->view('common/hierarchyForms/showSelectedValues', array('createtags'=>$createtags));
?>
