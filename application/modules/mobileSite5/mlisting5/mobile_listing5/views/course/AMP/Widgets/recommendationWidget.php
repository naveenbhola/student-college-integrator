 <!--smilar colleges-->
<?php 
        if(!empty($widgetHeading)){
            $heading = $widgetHeading;
            $gaEventName = "STUDENT_WHO_VIEWED";
        }else{
          if($widgetType == 'similar'){
            $heading = "Recommended Colleges";
            $gaEventName = "RECOMMENDED_COLLEGES";
          }else{
            $heading = "Students who viewed this ".$listing_type." also viewed the following";
            $gaEventName = "STUDENT_WHO_VIEWED";
          }
        }
$GA_Tap_On_Action = 'DBROCHURE_'.strtoupper($listing_type).'_'.strtoupper($widgetType);
if($listing_type=='course'){
	$fromwhere = 'coursepage';
  $height= '390px';
}else{
	$fromwhere = $listing_type;
  $height = '326px';
}
if(!empty($RecommendedListingData)){?>
         <section class="data-card">
             <h2 class="color-3 f16 heading-gap font-w6"><?=$heading;?></h2>
             <div class="card-cm">
                 <amp-carousel height="<?php echo $height;?>" width="auto"  type="carousel" class="s-c ga-analytic recmd-widget" data-vars-event-name="<?php echo $gaEventName;?>">
                <?php foreach($RecommendedListingData as $key=>$data){
			if($fromwhere=='coursepage'){
				$listingId = $data['course_id'];
			}else{
				$listingId = $data['institute_id'];
			}
		?>
                     <figure class="slide-fig color-w">
                        <a><amp-img src="<?=$data['image_url']?>" width=155 height=116 layout=responsive></amp-img></a>
                         <div class="pad-5">
                             <a href="<?=$data['institute_url']?>" class="caption color-3 f14 font-w6 m-5btm clg-tl"><?=substr(htmlentities($data['institute_name']),0,60)?></a>
                             <figcaption class="caption color-6 f12 font-w4 m-5btm clg-lc">
                                <?php if(!empty($data['establish_year']) || !empty($data['main_location'])){?>
                                    <?php if(!empty($data['main_location'])){ echo $data['main_location'];}           
                                                      if(!empty($data['establish_year']) && !empty($data['main_location'])){ echo ' | '.'Estd. '.$data['establish_year'];}else{
                                                        echo $data['establish_year'];
                                                      }
                                                ?>
                                  <?php } ?>
                              </figcaption>

                              <?php if($data['course_name']) {?>
                                    <a href="<?=$data['course_url']?>" class="caption color-3 f14 font-w6 clg-tl"><?php echo htmlentities(substr($data['course_name'],0,60))?></a>
                              <?php } ?>
			 <a href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?=$listing_type?>&listingId=<?=$listingId;?>&actionType=brochure&fromwhere=<?=$fromwhere;?>&pos=<?=$widgetType;?>" class="btn btn-primary color-o color-f f14 font-w7 m-top ga-analytic" title="<?=$data['institute_name'];?>" data-vars-event-name="<?=$GA_Tap_On_Action;?>">Apply Now</a>
                         </div>
                     </figure>
                <?php } ?>
                 </amp-carousel>
             </div>
         </section>
<?php } ?>
