
<style>
.inputBorder{*height:21px}
</style>
<?php

        global $coursesAvailable;
        $countCourse=0;
	$coursesAvailable = $coursesForInstitute;
        foreach($coursesAvailable as $course) {
        $countCourse++;

        }
        function getCourseComboOptions($selectedCourse = '') {
		global $coursesAvailable;
		$comboOptions = '';
		foreach($coursesAvailable as $course) {

                        $courseId = $course['courseID'];
			$courseName = $course['courseName'];
			$selected = $selectedCourse == $courseId ? 'selected'  : '';
			$comboOptions .= '<option value="'. $courseId .'" '. $selected .' >'. $courseName .'</option>';

                }
		return $comboOptions;
	}




function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}


$coursesAvailableNew= subval_sort($coursesAvailable,'courseName');






?>

  <script>
            var locations_array = '<?php echo json_encode($multi_locations);?>'
            //var locations_array = '<?php echo json_encode($multi_locations);?>'
			var SITE_URL = '<?php echo base_url() ."/";?>';
                        var BASE_URL = SITE_URL;
  </script>

<!-- Included for uploading Header Images-->
<script language="javascript" src="/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>

<!-- Included for uploading Header Images with AIM(Ajax Iframe Method)-->
<script language="javascript" src="/public/js/imageUpload.js"></script>


<!--Included for using the validateStr function lying inside common.js-->
<script language = "javascript" src = "/public/js/common.js"></script>


<!-- Course Drop Down Selection Overlay Begins -->
<div class="posRelative" style="display:none" id="Overlay_institute" >
<div class="brd bgWhite" style="position:absolute;left: ; top:; width:390px">
<div class="pf10">
<div class="bld">Course</div>
<div style="height:110px;overflow:auto" class="mb10">
<div style="width:390px;line-height:18px" id="inputBlock">
<form name="courseForm">

       <?php  foreach($coursesAvailable as $course) {
               $courseId = $course['courseID'];
               $courseName = $course['courseName']; ?>
               <input type="checkbox" name="course"   id="<?php echo $courseId?>" value="<?php echo $courseName ?>" /> <?php echo $courseName?><br />
       <?php } ?>

       <?php
               $index=0;
               foreach($companyLogoListing as $n => $m)
               {  $index++; }
       ?>


