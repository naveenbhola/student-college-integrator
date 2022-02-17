<?php 
if($totalCourseCount > 0) { 
    if(!$isLazyLoad) {
        ?>
            <?php 
            $resultString  = "Showing ".$totalCourseCount;
            $resultString .= ($totalCourseCount == 1)? ' Course':' Courses';
            // $resultString .= " <a href='".$instituteObj->getURL()."'>".htmlentities($instituteNameWithLocation)."</a>";
             ?>
             <div class="loc-title clg-count acp-header">
                 <h1 class="acp-h1"><?=$seoData['pageHeading'];?></h1> <span class="acp-spn"><?="(".$resultString.")";?></span>
                 <?php if(!empty($seoData['seoScriptedText'])) { ?>
                        <p class="course-Hd-Det"><?php echo getTextfromhtml($seoData['seoScriptedText'],70); ?>...<span class='rd-more-head link-blue-small' >readMore</span></p>
                        <p class="course-Hd-Det" style="display: none"> <?php echo $seoData['seoScriptedText']; ?> </p>
                 <?php } ?>
             </div>

        <?php
    } ?>
    <?php $tuplenumber = 1;
    foreach ($institutes['courseData'] as $courseObj) {
        $data = array();
        $data['course']    = $courseObj;
        $data['institute'] = $instituteObj;
        $data['tuplenumber'] = $tuplenumber;
        $data['courseWidgetData'] = $courseWidgetData[$courseObj->getId()];

        if(is_object($instituteObj) && is_object($courseObj)) {
            echo "<input type='hidden' id='tuplenum_".$instituteId."' value=".$tuplenumber." />"; ?>
            
            <div class="tuple-container nomorecourses">
                <?php 
                $this->load->view('msearch5/msearchV3/mtupleContent', $data); ?>
            </div>

            <?php 
            if($tuplenumber == 3 && empty($currentPageNum))
            {
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'C1'));            
            }
            if(($tuplenumber == 7 && empty($currentPageNum))||($currentPageNum == 2 && $tuplenumber == 2))
            {
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'C2'));
            }
            $tuplenumber++;
        } else {
            error_log('CORRUPT institute id for the tuple: '.$instituteId);
        }
    }
    if($tuplenumber <= 4 && empty($currentPageNum))
    {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'C1'));
    }
    ?>

    <?php 
        $trackingSearchId = 0;//$request->getTrackingSearchId();
        
        $newKeyword = '';
       /* if(!empty($relevantResults) && $relevantResults != 'relax'){
            $newKeyword       = $request->getSearchKeyword();
        }
*/
        if(0 && DO_SEARCHPAGE_TRACKING && !empty($trackingSearchId) && $updateResultCountForTracking){
            ?>
            <script type="text/javascript">
                var img = new Image();
                var url = SEARCH_PAGE_URL_PREFIX+"/trackSearchQuery?ts="+<?php echo $trackingSearchId; ?>+'&count='+<?php echo $totalInstituteCount+'&newSearch=1'; ?>;
                <?php 
                if(!empty($newKeyword)){
                    ?>
                    url += "&newKeyword=<?php echo htmlentities($newKeyword); ?>";
                    <?php
                }
                if(!empty($relevantResults)){
                    ?>
                    url += "&criteriaApplied=<?php echo $relevantResults; ?>";
                    <?php
                }
                ?>
                img.src = url;
            </script>
            <?php
        }
    ?>
<?php } ?>