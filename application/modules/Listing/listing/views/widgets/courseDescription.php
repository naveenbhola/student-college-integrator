<?php if(!empty($courseWiki)){
?>
<?php $wiki = base64_encode(json_encode($courseWiki));
$JsInstituteTitle =  (strlen($details['title'])>40)?substr($details['title'],0,40)."...":$details['title'];
?>

<script>

    </script>

<div class="wdh100 mb15">
<div  class="nlt_srlBar">
<div id = "courseMoveTab"></div>
	<div id ="courseBar" class="nlt_l_mLnk" style="display:block;text-decoration:none;color:#0065DE;padding-left:0">
		<span style="cursor:pointer;" onClick ="trackEventByGA('LinkClick','LISTING_OVERVIEW_EXPAND_COURSE_LINK'); showCourseWiki(<?php echo $course_id;?>,'<?php echo $identifier;?>','<?php echo $wiki;?>');">
			<span style="padding-left:28px">Course Details</span>
		</span>
		<span class="Fnt11">
			&nbsp; - &nbsp;
		
		<?php
			for($count=0; $count<4 && $count<count($wikiCaption['1']);$count++){
			 if($wikiCaption['1'][$count]['caption'] == 'Course Description'){
					$wikiCaption['1'][$count]['caption1'] = 'Description';
				}
				if($wikiCaption['1'][$count]['caption'] == 'Infrastructure / Teaching Facilities'){
					$wikiCaption['1'][$count]['caption1'] = 'Infrastructure';
				}
				if($wikiCaption['1'][$count]['caption'] == 'Top Recruiting Companies'){
					$wikiCaption['1'][$count]['caption1'] = 'Recruiting Companies';
			   }
		?>
		<span class="Fnt11 nbld" style="color:#000000" onclick="moveToDiv();">
			<a href="#course_<?=$wikiCaption['1'][$count]['caption']?>"><?php echo $wikiCaption['1'][$count]['caption1']?$wikiCaption['1'][$count]['caption1']:$wikiCaption['1'][$count]['caption']; ?></a>
		</span>
		<span class="sepClr">|</span>
		<?php }?>
		</span>
<script>
	function moveToDiv(){
		if($('courseCaption_rem').style.display == 'none')
			showCourseWiki(<?=$course_id?>,'<?=$identifier?>','<?=$wiki?>');
}
</script>
	</div>
</div>

<div style='display:none' id='courseNameForJS'><?php echo $details['title']; ?></div>
<div id ="courseDescription_nl">
<?php
            if($identifier == 'course'){
            $totalCaption = count($courseWiki);
            if($totalCaption!=0){
	    $caption = $courseWiki[0]['caption'];
            $attributeDescription = $courseWiki[0]['attributeValue'];
	    //error_log(print_r($attributeDescription,true),3,'/home/aakash/Desktop/aakash.log');
	    $attributeDescriptionDemo = (strlen($attributeDescription)<501)?trimmed_tidy_repair_string($attributeDescription):trimmed_tidy_repair_string(substr($attributeDescription,0,500))."..";
	    //error_log(print_r($attributeDescriptionDemo,true),3,'/home/aakash/Desktop/aakash.log');
	    $readMore = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onclick=\"showRemainingWikiPart('course',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Read More</a></div>";
	    ?>
	    <div id = "courseCaption_demo" class="editor_content mlr10" style = "display:block" onmouseover = "modifiedMouseOver('course',<?php echo $totalCaption;?>)"  onmouseout = "mouseOutHandlingFunction('course')">
            <div class = "Fnt14"><strong><?php echo $caption;?></strong><br/></p></div>
            <div style="margin-top:10px">
            <div style="padding-left:10px"><?php echo $attributeDescriptionDemo; ?></div>
            </div>
            </div>
	    <div id = "courseReadMore" style="display:block">
		<?php echo $readMore; ?>
	    </div>  
	    <div id = "courseCaption_rem" style = "display:none">
	    <?php
            for($i= 0;$i<$totalCaption;$i++){
                $caption = $courseWiki[$i]['caption'];
                $attributeDescription = $courseWiki[$i]['attributeValue'];
		//error_log(print_r($attributeDescription,true),3,'/home/aakash/Desktop/aakash.log');
		//error_log(print_r($i,true),3,'/home/aakash/Desktop/aakash.log');
                ?>
		
                <div class="editor_content mlr10">
                <div class = "Fnt14" id="course_<?php echo $caption;?>"><p><strong><?php echo $caption;?></strong><br/></p></div>
                <div style="margin-top:10px">
                <div style="padding-left:10px"><?php echo $attributeDescription; ?></div>
		<?php 
		if($i==$totalCaption-1){
        $readLess = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onclick=\"hideRemainingWikiPart('course',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Less</a></div>";
        if($details['packType']=='1'||$details['packType']=='2'){
		$readLess = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onclick=\"hideRemainingWikiPart('course',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Less</a></div><div class=\"mt10 Fnt13 bld \" style=\"height:26px;overflow:hidden\"><a href=\"javascript:void(0)\" onclick=\"document.getElementById('reqInfoDispName_foralert_detail').focus()\" style=\"float:left;padding:4px 10px;background:#ff8601;color:#fff;text-decoration:none;border:1px solid #cc750c;border-bottom:1px solid #a94c09\" >I am interested in studying at ".$JsInstituteTitle."</a></div>";
        }
		echo $readLess;
		}
		?>
                </div>
                </div>
		<?php
            }?>
	    </div>
	    <?php
	    }
            }
?>
    </div>

    </div>

<?php if($identifier=='course'){?>
<script>
            showCourseWiki('<?php echo $course_id;?>','<?php echo $identifier;?>');

    </script>
<?php }?>

<?php if($identifier=='institute'){
$wiki1 = base64_decode($wiki);
?>
<script>
        function showWikiOnOtherPage(){
	  	var decoded = <?=$wiki1?>;
        instituteNameJS = '<?php echo addslashes($JsInstituteTitle);?>';
	showCourseWiki('<?php echo $course_id;?>','onInstitutePageRefresh',decoded);

	  }			
</script>
<?php }?>

<?php }?>
