<div>
<h2 class="compare-detial-title">Miscellaneous</h2>
<table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
    <?php if($classProfileFlag){?>
    <tr>
        <td width="25%"><div class="compare-detail-content"><strong>Students profile</strong></div></td>
       <?php foreach ($courseDataObjs as $courseObj) { 
                        $classProfileObject = $courseObj->getClassProfile();
                        $flag =0;?>
        <td width="25%">
            <div class="compare-detail-content">
<?php       $getAverageWorkExperience= $classProfileObject->getAverageWorkExperience();
                                                                if(!empty($getAverageWorkExperience))echo "Average Work Experience "."</br>".$getAverageWorkExperience."</br>";
                                                                else{ $flag++;}
            $getAverageGPA= $classProfileObject->getAverageGPA();                                                                
                                                                if(!empty($getAverageGPA))echo "Average GPA "."</br>".$getAverageGPA."</br>";
                                                                else{ $flag++;}
            $getAverageXIIPercentage= $classProfileObject->getAverageXIIPercentage();                                                                
                                                                if(!empty($getAverageXIIPercentage))echo "Average XII Percentage"."</br>".$getAverageXIIPercentage."</br>";
                                                                else{ $flag++;}
            $getAverageGMATScore= $classProfileObject->getAverageGMATScore();                                                                
                                                                if(!empty($getAverageGMATScore))echo "Average GMAT Score"."</br>".$getAverageGMATScore."</br>";
                                                                else{ $flag++;}
            $getAverageAge= $classProfileObject->getAverageAge();                                                                
                                                                if(!empty($getAverageAge))echo "Average Age"."</br>".$getAverageAge."</br>";
                                                                else{ $flag++;}
            $getPercenatgeInternationalStudents= $classProfileObject->getPercenatgeInternationalStudents();                                                                
                                                                if(!empty($getPercenatgeInternationalStudents))echo "Percenatge International Students"."</br>".$getPercenatgeInternationalStudents."%"."</br>";
                                                                else{ $flag++;}
            if($flag==6)
            { echo "------"; } ?>
            </div>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td width="25%"></td>
        <td width="25%"></td>
        <?php } else if($coursesCount == 2){?>
        <td width="25%"></td>
        <?php } ?>
    </tr>
    <?php } ?>
    <?php if($rankFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Rank</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
        <td>
            <?php if(!empty($courseRankDetails[$courseObj->getId()]['rank'])) { ?>
            <div class="compare-detail-content">
                <?php echo "Ranked ".$courseRankDetails[$courseObj->getId()]['rank']." in "."<a href='".$courseRankDetails[$courseObj->getId()]['rankURL']."'>".htmlentities($courseRankDetails[$courseObj->getId()]['rankName'])."</a>";?>
            </div>
            <?php }else { echo "------";} ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?> 
    </tr>
    <?php } ?>
    <?php if($mediaFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Photos & Videos</strong></div></td>
         <?php
            foreach ($courseDataObjs as $courseObj) { 
                $univId   = $courseObj->getUniversityId();
                $universityObject  = $univDataObjs[$univId];
                if(!empty($universityObject)){
                    $mediaData['photos'] = $universityObject->getPhotos();
                    $mediaData['videos'] = $universityObject->getVideos();
                } ?>
                <td>
                    <?php if(count($mediaData['videos'])>0 || count($mediaData['photos'])>0) { ?>
                        <div class="photo-video-tab">
                            <?php if(count($mediaData['photos'])>0) {?>
                            <a class="active photoLink" style="cursor: pointer; text-decoration: none;" onclick="openPhotoVideoOverLay('photoOverLay',0,'<?php echo $univId; ?>');"><i class="common-sprite photos-icon"></i>Photos (<?php echo count($mediaData['photos']);?>)</a>
                            <?php //_p(count($mediaData['videos']));?>
                            <?php }if(count($mediaData['videos'])>0){?>
                            <a class="active videoLink" style="cursor: pointer; text-decoration: none;" onclick="openPhotoVideoOverLay('videoOverLay',0,'<?php echo $univId; ?>');"><i class="common-sprite videos-icon"></i>Videos (<?php echo count($mediaData['videos']);?>)</a>
                            <?php }?>
                        </div>
                    <?php } ?>
                    <?php if(count($mediaData['videos'])>0) { ?>
                    <!-- video overlay -->
                          <div id="videoOverLay<?php echo $univId; ?>" style="display: none">             
                          <?php $videoData  = array('mediaData'=>$mediaData['videos'],'universityObject'=>$universityObject);
                                $this->load->view('studyAbroadCommon/compareCourses/widgets/compareCoursesVideoOverlay',$videoData);?>
                          </div> 
                          <?php } if(count($mediaData['photos'])>0) { ?>
                    <!-- photo overlay -->
                           <div id="photoOverLay<?php echo $univId; ?>" style="display: none">             
                          <?php $photoData  = array('mediaData'=>$mediaData['photos'],'universityObject'=>$universityObject);
                                $this->load->view('studyAbroadCommon/compareCourses/widgets/compareCoursesPhotoOverlay',$photoData);?>
                          </div> 
                          <?php } ?>
                </td>
                <?php } ?>
                <?php if($coursesCount == 1){?>
                <td></td>
                <td></td>
                <?php } else if($coursesCount == 2){?>
                <td></td>
                <?php } ?> 
    </tr>
    <?php } ?>
    
   <!--  <tr>
        <td><strong>Popularity index</strong></td>
        <td>
            <div class="compare-detail-content">
                <p>3.5</p>
            </div>
        </td>
        <td>
            <div class="compare-detail-content">
                <p>2.1</p>
            </div>
        </td>
        <td></td>
    </tr> -->
    <?php if($univLinkFlag) { ?>
    <tr>
        <td><div class="compare-detail-content"><strong>University website</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId(); ?>
        <td>
            <?php if(!empty($universityContactDetails[$univId]['universityWebsiteLink'])) { ?>
            <div class="compare-detail-content">
                <a target="_blank" rel="nofollow" href="<?php echo $universityContactDetails[$univId]['universityWebsite']; ?>"><?php echo $universityContactDetails[$univId]['universityWebsite']; ?></a>
            </div>
            <?php } else { echo "------"; } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?> 
    </tr>
    <?php } ?>
    <?php if($univEmailFlag){ ?>
    <tr>
        <td><div class="compare-detail-content"><strong>University email</strong></div></td>
       <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId(); ?>
        <td>
            <?php if(!empty($universityContactDetails[$univId]['universityEmail'])) { ?>
            <div class="compare-detail-content"><a href="mailto:<?php echo $universityContactDetails[$univId]['universityEmail']; ?>"/><?php echo $universityContactDetails[$univId]['universityEmail']; ?></a>
            </div>
            <?php } else { echo "------";} ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?> 
    </tr>
    <?php } ?>
    <?php if($intlStudentsFlag){ ?>
    <tr>
        <td><div class="compare-detail-content"><strong>International students website</strong></div></td>
       <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $internationalPageLink = $universityObject->getInternationalStudentsPageLink(); ?>
        <td>
            <?php if(!empty($internationalPageLink)) { ?>
            <div class="compare-detail-content">
                <a target="_blank" rel="nofollow" href="<?php echo $universityObject->getInternationalStudentsPageLink(); ?>"><?php echo $universityObject->getInternationalStudentsPageLink(); ?></a>
            </div>
            <?php } else { echo "------"; }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?> 
    </tr>
    <?php } ?>
    <?php if($fbLinkFlag){?>
    <tr>
        <td><div class="compare-detail-content"><strong>Facebook page</strong></div></td>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $facebooklink = $universityObject->getFacebookPage(); ?>
        <td>
            <?php if(!empty($facebooklink)) { ?>
            <div class="compare-detail-content"><a target="_blank" rel="nofollow" href="<?php echo $universityObject->getFacebookPage(); ?>"><?php echo $universityObject->getFacebookPage(); ?></a>
            </div>
            <?php } else { echo "------"; }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?>
        <td></td>
        <td></td>
        <?php } else if($coursesCount == 2){?>
        <td></td>
        <?php } ?> 
    </tr>
    <?php } ?>
