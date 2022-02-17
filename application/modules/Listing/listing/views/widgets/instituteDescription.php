<?php if(!empty($instituteWiki)){?>

<?php $wiki = base64_encode(json_encode($instituteWiki));
$JsInstituteTitle =  (strlen($details['title'])>40)?substr($details['title'],0,40)."...":$details['title'];
//echo "<pre>";print_r(json_decode(base64_decode($wiki)));echo "</pre>";
?>
<div class="nlt_srlBar" >
<div id = "instituteMoveTab"></div>
    <div id ="instituteBar" class="nlt_l_mLnk" style="display:block;text-decoration:none;color:#0065DE;padding-left:0">
		 <span style="cursor:pointer" onClick ="trackEventByGA('LinkClick','LISTING_OVERVIEW_EXPAND_INSTITUTE_LINK'); showInstituteWiki(<?php echo $listing_type_id; ?>,'<?php echo $identifier;?>','<?php echo $wiki;?>')">
		 <span style="padding-left:28px">Institute Details</span>
		 </span>
		<span class="Fnt11">
			&nbsp; - &nbsp;
		<?php
			for($count=0; $count<4 && $count<count($wikiCaption['0']);$count++){
							if($wikiCaption['0'][$count]['caption'] == 'Institute Description'){
							$wikiCaption['0'][$count]['caption1'] = 'Description';
							}
							if($wikiCaption['0'][$count]['caption'] == 'Infrastructure / Teaching Facilities'){
							$wikiCaption['0'][$count]['caption1'] = 'Infrastructure';
							}
							if($wikiCaption['0'][$count]['caption'] == 'Top Recruiting Companies'){
							$wikiCaption['0'][$count]['caption1'] = 'Recruiting Companies';
							}
		?>
		<span class="Fnt11 nbld" style="color:#000000" onclick="moveToDiv1();">
			<a href="#institute_<?=$wikiCaption['0'][$count]['caption']?>"><?php echo $wikiCaption['0'][$count]['caption1']?$wikiCaption['0'][$count]['caption1']:$wikiCaption['0'][$count]['caption']; ?></a>
		</span>
		<span class="sepClr">|</span>
		<?php }?>
		</span>
<script>
function moveToDiv1(){
	if($('instituteCaption_rem').style.display == 'none')
		showInstituteWiki(<?=$listing_type_id?>,'<?=$identifier?>','<?=$wiki?>');
}
</script> 
	</div>
</div>
<div style="display:none" id="instituteNameForJS"><?php echo $details['title']; ?></div>							
<div id ="instituteDescription_nl">
            <?php
            if($identifier == 'institute'){
            $totalCaption = count($instituteWiki);
            if($totalCaption!=0){
	    $caption = $instituteWiki[0]['caption'];
            $attributeDescription = $instituteWiki[0]['attributeValue'];
	    $attributeDescriptionDemo = (strlen($attributeDescription)<501)?trimmed_tidy_repair_string($attributeDescription):trimmed_tidy_repair_string(substr($attributeDescription,0,500))."..";
	    $readMore = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onmouseover = \"\" onclick=\"showRemainingWikiPart('institute',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Read More</a></div>";
	    ?>
	    <div id = "instituteCaption_demo" class="editor_content mlr10" style = "display:block" onmouseover = "modifiedMouseOver('institute',<?php echo $totalCaption;?>)"  onmouseout = "mouseOutHandlingFunction('institute')">
                <div class = Fnt14 ><p><strong><?php echo $caption;?></strong><br/></p></div>
                <div style="margin-top:10px">
                <div style="padding-left:10px"><?php echo $attributeDescriptionDemo; ?></div>
                </div>
            </div>
	    <div id = "instituteReadMore">
		<?php echo $readMore; ?>
	    </div>  
	    <div id = "instituteCaption_rem" style = "display:none">
	    <?php
            for($i= 0;$i<$totalCaption;$i++){
                $caption = $instituteWiki[$i]['caption'];
                $attributeDescription = $instituteWiki[$i]['attributeValue'];
                ?>
                <div class="editor_content mlr10">
                <div class = Fnt14 id="institute_<?php echo $caption;?>"><p><strong><?php echo $caption;?></strong><br/></p></div>
                <div style="margin-top:10px">
                <div style="padding-left:10px"><?php echo $attributeDescription; ?></div>
		<?php 
		if($i==$totalCaption-1){
        $readLess = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onclick=\"hideRemainingWikiPart('institute',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Less</a></div>";
        if($details['packType']=='1'||$details['packType']=='2'){
		$readLess = "<div class=\"mt10 Fnt13 bld \"><a href=\"javascript:void(0)\" onclick=\"hideRemainingWikiPart('institute',$totalCaption)\" class=\"sprt_nlt rArrow\" style=\"padding-left:0\" >Less</a></div><div class=\"mt10 Fnt13 bld \" style=\"height:26px;overflow:hidden\"><a href=\"javascript:void(0)\" onclick=\"document.getElementById('reqInfoDispName_foralert_detail').focus()\" style=\"float:left;padding:4px 10px;background:#ff8601;color:#fff;text-decoration:none;border:1px solid #cc750c;border-bottom:1px solid #a94c09\" >I am interested in studying at ".$JsInstituteTitle."</a></div>";
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
<?php if($identifier=='course'){
$wiki1 = base64_decode($wiki);
?>
<script>
        function showWikiOnOtherPage(){
	  	var decoded = <?=$wiki1?>;
        instituteNameJS = '<?php echo addslashes($JsInstituteTitle);?>';
	showInstituteWiki('<?php echo $institute_id;?>','onCoursePageRefresh',decoded);

	  }			
</script>
<?php }?>
<?php }?>


