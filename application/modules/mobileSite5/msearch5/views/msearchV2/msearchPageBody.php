<?php 
if($totalInstituteCount > 0) { 
    if(!$isLazyLoad) {
        $keyword = $request->getOldKeyword();
        if(empty($keyword)){
            $keyword = $request->getSearchKeyword();
        }
        if(!empty($relevantResults)){
            $headingStr = 'You may also be interested in the following colleges:';
            if($relevantResults != 'relax'){
                $headingStr = 'Showing results for &#8220;'.htmlentities($request->getSearchKeyword()).'&#8221;';
            }
            ?>
            <div class="zrp-container">
                <div class="zrp-card">
                    <h1 class="zrp-h1">No results found for &#8220;<?php echo htmlentities($keyword); ?>&#8221;</h1>
                    <p class="zrp-p">Suggestion: Please enter only a college/course name or check spellings</p>
                    <p class="zrp-clgs"><?php echo $headingStr; ?></p>
                </div>
            </div>
            <?php
        }
        else{
            ?>
            <h1 class="loc-title clg-count">
                <?php 
                $resultString  = $totalInstituteCount;
                $resultString .= ($totalInstituteCount == 1)? ' College':' Colleges';
                $resultString .= ' for &#8220;'.htmlentities($request->getSearchKeyword()).'&#8221;';
                echo $resultString;
                ?>
            </h1>
            <?php
        }
    } ?>

    <?php $tuplenumber = 1;
        foreach ($institutes['instituteData'] as $instituteId => $instituteObj) { 
            $style = count($institutes['instituteLoadMoreCourses'][$instituteObj->getId()]) == 0 ? 'nomorecourses': '';
            
            $courseObj = reset($instituteObj->getCourses());
            $data = array();
            $data['course']    = $courseObj;
            $data['institute'] = $instituteObj;
            $data['tuplenumber'] = $tuplenumber;

            if(is_object($instituteObj) && is_object($courseObj)) {
                echo "<input type='hidden' id='tuplenum_".$instituteId."' value=".$tuplenumber." />"; ?>
                
                <div class="tuple-container <?php echo $style; ?>">
                    <?php $this->load->view('msearch5/msearchV2/mtupleContent', $data); ?>
                </div>
                <?php $tuplenumber++;
            } else {
                error_log('CORRUPT institute id for the tuple: '.$instituteId);
            } ?>
        <?php }
        if(DO_SEARCHPAGE_TRACKING) {
            $trackingSearchId = $request->getTrackingSearchId();
            $trackingFilterId = $request->getTrackingFilterId();
            if(!empty($trackingSearchId)){
                echo "<input type='hidden' id='trackingSearchId' value={$trackingSearchId}>";
            }
            if(!empty($trackingFilterId)){
                echo "<input type='hidden' id='trackingFilterId' value={$trackingFilterId}>";
            }
        } 
    ?>

    <?php 
        $trackingSearchId = $request->getTrackingSearchId();
        $relevantResults  = $request->getRelevantResults();
        $newKeyword = '';
        if(!empty($relevantResults) && $relevantResults != 'relax'){
            $newKeyword       = $request->getSearchKeyword();
        }

        if(DO_SEARCHPAGE_TRACKING && !empty($trackingSearchId) && $updateResultCountForTracking){
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