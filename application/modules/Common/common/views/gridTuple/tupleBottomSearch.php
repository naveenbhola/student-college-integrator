<?php
	$courseId = $course->getId();
  global $TupleClickParams;
  $locationSuffixForUrl = $displayDataObject[$course->getId()]->getLocationSuffixForUrl();
?>
<section class="tuple-bottom">
    <ul class="tupl-options">
        <li>
            <a>
                <div class="nav-checkBx">
                   <?php
                     $checked = "";
                     $compareText = "Add to Compare";
                     if(is_array($alreadyComparedCourses) && !empty($alreadyComparedCourses[$courseId])){
                        $checked = "checked='checked'";
                        $compareText = "Added to Compare";
                     }else{
                        $compareText = "Add to Compare";
                        $checked = "";
                     }
                     $id = "compare".$institute->getId()."-".$course->getId();
                     if($tupleListSource == "ebochureCallback"){
                        $id = "compare".$institute->getId()."-".$course->getId()."-layer";
                     }
                 ?>
                    <input type="checkbox" class="nav-inputChk" <?php echo $checked;?> name='compare' id="<?php echo $id;?>" tupleListSource="<?php echo $tupleListSource;?>"/>

                    <label class="nav-heck" product="<?php echo $product; ?>" track='on' instid="<?php echo $institute->getId();?>" courseid="<?php echo $course->getId();?>" id="<?php echo $id?>lableicon" comparetrackingPageKeyKeyId ="<?php echo $comparetrackingPageKeyId;?>" tupleListSource="<?php echo $tupleListSource;?>">
                        <i class="icons ic_checkdisable1"></i><?php echo $compareText;?>
                    </label>                     
                </div>
            </a>
        </li>
	
        <?php
            
            if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                $shortlistClass = "shortlistedd";
                $shortlistFn    = "removeShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                $shortlistText  = "Shortlisted";
            }else{
                $shortlistClass = "";
                $shortlistFn    = "addShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                $shortlistText    = "Shortlist";
            }
            ?>
        <li>
            <a class="shortlist <?=$shortlistClass?>"  href="javascript:void(0);" onclick=<?=$shortlistFn?> product="<?php echo $product; ?>" track="on" courseid="<?php echo $courseId?>" instid="<?php echo $institute->getId(); ?>" id="shrt<?php echo $courseId; ?>">
                <i class="icons ic_shorlist"></i><?=$shortlistText;?>
            </a>
        </li>

        <li>
        <?php
          
           if(isset($_COOKIE['applied_'.$course->getId()]) && $_COOKIE['applied_'.$course->getId()] == 1){
          
          ?>
            <a class="broucher broucherMailed" href="javascript:void(0);">
                  <i class="icons ic_broucher"></i>
                  <span>Brochure Mailed</span>
            </a>
            <?php } else{
              ?>
                <a class="broucher" href="javascript:void(0);" product="<?php echo $product; ?>" track="on" courseid="<?php echo $course->getId(); ?>" instid="<?php echo $institute->getId(); ?>" onclick='ajaxDownloadEBrochure(this,<?php echo $course->getId();?>,"course","<?php echo htmlspecialchars(addslashes($course->getName()),ENT_QUOTES);?>","<?php echo $tupleListSource;?>",<?php echo $ebrochureTrackingId; ?>)'>
                    <i class="icons ic_broucher"></i>
                    <span>Brochure</span>
                </a>
              <?php
            }
          
            ?>
            
        </li>

         <?php 
              if(!empty($onlineApplicationCoursesUrl[$courseId]) && (!empty($onlineApplicationCoursesUrl[$courseId]['of_external_url']) || !empty($onlineApplicationCoursesUrl[$courseId]['of_seo_url'])) ) {
                
                    ?>
                     <li>
                        <?php
                          if(!empty($onlineApplicationCoursesUrl[$courseId]['of_external_url'])) {                   
                              $seoURL = $onlineApplicationCoursesUrl[$courseId]['of_external_url'];
                            }else{
                              $seoURL = $onlineApplicationCoursesUrl[$courseId]['of_seo_url'];
                            }
                        ?>
                      <a href="<?php echo $seoURL;?>?tracking_keyid=<?php echo $applyOnlinetrackingPageKeyId?>" target="_blank">
                          <span class="apply-i" instid="<?php echo $institute->getId(); ?>" product="<?php echo $product; ?>" track="on" courseid="<?php echo $courseId?>" >
                           Apply
                             <i class="fc_icons ic_apply"></i>
                          </span>
                      </a>
                  </li>
                    <?php
              }
          ?>
    </ul>
    

    <?php
      if($product == "SearchV2" || $product == "AllCoursesPage"){
        if(DO_SEARCHPAGE_TRACKING && !empty($trackingSearchId)){
            $tupleQuestionCount = empty($tupleCourseCount) ? $tuplenumber : $tuplenumber.'|'.$tupleCourseCount;
            $trackingstring = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}={$tupleQuestionCount}&{$TupleClickParams['clicktype']}=viewdetails&{$TupleClickParams['listingtypeid']}={$course->getId()}";
            $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
            if(!empty($trackingFilterId)){
                $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
            }
        }
        ?>
        <a href="<?php echo add_query_params($course->getURL().$locationSuffixForUrl,$trackingstring); ?>" target="_blank"><span class="tup-view-details" style="margin-left:12px;">View Details <i class="icons ic_right"></i></span></a>
        <?php
     }
     ?>
    

    <input type="hidden" name="tupleListSource" id="tupleListSource" value="<?php echo $tupleListSource;?>">
    <input type="hidden" name="product" value="<?php echo $product;?>" id="product">
   <p class="clr"></p>
</section>