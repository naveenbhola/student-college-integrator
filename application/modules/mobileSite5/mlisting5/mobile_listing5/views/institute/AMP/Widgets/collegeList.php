<?php
    $toolTipData = $this->config->item("instituteToolTipData");
    if($collegesWidgetData['topInstituteData'] || $collegesWidgetData['providesAffiliaion']){
?>
	    <?php
            if($collegesWidgetData['topInstituteData']){
            ?>
	    <section>
	     <div class="data-card m-btm">
              <h2 class="color-3 f16 heading-gap font-w6 inline-block"> Colleges/Departments </h2>
              <?php if($collegesWidgetData['providesAffiliaion']){ ?>
                  <a class="pos-rl ga-analytic" on="tap:view-info-affiliated_definition" role="button" tabindex="0" data-vars-event-name="'.$GA_TAP_ON_HELP_TEXT.'">
                  <i class="cmn-sprite clg-info i-block v-mdl"></i>
                  </a>
                  <amp-lightbox class="" id="view-info-affiliated_definition" layout="nodisplay" scrollable>
                       <div class="lightbox">
                       <a class="cls-lightbox f25 color-f font-w6" on="tap:view-info-<?php echo 'affiliated_definition'?>.close" role="button" tabindex="0">&times;</a>
                         <div class="m-layer">
                           <div class="min-div color-w pad10">
                            <strong class="block m-5btm color-3 f14 font-w6"><?php echo $toolTipData['affiliated_definition']['name']?></strong>
                            <p class="color-3 l-18 f12"><?php echo $toolTipData['affiliated_definition']['helptext'] ?></p>
                          </div>
                        </div>
                      </div>
                  </amp-lightbox>
              <?php } ?>
              <div class="card-cmn color-w">
                 <h3 class="color-3 f14 font-w6 sp-l"><?=$collegesWidgetData['constituentCollegeText'];?> <?php echo htmlentities($instituteName);?></h3>
                 <ul class="cls-ul">
		              <?php
                        $intituteNameLimit = 90;
                        foreach ($collegesWidgetData['topInstituteData'] as $id => $instituteObj) {
                            $instituteFullName = $instituteObj->getShortName();
                            $instituteFullName = $instituteFullName ? $instituteFullName : $instituteObj->getName();
                            $instituteTimmedName = strlen($instituteFullName) > $intituteNameLimit ?  (substr($instituteFullName, 0, $intituteNameLimit)."...") : $instituteFullName;
                  ?>
                   <li>
                      <p>
                        <a href="<?php echo $instituteObj->getUrl();?>" class="f14 color-6 ga-analytic" data-vars-event-name="COLLEGE_ACAMEMIC"><?php echo htmlentities($instituteTimmedName);?></a>
                      </p>
                      <?php
                       if(!empty($collegesWidgetData['aggregateReviewsData'][$id])  && $collegesWidgetData['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0 && $collegesWidgetData['reviewCount'][$id] > 0) {
                      ?>
                      <div class="new_rating">
                        <?php
                         $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $collegesWidgetData['aggregateReviewsData'][$id], 'reviewsCount' => $collegesWidgetData['reviewCount'][$id],'id'=>'institute'.$id ));
                                          
                        ?>
                      </div>
                      <?php }?>
                   </li>
		   <?php }?>
                 </ul>
		<?php if($collegesWidgetData['showViewMoreCTA']){ ?>
                 <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic" on="tap:mostViewedCollegeList" data-vars-event-name="VIEW_ALL_COLLEGE_ACADEMIC">View All</a>
		<?php } ?>
              </div>
           </div>
	   </section>
	<?php } ?>
	<?php if($collegesWidgetData['providesAffiliaion']){?>
         <section>
           <div class="data-card m-5btm">
                 <div class="card-cmn color-w">
                     <h2 class="f14 color-3 font-w6 m-btm"><?=$collegesWidgetData['affiliatedCollegesText']?></h2>
                     <a class="btn btn-secondary color-w color-b f14 font-w6 ga-analytic" on="tap:affiliatedcollegeList" data-vars-event-name="VIEW_AFFILIATED_COLLEGES">Click Here</a>
                 </div>
             </div>
         </section>
	<?php } ?>
<amp-lightbox id="mostViewedCollegeList" class="" layout="nodisplay" scrollable>

         <div class="lightbox">
         <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:mostViewedCollegeList.close" role="button" tabindex="0">×</a>
            <div class="m-layer">

                     <div class="min-div color-w catg-lt">
                        <div class="f14 color-3 bck1 pad10 font-w6">
                        <?php echo $mostViewedCollegeList['heading'];?>
                      </div>
                         <ul class="color-6">
			 <?php foreach($mostViewedCollegeList['institutesData'] as $key=>$value){ $id=$value['listingId'];
       //_p($mostViewedCollegeList['aggregateReviewsData']);die();
       ?>
                            <li><a href="<?php echo $value['id'];?>" class="block"><p><?php echo $value['name'];?></p></a>
                               <?php
                                             if(!empty($mostViewedCollegeList['aggregateReviewsData'][$id])  && $mostViewedCollegeList['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0 && $mostViewedCollegeList['reviewCount'][$id] > 0 ) {?>
                            <div class="new_rating">
                                           
                                              <?php
                                             // _p($collegesWidgetData['aggregateReviewsData'][$id]);die();

                                              $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $mostViewedCollegeList['aggregateReviewsData'][$id], 'reviewsCount' => $mostViewedCollegeList['reviewCount'][$id],'dontHover'=>1,'dontRedirect'=>1,'id'=>'institute'.$id));
                                              ?>
                                            </div>
                                          <?php   }
                                            ?>

                            </li>
			 <?php } ?>
                        </ul>
                   </div>
                 </div>
         </div>
      </amp-lightbox>

<amp-lightbox id="affiliatedcollegeList" class="" layout="nodisplay" scrollable>

         <div class="lightbox">
         <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:affiliatedcollegeList.close" role="button" tabindex="0">×</a>
            <div class="m-layer">
      
                     <div class="min-div color-w catg-lt">
                        <div class="f14 color-3 bck1 pad10 font-w6">
                        <?php echo $affiliatedcollegeList['heading'];?>
                      </div>
                         <ul class="color-6">
                         <?php foreach($affiliatedcollegeList['institutesData'] as $key=>$value){ $id=$value['listingId']; ?>
                            <li><a href="<?php echo $value['id'];?>" class="block"><p><?php echo $value['name'];?></p></a>
                               <?php
                                             if(!empty($affiliatedcollegeList['aggregateReviewsData'][$id])  && $affiliatedcollegeList['aggregateReviewsData'][$id]['aggregateRating']['averageRating'] > 0) {?>
                            <div class="new_rating">
                                           
                                              <?php
                                             // _p($collegesWidgetData['aggregateReviewsData'][$id]);die();

                                              $this->load->view("mobile_listing5/aggregateReviewInterlinkingWidgetAmp", array('allReviewsUrl' => $instituteObj->getAllContentPageUrl('reviews'), 'aggregateReviewsData' => $affiliatedcollegeList['aggregateReviewsData'][$id], 'reviewsCount' => $affiliatedcollegeList['reviewCount'][$id],'dontHover'=>1,'dontRedirect'=>1 ,'id'=>'institute'.$id));
                                              ?>
                                            </div>
                                          <?php   }
                                            ?>

                            </li>
                         <?php } ?>
                        </ul>
                   </div>
                 </div>
         </div>
</amp-lightbox>
<?php } ?>


