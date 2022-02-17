<div class="abroad-cms-rt-box">
    <script>
        var URLForConsultant = "<?= $URL."?searchType=".$searchTypeOption ?>";
        function changeConsultantSearchType(){
                var optionVal = document.getElementById('consultantSearchType').value;
                var LoadURLForConsultant = URLForConsultant.concat(optionVal)
                var searchString = document.getElementById('q').value;
                if(searchString != "Search Content")
                {
                        LoadURLForConsultant = LoadURLForConsultant+"&q="+searchString;
                }

        location.assign(LoadURLForConsultant);
        }
    </script>
	<div class="abroad-cms-head" style="margin-top:0;">
            <h2 class="abroad-sub-title">All Student Profiles</h2>            
        </div>
        <div class="search-section">
            <div class="adv-search-sec">
                <div class="cms-adv-box">
                    <div class="cms-search-box search-box-width" style="width:360px;">
                        <form name="searchStudentProfile" action="<?=$URL?>">

                                <select name="searchType" class="universal-select art-guide-list" id="consultantSearchType" onchange = "changeConsultantSearchType()" >
                                    <option value = "consultants" <?php if('consultants' == $searchType) { echo 'selected' ;} ?>>Consultants</option>
                                    <option value = "universities" <?php if('universities' == $searchType) { echo 'selected' ;} ?>>Universities</option>
                                </select>
                                <i class="abroad-cms-sprite search-icon"></i>
                                <input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'width: 105px;color:black' : 'width: 130px;' ?>" defaulttext="Search <?=ucfirst($searchType)?>" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?html_escape($searchTerm): "Search ".ucfirst($searchType);?>" class="search-field"/>
                                    <?php if($searchTerm != ''){ 
                                    ?>
                                        <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchStudentProfile.submit();"></i>
                                    <?php
                                            } 
                                    ?>
                                <a href="javascript:void(0);" onclick="document.searchStudentProfile.submit();" class="search-btn">Search</a>

                        </form>
                    </div>
		</div>
			
                <div class="flRt display-sec">
                 
                    <ul>
                    	<li>Show:</li>
            <li class="active"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE.$queryParams?>">
            All (<?php if($totalCount)echo $totalCount; else echo 0;?>)
            </a></li>
                    </ul>
                    
                    <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
		</div>
                
                <div class="clearFix"></div>
            </div>
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                    <tr>
                        <th width="5%" align="center">
			    <span class="flLt" style="margin-top:6px;">S.No.</span>
			</th>
                        <th width="25%">
                            <span class="flLt" style="margin-top:6px;">Consultant Name</span>
                        </th>
                        <th width="30%">
                            <span class="flLt" style="margin-top:6px;">University mapped</span>
                        </th>
                        <th width="20%">
                            <span class="flLt" style="margin-top:6px;">Student profile</span>
                        </th>
                        <th width="20%" align="center">
                            <span class="flLt" style="margin-top:6px;">Updated on</span>
			</th>
                    </tr>
			
                    <?php if(empty($querydata)){ ?>
                    <tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=4><i>No Results Found !!!</i></td>
                    </tr>
                    <?php }
                      
                        $index = $paginator->getLimitOffset() + 1;
                        foreach($querydata as $key=>$value){  
                    ?>
                    
                    <tr>
                    	<td align="center"><?=($index++)?>.</td>
                        <td>
                            <p><?=htmlentities($consultants[$value['consultantId']])?></p>
                        </td>
                        <td>
                            <p><?php echo htmlentities($universities[$value['universityId']]);?></p>
                        </td>
                        <td>
                            <p class="cms-associated-cat">
                            <?php
                                 if($value['liveprofiles'])
                                 {
                                   echo ($value['liveprofiles']==1)? $value['liveprofiles'].' Profile': $value['liveprofiles'].' Profiles';
                                 }
                                else
                                 {
                                    echo '0 Profile';
                                 }
                            ?>
                            </p>
                            <?php if($value['liveprofiles'] >= 1){?>
			    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_STUDENT_PROFILE ?>?consultantId=<?= $value['consultantId']?>#bottomTable">View All</a>
			    <span class="cms-seperator"> | </span>
                            <?php }?>
			    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_STUDENT_PROFILE?>?consultantId=<?= $value['consultantId']?>&universityId=<?php echo $value['universityId']; ?>">Add Profile</a>
			    
                        </td>
                        <td>
                           <p class="cms-table-date" style="text-align: center"><?=($value['liveprofiles'] >= 1)?(date("d M Y",strtotime($value['lastModified']))):'-'?></p>

                        </td>
                </tr>

		<?php }	?>
                </table>
            </div>
	    
            <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>

        <div class="clearFix"></div>
        </div>
