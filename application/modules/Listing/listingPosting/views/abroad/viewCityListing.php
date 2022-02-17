      <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All City</h2>
	<?php if($this->input->get("msgType") == 1)
	{ ?>
		<span id="successMsg" style="color:green">Successfully Saved !!!</span>
	<?php
	}
	?>
                <div class="flRt"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_CITY?>" class="orange-btn" style="padding:6px 7px 8px">+ Add New City</a></div>
            </div>
            <script type="text/javascript">
            function toggleDefaultTextForCityList(thisObj,e){
              if( $j(thisObj).attr("defaulttext") == $j(thisObj).val() && e == 'focus')
            	{
            	$j(thisObj).val("");
            	$j(thisObj).css("color","black");
            	}
            	if($j(thisObj).val() == "" && e == "blur")
            	{
            	$j(thisObj).val("Search a City");
            	$j(thisObj).css("color","");
            	}
            	} 

            </script>
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
                		<form name = "searchCity" id= "searchCity" method="get" action ="<?php echo ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY?>">
                		<div class="cms-search-box">
                        <i class="abroad-cms-sprite search-icon"></i>
                       	<input id = "seachCitybox" name = "seachCitybox" type="text" style="<?=($searchCityString != '') ? 'color:black' : '' ?>" defaulttext="Search a City" onBlur="toggleDefaultTextForCityList(this,'blur');" onfocus="toggleDefaultTextForCityList(this,'focus');" value="<?= ($searchCityString) ? $searchCityString : "Search a City";?>" class="search-field"/>
                       <?php if($searchCityString != '')
						{ ?>
				        <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('seachCitybox').value='';document.searchCity.submit();"></i>
						<?php
						} ?>                        
						<a  href= "javascript:void(0);" class="search-btn" onclick ="document.searchCity.submit();" >Search</a>
                        
                    </div>  
                    </form>                	
                    </div>
                    <div class="flRt display-sec">
                    <?php if($totalCount>0) {?>
                    <ul> 
                    	<li>Show:</li>
                    	<li class="active">All (<?php echo $totalCount;?>)</li>
               
                    </ul>
                    <?php }?>
                   <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
                    </div>
                    <div class="clearFix"></div>
                </div>
                <?php   $S_NO = $paginator->getLimitOffset()+1;?>
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                	<tr>
                        <th width="5%">
                            <span class="flLt" style="margin-top:6px;">S.No.</span>
                        </th>
                        <th width="25%">
                            <span class="flLt" style="margin-top:6px;">City Name</span>
                        </th>
                        <th width="35%">
                            <span class="flLt" style="margin-top:6px;">Country</span>
                        </th>
                        <th width="20%">
                        <span class="flLt" style="margin-top:6px;">State</span>
                        </th>
                        <th width="25%">
                        <span class="flLt" style="margin-top:6px;">Date</span>
                        </th>
                    </tr>
                    
                    <?php foreach ($cityArray as $city) {?>
                    
                    <tr>
                    	<td align="center"><?php echo $S_NO++;?></td>
                        <td>
                            <p><?php echo htmlspecialchars($city['city_name']) ?></p>
                            <a href="<?php echo ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_CITY."?cityId=".$city['city_id']."&countryId=".$city['country_id'];?> ">Edit</a>
                        </td>
                        <td>
                        	<p><?php echo htmlspecialchars($city['country_name'])?></p>
                            <a href="<?php echo ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_CITY."?countryId=".$city['country_id'];?> ">Add a City</a>
                        </td>
                        <td>
                        <p><?php echo htmlspecialchars($city['state_name'])?></p>
                        </td>
                        
                        <td>
                       <p class="cms-table-date"><?php 
                        $modificationDate = date("d",strtotime($city['modificationDate']))." ".date("M",strtotime($city['modificationDate']))." ".date("Y",strtotime($city['modificationDate']) );
                          echo $modificationDate; ?></p>
                        </td>
                    </tr>
                    <?php } ?>
                     <?php if(empty($cityArray))
                     { ?>
                    <tr>
                    <td colspan=5>
                    <p class="no-found">No Data Found For This Search</p>
                    </td>
                    </tr>
                     <?php } ?>
                   </table>
                
            </div>
            <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>
        <div class="clearFix"></div>
        </div>  
