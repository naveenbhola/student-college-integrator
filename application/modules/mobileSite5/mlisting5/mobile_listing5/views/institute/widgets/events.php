<?php
if(is_array($events) && count($events)>0){
        $i = 0;

        //Find the Media Images of all Events
        $eventImage = array();
        foreach ($mediaPhotos as $pic){
                $tags = $pic->getTags();
                foreach ($tags as $tag) {
                        if($tag['type'] == 'event'){
				if(!isset($eventImage[$tag['id']]['url'])){
	                                $eventImage[$tag['id']]['url'] = $pic->getUrl();
        	                        $eventImage[$tag['id']]['title'] = $pic->getTitle();
                                  $eventImage[$tag['id']]['mediaId'] = $pic->getId();
				}
                        }
                }
        }
?>

<!--College Events-->
<?php 
  $GA_Tap_On_Image = 'EVENT_IMAGE';
 $GA_Tap_On_View_All  = 'VIEW_ALL_EVENT';
  if($listing_type == 'university')
  {
      $GA_Tap_On_View_More = 'VIEW_MORE_EVENT_DESC_UNIVERSITYDETAIL_MOBLDP';
  }
  else
  {
      $GA_Tap_On_View_More = 'VIEW_MORE_EVENT_DESC_INSTITUTEDETAIL_MOBLDP';
  }
?>

<div class="crs-widget listingTuple" id="events">
        <h2 class="head-L2">College Events</h2>
        <div class="lcard">
            <ul class="evnt-List">
                <?php $i = 0; 
                if(count($events) > 2 )
                {
                  $paddingCls =  'paddingCls';
                }
		      foreach ($events as $event){
            $onClickEvent = '';
            $gaAttr = '';
                                if(isset($eventImage[$event->getEventId()])){
                                          $imgSrc = getImageVariant($eventImage[$event->getEventId()]['url'], '1');
                                          $alt = $title = htmlentities($instituteName)." - ".htmlentities($eventImage[$event->getEventId()]['title']);
                                          $onClickEvent = "onClick=\"openGalleryDetailLayer($listing_id,'".$listing_type."','".$eventImage[$event->getEventId()]['mediaId']."','Event','".$currentCityId."','".$currentLocalityId."');\"";
                                          $hrefAttr = '#galleryDetailList';
                                          $gaAttr = "ga-attr='".$GA_Tap_On_Image."'";
                                }else{
                                          $imgSrc = SHIKSHA_HOME."/public/images/eventDummyImage.png";
                                          $alt = $title = htmlentities($instituteName)." - ".htmlentities($event->getEventName());
                                          $hrefAttr = 'javascript:void(0)';
                                }
                ?>
		<?php if($i==2){echo "</ul><ul id='eventsText' style='display:none;border-top-color: #f1f1f1;border-top-style: solid; border-top-width: 1px;padding-top: 8px;' class='evnt-List'>";} ?>

                <li class="<?php if($i == 1) {echo $paddingCls;};?>" <?php if($i == count($events)-1){echo "style='margin-bottom:0px;border-bottom:none;'";} ?>>
                    <div class="evnt-fig">
                      <a href="<?php echo $hrefAttr;?>" is-href-url="false" data-rel="dialog" <?php if($onClickEvent != ''){echo $onClickEvent." style='cursor:pointer;'";} ?> <?php echo $gaAttr;?>>
                       <img data-original=<?=$imgSrc?> alt="<?=$alt?>" title="<?=$title?>" class="lazy"/>
                       </a>
                   </div>

                        <p class="head-L3"><?=htmlentities($event->getEventName())?></p>
                        <p class="para-L4"><?=cutStringWithShowMore($event->getEventDescription(),80,'event'.$i,'more','event')?></p>
                 </li>
		<?php
		$i++;
		} ?>
            </ul>
            <input type="hidden" name="gaActionName" id="gaActionName_event" value="<?php echo $GA_Tap_On_View_More;?>">
	    <?php if(count($events) > 2){ ?>
	    <a id="eventsViewAll" href="javascript:void(0);" class="link-blue-medium  v-more" ga-attr="<?=$GA_Tap_On_View_All;?>" onClick="showSection('events');">View all</a>
	    <?php } ?>
        </div>
</div>

<?php
}
?>
