<section>
             <div class="data-card m-5btm" id="reviews-li">
                 <h2 class="color-3 f16 heading-gap font-w6"><?php echo $reviewHeading;?> Reviews <span class="f12 font-w4 color-3">(Showing 2 of <?php echo $totalReviews;?> reviews)</span></h2>
                 <div class="card-cmn color-w">
                     <ul class="d">
		<?php
		 $j = 0;
                    foreach ($reviewsData as $reviewRow) {
                        $j = $j+1;
                        $userName = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));
                        if($reviewRow['yearOfGraduation'])
                            $batch = "Batch of ".$reviewRow['yearOfGraduation'];

                        $reviewTitle = trim($reviewRow['reviewTitle']);

                        $reviewSegments = array();
                        $totalSegmentslen = 0;
                        if(trim($reviewRow['placementDescription'])){
                            $reviewSegments['Placements'] = $reviewRow['placementDescription'];
                            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Placements']);
                        }
                        if(trim($reviewRow['infraDescription'])){
                            $reviewSegments['Infrastructure'] = $reviewRow['infraDescription'];
                            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Infrastructure']);
                        }
                        if(trim($reviewRow['facultyDescription'])){
                            $reviewSegments['Faculty'] = $reviewRow['facultyDescription'];
                            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Faculty']);
                        }
                        if(trim($reviewRow['reviewDescription']) && ($reviewRow['placementDescription'] != '' || $reviewRow['infraDescription'] != '' || $reviewRow['placementDescription'] != '')){
                            $reviewSegments['Other'] = $reviewRow['reviewDescription'];
                            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Other']);
                        }else{
                            $reviewSegments['Description'] = $reviewRow['reviewDescription'];
                            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Description']);
                        }
			$showDescLen = 250;
        		$minCharPerSection = 40;
	          	$showEllipses = '...';
			?>
                         <li>
                             <div class="rvw-card">
                                 <p class="m-5btm color-3 f17 font-w6"><?php echo $userName;?> | <span class="color-3 f12 font-w4"><?php echo $batch;?></span></p>
                                 <div class="m-3btm pos-rl">
                                     <span class="f12 color-6 font-w4">Rating:</span>
                                     <span class="bck1 color-3 pad5 f13 font-w6" on="tap:view-review<?php echo $reviewRow['id'];?>" role="button" tabindex="0"><?php echo number_format($reviewRow['averageRating'], 1, '.', '');?>/ <span class="total">5</span> <i class="open-tick cmn-sprite "></i></span>
                                     <span class="color-9"> | </span>
                                     <span class="i-block v-mdl f13 color-3 font-w4">
				     <?php if($reviewRow['recommendCollegeFlag'] == 'YES'){?>
				     <i class="green-ico cmn-sprite v-mdl"></i>Recommended
				     <?php }else{ ?>
				     <i class="red-ico cmn-sprite v-mdl"></i>Not Recommended
				     <?php } ?>
				     </span>
                                 </div>
                                 <input type="checkbox" class="v-more-st" id="post-2" />
                                 <div class="f13 color-3 l-18 font-w4 v-wrap">
				<?php if($reviewTitle){ ?>
		                    <strong class="block"><?php echo nl2br(htmlentities($reviewTitle));?></strong>
                    		<?php }

				  foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                                  	if($showDescLen>0){?>
                                        <?php if($reviewSegmentName != 'Description'){ ?>
                                            <strong><?php echo $reviewSegmentName;?>: </strong>
                                        <?php
                                             }
                                            echo nl2br(htmlentities(substr($reviewSegmentText,0,$showDescLen)));
                                            $remainingLen =  $totalSegmentslen-$showDescLen;
                                            $showDescLen = $showDescLen - strlen($reviewSegmentText);
                                            if($showDescLen>0 && $showDescLen<$minCharPerSection && $remainingLen>$minCharPerSection){
                                                $showDescLen = $minCharPerSection;
                                            }
                             		}
                         	  }
				  if($remainingLen>0){
                            		echo $showEllipses;
                            	   ?>
				   <a class="f13 color-b ga-analytic" data-vars-event-name="REVIEWS_CONTENT_MORE" href="<?php echo $all_review_url.'#id='.$reviewRow['id'].'&seqId='.$j;?>" >more</a>
                                  <?php } ?>
                                 </div>
                             </div>
                         </li>
			 <!--amp view aminities lighthbox-->

			<?php } ?>
                     </ul>

            <?php foreach ($reviewsData as $reviewRow) {?>
            <amp-lightbox class="" id="view-review<?php echo $reviewRow['id'];?>" layout="nodisplay">
                <div class="lightbox">
                  <a class="cls-lightbox f25 color-f font-w6" on="tap:view-review<?php echo $reviewRow['id'];?>.close" role="button" tabindex="0">&times;</a>
                  <div class="m-layer">
                    <div class="min-div colo-w">
                    <div class="pad10  rvw-fix color-w">
                        <ol>
                         <?php foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){?>
                         <li class="v-mdl color-3 f12 pos-rl l-20">
                           <label class="v-mdl f13 color-3 i-block"><?php echo $desc;?></label>
                           <p class="star-r i-block v-mdl">
                             <span class="star-rating i-block v-mdl">
                            <?php for($i = 0;$i<$rating;$i++){?>
                             <span class="rating-primary full f-lt"></span>
                            <?php } ?>
                            <?php for($i = 0;$i<5-$rating;$i++){?>
                             <span class="rating-primary none f-lt"></span>
                            <?php } ?>
                             </span>
                           </p>
                         </li>
                         <?php } ?>
                        </ol>
                    </div>
                  </div>
                </div>
                </div>
           </amp-lightbox>
            <?php } ?>
		     <?php if($totalReviews>2){?>
                     <a class="btn btn-ter color-w color-3 f14 font-w6 m-15top ga-analytic" href="<?php echo $all_review_url;?>" data-vars-event-name="COURSE_REVIEW_VIEWALL">View all <?php echo $totalReviews;?> Reviews</a>
		     <?php } ?>
                 </div>
             </div>
         </section>


