         <!--ask current students-->
         <?php 
         $query_params = '';
         if($listing_type == 'course')
         {
            $query_params = '?courseId='.$listing_id.'&listingId='.$listing_parent_id.'&fromwhere=coursepage';
         }else{
            $query_params = '?listingId='.$listing_id.'&fromwhere='.$listing_type.'&city='.$currentCityId.'&locality='.$currentLocalityId;
         }
         ?>
         <section class="data-card m-5btm">
             <h2 class="color-3 f16 heading-gap font-w6">Ask your queries to current students</h2>
             <div>
                 <amp-carousel height="155" width="auto" layout="fixed-height" type="carousel" class="s-c student-div">
                 <?php foreach ($campusReps as $rep){ ?>
                     <figure class="color-w ask-s">
                         <div class="usr-id t-cntr m-5btm">
                             <?php if($rep['imageURL'] != '' && strpos($rep['imageURL'],'photoNotAvailable') === false){?>
                                    <amp-img src="<?=getSmallImage($rep['imageURL'])?>" width="61" height="61" layout="responsive" alt="<?php echo ucwords(substr($rep['displayName'],0,1));?>">
                                    </amp-img>
                                    <?php }
                                    else
                                    {
                                        echo ucwords(substr($rep['displayName'],0,1)); 
                                    }
                                     ?>
                         </div>
                         <!-- <amp-img src="images/rco-clg-1.jpg" width=155 height=116 layout=responsive></amp-img> -->
                         <div class="m-top">
                             <figcaption class="caption color-6 f12 font-w7 ellipsis">
                                 <?php
                                        //if(strlen(trim($rep['displayName'])) <= 24){
                                                echo trim($rep['displayName']);

                                        //}
                                        // else{
                                        //         echo substr(trim($rep['displayName']),0,21);
                                        // }
                                ?>
                             </figcaption>
                         </div>
                     </figure>
                <?php } ?>
                 </amp-carousel>
                 <?php if(!$showCampusReps){ ?>
                     <a class="btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic " data-vars-event-name="CA_ASK_NOW" href="<?=SHIKSHA_HOME?>/mAnA5/AnAMobile/getQuestionPostingAmpPage<?=$query_params;?>">Ask Question</a>
                <?php } ?>
                
             </div>
         </section>
   