</form>
</div>
</div>
<div class="line_1"></div>
<div class="mt10" align="center"><input type="button" class="entr_Allbtn ebtn_6" value="&nbsp;" onclick="updateHideOverlay('sOverlay_institute')" /></div>
</div>
</div>
</div>
<!-- Course Selection Drop Down Overlay Ends Here -->





	<div class="lineSpace_5">&nbsp;</div>
	<select id="coursesList" style="display:none" onchange="associateCourse(this);">
		<?php echo getCourseComboOptions(); ?>
	</select>

	<div style="margin:0 10px">
           		
	  <div style="display:none">
                <div class="row">
			<span style="float:right;padding-top:3px">All field marked with <span class="redcolor fontSize_13p">*</span> are compulsory to fill in</span>
			<span class="formHeader" id="documentsBlock"><a class="formHeader" name="docs" >Upload Documents</a></span>
			<div class="line_1"></div>
		</div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Add various documents &amp; presentations associated to your institute &amp; course.You may upload Institute Brochure, Course Material etc.</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already uploaded document</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_documents" filesUploaded="<?php echo  (is_array($documents) ? count($documents) : 0); ?>">
					<?php

                                                if(is_array($documents))
						foreach($documents as $documentId => $document) {
							$instituteId = '';
							$courseId = '';
							foreach($document['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $document['mediaCaption'];
							$mediaUrl = $document['mediaUrl'];
							$mediaThumbUrl = $document['mediaThumbUrl'];
							$mediaUploadDate = $document['mediaUploadDate'];
							$mediaAssociationDate = $document['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$mediaFileName = $fileNameSplitArray[0];
							$mediaName = $mediaFileName;
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>

                                        <li style="padding-bottom:15px;" id="<?php echo $documentId; ?>_documents"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" maxlength="50" /><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $documentId; ?>);" <?php echo $instituteChecked; ?>  original="<?php echo $instituteId; ?>"/> Institute &nbsp; &nbsp; <input type="checkbox" name="course" value="<?php echo $courseId; ?>" onclick="toggleAssociation(this, <?php echo $documentId; ?>);" <?php echo $courseChecked; ?> original="<?php echo $courseId; ?>" /> Course <select id="coursesList_<?php echo $documentId; ?>" onchange="associateCourse(this);" style="display:<?php echo $showCourse; ?>"><?php echo getCourseComboOptions($courseId); ?></select></li>


                                        <?php
                                              } ?>
				</ol>
			</div>
			<div style="line-height:10px">&nbsp;</div>
			<div style="margin-left:250px;" class="bld">(DOC,PDF allowed Max size=5MB)</div>
			<div id="documents">
				<div id="addMoreFordocuments"><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'documents')">+ Add More</a></div>
			</div>
		</div>
          </div>
                <div style="line-height:20px">&nbsp;</div>
                <div>
                <ul>
		<li style="list-style-type:upper-roman;margin-left:20px;">We will gather the photos and then send it to “Resizing Team”.</li>
		<li style="list-style-type:upper-roman;margin-left:20px;">While sending them to the resizing team name them appropriately as “Main 1” for first Main header image and “Main 1 Sub” for second main header image.</li>
		<li style="list-style-type:upper-roman;margin-left:20px;">
		Once the photos have been resized, we will upload them accordingly.
		</li>
		</ul>		
		</div>
               <div style="line-height:20px">&nbsp;</div>
		<div class="formHeader" id="photosBlock"><a class="formHeader" name="photosmedia" >Upload Photos <span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'photosBlock_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></a></div>
		<div id="photosBlock_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('photosBlock_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Add pictures of your institute to the listing. Photos can be of institute’s infrastructure, library, labs, fests, seminars etc.</li>
					<li>You may upload photos of building premises, library, hostel, laboratories, computer rooms etc..</li>	
					<li>Selection of photographs should be keeping in mind what a student will look and like.</li>
					<li>Naming would be done as Photo1,photo2 etc.</li>
					</ul>
	</div>
		<div class="line_1"></div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Add pictures of your institute to the listing. You may upload photos of building premises, library, hostel, laboratories, computer rooms etc.</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already uploaded photos</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_photos" filesUploaded="<?php echo (is_array($photos) ? count($photos) : 0); ?>">
					<?php

                                                if(is_array($photos))
						foreach($photos as $photoId => $photo) {
							$instituteId = '';
							$courseId = '';
							foreach($photo['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $photo['mediaCaption'];
							$mediaUrl = $photo['mediaUrl'];
							$mediaThumbUrl = $photo['mediaThumbUrl'];
							$mediaUploadDate = $photo['mediaUploadDate'];
							$mediaAssociationDate = $photo['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$mediaFileName = $fileNameSplitArray[0];
							$mediaName = $mediaFileName;
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>
					<li style="padding-bottom:15px;" id="<?php echo $photoId; ?>_photos"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" maxlength="50"/><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $photoId; ?>);" original="<?php echo $instituteId; ?>" <?php echo $instituteChecked; ?> /> Institute &nbsp; &nbsp;</li>
					<?php
						}
					?>
				</ol>
			</div>
			<div style="line-height:10px">&nbsp;</div>
			<div style="margin-left:250px;" class="bld">(JPG,PNG,GIF allowed Max size=5MB)</div>
			<div id="photos">
				<div id="addMoreForphotos"><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'photos')">+ Add More</a></div>
			</div>
		</div>
		<div style="line-height:20px">&nbsp;</div>

		<div class="formHeader" id="videosBlock"><a class="formHeader" name="videosmedia" >Attach Videos<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'videosmedia_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span> </a></div>
		<div id="videosmedia_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('videosmedia_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Add videos of your institute. Videos can be of institute’s advertisement, fest, placement cell/organization’s feedback, alumni feedback etc.</li>
					<li>You may attach videos of important events, interviews of professors & students etc.</li>	
					<li>Only You Tube URL can be pasted.</li>
					<li>If You Tube URL is not available then upload the video on You Tube first and then paste the You Tube URL.</li>
					</ul>
	</div>	
		<div class="line_1"></div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Add videos of your institute. You may attach videos of important events,interviews of professors &amp; students etc.</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already attached videos</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_videos" filesUploaded="<?php echo (is_array($videos) ? count($videos) : 0); ?>">
					<?php
						if(is_array($videos))
						foreach($videos as $videoId => $video) {
							$instituteId = '';
							$courseId = '';
							foreach($video['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $video['mediaCaption'];
							$mediaUrl = $video['mediaUrl'];
							$mediaThumbUrl = $video['mediaThumbUrl'];
							$mediaUploadDate = $video['mediaUploadDate'];
							$mediaAssociationDate = $video['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$fileExtension = '';//Youtube videos
							$mediaFileName = $fileNameSplitArray[0];
							$mediaName = $mediaFileName;
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>
					<li style="padding-bottom:15px;" id="<?php echo $videoId; ?>_videos"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" maxlength="50"/><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $videoId; ?>);" original="<?php echo $instituteId; ?>" <?php echo $instituteChecked; ?> /> Institute &nbsp; &nbsp;</li>
					<?php
						}
					?>
				</ol>
			</div>
			<div style="line-height:10px">&nbsp;</div>
			<div style="margin-left:210px;"><b>(Paste youtube URL)</b> Example: http://in.youtube.com/watch?v=AxYgh_cgYhn</div>
			<div id="videos">
				<div id="addMoreForvideos"><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'videos')">+ Add More</a></div>
			</div>
		</div>

                <!-- Here It begins for Company Logo Add feature -->


                <div style="line-height:20px">&nbsp;</div>





<div style="margin:0 10px">
                <div class="row">
                    <span class="formHeader">Top Recruiting Companies<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'top_rect_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></span>
                    <div class="line_1"></div>
		   <div id="top_rect_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('top_rect_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>Find and attach all companies where students have been placed, attended trainings.</li>
					<li>Attach the organization with the course it is associated with (if any).</li>	
					</ul>
	</div>		
                </div>
                <div style="line-height:15px">&nbsp;</div>
                <div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already attached compaines</div>
                <div style="margin:0 50px">
                    <div class="lfbx">
                        <!--Start_displaying added companies -->
                        <div class="clear_B">&nbsp;</div>
                        <!-- displaying companies added -->
                        <div class="wdh100 mtb10">
                        <ol type="1" start="1"  id="addedCompanyLogo" style="list-style:none;margin:0;padding:0">
                        <?php 
			for($il=1 ; $il<= $logo_count; $il++){
                        ?><li><div id="<?php echo $il;?>"class="float_L w60"><div><select name="<?php echo $logoarr[$il-1]['company_order'];?>" id="order<?php echo $il;?>" style="width:50px"><?php for($op=1;$op<= $logo_count ;$op++){?><option><?php echo $op;?></option><?php } ?></select></div></div><div class="float_L w130"><div><img src="<?php echo $logoarr[$il-1]['logo_url']; ?>" border="0" /></div></div><div class="float_L w600"><div><a href="#"><?php echo $logoarr[$il-1]['company_name']; ?></a> <span class="fcGya">[</span> <a name="<?php echo $logoarr[$il-1]['company_name']; ?>" onClick="deleteCompany(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div><div>Associate with: <input name="insti<?php echo $il; ?>" value="<?php echo $logoarr[$il-1]['institute']; ?>" id="<?php echo $logoarr[$il-1]['logo_id'];?>" type="hidden" /><input value="<?php echo $logoarr[$il-1]['iscourse'];?>" type="checkbox" id="course<?php echo $il;?>" onClick="showCourseOverlay(this, <?php echo $il;?>,'company')"/> Course<input  id ="text<?php echo $il;?>" style="display : none" type="text" class="lfsBx" value="Select" /><div class="posRelative" style="display:none" id="sOverlay_institute<?php echo $il; ?>" ><div class="brd bgWhite" style="position:absolute; width:390px"><div class="pf10"><div class="bld">Course</div><div style="height:110px;overflow:auto" class="mb10"><div style="width:390px;line-height:18px" id="inputBlock"><input type="checkbox" onClick="clickAll(this)"  id="Allcourse<?php echo $il; ?>"<?php if ($logoarr[$il-1]['course_count']== $countCourse){?> value="1"<?php } else{?>value="0"<?php }?> name=""/>All<br/><?php $dummycount=0; foreach($coursesAvailableNew as $course) { $dummycount++; $courseId = $course['courseID']; $courseName = $course['courseName'];?><input type="checkbox" onclick="updateClickAll('<?php echo $il; ?>');" <?php $cchedked=0; for( $n=1; $n <= $logoarr[$il-1]['course_count']; $n++){ if ($courseId == $logoarr[$il-1]['courseId'.$n]){?>value="1"<?php $cchecked=1;break;}} if ( $cchecked == 0){?>value="hdhdhdh"<?php }?>  name="<?php echo $courseId;?>" id="<?php echo $il;?>course<?php echo $dummycount;?>" /> <?php echo $courseName; ?><br /><?php } ?></div></div><div class="line_1"></div><div class="mt10" align="center"><input type="button" class="btnOkk" value="&nbsp;" onclick="closeHideOverlay('sOverlay_institute<?php echo $il;?>','text<?php echo $il;?>')" /></div></div></div></div></div></div> <div class="clear_B">&nbsp;</div><input type = "hidden" class="logo_ids" value="<?php echo $logoarr[$il-1]['logo_id']; ?>"/></li>
                        <?php } ?></ol>
                        </div>
                        <!-- end displaying added companies -->
                    </div>
                    <div style="line-height:10px">&nbsp;</div>
                    <div>
                            <div class="mb5">
                            <?php $loop=0;?>
                            <select id="<?php echo "selectcompany".$loop ;?>" class="sLSel12 w300">
                            <?php
                            $index=0;$logoname= Array();
                            $logourl= Array();
                            ?>

                            <option id="no" value="no">Select Company </option>
                            <?php
                            foreach($companyLogoListing as $n => $m)
                            {      $index++;
                                    foreach($m as $key => $value)
                                    {
                                        if( $key == 'id')
                                        {

                                        ?>
                                        <option id="<?php echo $index ?> " value="<?php echo $value ?>" >
                                        <?php
                                        }
                                        if($key != 'logo_url' && $key != 'id'){
                                        echo $value;
                                        $logoname[$index]= $value;

                                        ?></option>
                            <?php
                                                                               }

                                    if( $key == 'logo_url')
                                    $logourl[$index]=$value;

                                    }
                            }
                            $sel= 'selectcompany'.$loop;
                            //echo $sel;
                            ?>
                        </select> <input type="button" class="defaultBtn_mp" value="&nbsp;" onClick="addCompany('<?php echo $sel;?>')"/>
                        </div>
                        </div>
                        <div class="bbm">&nbsp;</div>
                        <div align="center" class="mtb10">
                            <input type="button" value="&nbsp;" class="btnCompanies" onClick="mapCompany()" />
                        </div>
                </div>
                <div style="display: none" class="showMessages" id ="TRC" align="center">Top Recruiting Companies Saved</div>
                <div class="lineSpace_30">&nbsp;</div>

               <!-- new div by me -->
               <div style="display: none" id="HeaderHeader">

                <div class="row">
                    <span class="formHeader">Main Header Images<span>[ <a href="javascript:void(0);" onClick="showPopup(300,100,'header_main_help',obtainPostitionX(this)-5 , obtainPostitionY(this)+15);" >view example</a> ]</span></span>
                    <div class="line_1"></div>
		   <div id="header_main_help" class="help-box" style="visibility:hidden;">
					<div style="text-align:right"><a href="javascript:void(0);" onclick="hidePopup('header_main_help');" > Close <span style="background:#CCC" >X</span></a></div>
					<ul>					
					<li>These images will appear on the main page and are of extreme importance.</li>
					<li>Should mainly contain the infrastructure like institute building, lab, other facility etc.</li>	
					<li>Naming to send photos to resizing team should be done as ‘Main1’,Main1 Sub’,’Main2’,’Main2 Sub’ etc.</li>
					</ul>
	</div>		
                </div>
                <div style="line-height:15px">&nbsp;</div>
                <div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already uploaded photo</div>
                <div style="margin:0 50px">
                    <div class="lfbx">
                        <div class="clear_B">&nbsp;</div>
                        <div class="wdh100 mtb10">



               <!--       <div class="float_L" style="width:20px"><div>1</div></div><div class="float_L w600"><div><a href="#">Rediff.com</a> <span class="fcGya">[</span> <a href="#">Delete</a> <span class="fcGya">]</span></div><div>Associate with: <input type="checkbox" /> Institute <input type="checkbox" /> Course <input type="text" class="lfsBx" value="Select" /></div></div><div class="clear_B">&nbsp;</div> -->

                        <ol type="1" start="1"  id="addedHeaderImage" style="list-style:none;margin:0;padding:0">


                        <?php for($il=1 ; $il<= $header_count; $il++){
                        ?>
                        <li><div class="float_L" style="width:20px"><input type="hidden" id="<?php echo $Header[$il-1]['header_order']; ?>"><input type="hidden" name="thumbURL<?php echo $il; ?>" type= "hiddden" id="<?php echo $Header[$il-1]['thumb_url'];?>"><input type="hidden" name="largeURL<?php echo $il;?>" type= "hiddden" id="<?php echo $Header[$il-1]['large_url']; ?>"><div><?php echo $il; ?></div></div><div class="float_L w600"><div><a id="<?php echo $Header[$il-1]['name'];?>"href="#"><?php echo $Header[$il-1]['name'];?></a> <span class="fcGya">[</span> <a onClick="deleteHeader(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div></div><div class="clear_B">&nbsp;</div></li>
                        <?php }?>

                        </ol>

                      <!--  <li><div class="float_L" style="width:20px"><input type="hidden" id="<?php //echo $Header[$il-1]['header_order']; ?>"><input type="hidden" name="thumbURL<?php //echo $il; ?>" type= "hiddden" id="<?php //echo $Header[$il-1]['thumb_url'];?>"><input type="hidden" name="largeURL<?php// echo $il;?>" type= "hiddden" id="<?php// echo $Header[$il-1]['large_url']; ?>"><div><?php// echo $il; ?></div></div><div class="float_L w600"><div><a id="<?php// echo $Header[$il-1]['name'];?>"href="#"><?php //echo $Header[$il-1]['name'];?></a> <span class="fcGya">[</span> <a onClick="deleteHeader(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div><div>Associate with: <input name="hinsti<?php //echo $il; ?>" value="<?php //echo $Header[$il-1]['institute']; ?>" id="" type="checkbox" /> Institute <input type="checkbox" value="<?php //echo $Header[$il-1]['iscourse'];?>" id="hCourse<?php /*echo $il;?>" onClick="showCourseOverlay(this,<?php echo $il;?> ,'header')"/> Course <input type="text" id ="htext<?php echo $il;?>" style="display : none"class="lfsBx" value="Select" /><div class="posRelative" style="display:none" id="hOverlay_institute<?php echo $il;?>" ><div class="brd bgWhite" style="position:absolute;left: ; top:; width:390px"><div class="pf10"><div class="bld">Course</div><div style="height:110px;overflow:auto" class="mb10"><div style="width:390px;line-height:18px" id="inputBlock"><input type="checkbox" id="hAllcourse<?php echo $il;?>"<?php if ($Header[$il-1]['course_count']== $countCourse){?> value="1"<?php } else{?>value="0"<?php }?> name=""/>All<br/><?php $dummycount=0; foreach($coursesAvailable as $course) { $dummycount++; $courseId = $course["courseID"]; $courseName = $course['courseName'];?><input type="checkbox" <?php $cchedked=0; for( $n=1; $n <= $Header[$il-1]['course_count']; $n++){ if ($courseId == $Header[$il-1]['courseId'.$n]){?>value="1"<?php $cchecked=1;break;}} if ( $cchecked == 0){?>value="hdhdhdh"<?php }?>  name="<?php echo $courseId;?>" id="<?php echo $il;?>hcourse<?php echo $dummycount;?>" /> <?php echo $courseName; ?><br /><?php } ?></div></div><div class="line_1"></div><div class="mt10" align="center"><input type="button" class="btnOkk" value="&nbsp;" onclick="closeHideOverlay('hOverlay_institute<?php echo $il;?>','htext<?php echo $il;*/?>')" /></div></div></div></div></div></div><div class="clear_B">&nbsp;</div></li>-->




                       </div>
                    </div>
                    <div style="line-height:10px">&nbsp;</div>
                    <div class="pl21">

                        <?php for($hcount =0; $hcount < 3; $hcount++){    ?>

                        <div class="mb5">

                            <select id="hselect<?php echo $hcount;?>" class="sLSel12" style="width:50px"><?php for($ho=1; $ho<= 25 ; $ho++) {?><option><?php echo $ho;?></option><?php }?></select> &nbsp;

                            <input type="text" style="width:150px;padding:0 5px" class="inputBorder" value="Enter Photo Title" id="headerImageName<?php echo $hcount; ?>" onfocus="removeT(this)" onblur="addT(this)"/> &nbsp;

                            <form style="display: inline" id="thumbForm<?php echo $hcount; ?>" name="thumbForm<?php echo $hcount; ?>" enctype="multipart/form-data" action="/listing/posting/MediaPost/uploadHeaderImage/thumb"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': doneThumbImage});"  autocomplete="off" method="post">
                            <input type="file" id="thumbImage<?php echo $hcount;?>" name="myImage[]" size="12"/>
                            <input type="submit" class="uploadBtn_mp" value="&nbsp;" onClick="uploadThumbHeaderImage(<?php echo $hcount;?>)"/> &nbsp;
                            </form>


                            <form style="display: inline" id="largeForm<?php echo $hcount; ?>" name="largeForm<?php echo $hcount; ?>" enctype="multipart/form-data" action="/listing/posting/MediaPost/uploadHeaderImage/large"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': doneLargeImage});"  autocomplete="off" method="post">
                            <input type="file" id="largeImage<?php echo $hcount;?>" name="myImage[]"size="12"/>
                            <input type="submit" class="uploadBtn_mp" value="&nbsp;" onClick="uploadLargeHeaderImage(<?php echo $hcount;?>)"/>
                            </form>

                        </div>

                        <?php } ?>

                    </div>

                    <div class="bbm">&nbsp;</div>
                    <div align="center" class="mtb10">
                            <input type="button" value="&nbsp;" class="btnHeader" onClick="mapHeaderImage()" />
                    </div>
                    <div style="display: none" class="showMessages" id ="HIMAGE" align="center">Header Images Saved</div>
             </div>
</div>
                <div class="lineSpace_30">&nbsp;</div>
		
		
</div>

                <div style="line-height:10px">&nbsp;</div>
		<form action="/listing/posting/MediaPost/post" name="courseListing" id="courseListing" method="post" onsubmit="return postMediaWithAssociation();" style="margin:0;padding:0">
			<!-- mandatory comment box section : starts-->
			<?php $this->load->view('listing_forms/mandatory_comments',array('userid'=>$userid,'listing_id'=>$listingTypeId,'tab'=>'media')); ?>
			<!-- mandatory comment box section : ends-->
		
			<div align="center"><input type="submit" value="Proceed to Publish" class="searchWidgetBtn_n" /><!-- &nbsp; &nbsp; <input type="button" value="Cancel"  onclick=" try { ListingOnBeforeUnload.prompt = true;location.replace('/enterprise/Enterprise/index/7'); } catch (err) { }" class="btnCancelled" />--> </div>
			<input type="hidden" id="listingType" name="listingType" value="<?php echo $listingType; ?>"/>
			<input type="hidden" id="mediaAssoc" name="mediaAssoc"/>
			<input type="hidden" id="listingId" name="listingId" value="<?php echo $listingTypeId; ?>"/>
		</form>
	</div>

<script>
var cms = <?php echo $isHeader;?>;
if(cms == 1)
document.getElementById("HeaderHeader").style.display = '';


// Javascript below is related to Main Header Images : don't change
var countHeaderImageAdded=<?php echo $header_count ;?>;


// Arrays to keep a count of the mapping between thumb Images and Large Imgas at the time of uploading
// when a single image is upoaded by clicking Upload button corresponding array value changes to 1 from 0
// and then to 2 if the upload was successful,in case there is amatch found then the both images are added
// to th already added section with the common name displayed.

var thumbImage= new Array(0,0,0);
var largeImage= new Array(0,0,0);


//Default Ordering of Header Images
document.getElementById("hselect0").options[0].selected= true;
document.getElementById("hselect1").options[1].selected= true;
document.getElementById("hselect2").options[2].selected= true;

//Arrays to store the URL genersted by the server
var thumbURL = new Array();
var largeURL= new Array();

var order = new Array();

// functions to keep the image name text area's content consistent
function removeT(obj)
{
    if ( obj.value=="Enter Photo Title")
       obj.value="";

}
function addT(obj)
{
    if ( obj.value=="")
       obj.value="Enter Photo Title";

}

//This function is invoked whenever we get a response from the image upload AIM procedure through
//Ajax call, if upload is success corresponding other image mapping is checked.
function doneThumbImage(response)
{

    if( response != 'Please select a photo to upload' && response != 'Image size must be 124*104 px' && countHeaderImageAdded < 3)//Upload success case
    {

         for(var i =0; i< 3; i++)
         {
               if( thumbImage[i] == 1)
               {
                     thumbImage[i]=2;
                     thumbURL[i]= response;
                     if(largeImage[i]==2)
                     {


                           var imageName = document.getElementById("headerImageName"+i).value;
                           imageName= trim(imageName);
                           var checkvalue = validateStr(imageName,"Header Image Name",50,1);
                           if(checkvalue == true && imageName != "Enter Photo Title" && imageName != '')
                           {

                                countHeaderImageAdded++;
                                var ord= document.getElementById("hselect"+i);
                                var orderValue= ord.value;
                                var localHeaderImageCount= countHeaderImageAdded;
                                var container = document.getElementById('addedHeaderImage');
                                var newelement = document.createElement('li');
                                var innerContent='<div class="float_L" style="width:20px"><input type="hidden" id="'+orderValue+'"><input type="hidden" name="thumbURL'+countHeaderImageAdded+'" type= "hiddden" id="'+thumbURL[i] +'"><input type="hidden" name="largeURL'+countHeaderImageAdded+'" type= "hiddden" id="'+largeURL[i] +'"><div>'+countHeaderImageAdded+'</div></div><div class="float_L w600"><div><a id="'+imageName+'"href="#">'+imageName+'</a> <span class="fcGya">[</span> <a onClick="deleteHeader(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div></div><div class="clear_B">&nbsp;</div>';
                                newelement.innerHTML = innerContent;
                                container.appendChild(newelement);
                                document.getElementById('HIMAGE').style.display = "none";
                                document.getElementById("headerImageName"+i).value="Enter Photo Title";
                                document.getElementById("thumbImage"+i).form.reset();
                                document.getElementById("largeImage"+i).form.reset();

                                for(var it=0;it<3;it++)
                                {

                                    if( it == i)
                                    {
                                             thumbImage[it]=0;
                                             largeImage[it]=0;
                                             thumbURL[it]=0;
                                             largeURL[it]=0;
                                    }
                                }
                           }
                           else
                           {
                                    if (imageName == "Enter Photo Title" || imageName == '')
                                    alert("Please provide a valid name for the Header Image");

                                    for(var it=0;it<3;it++)
                                    {

                                            if( it == i)
                                            {
                                                thumbImage[it]=0;
                                                largeImage[it]=0;
                                                thumbURL[it]=0;
                                                largeURL[it]=0;
                                            }
                                    }


                            }

                       }

               }

         }

    }
    else// Upload fail case
    {

        for( i =0; i< 3; i++)
                {
                       if( thumbImage[i] == 1)
                                thumbImage[i] =0;
                }
         if( countHeaderImageAdded < 3)
         alert(response);
    }


}
//This function is invoked whenever we get a response from the image upload AIM procedure through
//Ajax call, if upload is success corresponding other image mapping is checked.
function doneLargeImage(response)
{

    if( response != 'Please select a photo to upload' && response != 'Image size must be 303*210 px' && countHeaderImageAdded < 3)//Image upload success case
    {
         for(var i =0; i< 3; i++)
         {
               if( largeImage[i] == 1)
               {
                     largeImage[i]=2;
                     largeURL[i]= response;
                     if(thumbImage[i]==2)
                     {


                           var imageName = document.getElementById("headerImageName"+i).value;
                           var checkvalue = validateStr(imageName,"Header Image Name",50,1);
                           imageName= trim(imageName);
                           if(checkvalue == true && imageName != "Enter Photo Title" && imageName != '')
                           {

                                countHeaderImageAdded++;
                                var ord= document.getElementById('hselect'+i);
                                var orderValue= ord.value;
                                var localHeaderImageCount= countHeaderImageAdded;
                                var container = document.getElementById('addedHeaderImage');
                                var newelement = document.createElement('li');
                                var innerContent='<div class="float_L" style="width:20px"><input type="hidden" id="'+orderValue+'"><input type="hidden" name="thumbURL'+countHeaderImageAdded+'" type= "hiddden" id="'+thumbURL[i] +'"><input type="hidden" name="largeURL'+countHeaderImageAdded+'" type= "hiddden" id="'+largeURL[i] +'"><div>'+countHeaderImageAdded+'</div></div><div class="float_L w600"><div><a id="'+imageName+'"href="#">'+ imageName+'</a> <span class="fcGya">[</span> <a onClick="deleteHeader(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div></div><div class="clear_B">&nbsp;</div>';
                                newelement.innerHTML = innerContent;
                                container.appendChild(newelement);
                                document.getElementById('HIMAGE').style.display = "none";
                                document.getElementById("headerImageName"+i).value="Enter Photo Title";
                                document.getElementById("thumbImage"+i).form.reset();
                                document.getElementById("largeImage"+i).form.reset();

                                for(var it=0;it<3;it++)
                                {
                                    if( it == i)
                                    {
                                             thumbImage[it]=0;
                                             largeImage[it]=0;
                                             thumbURL[it]=0;
                                             largeURL[it]=0;
                                    }
                                }

                           }
                           else
                           {
                                        if (imageName == "Enter Photo Title" || imageName == '')
                                        alert("Please provide a valid name for the Header Image");

                                        for(var it=0;it<3;it++)
                                        {
                                            if( it == i)
                                            {
                                                 thumbImage[it]=0;
                                                 largeImage[it]=0;
                                                 thumbURL[it]=0;
                                                 largeURL[it]=0;
                                            }
                                        }
                            }





                      }

               }

         }

    }
    else// Image uplaod fails
    {

        for(var i =0; i< 3; i++)
                {
                       if( largeImage[i] == 1)
                                largeImage[i] =0;
                }
        if( countHeaderImageAdded < 3)
        alert(response);


    }
}

function uploadThumbHeaderImage(ind)
{

    var index = ind;
    if(thumbImage[index] == 0 && countHeaderImageAdded < 3)
    thumbImage[index]=1;
    if(countHeaderImageAdded == 3)
    alert("Sorry, You can not add more than 3 Header Images !! ");

}

function uploadLargeHeaderImage(ind)
{

      var index = ind;
      if(largeImage[index] == 0 && countHeaderImageAdded < 3)
      largeImage[index]=1
      if(countHeaderImageAdded == 3)
      alert("Sorry, You can not add more than 3 Header Images !! ");
}
function deleteHeader(obj)
{

    countHeaderImageAdded--;
    var container=  document.getElementById('addedHeaderImage');
    container.removeChild(obj.parentNode.parentNode.parentNode);

}

//Maps the added Header Images with courses/institutes on clicking save main eader images
function mapHeaderImage()
{
 if( countHeaderImageAdded > 0)
  {
    var mediaElements = document.getElementsByTagName('ol');//fetching all ordered list HTML elements
    var companyElement= mediaElements[4];// fetching the fourth <ol> element in the view page which concerns with  main header images
    var totalListElement = companyElement.getElementsByTagName('li');//fetching the first added Header Image list element

    var listingIdInstitute= new Array();
    var listingTypeInstitute= new Array();
    var orderInstitute= new Array();
    var thumbUrlInstitute= new Array();
    var largeUrlInstitute= new Array();
    var nameI= new Array();
    var instituteIdI= new Array();

    var dummy=0;

    var listElement;
    for(  listElementCount=0; listElement= totalListElement[listElementCount++];)//traversing all list elements added inside <ol>
    {
               var orderElement=listElement.firstChild.firstChild;//orderElement stores the order drop down HTML element of the added company
               listingIdInstitute[dummy]= <?php echo $listingTypeId ;?>;
               instituteIdI[dummy]= <?php echo $listingTypeId ;?>;
               listingTypeInstitute[dummy]= 'institute';
               orderInstitute[dummy]=orderElement.id;
               thumbUrlInstitute[dummy]=listElement.firstChild.firstChild.nextSibling.id;
               largeUrlInstitute[dummy]=listElement.firstChild.firstChild.nextSibling.nextSibling.id;
               nameI[dummy]=listElement.firstChild.nextSibling.firstChild.firstChild.id;
               dummy++;

     }
     var noHeader=1;
     var url = "/listing/posting/MediaPost/mapHeader/";
     var dat = 'listingId='+listingIdInstitute+'&thumbURL='+thumbUrlInstitute+'&largeURL='+largeUrlInstitute+'&order='+orderInstitute+'&listingType='+listingTypeInstitute+'&instituteId='+instituteIdI+'&name='+nameI+'&noHeader='+noHeader;
     new Ajax.Request (url,{method:'post', parameters: dat, onSuccess:function (request) {addedHeader(request.responseText);}});
  }
  else
  {
     var noHeader2=0;
     var url2 = "/listing/posting/MediaPost/mapHeader/"
     var dat2 = 'listingId='+listingIdInstitute+'&thumbURL='+thumbUrlInstitute+'&largeURL='+largeUrlInstitute+'&order='+orderInstitute+'&listingType='+listingTypeInstitute+'&instituteId='+<?php echo $listingTypeId ;?>+'&name='+nameI+'&noHeader='+noHeader2;
     new Ajax.Request (url2,{method:'post', parameters: dat2, onSuccess:function (request) {addedHeader(request.responseText);}});
  }

}





// Javascript below is related to Top recruting Companies : dont change

//var maxTRC= 100;
var deletedIndex = new Array();
if( <?php echo $logo_count ;?> != null || <?php echo $logo_count ;?> != 0 || <?php echo $logo_count; ?> != '')
{

    var countaddedcompanies= <?php echo $logo_count ;?>;
}
else
{
    var countaddedcompanies=0;

}
var correctAddedOrder= countaddedcompanies;
var currentCompany;
var insticheck= new Array();

// facilitating the fetching of the company order on already added top recruitin companies
if( countaddedcompanies > 0)
{

    for( var ik =1; ik <= countaddedcompanies; ik++)
    {
        var temsel= document.getElementById('order'+ik+'');
        var selopt= temsel.name;
        temsel.options[selopt-1].selected = true;
    }

}

// facilitating checking/unchecking of course check on already added top recruitin companies
if( countaddedcompanies > 0)
{   for( var il =1; il <= countaddedcompanies; il++)
    {
        var checkAll=0;
        var temcheck= document.getElementById('course'+il+'');
        var cocheck= temcheck.value;
        if(cocheck == 1)
        {
             temcheck.checked=true;

             var allbox= document.getElementById('Allcourse'+il);
             if( allbox.value == 1)
             {
                allbox.checked= true;
                checkAll=1;


             }

             for( var abc=1; abc <= <?php echo $countCourse;?>; abc++)
             {
                var tempcoursebox= document.getElementById(il+'course'+abc);
                var valcoursebox = tempcoursebox.value;
                if( checkAll == 1)
                {
                    tempcoursebox.checked= true;
                    continue;
                }

                if( valcoursebox == 1)
                    tempcoursebox.checked= true;
                else
                    tempcoursebox.checked= false;
            }

        }
        else
        temcheck.checked=false;
    }

}


function mapCompany(){


                var listElement;
                // The case when already attached comapnies are > 0
                if ( countaddedcompanies > 0)
                {
                        var v=1, flag1=1, flag2=0;
                        var order= Array();// order arrray created to make sure that no to institute/course mapping have the same order for two different companies
                        var mediaElements = document.getElementsByTagName('ol');//fetching all ordered list HTML elements
                        var companyElement= mediaElements[3];// fetching the third <ol> element in the view page which concerns with  top recruting companies
                        var totalListElement = companyElement.getElementsByTagName('li');

                        //Function to make sure that two or more companies don have the same order
                        for( var listElementCount=0; listElement= totalListElement[listElementCount++]; )
                        {

                                var orderSelectElement= listElement.firstChild.firstChild.firstChild;//fetches the comapny order drop down element
                                var selectedIndex=orderSelectElement.selectedIndex+1;// retrieves the value of the index selected in the comapny order drop down menu
                                v=1;
                                while (order[v] != null)//Loop checking if any of the previous option entry is already selected for this position
                                {
                                        flag2=1;
                                        if(selectedIndex == order[v])
                                        flag1=0;
                                        v++;
                                }

                                if(flag2 ==0)
                                order[v]= selectedIndex;
                                if(flag1 !=0)
                                order[v]= selectedIndex;



                         }
                         // checking if there exists any logo not yet associated
                         var delNotAssociated=1;
                         for(listElementCount=0; listElement= totalListElement[listElementCount++];)
                         {
                            var compOrder= listElement.firstChild.firstChild.firstChild;// maintains the orderof the logo attached
                            var currentElementCourse=listElement.firstChild.nextSibling.nextSibling.firstChild.nextSibling.firstChild.nextSibling.nextSibling;//maintains the course checkbox
                            if(!currentElementCourse.checked)
                            {
                                var answer = confirm("Some of the Top Recruiting Company Logos are not associated with any course, Do you want to continue");
                                if (answer)
                                    delNotAssociated=1;
                                else
                                    delNotAssociated=0;

                                break;
                            }

                         }
                         // decrementing the company order in case an association is skipped
                        if( delNotAssociated == 1){
                         for(listElementCount=0; listElement= totalListElement[listElementCount++];)
                         {
                                        compOrder= listElement.firstChild.firstChild.firstChild;// maintains the orderof the logo attached
                                        currentElementCourse=listElement.firstChild.nextSibling.nextSibling.firstChild.nextSibling.firstChild.nextSibling.nextSibling;//maintains the course checkbox
                                        if(!currentElementCourse.checked)
                                        {

                                                var checkOrder= compOrder.selectedIndex;
                                                var tempListElement;

                                                for( tempListElementCount=0; tempListElement= totalListElement[tempListElementCount++];)
                                                {
                                                    var tempCompSelect= tempListElement.firstChild.firstChild.firstChild;
                                                    var tempCompOrder= tempCompSelect.selectedIndex;
                                                    if( tempCompOrder > checkOrder)
                                                        tempCompSelect.options[tempCompOrder-1].selected = true;
                                                }


                                             var container=  document.getElementById('addedCompanyLogo');
                                             container.removeChild(listElement);
                                             countaddedcompanies--;
                                             listElementCount--;

                                             var tempTotalListElement = companyElement.getElementsByTagName('li');
                                             for ( templistElementCount=0; templistElement= tempTotalListElement[templistElementCount++];)
                                             {
                                                        var selectElement = templistElement.firstChild.firstChild.firstChild;
                                                        for( var k= countaddedcompanies; k<= selectElement.options.length-1; k++ )
                                                        selectElement.remove(k);

                                             }



                                        }

                        }
                       }
                        // End of order decrement on skipping of association


                        var listingIdCourse= new Array();
                        var listingTypeCourse= new Array();
                        var orderCompanyCourse= new Array();
                        var logoIdCourse= new Array();
                        var instituteIdCourse= new Array();

                        // flag=1 signifies all company logos have different order
                        if( flag1 == 1 && delNotAssociated==1)
                        {
                                var dummy=0;
                                var alertError=0;

                                for( listElementCount=0; listElement= totalListElement[listElementCount++];)
                                {

                                        var orderElement2= listElement.firstChild.firstChild.firstChild;// maintains the orderof the logo attached
                                        var currentElementCourse=listElement.firstChild.nextSibling.nextSibling.firstChild.nextSibling.firstChild.nextSibling.nextSibling;//maintains the course checkbox
                                        var currentElementInstitute2=listElement.firstChild.nextSibling.nextSibling.firstChild.nextSibling.firstChild.nextSibling;// maintains the institute checkbox : to be used in fetching the logo Id
                                        if(currentElementCourse.checked)
                                        {

                                            var errorAlert=0;
                                            var individualCourseCheckBox = currentElementCourse.nextSibling.nextSibling.nextSibling.firstChild.firstChild.firstChild.nextSibling.firstChild.firstChild;
                                            var allcheck=0;
                                            var ifallcheck=0;
                                            while ( individualCourseCheckBox != null)
                                            {

                                                    allcheck++;
                                                    if( allcheck == 1)
                                                    {
                                                            if( individualCourseCheckBox.checked)
                                                            {

                                                                    errorAlert++;
                                                                    ifallcheck=1;
                                                                    individualCourseCheckBox= individualCourseCheckBox.nextSibling.nextSibling.nextSibling;
                                                                    continue;
                                                            }
                                                    }

                                                    if( ifallcheck ==1 && allcheck > 1)
                                                    {



                                                        listingIdCourse[dummy]= individualCourseCheckBox.name;
                                                        instituteIdCourse[dummy]= <?php echo $listingTypeId ;?>;
                                                        listingTypeCourse[dummy]= 'course';
                                                        orderCompanyCourse[dummy]= orderElement2.selectedIndex+1;
                                                        logoIdCourse[dummy]=currentElementInstitute2.id;
                                                        dummy++;
                                                    }

                                                    if( ifallcheck == 0 && allcheck >1)
                                                    {

                                                        if( individualCourseCheckBox.checked)
                                                        {
                                                                errorAlert++;
                                                                listingIdCourse[dummy]= individualCourseCheckBox.name;
                                                                instituteIdCourse[dummy]= <?php echo $listingTypeId ;?>;
                                                                listingTypeCourse[dummy]= 'course';
                                                                orderCompanyCourse[dummy]= orderElement2.selectedIndex+1;
                                                                logoIdCourse[dummy]=currentElementInstitute2.id;
                                                                dummy++;
                                                        }
                                                    }

                                                    individualCourseCheckBox= individualCourseCheckBox.nextSibling.nextSibling.nextSibling;

                                            }
                                            if(errorAlert == 0)
                                            alertError++;

                                       }

                                }
										
                                if( alertError ==0 )
                                {
                                    var noCompany = 1;
									var coursesAvailable = <?php echo json_encode($coursesAvailable); ?>;
									var dummy = listingIdCourse.length;
									for(var ci=0;ci<coursesAvailable.length;ci++){
										listingIdCourse[dummy] = coursesAvailable[ci]["courseID"];
										logoIdCourse[dummy] = 0;
										orderCompanyCourse[dummy] = 0;
										listingTypeCourse[dummy] = 'course';
										instituteIdCourse[dummy]= <?php echo $listingTypeId;?>;
										dummy++;
									}
                                    url= "/listing/posting/MediaPost/mapTopRecruitingCompanies/";
                                    dat = 'listingId='+listingIdCourse+'&logoId='+logoIdCourse+'&order='+orderCompanyCourse+'&listingType='+listingTypeCourse+'&instituteId='+instituteIdCourse+'&noCompany='+noCompany;
                                    new Ajax.Request (url,{method:'post', parameters: dat, onSuccess:function (request) {addedLogo(request.responseText);}});
                                }
                                else //if( flag1 == 0)
                                alert("You have missed associating one or more attached companies with courses on the course list!! Please associate top recruiting company to at least one course");

                        }
                        else
                        alert("two or more courses have the same order !! Attaching top recruiting companies falied !! ");

                }
                else
                {
                        var noCompany = 0;
                        url= "/listing/posting/MediaPost/mapTopRecruitingCompanies/";
                        dat = 'listingId='+listingIdCourse+'&logoId='+logoIdCourse+'&order='+orderCompanyCourse+'&listingType='+listingTypeCourse+'&instituteId='+<?php echo $listingTypeId ;?>+'&noCompany='+noCompany;
                        new Ajax.Request (url,{method:'post', parameters: dat, onSuccess:function (request) {addedLogo(request.responseText);}});
                }

}

function closeHideOverlay (overId, textId)
{
    var overlayId= overId;
    var textSpaceId = textId;
    document.getElementById(overlayId).style.display='none';
    document.getElementById(textSpaceId).style.display='none';
}


function addCompany(selectindex){
if( countaddedcompanies < 25){


    var sindex= selectindex;
    var index= <?php echo $index ?>;
    var loop= 0;
    var already_present_flag;
    while ( loop <= index)
    {
        if( document.getElementById(sindex).options[loop].selected == true)
        {
            var id = document.getElementById(sindex).value;
	/*code added to check if comapny already added: */
	    $j(".logo_ids").each(function(index){
		    if ($j(this).val() ==id) {
			    already_present_flag=1 ;
			    return;
		    }
		    });
	    if (already_present_flag==1) {
		    alert("Cannot add company. Company already added.");
		    break;//return false;
	    }
	/* code added to check if comapny already added: ENDS*/
                    if( id != "no")
                    {
                        countaddedcompanies++;
                        correctAddedOrder++;


                        url ="/enterprise/ShowForms/addLogoListing/";
                        data = 'id='+id+'&listingTypeId='+<?php echo $listingTypeId?>;
                        new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {addCompanyListing(request.responseText);}});
                        break;
                     }
        }
        loop++;
    }

}
else
alert("Sorry, You can not add more than 25  Top Recruiting Companies");
}

function addCompanyListing(response){


    var ajaxresponse= eval(response);
    var id=ajaxresponse[0]['id'];
    var name=ajaxresponse[0]['company_name'];
    var url=ajaxresponse[0]['logo_url'];
    var localCompanyCount= correctAddedOrder;
    var tempCount= countaddedcompanies;
    var innercontent= '<div id="'+correctAddedOrder+'" class="float_L w60"><div><select id="order'+countaddedcompanies+'"style="width:50px">';
    for( var ct=1;ct <= countaddedcompanies; ct++)
    {
        var tempInnerHtml = '<option >'+ct+'</option>';
        innercontent = innercontent + tempInnerHtml;
    }
    innercontent= innercontent+ '</select></div></div><div class="float_L w130"><div><img src="'+url+'" border="0" /></div></div><div class="float_L w600"><div><a href="#">'+name+'</a> <span class="fcGya">[</span> <a name="'+name+'" onClick="deleteCompany(this)" href="javascript: void(0)">Delete</a> <span class="fcGya">]</span></div><div>Associate with: <input id= "'+id+'" type="hidden" /><input type="checkbox" id="course'+correctAddedOrder+'" onClick="showCourseOverlay(this, '+localCompanyCount+',\'company\')" /> Course <input id ="text'+localCompanyCount+'" style="display : none"type="text" class="lfsBx" value="Select" /><div class="posRelative" style="display:none" id="sOverlay_institute'+correctAddedOrder+'" ><div class="brd bgWhite" style="position:absolute;left: ; top:; width:390px"><div class="pf10"><div class="bld">Course</div><div style="height:110px;overflow:auto" class="mb10"><div style="width:390px;line-height:18px" id="inputBlock"><input type="checkbox" id="Allcourse'+correctAddedOrder+'" value="" name="" onClick="clickAll(this)"/>All<br/><?php foreach($coursesAvailableNew as $course) { $courseId = $course['courseID']; $courseName = $course['courseName']; ?><input type="checkbox" onclick="updateClickAll('+correctAddedOrder+')" name="<?php echo $courseId; ?>"   id="<?php echo $courseId;?>" value="<?php echo addslashes(trim($courseName)); ?>" /> <?php echo addslashes(trim($courseName)); ?><br /><?php } ?></div></div><div class="line_1"></div><div class="mt10" align="center"><input type="button" class="btnOkk" value="&nbsp;" onclick="closeHideOverlay(\'sOverlay_institute'+correctAddedOrder+'\',\'text'+localCompanyCount+'\')" /></div></div></div></div></div></div> <div class="clear_B">&nbsp;</div>';
    innercontent= innercontent+ '<input type = "hidden" class="logo_ids" value="'+id+'"/>';
    // adding new company Logo to already attached companies
    var container = document.getElementById('addedCompanyLogo');
    var newelement = document.createElement('li');
    newelement.Id= id;
    newelement.innerHTML = innercontent;
    container.appendChild(newelement);
    document.getElementById('TRC').style.display = "none";
    // to dynamicallly arrange the drop down for order of already attached comapnies
    var mediaElements = document.getElementsByTagName('ol');
    var companyElement= mediaElements[3];
    var totalListElement = companyElement.getElementsByTagName('li');
    var listElement;
    //alert(totalListElement.length);
    for ( var listElementCount=0; listElement= totalListElement[listElementCount++];)
    {

        var selectElement = listElement.firstChild.firstChild.firstChild;
        selectElement.options[countaddedcompanies-1] = new Option(countaddedcompanies, countaddedcompanies);

        if ( listElementCount == countaddedcompanies)
        selectElement.options[countaddedcompanies-1].selected = true;
    }


}

function showCourseOverlay(courseObj, localCompanyCount, stuff){

        var mediatype = stuff;
              if(mediatype == 'company')
              {


                        currentCompany= localCompanyCount;
                        if( courseObj.checked)
                        {
                                    var mediaElements = document.getElementsByTagName('ol');
                                    var companyElement= mediaElements[3];
                                    var totalListElement = companyElement.getElementsByTagName('li');
                                    var listElement;
                                    for ( var listElementCount=0; listElement= totalListElement[listElementCount++];)
                                    {
                                        var courseCheckBx=listElement.firstChild.nextSibling.nextSibling.firstChild.nextSibling.firstChild.nextSibling.nextSibling;
                                        var courseTextAr=courseCheckBx.nextSibling.nextSibling;
                                        var courseOverLay=courseTextAr.nextSibling;
                                        if (courseCheckBx.checked)
                                        {
                                            courseTextAr.style.display='none';
                                            courseOverLay.style.display='none';
                                        }
                                    }
                        }
                        var textAreaObj= courseObj.nextSibling.nextSibling;
                        var objOver = document.getElementById("sOverlay_institute"+currentCompany);
                        if( courseObj.checked)
                        {
                                textAreaObj.style.display= '';
                                objOver.style.left= 150+'px';
                                objOver.style.display = "";
                        }
                        else
                        {
                                textAreaObj.style.display= 'none';
                                objOver.style.display = 'none';
                        }

              }
           

}

function deleteCompany(delobj)
{

var answer1 = confirm("Are you sure you want to delete "+delobj.name);
if (answer1){

  countaddedcompanies--;
  var container=  document.getElementById('addedCompanyLogo');
  var delIndex = delobj.parentNode.parentNode.previousSibling.previousSibling.id;
  var delOrderIndex = delobj.parentNode.parentNode.parentNode.firstChild.firstChild.firstChild.selectedIndex;
  deletedIndex.push(delIndex);
  var tempOrder = delobj.parentNode.parentNode.parentNode.firstChild.firstChild.firstChild.selectedIndex;
  container.removeChild(delobj.parentNode.parentNode.parentNode);

  var mediaElements = document.getElementsByTagName('ol');
  var companyElement= mediaElements[3];
  var totalListElement = companyElement.getElementsByTagName('li');
  var listElement;

  for ( var listElementCount=0; listElement= totalListElement[listElementCount++];)
  {
        var selectElement = listElement.firstChild.firstChild.firstChild;
        var getOrder = selectElement.selectedIndex;

        if ( getOrder > tempOrder)
        selectElement.options[getOrder-1].selected= true;
  }

  for ( listElementCount=0; listElement= totalListElement[listElementCount++];)
  {
        var selectElement = listElement.firstChild.firstChild.firstChild;
        for( var k= countaddedcompanies; k<= selectElement.options.length-1; k++ )
        selectElement.remove(k);

  }

 }

}
function clickAll(obj)
{
    var allCheckBoxObject = obj;
    if ( allCheckBoxObject.checked == true)
    {
        allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
        while ( allCheckBoxObject != null)
        {

            allCheckBoxObject.checked= true;
            allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
        }
    }
    else
    {
        allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
        while ( allCheckBoxObject != null)
        {

            allCheckBoxObject.checked= false;
            allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
        }
    }
}

function updateClickAll(id)
{
    var allCheckBoxObject = document.getElementById('Allcourse'+id);
    
	var allChecked = true;
	
    allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
    while ( allCheckBoxObject != null)
    {
		if(allCheckBoxObject.checked == false)
		{
				allChecked = false;
				break;
		}
		allCheckBoxObject=allCheckBoxObject.nextSibling.nextSibling.nextSibling;
    }
	
	document.getElementById('Allcourse'+id).checked = allChecked;
}


function trim(sValue)
{
		return sValue.replace(/^\s+|\s+$/g, "");
}

function addedLogo(response)
{
		document.getElementById('TRC').style.display = "block";
}

function addedHeader(response)
{
		document.getElementById('HIMAGE').style.display = "block";
}
// End of Javascript for top recruiting companies
MAX_FILES_UPLOAD = 25;
NEW_FILES_ADD = 3;
addMoreUploadFields(document.getElementById('addMoreFordocuments'),'documents','1');
addMoreUploadFields(document.getElementById('addMoreForphotos'),'photos','1');
addMoreUploadFields(document.getElementById('addMoreForvideos'),'videos','1');
assignAjaxUploadToFields();
// innercontent= innercontent+ '</select></div></div><div class="float_L w130"><div><img src="'+url+'" border="0" /></div></div><div class="float_L w600"><div><a href="#">'+name+'</a> <span class="fcGya">[</span> <a onClick="deleteCompany(this)" href="#">Delete</a> <span class="fcGya">]</span></div><div>Associate with: <input id= "'+id+'" type="checkbox" /> Institute <input type="checkbox" id="course'+countaddedcompanies+'" onClick="showCourseOverlay(this, '+localCompanyCount+')" /> Course <input style="display : none"type="text" class="lfsBx" value="Select" /></div></div> <div class="clear_B">&nbsp;</div>';
</script>

