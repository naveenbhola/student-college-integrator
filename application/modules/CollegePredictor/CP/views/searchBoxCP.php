<input type="hidden" name="noStateDropDown" id ="noStateDropDown" value= "<?php echo $noStateDropDown ?>" />

	<?php if($tab == 1):?>
		<?php $this->load->view('CP/searchBoxTab1');?>	
	<?php elseif($tab == 2):?>
		<?php $this->load->view('CP/searchBoxTab2');?>
	<?php elseif ($tab == 3):?>
		<?php $this->load->view('CP/searchBoxTab3');?>
	<?php endif;?>
