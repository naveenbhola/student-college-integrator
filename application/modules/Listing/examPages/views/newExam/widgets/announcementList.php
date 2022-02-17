<?php
	if(isset($updateClass) && $updateClass !=''){
		$listClass = $updateClass;
	}else{
		$listClass = 'anmtlist';
	}
	
?>
<div class="fixed__height mtop__10 <?=$listClass?>">
	 <div class="right__space">
	  <?php foreach($updates['updateList'] as $key=>$val){?>
	    <div class="mt__10">
	       <p class="f14__clr3 mtop__5">
	       		<?php if(!empty($val['announce_url'])) {
	       				$followAttr = "";
	       				if(strpos($val['announce_url'], "shiksha.com") === FALSE){
	       					$followAttr = "rel='nofollow'";
	       				}
	       			?>
	       			<a href="<?=$val['announce_url'];?>" <?=$followAttr;?>><?=$val['update_text']?></a>
	       		<?php } else{
	       			echo $val['update_text'];
	       		  }
	       		?>
	       	</p>
	    </div>
	  <?php } ?>
	</div>
</div>