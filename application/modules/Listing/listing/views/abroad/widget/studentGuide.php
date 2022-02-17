<div class="course-detail-tab studentGuide-tab">
    <div class="course-detail-mid flLt">
        <div class="clearfix">
            <div  class="overview-details">
                <h2>Student guides available for free download</h2>
                <div class="cons-scrollbar1 scrollbar1 clearwidth" style="width:735px;">
                    <div class="cons-scrollbar scrollbar" style="visibility:hidden;">
                        <div class="track">
                            <div class="thumb"></div>
                        </div>
                    </div>
                    <div class="viewport" style="height:290px">
                        <div class="overview" style="width:98%;">
                        <?php
                            foreach($studentGuide as $guideData){
                        ?>
                            <div class="student-guide-sec clearfix">
                                <div class="flLt">
                                    <!--<i class="listing-sprite stu-guide-fig"></i>-->
                                    <a href="<?=$guideData['contentURL']?>" ><img class="lazy" title="<?=$guideData['strip_title']?>" alt="<?=$guideData['strip_title']?>" src="" data-original="<?=getImageUrlBySize($guideData['contentImageURL'],'172x115')?>" /></a>
                                </div>
                                <div class="stu-guide-detail">
                                    <a href="<?=$guideData['contentURL']?>" style="font-weight:bold;" class="font-13"><?=$guideData['strip_title']?></a>
                                    <p><?=$guideData['summary']?></p>
                                    <a href="javascript:void(0);" onclick="downloadStudentGuide('<?=  base64_encode($guideData['download_link'])?>','<?=$guideData['content_id']?>','<?=formatArticleTitle(html_entity_decode($guideData['strip_title']),30)?>',43,'new','downloadGuide','guide');" class="gray-download-guidebtn">
                                        <span class="font-14">Download Guide</span>
                                    </a>
                                    <?php if(isset($guideData['downloadCount'])){?>
                                        <div class="font-12"><?=$guideData['downloadCount']?> people downloaded this guide</div>
                                    <?php }?>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
