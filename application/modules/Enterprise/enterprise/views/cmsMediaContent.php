<?php
    global $maxVideos;
    global $maxPhotos;
    global $maxDocs;
    global $featuredLogo;
    global $featuredPanel;
    if($listingType == 'course'){
        $listTypeId = $course_id;
    }
    if($listingType == 'institute'){
        $listTypeId = $institute_id;
    }
?>
<div class="lineSpace_10">&nbsp;</div>
<div class="row" id="mediaCourse" style="display:none;">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Media Content</span></div>
   <div class="lineSpace_5">&nbsp;</div>
   <div class="grayLine"></div>							
</div>
<div class="lineSpace_10">&nbsp;</div>

<div class="row" id="mediaCourseName" style="display:none;">
   <div class="" style="width:80%">

       <div id="editVids" style="float:left; width:33%; display:none;">
           <?php if(isset($packType)){ ?> 
           <div class="normaltxt_11p_blk bld">Edit Video(s): </div> </br>
           <?php }else{ ?>
           <div class="normaltxt_11p_blk bld">Upload Video(s): </div> </br>
         <?php } ?>
         <?php
             $v=0;
             if(isset($videos)){
                 foreach($videos as $cmsVideos)
                 {
                     echo '<img id="videos_fetched_'.$v.'" width="119" height="78" border="0" src="'.$cmsVideos["thumburl"].'"/>
                     <div id="videos_anchor_'.$v.'"><a  onClick="removeCourseMedia('.$listTypeId.',\'video\','.$cmsVideos["videoid"].','.$v.',\''.$listingType.'\');" href="javascript:void(0);" > Remove </a>'.$cmsVideos["name"].'</div></br></br>';
                     $v++;
                 }
             }
         ?>
         <?php
             for($i=0;$i<$maxVideos;$i++){
               if($i<$v){
                   echo '<input type="text" name="c_videos_caption[]" id="c_videos_caption'.$i.'" size="14" style="display:none;"/>&nbsp;&nbsp;<input type="file" name="c_videos[]" id="c_videos_'.$i.'" size="4" style="display:none;"/></br></br>';
               }else{
                   echo '<input type="text" name="c_videos_caption[]" id="c_videos_caption'.$i.'" size="14" style=""/>&nbsp;&nbsp;<input type="file" name="c_videos[]" id="c_videos_'.$i.'" size="4" style=""/></br></br>';
               }
            }
         ?>
      </div>


      <div id="editPhotos" style="float:left; width:33%; display:none;">
           <?php if(isset($packType)){ ?> 
           <div class="normaltxt_11p_blk bld">Edit Photo(s): </div> </br>
           <?php }else{ ?>
           <div class="normaltxt_11p_blk bld">Upload Photo(s): </div> </br>
         <?php } ?>
         <?php
            $p=0;
            if(isset($photos)){
                foreach($photos as $cmsPhotos)
                {
                    echo '<img id="photos_fetched_'.$p.'" width="119" height="78" border="0" src="'.$cmsPhotos["thumburl"].'"/>
                    <div id="photo_anchor_'.$p.'"><a onClick="removeCourseMedia('.$listTypeId.',\'photo\','.$cmsPhotos["photoid"].','.$p.',\''.$listingType.'\');" href="javascript:void(0);" > Remove </a>'.$cmsPhotos["name"].'</div></br></br>';
                    $p++;
                }
            }
         ?>
         <?php
             for($i=0;$i<$maxPhotos;$i++){
               if($i<$p){
                   echo '<input type="text" name="c_images_caption[]" id="c_images_caption'.$i.'" size="14" style="display:none;"/>&nbsp;&nbsp;<input type="file" name="c_images[]" id="c_images_'.$i.'" size="4" style="display:none;"/></br></br>';
               }else{
                   echo '<input type="text" name="c_images_caption[]" id="c_images_caption'.$i.'" size="14" style=""/>&nbsp;&nbsp;<input type="file" name="c_images[]" id="c_images_'.$i.'" size="4" style=""/></br></br>';
               }
            }
         ?>
      </div>


      <div id="editDocs" style="float:left; width:33%; display:none;">
           <?php if(isset($packType)){ ?> 
           <div class="normaltxt_11p_blk bld">Edit Document(s): </div> </br>
           <?php }else{ ?>
           <div class="normaltxt_11p_blk bld">Upload Document(s): </div> </br>
         <?php } ?>
         <?php
            $d=0;
            if(isset($docs)){
                foreach($docs as $cmsDocs)
                {
                    echo '<img id="docs_fetched_'.$d.'" width="119" height="78" border="0" style="display:none;" src="'.$cmsDocs["thumburl"].'"/>';
                    echo '<div id="docs_anchor_'.$d.'"><a onClick="removeCourseMedia('.$listTypeId.',\'document\','.$cmsDocs["docid"].','.$d.',\''.$listingType.'\');" href="javascript:void(0);" > Remove </a>'.$cmsDocs["name"].'</div></br></br>';
                    $d++;
                }
            }
         ?>
         <?php
             for($i=0;$i<$maxDocs;$i++){
               if($i<$d){
                   echo '<input type="text" name="c_docs_caption[]" id="c_docs_caption'.$i.'" size="14" style="display:none;"/>&nbsp;&nbsp;<input type="file" name="c_docs[]" id="c_docs_'.$i.'" size="4" style="display:none;" /></br></br>';
               }else{
                   echo '<input type="text" name="c_docs_caption[]" id="c_docs_caption'.$i.'" size="14" style=""/>&nbsp;&nbsp;<input type="file" name="c_docs[]" id="c_docs_'.$i.'" size="4" style="" /></br></br>';
               }
            }
         ?>
      </div>
      <div class="clear_L"></div>
   </div>



   <div class="lineSpace_10">&nbsp;</div>
   <div class="grayLine"></div>
</div>
