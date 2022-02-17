<?php 
if($totalInstituteCount > 0) {
    if(!$isLazyLoad) {
        if(!empty($relevantResults)){
            $headingStr = 'You may also be interested in the following colleges:';
            if($relevantResults == 'spellcheck') {
                $headingStr = 'Showing results for &#8220;'.htmlentities($searchKeyword).'&#8221;';
            }else{
                /*$headingStr = 'Showing results for &#8220;'.$strikeMessage.'&#8221;';*/
                if(!empty($strikeMessage))
                    $headingStr = 'Showing results for &#8220;'.$strikeMessage.'&#8221;';
                else if($relevantResults == "relax"){
                    $headingStr = "You may also be interested in the following colleges:";
                }
                else{
                    $headingStr = 'Showing results for &#8220;'.htmlentities($searchKeyword).'&#8221;';   
                }
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
            if($product == 'McategoryList') {
                $spanString  = $totalInstituteCount;
                if($totalInstituteCount == 1) {
                    $keyword = str_replace('colleges', 'college', $keyword);
                    $keyword = str_replace('courses', 'course', $keyword);
                    $keyword = str_replace('institutes', 'institute', $keyword);
                } ?>
                <div class="loc-title">
                    <span class="cat-cnt-spn"><?php echo $spanString; ?> </span>
                    <h1 class="cat-h1"><?php echo htmlentities($keyword); ?></h1>
                    <span class="offrd-courses"> Offering <?php echo $totalCourseCountForAllInstitutes; ?> Courses</span>
                    <?php 
                    if(!empty($h2Text)){
                        ?>
                        <p class="course-Hd-Det">Check the <strong>list of all <?php echo $h2Text['criteria']; ?> colleges/institutes in <?php echo $h2Text['locationString']; ?> listed on Shiksha</strong>. Get all information <a href="javascript:void(0);" class="rd-mre">...more</a><span class="hid"> related to admissions, fees, courses, placements, reviews & more on <?php echo $h2Text['criteria']; ?> colleges in <?php echo $h2Text['locationString']; ?> to help you decide which college you should be targeting for <?php echo $h2Text['criteria']; ?> admissions in <?php echo $h2Text['locationString']; ?>.</span></p>
                        <?php
                    }
                    ?>
                </div>
            <?php } else {
                $spanString  = $totalInstituteCount;
                $spanString .= ($totalInstituteCount == 1)? ' College':' Colleges';
                $spanString .= ' for ';//&#8220;'.htmlentities($keyword).'&#8221;'; ?>
                <div class="loc-title clg-count">
                    <span><?php echo $spanString; ?> </span>
                    &#8220;<h1 style="display: inline-block; font-weight: 400;"><?php echo htmlentities($keyword); ?></h1>&#8221;
                </div>
            <?php }
        }
    } ?>
    <input type="hidden" name="abTestVersion" value="<?php echo $abTestVersion;?>" id="abTestVersion">
    <?php $tuplenumber = 1;
        foreach ($institutes['instituteData'] as $instituteId => $instituteObj) { 
            $style = count($institutes['instituteLoadMoreCourses'][$instituteObj->getId()]) == 0 ? 'nomorecourses': '';
            
            $courseObj = reset($instituteObj->getCourses());
            $data = array();
            $data['course']    = $courseObj;
            $data['institute'] = $instituteObj;
            $data['tuplenumber'] = $tuplenumber;
            $data['courseWidgetData'] = $courseWidgetData[$data['course']->getId()];
            
            if(is_object($instituteObj) && is_object($courseObj)) {
                echo "<input type='hidden' id='tuplenum_".$instituteId."' value=".$tuplenumber." />"; ?>
                
                <div class="tuple-container <?php echo $style; ?>">
                    <?php $this->load->view('msearch5/msearchV3/mtupleContent', $data); ?>
                </div>
                <?php
                if($showIIMCallPredictor && (($currentPage == 1 && $tuplenumber == 4) || ($tuplenumber == $totalInstituteCount && $totalInstituteCount < 4))) { ?>
                    <section class="content-wrap2 clearfix-flt">
                        <?php echo Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget', 'categoryPage'); ?>
                    </section>
                <?php }
                if ($currentPage == 1 && $tuplenumber == 3){
                    $this->load->view('mcommon5/dfpBannerView',array('bannerPlace' => 'SRP_Slot1'));
                }
                if ($currentPage == 2 && $tuplenumber == 3){
                    $this->load->view('mcommon5/dfpBannerView',array('bannerPlace' => 'SRP_Slot2'));
                }
                $tuplenumber++;
            } else {
                error_log('CORRUPT institute id for the tuple: '.$instituteId);
            }
        }
    if ($tuplenumber <= 3){
        $this->load->view('mcommon5/dfpBannerView',array('bannerPlace' => 'SRP_Slot1'));
    }
    ?>
<?php } ?>