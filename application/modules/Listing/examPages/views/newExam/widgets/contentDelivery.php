<?php
$countOfContentDelivery = count($info);
$findString = '.shiksha.com';
if($countOfContentDelivery==1){ ?>

	<div class="dflt__card mt__15 ps__rl global-box-shadow">
	<?php foreach($info as $key=>$value){ 
		$checkShikshaUrl = strpos($value['redirection_url'], $findString);
		if($checkShikshaUrl === false){
			$addNoFollow = 'rel="nofollow"';
		}else{
			$addNoFollow = '';
		}
		?>
	  	<div class="promtn__widget">
	    	<p class="f16__clr3 fnt__sb"><?php echo htmlentities($value['heading']); ?></p>
	    	<p class="mtop__10 f14__clr6"><?php echo htmlentities($value['body']); ?></p>
	  	</div>
		<a class="blue__brdr__btn ps__abs right__pos cd-wdt" cd_attr = "<?php echo $value['id'];?>" href="<?php echo $value['redirection_url'];?>" target="_blank" ga-attr="CD_LINK" <?=$addNoFollow?> ><?php echo htmlentities($value['CTA_text']); ?></a>
	<div class="cd-sponsored">Sponsored</div>
	</div>
	<?php }
	}else{ ?>

	<div class="dflt__card dmy-crd mt__15 global-box-shadow clear__space">
	<?php foreach($info as $key=>$value){ 
		$checkShikshaUrl = strpos($value['redirection_url'], $findString);
		if($checkShikshaUrl === false){
			$addNoFollow = 'rel="nofollow"';
		}else{
			$addNoFollow = '';
		}
		?>
	    <div class="dmy-dv">
	        <div class="promtn__widget">
	        <p class="f16__clr3 fnt__sb"><?php echo htmlentities($value['heading']); ?></p>
	        <p class="mtop__10 f14__clr6 cd_<?php echo $key;?>"><?php echo htmlentities($value['body']); ?></p>
	      </div>
	        <a class="blue__brdr__btn right__pos mtop__10 cd-wdt" cd_attr = "<?php echo $value['id'];?>" href="<?php echo $value['redirection_url'];?>" target="_blank" ga-attr="CD_LINK" <?=$addNoFollow?>  ><?php echo htmlentities($value['CTA_text']); ?></a>
	    </div>
	<?php } ?>	
	<div class="cd-sponsored case-2">Sponsored</div>
	<?php if($countOfContentDelivery==1){ ?>
	<div class="clear__space"></div>
	<?php } ?>
	</div>
	<script>
	var cdFlag = 'true';
	</script>
<?php }
?>
