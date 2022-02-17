<div class="abroad-cms-rt-box">
<script>
var URLForContent = "<?= $URL."?searchTyp=".$searchTypeOption ?>";
function changeContentSearchType(){
	var optionVal = document.getElementById('searchTypeSelect').value;
	var LoadURLForContent = URLForContent.concat(optionVal)
	var searchString = document.getElementById('q').value;
	if(searchString != "Search <?= $searchType;?>")
	{
		LoadURLForContent = LoadURLForContent+"&q="+searchString;
	}
	
location.assign(LoadURLForContent);
}
</script>
		<div class="abroad-cms-head" style="margin-top:0;">
	<h2 class="abroad-sub-title">Map Consultants & Universities </h2>
	<div class="flRt"><a href="<?php echo ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING;?>" class="orange-btn" style="padding:6px 7px 8px">+ Add New Mapping</a></div>
    </div>
    <div class="search-section">
		<div class="adv-search-sec">
	    <div class="cms-adv-box">	
                		<div class="cms-search-box search-box-width" style="width: 355px;">
                                        <form name="searchContent" action="<?=$URL?>">	
                                            <select name = "searchTyp" class="universal-select art-guide-list" id = "searchTypeSelect" onchange = "changeContentSearchType()" >
                                                <?php foreach($searchTypeOptions as $searchTypeOption) {?>
                                                 <option value = "<?=$searchTypeOption ?>" <?php if($searchTypeOption == $searchType) { echo 'selected' ;} ?>><?=$searchTypeOption ?></option>
                                                <?php }?>
                                            </select>	
                                            <i class="abroad-cms-sprite search-icon"></i>
                                            <input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'width: 140px;color:black' : 'width: 145px;' ?>" defaulttext="Search <?= $searchType;?>" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?html_escape($searchTerm):"Search ".$searchType;?>" class="search-field"/>
                                            <?php if($searchTerm != '')
                                            { ?>
                                            <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchContent.submit();"></i>
                                            <?php
                                            } ?>
                                        <a href="javascript:void(0);" onclick="document.searchContent.submit();" class="search-btn">Search</a>
                                        </form>
				</div>
		    </div>
	    <div class="flRt display-sec">
	                     <ul>
                    	<li>Show:</li>
			<?php
				$activeClass = "all";
				if($displayDataStatus)
				
			?>
                    	<li class="active"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE.$queryParams?>">All (<?=empty($totalResultCount["all_count"])? 0 : $totalResultCount["all_count"] ?>)</a></li>
                    </ul>
	  <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
	    </div>
	    <div class="clearFix"></div>
	</div>
	<table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
		<tr>
		<th width="5%" align="center">S.No.</th>
		<th width="34%">
		    <span class="flLt">Consultant</span>
		</th>
		<th width="34%">
		    <span class="flLt">University Mapped</span>
		</th>
		<th width="12%">
		    <span class="flLt">Certified by University</span>
		</th>
		<th width="14%">
		<span class="flLt">Updated on</span>
		</th>
	    </tr>
	      <?php
	      
	     if(empty($reportData))
		{
		?>
		<tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=4><i>No Results Found !!!</i></td>
		</tr>
		<?php
		}
		$count = $paginator->getLimitOffset() + 1;
		foreach($reportData as $key=>$value)
		{
		?>
	    <tr>
		<td align="center"><?=($count++)?>.</td>
		<td>
		    <p><?= stripcslashes(htmlspecialchars($value["consultantName"])); ?></p>
		    <div class="edit-del-sec">
			<a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING."/?consutId=".$value["consultantId"]."#bottomTable"?>">View All Universities Mapped</a><br/>
			<a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING."/?consutId=".$value["consultantId"]?>">Map New University</a>
		    </div>
		</td>
		<td><p><?= stripcslashes(htmlspecialchars($value["universityName"]));?></p></td>
		<td><p><?= ucfirst($value['isOfficialRepresentative']);?></p></td>
		<td><p class="cms-table-date"><?=(date("d M Y",strtotime($value["modifiedAt"])));?></p></td>
		
	    </tr>
	   <?php }?> 
	</table>
    </div>
     <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>
<div class="clearFix"></div>
</div>