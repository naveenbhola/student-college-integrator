<?php
    $courseId = $course->getId();

    if(empty($brochureText)) {
        $brochureText = 'Apply Now';
    }
?>
<section class="tuple-bottom">
    <ul class="tupl-options">
        <?php if($mainPageName != 'courseHomePage' && $mainPageName != 'categoryPage' && $mainPageName != 'ND_Category' && $mainPageName != 'ND_SERP' && $mainPageName != 'searchPage' && $mainPageName != 'ND_Ranking' && $mainPageName != 'AllCoursesPage' && $mainPageName != 'ND_AllContentPage_Admission' && $mainPageName != 'ND_AllContentPage_Placement') { ?>
        <li>
            <a style="position: relative;">
                <?php
                if(in_array($product, array('AllCoursesPage','Category','SearchV2'))) {
                    ?>
                    <span style="display: none;left:0;top:22px;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Compare'] ?></p></span>
                    <?php
                } ?>
                <div class="nav-checkBx">
                 <?php
                 $checked = "";
                 $compareText = "Add to Compare";
                 if(is_array($alreadyComparedCourses) && !empty($alreadyComparedCourses[$courseId])){
                    $checked = "checked='checked'";
                    $compareText = "Added to Compare";

                 }else{
                    $checked = "";
                    $compareText = "Add to Compare";
                 }
                 $id = "compare".$institute->getId()."-".$course->getId();
                 if($tupleListSource == "ebochureCallback"){
                    $id = "compare".$institute->getId()."-".$course->getId()."-layer";
                 }
                 ?>
                 <input type="checkbox" class="nav-inputChk" <?php echo $checked;?> name='compare' id="<?php echo $id;?>" tupleListSource="<?php echo $tupleListSource;?>"/>
                    
                    <label class="nav-heck compare-site-tour" product="<?php echo $product; ?>" track='on' instid="<?php echo $institute->getId();?>" courseid="<?php echo $course->getId();?>" id="<?php echo $id?>lableicon" comparetrackingPageKeyKeyId ="<?php echo $comparetrackingPageKeyId;?>" tupleListSource="<?php echo $tupleListSource;?>">
                        <i class="icons ic_checkdisable1"></i><?php echo $compareText;?>
                    </label>                     
                </div>
            </a>
        </li>

        <?php }?>

        <?php
            
            if(is_array($shortlistedCoursesOfUser) && in_array($courseId, $shortlistedCoursesOfUser)){
                $shortlistFn    = "removeShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                $shortlistText  = "Shortlisted";
            }else{
                $shortlistFn    = "addShortlistFromSearch(".$courseId.",".$shortlistTrackingId.",'".$product."')";
                $shortlistText    = "Shortlist";
            }
             $shrtid = "shrt".$course->getId();
             if($tupleListSource == "ebochureCallback"){
                $shrtid = "shrt-layer".$course->getId();
             }
            ?>

          <?php 
              if(!empty($onlineApplicationCoursesUrl[$courseId]) && (!empty($onlineApplicationCoursesUrl[$courseId]['of_external_url']) || !empty($onlineApplicationCoursesUrl[$courseId]['of_seo_url'])) ) {
                    ?>
                     <li>
                        <?php
                          if(!empty($onlineApplicationCoursesUrl[$courseId]['of_external_url'])) {                   
                            $seoURL = $onlineApplicationCoursesUrl[$courseId]['of_external_url'];
                          }
                          else{
                            $seoURL = $onlineApplicationCoursesUrl[$courseId]['of_seo_url'];
                          }
                        ?>
                      <a target="_blank" href="<?php echo $seoURL;?>?tracking_keyid=<?php echo $applyOnlinetrackingPageKeyId?>">
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

    <input type="hidden" name="tupleListSource" id="tupleListSource" value="<?php echo $tupleListSource;?>">
    <input type="hidden" name="product" value="<?php echo $product;?>" id="product">

    <?php if(isset($_COOKIE['applied_'.$course->getId()]) && $_COOKIE['applied_'.$course->getId()] == 1) { ?>
        <a href="javascript:void(0);">
            <span class="tup-compare disable-btn deb-site-tour"><?php echo $brochureText; ?>
            <?php if(in_array($product,array('AllCoursesPage','Category','SearchV2'))) { ?>
                <span style="display: none;top:41px;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['DEB'] ?></p></span>
            <?php } ?>
          </span>
        </a>
    <?php } else {
        if($tupleListSource != "ebochureCallback") { ?>
            <a href="javascript:void(0);" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick='ajaxDownloadEBrochure(this,<?php echo $course->getId();?>,"course","<?php echo htmlspecialchars(addslashes($course->getName()),ENT_QUOTES);?>","<?php echo $tupleListSource;?>",<?php echo $ebrochureTrackingId; ?>)'>
                <span class="tup-compare deb-site-tour" instid="<?php echo $institute->getId(); ?>" product="<?php echo $product; ?>" track="on" courseid="<?php echo $courseId?>" ><?php echo $brochureText; ?>
                    <?php if(in_array($product,array('AllCoursesPage','Category','SearchV2'))) { ?>
                        <span style="display: none;top:41px;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['DEB'] ?></p></span>
                    <?php } ?>
                </span>
            </a>
        <?php } else { ?>
            <a href="javascript:void(0);" onclick="ajaxDownloadEBrochureFromLayer(this,<?php echo $course->getId();?>,'<?php echo $ebrochureTrackingId;?>')"><span class="tup-compare"><?php echo $brochureText; ?></span></a>
        <?php } ?>
    <?php } ?>

   <a href="javascript:void(0);" class="category_shortlist" onclick=<?=$shortlistFn?> id="<?php echo $shrtid?>" instid="<?php echo $institute->getId(); ?>" product="<?php echo $product; ?>" track="on" courseid="<?php echo $courseId?>">
    <span class="tup-view-details shortlist-site-tour">
     <?php echo $shortlistText;?>
     <?php 
     if(in_array($product,array('AllCoursesPage','Category','SearchV2'))){
         ?>
         <span style="display: none;top:41px" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Shortlist'] ?></p></span>
         <?php
     }
     ?>
    </span>
    
   </a>

   <?php 
    if(isset($_COOKIE['applied_'.$course->getId()]) && $_COOKIE['applied_'.$course->getId()] == 1){
      ?>
      <p class="success-msg"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
      <?php
    }
    else{
      if($tupleListSource != "ebochureCallback"){
        ?>
        <p class="success-msg" id="success-msg_<?php echo $course->getId();?>" style="display:none;"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
        <?php
      }
      else{
        ?>
        <p class="success-msg" id="success-msg-layer_<?php echo $course->getId();?>" style="display:none;"><?=BROCHURE_SUCCESS_MSG_TXT?></p>
        <?php
      }
    }
   ?>
	 <p class="clr"></p>
</section>
