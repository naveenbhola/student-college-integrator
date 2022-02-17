<div class="abroad-cms-rt-box">
	<div style="margin-top:0;" class="abroad-cms-head">
            <h2 class="abroad-sub-title">All Locations</h2>
            <div class="flRt"><a style="padding:6px 7px 8px" class="orange-btn" href=<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_LOCALITY?>>+ Add New Location</a></div>
        </div>
        <div class="search-section">
                <div class="adv-search-sec">
                        <div class="cms-adv-box">
                                <form action="<?=$URL?>" name="searchLocations">
                                            <div style="width:250px;" class="cms-search-box">
                                                        <i class="abroad-cms-sprite search-icon"></i>
                                                        <input type="text" class="search-field" style="<?=($searchLocality)?'color:black;':''?>width:130px;" value="<?=($searchLocality)?$searchLocality:'Search a Location'?>" defaulttext="Search a Location" onfocus="toggleDefaultText(this,'focus');" onblur="toggleDefaultText(this,'blur');" id="searchLocality" name="searchLocality">
                                                        <?php if($searchLocality != ''){ 
                                                        ?>
                                                                <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('searchLocality').value='';document.searchLocations.submit();"></i>
                                                        <?php }
                                                        ?>
                                                        <a class="search-btn" onclick="document.searchLocations.submit();" href="javascript:void(0);">Search</a>
                                            </div>
                                </form>
                        </div>

                        <div class="flRt display-sec">
                            <ul>
                                <li>Show:</li>
                                <li class="active">All(<?=$resultCount['totalCount']?>)</li>
                            </ul>
                                <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
                        </div>

                        <div class="clearFix"></div>
                </div>
                <table cellspacing="0" cellpadding="0" border="1" class="cms-table-structure">
                        <tbody>
                                <tr>
                                        <th width="5%" align="center">
                                            <span style="margin-top:6px;" class="flLt">S.No.</span>
                                        </th>
                                        <th width="30%">
                                            <span style="margin-top:6px;" class="flLt">Localities</span>
                                        </th>
                                        <th width="50%">
                                            <span style="margin-top:6px;" class="flLt">City</span>
                                        </th>
                                        <th width="15%">
                                            <span style="margin-top:6px;" class="flLt">Created/Updated on</span>
                                        </th>
                                </tr>
                                
                                <?php
                                        if(empty($locationsData)){ 
                                ?>
                                        <tr>
                                            <td align="center">&nbsp;</td>
                                            <td colspan=4><i>No Results Found !!!</i></td>
                                        </tr>
                                <?php }
                                        $index = $paginator->getLimitOffset() + 1;
                                        foreach($locationsData as $key=>$locData){
                                ?>
			        
                                <tr>
                                        <td align="center"><?=$index++?>.</td>
                                        <td>
                                            <p><?=  htmlentities($locData['locality'])?></p>
                                                <div class="edit-del-sec">
                                                    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_LOCALITY.'?localityId='.$locData['id']?>">Edit</a>&nbsp;&nbsp;
                                                </div>
                                        </td>
                                        <td>
                                            <p class="cms-associated-cat"><?=  htmlentities($locData['city'])?></p>
                                        </td>
                                        <td>
                                                <p class="cms-table-date"><?=  date('d M Y',  strtotime($locData['modifiedAt']))?></p>
                                        </td>
                                </tr>
                                <?php 
                                        }
                                ?>
                        </tbody>
                </table>
            </div>
	  <?php
                $this->load->view('listingPosting/paginator/paginationBottomSection');
          ?>  
                            
          
        
        <div class="clearFix"></div>
        </div>