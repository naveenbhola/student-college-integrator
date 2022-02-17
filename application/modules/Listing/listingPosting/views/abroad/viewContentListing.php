<div class="abroad-cms-rt-box">
<script>
var URLForContent = "<?= $URL."?searchTyp=".$searchTypeOption ?>";
function changeContentSearchType(){
	var optionVal = document.getElementById('contentSearchType').value;
	var LoadURLForContent = URLForContent.concat(optionVal)
	var searchString = document.getElementById('q').value;
	if(searchString != "Search Content")
	{
		LoadURLForContent = LoadURLForContent+"&q="+searchString;
	}
	
location.assign(LoadURLForContent);
}
</script>
	<div class="abroad-cms-head" style="margin-top:0;">
	<h2 class="abroad-sub-title">All Content</h2>
	<div class="flRt"><a href="<?php echo ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_CONTENT;?>" class="orange-btn" style="padding:6px 7px 8px">+ Add New Content</a></div>
    </div>
    <div class="search-section">
		<div class="adv-search-sec">
	    <div class="cms-adv-box">	
        <div class="cms-search-box search-box-width" style="width:340px;">
    	<form name="searchContent" action="<?=$URL?>">	
	    	<select name = "searchTyp" class="universal-select art-guide-list" id = "contentSearchType" onchange = "changeContentSearchType()" >
				<?php foreach($searchTypeOptions as $searchTypeOption) {?>
	 			<option value = "<?=$searchTypeOption ?>" <?php if(strtoupper($searchTypeOption) == $searchType) { echo 'selected' ;} ?>><?=$searchTypeOption ?></option>
				<?php }?>
			</select>	
	        <i class="abroad-cms-sprite search-icon"></i>
	    	<input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'width: 105px;color:black' : 'width: 115px;' ?>" defaulttext="Search Content" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?html_escape($searchTerm):"Search Content";?>" class="search-field"/>
			<?php if($searchTerm != ''){ ?>
			<i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchContent.submit();"></i>
			<?php } ?>
			<a href="javascript:void(0);" onclick="document.searchContent.submit();" class="search-btn">Search</a>
		</form>
		</div>
	    </div>
	    <div class="flRt display-sec">
        <ul>
        	<li>Show:</li>
			<?php $activeClass = "all";	if($displayDataStatus)	?>
        	<li class="<?=(!in_array($displayDataStatus,array('draft','published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT.$queryParams?>">All (<?=empty($totalResultCount["all_count"])? 0 : $totalResultCount["all_count"] ?>)</a></li>
            <li><span class="cms-seperator"> | </span></li>
            <li class="<?=(in_array($displayDataStatus,array('published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT."/published".$queryParams?>">Published (<?=empty($totalResultCount["published_count"])? 0 : $totalResultCount["published_count"]?>)</a></li>
            <li><span class="cms-seperator"> | </span></li>
            <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT."/draft".$queryParams?>">Drafts (<?=empty($totalResultCount["draft_count"])? 0 : $totalResultCount["draft_count"]?>)</a></li>
        </ul>
	    <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
	    </div>
	    <div class="clearFix"></div>
	</div>
	<table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
		<tr>
		<th width="5%" align="center">S.No.</th>
		<th width="50%">
		    <span class="flLt">Name</span>
		</th>
		<th width="15%">
		    <span class="flLt">Content Type</span>
		</th>
		<th width="15%">
		    <span class="flLt">Tags</span>
		</th>
		<th width="14%">
		<span class="flLt">Date</span>
		</th>
	    </tr>
	      <?php if(empty($reportData)){?>
		<tr>
            <td align="center">&nbsp;</td>
			<td colspan=4><i>No Results Found !!!</i></td>
		</tr>
		<?php }
		$count = $paginator->getLimitOffset() + 1;
		foreach($reportData as $key=>$value){ ?>
	    <tr>
		<td align="center"><?=($count++)?>.</td>
		<td>
			<p><?= stripcslashes(htmlspecialchars($value["contentName"])); ?></p>
			<?php if(!empty($value["courseType"])){?>
			<p class="cms-sub-cat"><?=implode(", ",$value["courseType"]) ?></p>
			<?php } else {?>
			<p class="cms-sub-cat"><?="-" ?></p>
			<?php }?>
		    
		    <div class="edit-del-sec">
			<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_CONTENT."/".$value["contentId"]?>">Edit</a>&nbsp;&nbsp;
			<?php
						// show delete link only to SA admin
						if($usergroup == 'saAdmin' || $usergroup == 'saContent')
						{
						?>
								<a href="javascript:void(0);" onclick="delete_row('<?=ENT_SA_FORM_DELETE_CONTENT?>',<?=$value["contentId"]?>)">Delete</a>
						<?php
						}
							
			?>
			<span class="flRt font-11"><?php if(strtoupper($value["ContentType"]) == "GUIDE"){ echo $value["noOfSection"]." Section | ";}?><?php echo $value['commentCount'] ?> Comments | <?php echo $value['viewCount']; ?> Views</span>
		    </div>
		</td>
		<td>
			<p>
				<?php // echo $value["ContentType"]=="examPage"?"Exam":ucfirst($value["ContentType"]); ?>
				<?php if($value["ContentType"]=="examPage") { echo "Exam";}
					  else if($value["ContentType"]=="applyContent") { echo "Apply Content";}
					  else { echo ucfirst($value["ContentType"]);}?>
			</p>
			<!-- <?php if(strtoupper($value["ContentType"]) == "GUIDE" || strtoupper($value["ContentType"]) == "APPLYCONTENT"){?>
			<div class="edit-del-sec">
			<?php 	if($downloadInfoArray[$value["contentId"]]=='')
					$noOfDownloads = 0;
				else
					$noOfDownloads = $downloadInfoArray[$value["contentId"]];
				if($noOfDownloads == 1)
					$str = 'Download';
				else
					$str = 'Downloads';
				?>
			    <span class="flLt font-11" style="margin-top:21px;"><?=$noOfDownloads;?> <?=$str;?></span>
		    </div>
			<?php }?> -->
		</td>

		<td> <?php if(!empty($value["ExamType"]) && empty($value["appContId"])){?>
				<p><?= $value['ExamType'];?></p>
			<?php }elseif((!empty($value["countryNames"]) || !empty($value["examNames"])) && empty($value["appContId"])) {?>
		    	<p><?=htmlspecialchars(implode(", ",explode(",",$value["countryNames"])))?></p>
		    	<p><?=htmlspecialchars(implode(", ",explode(",",$value["examNames"])))?></p>
		    <?php } elseif(!empty($value["applyContentTag"])){?>
				<p><?=htmlentities($value["applyContentTag"]); ?></p>
		    <?php } else {?>
		    	<p><?="-"?></p>
		    <?php }?>
		</td>
		<td>
           	<p class="cms-table-date"><?=(date("d M y",strtotime($value["submitDate"])))?></p>
				<?php
				   if( $value["status"] == ENT_SA_PRE_LIVE_STATUS)
				      echo "<p class=\"publish-clr\">Published</p>";
				   else if( $value["status"] == 'draft')
				      echo "<p class=\"draft-clr\">Draft</p>";
				?>                            
       </td>
		
	    </tr>
	   <?php }?> 
	</table>
    </div>
     <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>
<div class="clearFix"></div>
</div>  