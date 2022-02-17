<div class="cms-pane" id="tab3">
                  <div class="cms-box">
                      <div class="cms-box-form">
                        <form class="cms-form" id="form" action="/home/HomePageMarketingCMS/saveMarketingFoldForHomePage" enctype="multipart/form-data" method="post">
                          <ul>
                             <li>
                               <label>Add New Content</label>
                               <div class="left-div">
                                 <select class="positn-select" name="selectedContent" id="selectContent" onChange="changeMarketingCnt(this.selectedIndex);">
                                   <option value="">Select</option>
                                   <option value="1">Image With Text</option>
                                   <option value="2">Image Only</option>
                                   <option value="3">Video Only</option>
                                 </select>
             					<div id="selectContent_error" class="errorMsg"></div>
                               </div>
                             </li>
                          </ul>
                          <div id="marketingForm"></div>
                          <div class="save">
                          <input type="button" class="table-save-btn" value="Save" onclick="submitBannerFeaturedCollege('marketing', 'submit');" title="Submit">
                          </div>
              <input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType; ?>" />
						  <input type="hidden" id="action" name="action" value="<?php echo $action; ?>" />
              <input type="hidden" id="bannerRemoved" name="bannerRemoved" value="0"/>
              <input type="hidden" id="idForMrktng" name="idForMrktng" value="<?php echo $slotData['id']; ?>" />
              <input type="hidden" id="marketingBannerId" name="marketingBannerId" value="<?=$marketingBannerId?>" />
			        <input type="hidden" name="userId" value="<?php echo $userId?>" />
              <input type="hidden" id="status" name="status" value="<?php echo $status;?>" />
              <input type="hidden" id="bannerImage" name="bannerImage" value="<?php echo $slotData['bannerImageUrl'];?>" />
              <input type="hidden" id="creationDate" name="creationDate"  value="<?php echo $slotData['creationDate']; ?>"/>

						</form>
                      </div>
<script>
  if(document.all) {
    document.body.onload = updateFormElem();
  } else {
    updateFormElem();
  }
</script>