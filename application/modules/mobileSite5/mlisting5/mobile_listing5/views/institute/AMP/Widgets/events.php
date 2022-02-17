<?php

$GA_Tap_On_Image = 'EVENT_IMAGE';
$GA_Tap_On_View_All  = 'VIEW_ALL_EVENT';
$GA_Tap_On_View_More = 'VIEW_MORE_EVENT_DESC';


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
<!--college events-->
		 <section>
		  <div class="data-card m-5btm">
		    <h2 class="color-3 f16 heading-gap font-w6">College Events</h2>
			 <div class="card-cmn color-w">
			 <input type="checkbox" class="read-more-state-out hide" id="post-evnt" />
			    <ul class="sc-ul clg-ev-ul read-more-wrap">
				 <?php 
				 $i = 0;
                foreach ($events as $event){
                	$i++;
                                if(isset($eventImage[$event->getEventId()])){
                                          $imgSrc = getImageVariant($eventImage[$event->getEventId()]['url'], '1');
                                          $alt = $title = htmlentities($instituteName)." - ".htmlentities($eventImage[$event->getEventId()]['title']);
                                          $tapAttribute = 'on="tap:lightbox11"';
                                          $gaClassName = 'ga-analytic';
                                          $dataAttribute = 'data-vars-event-name="'.$GA_Tap_On_Image.'"';
                                }else{
                                          $imgSrc = SHIKSHA_HOME."/public/images/eventDummyImage.png";
                                          $alt = $title = htmlentities($instituteName)." - ".htmlentities($event->getEventName());
                                          $tapAttribute = '';
                                          $gaClassName = '';
                                          $dataAttribute = '';
                                }
                ?>
				  <li class="<?=($i>2?'read-more-target-out':'')?>" >
				    <div class="clgeve-img">
            			<amp-img class="no-out <?=$gaClassName?>" <?=$tapAttribute;?> role="button" tabindex="0" src="<?=$imgSrc?>" alt="<?=$alt?>" width="68" height="58" <?=$dataAttribute;?> ></amp-img>
					</div>
					<p class="m-5btm color-3 f14 font-w7"><?=htmlentities($event->getEventName())?></p>
					             <input type="checkbox" class="read-more-state hide" id="event<?php echo $event->getEventId();?>">

					<p class="read-more-wrap word-break"><?=CutStringWithShowMoreInAMP($event->getEventDescription(),80,'event'.$event->getEventId(),'more',true,false,$GA_Tap_On_View_More)?>				   
					</p>
					
				  </li>
				<?php
                		} ?>
				</ul>
				<?php 
				if(count($events) > 2){ ?>
                   <label for="post-evnt" class="read-more-trigger t-cntr color-b f14 color-b block ga-analytic font-w6 v-arr" data-vars-event-name="<?=$GA_Tap_On_View_All;?>">View all</label>
				<?php } ?>
			 </div>
		  </div>
		 </section>

<?php } ?>
