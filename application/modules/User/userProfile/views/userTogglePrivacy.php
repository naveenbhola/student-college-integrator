<div class="right-sec">
    <em><a href="javascript:void(0);">
    	<?php if($publicFlag == true) { ?>
           <i class="icons1 ic_view" title="Visibility: public" onclick="togglePrivacy(this,'<?php echo $userId; ?>' ,<?php echo $privacyFields;?>);"></i>
      	<?php }else{?>     
        	<i class="icons1 ic_none" title="Visibility: private" onclick="togglePrivacy(this,'<?php echo $userId; ?>' ,<?php echo $privacyFields;?>);"></i>         
      	<?php }?>
    </a></em>
</div>