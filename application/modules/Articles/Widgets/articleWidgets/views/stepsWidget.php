<div class="stepsToStudyMainCol">
	<h3><p>Steps to Study <?=$categoryName?> in <?=$pageName?></p></h3>
	<div class="clearFix"></div>
	<div class="stepsToStudy">
		<ul>
<?php
$stepsTypes = array('Exam Needed','College Selection','Applying to colleges','Preparing for interviews','Applying for loans','Applying for Visa','Check list before you leave');
$stepCount = 0;
foreach($stepsTypes as $steps){
	if($data[$steps] && count($data[$steps])){
		$stepCount++;
?>	

			<li class="active-step" style="display:none" id="open-<?=$stepCount?>" onmouseover="openSAStepsWidget('<?=$stepCount?>');" onmouseout="openSAStepsWidget(0);">
				<span class=""><?=$stepCount?></span>
				<div class="stepsDetail">
					<strong><?=titleCase($steps)?></strong>
					<ul>
						<?php
						foreach($data[$steps] as $content){
							$contentTitle = $content['blogTitle'];
							if(strlen($contentTitle)>33){
								$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 30))."...";
							}
						?>
						<li><a href="<?=$content['url']?>" target="_blank" title="<?=$content['blogTitle']?>"><?=$contentTitle?></a></li>
						<?php
						}
						?>
					</ul>
				</div>
				<div class="clearFix"></div>
			</li>
			
			<li id="closed-<?=$stepCount?>" onmouseover="openSAStepsWidget('<?=$stepCount?>');">
				<span><?=$stepCount?></span>
				<div class="stepsDetail"><a href="#"><?=titleCase($steps)?></a></div>
				<div class="clearFix"></div>
			</li>
<?			
}}
?>			
		</ul>
	</div>
</div>
<script>
function openSAStepsWidget(openDiv){
    for(var i=1;i<8;i++){
        if($('open-'+i)){
			if($('open-'+i).style.display != 'none')
				$('open-'+i).style.display = 'none';
			if($('closed-'+i).style.display == 'none')	
				$('closed-'+i).style.display = '';
        }
    }
    if($('closed-'+openDiv)){
        $('open-'+openDiv).style.display = '';
        $('closed-'+openDiv).style.display = 'none';
    }
}
</script>