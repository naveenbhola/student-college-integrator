<?php
$firstStudentGuide = reset($studentGuide);
?>
<div class="process-box studentGuideRightWidget clearwidth">
    <h2 class="font-14"><a href="<?=$firstStudentGuide['contentURL']?>"><?=  htmlentities($firstStudentGuide['strip_title'])?></a></h2>
    <img align="middle" alt="guide" src="//<?php echo IMGURL;?>/public/images/guide-icon2.jpg">

    <div class="details clearwidth">
    	<div class="scrollbar1 fat-scrollbar1">

            <div class="scrollbar">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>

            <div style="width:94%; height:415px;" class="viewport">
                <div class="overview">
                    <ul class="updated-student-guide-list">
                        <?php
                                $i = 0;
                                foreach ($studentGuide as $studentGuideData){
                        ?>
                                    <li>
                                        <?php if(++$i > 1){
                                        ?>
                                            <a href="<?=$studentGuideData['contentURL']?>"><?=  htmlentities($studentGuideData['strip_title'])?></a>
                                        <?php   }?>
                                        <p style="font-size:14px !important"><?=  ($studentGuideData['summary'])?></p>
                                        <a style="margin:0px 0 3px 0;" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="downloadStudentGuide('<?=  base64_encode($studentGuideData['download_link'])?>','<?=$studentGuideData['content_id']?>','<?=formatArticleTitle(html_entity_decode($studentGuideData['strip_title']),30)?>',7,'new','downloadGuide','guide');">
                                            <i class="listing-sprite pdf-icon"></i>
                                            <span class="font-12" style="font-weight:bold;">Download Student Guide</span>
                                        </a>
                                        <?php
                                            if(isset($studentGuideData['downloadCount'])){
                                        ?>
                                                <div class="font-10"><?=$studentGuideData['downloadCount']?> people downloaded this guide</div>
                                        <?php   }
                                        ?>
                                    </li>
                        <?php   }
                        ?>
                    </ul>
		        </div>
            </div>
        </div>
    </div>

</div>
