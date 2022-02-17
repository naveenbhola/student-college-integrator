<?php
$isDownloadable = $content['data']['is_downloadable'];
$downloadUrl = $content['data']['download_link'];
$contentId = $content['data']['content_id'];
$type = $content['data']['type'];
?>
<div class="guide-navigation" id="jump_section" style="display: none;">
  <!-- <i class="article-sprite guide-arrow"></i> -->
  <p class="guideTitle">Guide Menu</p>
    <p class="jump-title">JUMP TO SECTION</p>
    <div class="scrollbar1" id="sections_guide">
    	<div class="scrollbar" id="scroller">
        	<div class="track">
            	<div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:175px;">
        	<div class="overview">
            	<ul>
            		<?php foreach ($content['data']['sections'] as $index => $data):?>
				<?php $heading = trim($data['heading']);
				      $heading =  substr($heading,0,180); ?>
				<?php if(strlen(trim($data['heading']))>180) { $heading = $heading.'...';}?>
            		<li><a href="javascript:void(0);" onclick="navigate('section_<?php echo $index ?>'); studyAbroadTrackEventByGA('ABROAD_GUIDE_PAGE', 'layerSection');"><?php echo strip_tags($heading);?><i class="article-sprite nav-arr"></i></a></li>
            		<?php endforeach;?>

        		</ul>
            </div>
        </div>
    </div>
    <?php if($downloadUrl!='' && $isDownloadable!= 'no'):
          $downloadUrl=base64_encode($downloadUrl); ?>
    <div class="mL20" style="margin-top:10px;">
    	<a  uniqueattr = "SA_SESSION_ABROAD_GUIDE_PAGE/downloadGuide" href="javascript:void(0);" onclick="directDownloadORShowTwoStepLayer('<?=$downloadUrl;?>','<?=$contentId;?>','<?=$totalGuideDownloded;?>',11,'new','downloadGuide','<?=$type;?>');" class="button-style" style="padding:8px 28px; font-weight:bold; border-radius:0"><i class="article-sprite guide-dwnld"></i>
	Download this Guide
	</a>
        <!--p class="file-size">file size: <?=$size;?></p-->
	<div class="font-12" id="leftButton" style="margin-top:8px;color:#666<?php if($totalGuideDownloded<50){ ?>display:none;<?php } ?>" id="rightButton"><?php if($totalGuideDownloded>=50){ ?><?php echo $totalGuideDownloded." People downloaded this guide"; ?><?php } ?></div>
    </div>
    </div>
    <?php endif;?>
