<div class="navCont" style="width: 100%;overflow: scroll;">
<section class="detail-header">
    <a href="javascript:void(0);" id="descriptionlnk" class="navLinks active" onclick="moveToSection(this,'descriptionSection')">
        <i class="sprite overview-icn overview-icn-a"></i>
        <p>Overview</p>
        <i class="sprite pointer"></i>
    </a>
    <?php  if($applicationProcessDataFlag){?>
        <a href="javascript:void(0);" id="entryReqlnk" class="navLinks" onclick="moveToSection(this,'entryReqSection')">
                <i class="sprite eligibility-icn"></i>
            <p>Entry Req</p>
            <i class="sprite pointer"></i>
        </a>
    <?php }else{?>
        <a href="javascript:void(0);" id="eligibilitylnk" class="navLinks" onclick="moveToSection(this,'eligibilitySection')">
                <i class="sprite eligibility-icn"></i>
            <p>Entry Req</p>
            <i class="sprite pointer"></i>
        </a>
    <?php } ?>
    <a href="javascript:void(0);" id="feelnk" class="navLinks" onclick="moveToSection(this,'feeSection')">
            <i class="sprite fee-icn"></i>
        <p>&nbsp;&nbsp;Fees&nbsp;&nbsp;</p>
        <i class="sprite pointer"></i>
    </a>
    <?php if($applicationProcessDataFlag == 1){?>
    <a href="javascript:void(0);" id="applicationlnk" class="navLinks" onclick="moveToSection(this,'applicationProcessSection')">
            <i class="sprite application-icn"></i>
        <p>&nbsp;Application&nbsp;</p>
        <i class="sprite pointer"></i>
    </a>
    <?php } ?>
    
    <?php if($isPlacementFlag){?>
    <a href="javascript:void(0);" id="placementlnk" class="navLinks" onclick="moveToSection(this,'placementSection')">
            <i class="sprite placement-icn"></i>
        <p>Placement</p>
        <i class="sprite pointer"></i>
    </a>
    <?php }?>
    <?php if($scholarshipTabFlag){?>
     <a href="javascript:void(0);" id="scholarshiplnk" class="navLinks" onclick="moveToSection(this,'scholarshipSection')">
            <i class="sprite scholarship-icn"></i>
        <p style="margin-top:4px">Scholarship</p>
        <i class="sprite pointer"></i>
    </a>
	<?php }?>
    <?php  if( !empty($consultantData) ){ ?>
    <a href="javascript:void(0);" id="consultantlnk" class="navLinks" onclick="moveToSection(this,'consultantSection')">
            <i class="sprite consultant-icn"></i>
        <p>Consultant</p>
        <i class="sprite pointer"></i>
    </a>
    <?php } ?>
    
    
    <?php if(count($universityObj->getPhotos())>0){?>
    <a href="javascript:void(0);" id="coursePhotoVideolnk" class="navLinks" onclick="moveToSection(this,'coursePhotoVideoSection')">
            <i class="sprite photo-icn"></i>
        <p>&nbsp;&nbsp;Photos&nbsp;&nbsp;</p>
        <i class="sprite pointer"></i>
    </a>
    <?php }?>
    
    <!--Remove the below condition(which will never be true) to enable the video section in navigation bar -->
    <?php if(1==2 && count($universityObj->getVideos())>0){?>
    <a href="javascript:void(0);" id="moveTolnk" class="navLinks" onclick="moveToSection(this,'courseVideoSection')">
            <i class="sprite video-icn"></i>
        <p>&nbsp;&nbsp;Video&nbsp;&nbsp;</p>
        <i class="sprite pointer"></i>
    </a>
    <?php }?>
</section>
</div>