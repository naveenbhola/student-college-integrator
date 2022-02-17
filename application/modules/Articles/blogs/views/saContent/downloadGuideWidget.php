<?php if(!empty($widgetData)){
?>

<div class="newExam-widget clearwidth">
    <h2>
            <div class="widget-head"><p>You might be interested in <i class="common-sprite blue-arrw"></i></p></div>
     </h2>
    <div class="new-slider-box">
       <a class="ui-arrow lft-arrw counselorLeft"><i class="prv-disbl"></i></a>
     <div class="r-cards">
        <ul class="counslrs-list" style="width:10000px;position:relative;margin-left:0px;">
        <?php foreach($widgetData as $key=>$data){?>
           <li>
                <div class="student-guide-sec clearfix">
                    <div class="flLt">
                        <a href = "<?php echo $data['contentURL']; ?>">
                          <img class="lazy" title="<?php echo htmlentities($data['strip_title']); ?>" alt="<?php echo htmlentities($data['strip_title']); ?>" src="<?php echo str_replace("_s","_172x115",$data['contentImageURL']); ?>" data-original="<?php echo $data['contentImageURL']; ?>" style="display: inline;">
                        </a>
                    </div>
                    <div class="stu-guide-detail">
                        <a href="<?php echo $data['contentURL']; ?>" style="font-weight:600;" class="font-14"><?php echo htmlentities(formatArticleTitle($data['strip_title'],90)); ?></a>
                        <p><?php echo formatArticleTitle(strip_tags($data['summary']),115);?></p>

                        <span class="sop-commnt-title new-txt-commit"><i class="sop-sprite gray-commnt-icon"></i>
                           <?php if($data['commentCount']>0){ ?>
                           <?php echo $data['commentCount']; if($data['commentCount']>1)echo" comments"; else echo " comment"; }
                           if($data['commentCount']>0 && $data['viewCount']>0)echo ' | ';
                           if($data['viewCount']>0){
                           ?>
                            <?php echo $data['viewCount'];if($data['viewCount']>1)echo" views"; else echo " view"; ?>
                           <?php } ?>
                            </span>
                        <a class="gray-download-guidebtn" onclick ="directDownloadORShowTwoStepLayer('<?php echo base64_encode($data['download_link'])?>','<?php echo $data['content_id'] ?>','<?php echo $data['downloadCount'];?>','1242','new','downloadGuide','<?php echo $data['type'];?>');">
                            <span class="font-14" >Download Guide</span>
                        </a>
                        <?php if($data['downloadCount']>0){?>
                        <p class="clr-9"><?php echo $data['downloadCount'];?> people downloaded this guide</p>
                        <?php } ?>
                    </div>
                </div>
            </li>
        <?php } ?>
        </ul>
</div>
        <a class="ui-arrow rgt-arrw counselorRight"><i class="nxt-disbl"></i></a><a class="ui-arrow rgt-arrw counselorRight"><i class="nxt"></i></a>
    </div>

<div class="indications">
  <ul>
    <li><a class="dot-pt active"></a></li>
     <li><a class="dot-pt"></a></li>
     <li><a class="dot-pt"></a></li>
  </ul>
</div>
</div>

<?php } ?>