</table>  
</div>




<script>

function openPhotoVideoOverLay(layerType,index,univId){
     $j("#"+layerType+univId).show();
      
    if(typeof($j("#"+layerType+univId).find(".abroad-media-layer"))!='undefined'){
    $j("#"+layerType+univId).find("#modal-overlay").css({
        'position':'fixed',
        'background-color':'#000000',
        'opacity':0.35,
        '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=35)',
        'height':$j(window).height()+'px',
        'width':$j(window).width()+'px',
        'top':'0px',
        'left':'0px',
        'z-index':999}).show();
    var posTop = ($j(window).height()/2) - 268;
    var posLeft = ($j(window).width()/2) - 235;
    $j("#"+layerType+univId).find(".abroad-media-layer").css({
        'position':'fixed',
        'top':posTop+'px',
        'left':posLeft+'px',
        'z-index':9999}).show();
    }
     if(layerType == 'videoOverLay')
     {
         openVideoLayer(index,univId);
     }
     if(layerType == 'photoOverLay')
     {      
        
    var royalSliderInstance = $j('#royalSlider'+univId).royalSlider({
    controlNavigation: 'thumbnails',
    slideshowAutoStart:false,
    numImagesToPreload:6,
    arrowsNavAutohide: false,
    arrowsNavHideOnTouch: false,
    startSlideId: index,
    thumbs: {
            fitInViewport: true,
            controlsInside : false,
            spacing: 4,
            arrowsAutoHide: false
  },
   globalCaption:true
  }).data("royalSlider");

    $j('.rsOverflowt').attr('style', function(i, style)
            {
                return style.replace(/display[^;]+;?/g, '');
            });
    royalSliderInstance.st.transitionSpeed = 0;
    royalSliderInstance.goTo(index);
    setTimeout(function() {
        royalSliderInstance.st.transitionSpeed = 600;
    }, 10);
         
    $j("#"+layerType+univId).show();

    if(typeof($j("#"+layerType+univId).find(".abroad-media-layer"))!='undefined'){
        
    $j("#"+layerType+univId).find("#modal-overlay").css({
        'position':'fixed',
        'background-color':'#000000',
        'opacity':0.35,
        '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=35)',
        'height':$j(window).height()+'px',
        'width':$j(window).width()+'px',
        'top':'0px',
        'left':'0px',
        'z-index':999}).show();
    var posTop = ($j(window).height()/2) - ($j("#"+layerType+univId).find(".abroad-media-layer").height()/2);
    var posLeft = ($j(window).width()/2) - ($j("#"+layerType+univId).find(".abroad-media-layer").width()/2);;
    $j("#"+layerType+univId).find(".abroad-media-layer").css({
        'position':'fixed',
        'top':posTop+'px',
        'left':posLeft+'px',
        'z-index':9999}).show();
    }
     }
 }

function closePhotoVideoOverlay(mediaType,univId){
    $j("#"+mediaType+univId).find("#modal-overlay").hide();
    $j("#"+mediaType+univId).find(".abroad-media-layer").hide();
    $j("#"+mediaType+univId).hide();
}
</script>
