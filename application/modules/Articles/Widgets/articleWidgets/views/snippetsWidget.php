<?php if($showArticle == 'YES'): ?>
<div id="SACarouselBox" uniqueattr="StudyAbroadPage/topWidget" style="overflow:hidden;">
	<div id="royalSlider_new" class="royalSlider rsDefault" style="width:435px;">	
	<?php
		$count = 0;
		foreach($data['top'] as $content){
		$length = 135;
                $length1 = 30;
                $summary_new = trim($content['summary']);
                $title_new = trim($content['blogTitle']);
		$title_new_to_show = trim($content['blogTitle']);
		if(strlen($summary_new) > $length){
			$summary = new tidy();
			$summary->parseString(wordLimiter(substr($content['summary'],0,$length),$length),array('show-body-only'=>true),'utf8');
			$summary->cleanRepair();
			$summary.="...";
		}else{
			$summary = new tidy();
			$summary->parseString($content['summary'],array('show-body-only'=>true),'utf8');
			$summary->cleanRepair();
		}
                if(strlen($title_new)>$length1) {
			$title_new = wordLimiter(substr($title_new,0,$length1),$length1);
                        $title_new.="...";
                }
	?> 
        <div class="rsContent"> 
	<div class="SACarouselImgCol"  id="satopimage-<?=$count?>"><a href="<?=$content['url']?>" target="_blank"><img uniqueattr="Studyabroad-Left-Article" src="<?=$content['image_url']?>"  width="349" height="297" /></a></div>
	<div class="SACarouselDetailsCol" id="satopcontent-<?=$count?>">
    	<div style="height:118px;">
		<h2><a uniqueattr="Studyabroad-Left-Article" href="<?=$content['url']?>" title="<?=$title_new_to_show?>" target="_blank"><?=$title_new ?></a></h2>
		<p style="cursor:pointer;" onclick="window.open('<?=$content['url']?>')"><?=$summary?></p>
        </div>
		<p class="knowMore"><a uniqueattr="Studyabroad-Left-Article" href="<?=$content['url']?>" target="_blank">Know more...</a></p>
	</div>
        </div>
	<?php
                $count++;
		}
	?>
   </div>
	<div class="clearFix"></div>
</div>
<?php endif;?>
<div class="flRt"><a href="javascript:void(0);" uniqueattr="Studyabroad-Help-Widget" onclick="showRegisLayer();"> <img width="207" height="151" src="<?php echo $help_image;?>" alt="" /></a></div>
<script>
var user_logged_in = '<?php echo $user_logged_in;?>';
function showRegisLayer() {
        if(user_logged_in == 'TRUE') {
		displayMessage("/categoryList/CategoryList/showAnawidget",500,380);
                return;
        }
        var testData = {"layerTitle" :'<?php echo $registrationLayerTitle ;?>' , "layerHeading" : '<?php echo $registrationLayerMsg; ?>', "callback" : function() {window.location.reload(true)},"source": "Abroad-help-widget","referer":"https://www.shiksha.com#Abroad-help-widget"};
	shikshaUserRegistration.showRegisterFreeLayer(testData);
}
</script>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("anythingSlider"); ?>" type="text/css" rel="stylesheet" />
