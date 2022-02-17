<section>
	<?php 
	    
	    if($listing_type =='university' || $listing_type=='institute'){
	      $reviewHeading = 'College'; 
	    }   
	    else{
	      $reviewHeading = 'Course'; 
	    }
	   $showAggregateWidget = true;


	   if(empty($aggregateReviewsData)){
	     $showAggregateWidget = false;  
	   }

	   if(isset($isPaid) && $isPaid && $aggregateReviewsData['aggregateRating']['averageRating']<3.5){
	      $showAggregateWidget = false;  
	   } 


	?>

	<div class="data-card m-5btm " id="reviews-li">
	  <h2 class="color-3 f16 heading-gap font-w6"><?php echo $reviewHeading; ?> Reviews<span class="f12 font-w4 color-3"> (Showing <?php echo $reviewShowing; if(!$allCoursePage){ echo ' of '.$totalReviews;} ?> review<?php if($totalReviews>1) echo 's'; ?>)</span></h2>
	  <?php 
	    if($showAggregateWidget){
	        ?>
	        <div class="card-cmn color-w m-15btm">
	            <?php $this->load->view('mobile_listing5/overallReviewRatingWidgetAmp'); ?>
	        </div>
	        <?php
	    }
	  ?>
	  <div class="card-cmn color-w">
	  <?php
	      $j = 0;
	      foreach ($reviewsData as $reviewRow) {
	        $j++;
	        $lastChild ='';
	        if($j == count($reviewsData)){
	          $lastChild ='last-child';
	        }
	        $userDetails = array();
	        $userDetails['userName'] = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));

	        if($reviewRow['yearOfGraduation'] && $courseInfo[$reviewRow['courseId']]['courseName']){
	          $userDetails['batchInfo'] = " - Batch of ".$reviewRow['yearOfGraduation'];
	        }
	        else{
	          $userDetails['batchInfo'] = "Batch of ".$reviewRow['yearOfGraduation'];
	        }


        	$allReviewUrl  = $allCoursePage ? $courseInfo[$reviewRow['courseId']]['course_review_url'] : $all_review_url;
	        $showDescLen = 450;
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
	        
	        $minCharPerSection = 10;
	        $showEllipses = '...';

	        $ratingBar = $reviewRow['averageRating']*100/count($reviewRating[$reviewRow['id']]);
	        ?>

	 <div class="group-card gap pad-off <?php echo $lastChild;?>" >
	      <div class="rvwv1Heading">
	        <div>
	          <div class="new_rating">
	            <span class='rating-block' on="tap:view-review<?php echo $reviewRow['id'];?>" role="button" tabindex="0">
	              <?php echo number_format($reviewRow['averageRating'], 1, '.', ''); ?>
	              <i class="empty_stars starBg">
	                <i class="full_starts starBg <?php echo 'w-'.$ratingBar; ?>"></i>
	              </i>
	              <b class="icons bold_arw"></b>  
	            </span>
	            <span><?php echo nl2br(htmlentities($reviewRow['reviewTitle']));?></span>
	             <amp-lightbox class="" id="view-review<?php echo $reviewRow['id'];?>" layout="nodisplay">
	                 <div class="lightbox">
	                   <a class="cls-lightbox f25 color-f font-w6" on="tap:view-review<?php echo $reviewRow['id'];?>.close" role="button" tabindex="0">&times;</a>
	                   <div class="m-layer">
	                     <div class="min-div colo-w">
	                     <div class="pad10  rvw-fix color-w">
	                         <ol>
	                         <?php 
	                         	foreach ($aggregateRatingDisplayOrder as $ratingName => $displayName) {
	                         		foreach($reviewRating[$reviewRow['id']] as $ratingId => $rating){
	                         			if($ratingName == $crMasterMappingToName[$ratingId]){
	                         				$ratingBar = $rating*20;
	                         				?>
	                         				<div class="table_row">
												<div class="table_cell">
	                         						<p class="rating_label"><?php echo $displayName;?> </p>
	                         					</div>
	                         					<div class="table_cell">
	                         						<div  class="loadbar"><div class="fillBar <?php echo 'w-'.$ratingBar; ?>"></div></div>
	                         					    <b class="bar_value"><?php echo $rating; ?></b>
	                         					</div>
	                         				</div>
	                         				<?php
	                         				break;
	                         			}
	                         		}
	                         	}
	                         ?>
	                         <?php
	                             foreach ($ratingDisplayOrder as $key => $segment) {
	                                 foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){ 
	                                     if($desc == $segment){
	                                       $ratingBar = $rating*20;            
	                           ?>
	                          <div class="table_row">
	                             <div class="table_cell">
	                               <p class="rating_label"><?php echo $desc;?> </p>
	                             </div>
	                             <div class="table_cell">
	                                <div  class="loadbar"><div class="fillBar" style="width: <?php echo $ratingBar.'%'; ?>"></div></div>
	                                <b class="bar_value"><?php echo $rating; ?></b>
	                             </div>
	                          </div>
	                          <?php } } }?>
	                         </ol>
	                     </div>
	                   </div>
	                 </div>
	                 </div>
	            </amp-lightbox>
	          </div>
	          <p class="byUser">by <span><?php echo $userDetails['userName']; ?></span>, <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?> | <?php echo $courseInfo[$reviewRow['courseId']]['courseName'].$courseInfo[$reviewRow['courseId']]['courseNameSuffix'].$userDetails['batchInfo']; ?></p>
	        </div>
	      </div>
	      <div class="rvwv1-h">
	        <div class="tabcontentv1">
	          <div class="tabv_1">
	            <?php
	                foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {

	                    if($showDescLen>0){ ?>
	                    <p>
	                    <?php if($reviewSegmentName != 'Description'){ 
	                            ?>
	                            <strong class='rateHead'><?php echo $reviewSegmentName;?> :</strong> 
	                            <?php 
	                        } 
	                        echo nl2br(htmlentities(substr($reviewSegmentText,0,$showDescLen)));
	                        
	                        $remainingLen =  $totalSegmentslen-$showDescLen;
	                        $showDescLen = (int)($showDescLen) - strlen($reviewSegmentText);
	                        if($showDescLen>0 && $showDescLen<$minCharPerSection && $remainingLen>$minCharPerSection){
	                            $showDescLen = $minCharPerSection;
	                        }  
	                    }
	                } 
	                if($remainingLen>0){
	                    echo $showEllipses;
	                    ?>
	                    <a class="readMoreLnk link-blue-small" href="<?php echo $allReviewUrl.'#id='.$reviewRow['id'].'&seqId='.$j;?>" >more</a>
	                    <?php 
	                }
	            ?>
	            </p>
	          </div>
	        </div>
	      </div> 
	    </div>   
	        <?php } ?>
	        <?php 
	            if($totalReviews>=4 || $allCoursePage){
	                ?>
	                    <a class="btn-mob-ter" href="<?php echo $all_review_url;?>" id="reviews_count">View All <?php if(!$allCoursePage){ echo $totalReviews; }?> Review<?php if($allCoursePage || $totalReviews>1) echo 's'; ?></a>
	                <?php 
	            } 
	        ?>
	      </div>
	    </div>

</section>