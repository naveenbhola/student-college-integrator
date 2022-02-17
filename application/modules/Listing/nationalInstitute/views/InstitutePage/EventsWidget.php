<?php
if(is_array($events) && count($events)>0){
	$i = 0;

	//Find the Media Images of all Events
	$eventImage = array();
	foreach ($mediaPhotos as $pic){
		$tags = $pic->getTags();
		foreach ($tags as $tag)	{
			if($tag['type'] == 'event'){
				if(!isset($eventImage[$tag['id']]['url'])){
					$eventImage[$tag['id']]['url'] = $pic->getUrl();
					$eventImage[$tag['id']]['title'] = $pic->getTitle();
					$eventImage[$tag['id']]['tagName'] = $tag['name'];
					$eventImage[$tag['id']]['mediaId'] = $pic->getId();
				}
			}
		}
	}
?>

<?php 
      $GA_Tap_On_Image = 'EVENT_IMAGE';
      $GA_Tap_On_View_More = 'VIEW_MORE_EVENT_DESC';
?>

<!--College Events-->
    <div class="new-row">
      <div class="group-card no__pad gap clear listingTuple" id="events">
         <h2 class="head-1 gap">College Events</h2> 
         <div class="events">
             <ul class="flex-ul">
			<?php foreach ($events as $event){
				$onClickEvent = $gaAttr = '';
				if(isset($eventImage[$event->getEventId()])){
                                                        $imgSrc = getImageVariant($eventImage[$event->getEventId()]['url'], '3');
							$alt = $title = htmlentities($instituteName)." - ".htmlentities($eventImage[$event->getEventId()]['title']);
							$onClickEvent = "onClick=\"openGalleryLayer($listing_id,'".$listing_type."','Event','".$eventImage[$event->getEventId()]['mediaId']."',$currentCityId,$currentLocalityId);\"";
							$gaAttr = "ga-attr='$GA_Tap_On_Image'";
							
                                                }else{
                                                        $imgSrc = SHIKSHA_HOME."/public/images/eventDummyImage.png";
							$alt = $title = htmlentities($instituteName)." - ".htmlentities($event->getEventName());
                                                }
			 ?>
                              <li>
                                <div class="lcard clg-evnt">
                                    <img data-original=<?=$imgSrc?> alt="<?=$alt?>" title="<?=$title?>" class="lazy" <?php if($onClickEvent != ''){echo $onClickEvent." style='cursor:pointer;'";} ?> <?=$gaAttr?> />
                                        <p class="head-L3"><?=htmlentities($event->getEventName())?></p>
                                        <p class="para-L3"><?=cutStringWithShowMore($event->getEventDescription(),200,'event'.$i,'Read More','event')?></p>
                                </div>
                              </li>
			<?php 
				$i++;
				} ?>
              </ul>
         </div>
       </div>
    </div>     
    <input type="hidden" name="gaActionName" id="gaActionName_event" value="<?php echo $GA_Tap_On_View_More;?>">
<?php } ?>
