<?php if(!empty($widgetData)){
?>
  <section class="m-slider">
  <div class="bottom-slider newExam-widget article-slider">
    <h2>
      <div class="widget-head">
        <p>You might be intrested in<i class="sprite blue-arrw"></i></p>
      </div>
    </h2>
    <div class="slider-box article-box" id="guideSliderWrapper">
      <ul id="guideUl" style="width: 10000px; left: -233px;" class="sliderUl">
      <?php foreach($widgetData as $key=>$data){
        $commentViewCountString  = '';
        if($data['commentCount'] > 0)
        {
            $commentViewCountString = $data['commentCount'].' comment';
          $commentViewCountString .= ($data['commentCount'] > 1 ? 's':'');
        }
        if($commentViewCountString != '' && $data['viewCount'] > 0)
        {
            $commentViewCountString .= ' | ';
        }
        if($data['viewCount'] > 0)
        {
            $commentViewCountString .= $data['viewCount'].' view';
            $commentViewCountString .= ($data['viewCount'] > 1 ? 's':'');
        }
        $classDLCount = "dlCount".$data['content_id'];
        if($data['downloadCount'] >= 50){
            $hideNumber = false;
        }else{
            $hideNumber = true;
        }
      ?>
       <li class="trendtuple" style="height: 400px">
          <div class="box-cube">
             <div class="inner-pad">
                <p>
                  <a href="<?php echo $data['contentURL']; ?>" ><?=(formatArticleTitle(htmlentities($data['strip_title']),40))?></a>
                </p>
                <div class="img-sec">
                  <a href="<?=($data['contentURL'])?>">
                   <img class="lazy" src="" data-src="<?=resizeImage($data['contentImageURL'],'172x115')?>" alt="<?=(htmlentities($data['strip_title']))?>" width="172" height="115"> 
                  </a>
                </div>
            </div>
            <div class="inf-article">
               <p class="inf-p"><?=(formatArticleTitle(strip_tags($data['summary']),120))?></p>
               <p class="comment-view-nfo">
                    <?php if($commentViewCountString!=''){ ?>
                      <i class="mobile-sop-sprite sop-comment-icon"></i>
                      <?=$commentViewCountString?> 
                    <?php } ?>
                </p>
               <a href="javascript:void(0)" dlGuideTrackingId="1247" widgetContentId="<?=($data['content_id'])?>" articleTagWidget="true" type="<?=($data['type'])?>" guideURL="<?=($data['download_link'])?>" class="dlGuideInline btn btn-default btn-full ui-link"> 
                 <i class="sprite bro-icn"></i>Get this guide
              </a>
              <span style="<?=($hideNumber)?'display:none;':''?>" class="people-count"><span id="contentDownloadCountSticky" class="<?=$classDLCount;?>"><?php echo $data['downloadCount']; ?></span> people downloaded this guide</span>
            </div>
          </div>
        </li>   
      <?php } ?>
      </ul>
    </div>
  </div>
<?php } ?>