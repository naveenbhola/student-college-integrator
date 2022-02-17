<?php 
// $beaconTrackData = array(
//                                               'pageIdentifier' => 'recommendationPage',
//                                               'pageEntityId' => 0,
//                                               'extraData' => null
//                                               );
// loadBeaconTracker($beaconTrackData); 
 ?>
        <!-- <div class="header-fixed" style="box-shadow:none;">
            <div class="layer-header">
                <a href="javascript:void(0);" id="mobileRecoClose" onclick="closeOrReload();" data-rel="back" class="back-box"><i class="sprite back-icn"></i></a>
                <p style="text-align:center"><?= htmlentities(formatArticleTitle($universityName,74));?></p>
            </div>
            <section class="sent-brochure-col" style= "padding: 5px 10px;">
            	<p><i class="sprite sent-bro-icon"></i>Brochure sent to <div style="margin-left:22px;"><?= formatArticleTitle($email,74);?></div></p>
                <p style="margin:2px 0 0 22px;"><strong>Student who viewed this college also viewed</strong></p>
            </section>
        </div> -->
        <section style="width:100%;background-color: #eee;">
		<?php
		if(!isset($userComparedCourses)){
			if(!isset($this->compareCourseLib)){
				$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
			}
			$userComparedCourses = $this->compareCourseLib->getUserComparedCourses();
		}
		?>
        <?php
            $jqFlag = ($source == 'course' && $widgetName == 'alsoViewed'?true:false);
            foreach($alsoViewedCourses as $courseIds=>$objData)
                {
                $dataArray = array(
                          'universityObject' => $objData['universityObj'],     
                          'courseObj' => array($objData['courseObj']),
                          'identifier' => $widgetName,
                          'pageType' => 'signupThankYouPage',//$source,
                          'trackingPageKeyId' => $trackingPageKeyId,
                          'shortlistTrackingPageKeyId'=>$shortlistTrackingPageKeyId,
                          'rmcRecoTrackingPageKeyId'=>$rmcRecoTrackingPageKeyId,
			              'compareTupleTrackingSource'=>$compareTrackingPageKeyId,
                          'userComparedCourses'=>$userComparedCourses,
                          'jqFlag' => $jqFlag,
                          'mil'=>(isset($objData['mil'])?$objData['mil'] : false)
                        );
                $this->load->view("categoryPage/widgets/categoryPageTuple",$dataArray);
            }
        ?>
        </section>
