<?php if($courseComplete->getDescriptionAttributes()){ ?>
<div class="desc-details-wrap">

	<div id="courseDesc" onclick="$j('body,html').animate({scrollTop:$j('#courseDesc').offset().top - 70},500);">
<?php if($pageType == 'institute') {
	ob_start(); 
}
?>
		<ol>
	<?php
			foreach($courseComplete->getDescriptionAttributes() as $attribute){ 
		?>
                <li>
                    <?php
						$contentTitle = $attribute->getName();
						if(strlen($contentTitle)>33){
							$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 30))."...";
						}
					?>
					<h2><span title="<?=$attribute->getName()?>"><?=$contentTitle?></span></h2>
                    <div>
                        <h3><?=$attribute->getName()?></h3>
                        <div class="user-content">
						<?php
							$summary = new tidy();
							$summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
							$summary->cleanRepair();
						?>
						<?=$summary?>
                        </div>
                    </div>
                </li>
	<?php
		}
	?>
	
		</ol>
<?php if($pageType == 'institute') {
 $pageContent = ob_get_contents();
  ob_end_clean();
?>
<script>
	$('courseDesc').innerHTML = base64_decode("<?=base64_encode($pageContent)?>");
</script>
<?php
}
?>
    </div>
</div>
<?php } ?>
<?php if($instituteComplete->getDescriptionAttributes()){ ?>
<div class="desc-details-wrap">
	<div id="instituteDesc" onclick="$j('body,html').animate({scrollTop:$j('#instituteDesc').offset().top - 70},500);">
<?php if($pageType == 'course') {
	ob_start(); 
}
?>
		<ol>
	<?php
			foreach($instituteComplete->getDescriptionAttributes() as $attribute){ 
		?>
                <li>
                    <?php
						$contentTitle = $attribute->getName();
						if(strlen($contentTitle)>43){
							$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 40))."...";
						}
					?>
					<h2><span title="<?=$attribute->getName()?>"><?=$contentTitle?></span></h2>
                    <div>
                        <h3><?=$attribute->getName()?></h3>
                        <div class="user-content">
						<?php
							$summary = new tidy();
							$summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
							$summary->cleanRepair();
						?>
						<?=$summary?>
                        </div>
                    </div>
                </li>
	<?php
		}
	?>
	
		</ol>
		
<?php if($pageType == 'course') {
$pageContent = ob_get_contents();
  ob_end_clean();
?>
<script>
	$('instituteDesc').innerHTML = base64_decode("<?=base64_encode($pageContent)?>");
</script>
<?php
}
?>
	</div>
</div>
<?php } ?>
		
