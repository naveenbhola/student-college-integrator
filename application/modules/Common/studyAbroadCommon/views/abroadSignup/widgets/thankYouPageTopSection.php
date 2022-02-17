<div class="em-blck">
    <div class="wlc-msg">
        <?php
			if($downloadMessageType == 'downloadBrochure'){
                $makePlural = $dlBrochureData['brochureURL'] != "" && $dlBrochureData['universityBrochureURL'] != ""?'s':'';
                $courseBrochureDownloadArray = $univBrochureDownloadArray = array(
                                                        "downloadedFrom"=>$downloadedFrom,
                                                        "tempLmsTableId"=>$tempLmsTableId>0?$tempLmsTableId:0,
                                                        "brochureEmailInsertId"=>$brochureEmailInsertId>0?$brochureEmailInsertId:0);
                $courseBrochureDownloadArray['listingType'] = "course";
                $courseBrochureDownloadArray['listingTypeId'] = $dlBrochureData['courseObj']->getId();
                $courseBrochureDownloadArray['brochureURL'] = base64_encode($dlBrochureData['brochureURL']);
                $univBrochureDownloadArray['listingType'] = "university";
                $univBrochureDownloadArray['listingTypeId'] = $dlBrochureData['courseObj']->getUniversityId();
                $univBrochureDownloadArray['courseId'] = $dlBrochureData['courseObj']->getId();
                $univBrochureDownloadArray['brochureURL'] = base64_encode($dlBrochureData['universityBrochureURL']);
                $univBrochureDownloadArray['courseId'] = $dlBrochureData['courseObj']->getId();
                                                
		?>
        <p class="mailing-txt"><i class="pin-show"></i>Brochure<?php echo $makePlural; ?> of <?php echo $dlBrochureData['courseObj']->getName(); ?> from <?php echo $dlBrochureData['courseObj']->getUniversityName(); ?> sent to email : <?php echo $email; ?></p>
        <p class="guide-txts">You can directly download brochure from the below link<?php echo $makePlural; ?>
            <?php if($dlBrochureData['brochureURL']!=''){ ?>
            <br> <a href="Javascript:void(0);" onclick="startAbroadListingsBrochureDownload('<?php echo base64_encode(json_encode($courseBrochureDownloadArray)); ?>');">Click here to download</a> course brochure (size: <?php echo $dlBrochureData['brochureURLSize']; ?>)
            <?php }
                if($dlBrochureData['universityBrochureURL']!=''){ ?>
            <br> <a href="Javascript:void(0);" onclick="startAbroadListingsBrochureDownload('<?php echo base64_encode(json_encode($univBrochureDownloadArray)); ?>');">Click here to download</a> university Brochure (size: <?php echo $dlBrochureData['UniversityBrochureURLSize']; ?>)
            <?php } ?>
        </p>
		<?php }else if($downloadMessageType == 'scholarshipDownloadBrochure'){
                $scholarshipBrochureDownloadArray = array(
                                                        "downloadedFrom"=>$downloadedFrom,
                                                        "tempLmsTableId"=>$tempLmsTableId>0?$tempLmsTableId:0,
                                                        "brochureEmailInsertId"=>$brochureEmailInsertId>0?$brochureEmailInsertId:0);
                $scholarshipBrochureDownloadArray['listingType'] = "scholarship";
                $scholarshipBrochureDownloadArray['listingTypeId'] = $dlScholarshipBrochureData['scholarshipObj']->getId();
                $scholarshipBrochureDownloadArray['brochureURL'] = base64_encode($dlScholarshipBrochureData['scholarshipObj']->getApplicationData()->getBrochureUrl());
            ?>
            <p class="mailing-txt"><i class="pin-show"></i>Brochure of <?php echo $dlScholarshipBrochureData['scholarshipObj']->getName(); ?> scholarship sent to email : <?php echo $email; ?></p>
            <p class="guide-txts">You can directly download brochure from the below link<?php if($dlScholarshipBrochureData['scholarshipObj']->getApplicationData()->getBrochureUrl()!=''){ ?>
            <br> <a href="Javascript:void(0);" onclick="startAbroadListingsBrochureDownload('<?php echo base64_encode(json_encode($scholarshipBrochureDownloadArray)); ?>');">Click here to download</a> scholarship brochure (size: <?php echo $dlScholarshipBrochureData['brochureURLSize']; ?>)
            <?php } ?>
            </p>
            <?php 
            }else{ ?>
        <p class="mailing-txt"><i class="pin-show"></i>Your download will begin shortly.</p>
        <p class="guide-txts">You can also directly download the guide from the link below.
            <br> <a id="startDownload" href="Javascript:void(0);" onclick = "contentDownloadFlowStart = true;downloadPDF('<?php echo $downloadControllerUrl; ?>',['<?php echo $url; ?>','<?php echo $contentId; ?>','<?php echo $trackingPageKeyId; ?>','<?php echo addslashes(htmlentities($contentData['strip_title'])); ?>','new','<?php echo $MISTrackingDetails['conversionType']; ?>','<?php echo $contentType; ?>']);">Click here to download</a> guide (size : <?php echo $contentData['download_size']; ?>)
        </p>
		<?php } ?>
        <?php $this->load->view('studyAbroadCommon/abroadSignup/widgets/thankYouPageBackLink'); ?>
    </div>
</div>
