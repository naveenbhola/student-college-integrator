<style>
#suggestions_container li{     border-bottom: 1px solid #f6f6f6;
  color: #808080;
  cursor: pointer;
  display: block;
  font-size: 12px;
  padding: 8px 10px;
  text-align: left;
  text-decoration: none;}
#suggestions_container li span strong{  color: #000;  display: inline; font-size: 12px; color:#808080; font-weight: bold;}
</style>
<section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:0px;">
  <h1 class=" content-inner cr_titltxt"><?php echo $m_meta_title;?></h1>
  <div class="cr_searchBx college-conn">    
    <p class="cr_searchBx_p1"><strong style="font-size:12px; line-height: 20px;">Ask Current Students before making a <br>College Decision</strong></p>
    <div class="cr_inputsrchBx bordRdus3px">
      <div class="cr_inputsrchBxInnr " id="cmp-search-box">
	<i class="icn-lst icn-search" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest','campusconnect');" style="cursor: pointer;"></i>
        <input type="text" placeholder="Search by college name" name="ca_key" id="keywordSuggest" minlength="1" autocomplete="off" onfocus="if($('#collegeCardDownList').length>0){$('#collegeCardDownList').css({'z-index':'0'});}"/>
      </div>
       <div class="cr_sugestd">
            	<ul id="suggestions_container" style="text-align: left;"></ul>
       </div>
    </div>
  </div>
</section>

<script>
// call this function, when the user click on suggested list  
function instituteSelected(instId,instTitle,listingType){
	if(instId > 0){
        if(typeof(listingType) == 'undefined' || listingType ==''){
          listingType = '';
        }
        $.ajax({
            url: "/CA/CampusConnectController/getCampusIntermediateUrl",
            type: "POST",
            data: {'instituteId':instId, 'listingType':listingType ,'action':'CCHomeSearchTracking', 'search_string':keywordEnteredByUser, 'search_clicked':instTitle},
            success: function(result)
            {
                obj = JSON.parse(result);
        		    if(obj.url !='') {
        			     window.location = obj.url;
        		    }
            },
            error: function(e){                        
            }
        });                
	}
}  

//hide autosuggestor container click on outside of container
$(document).click(function (e)
{  
    var container = $("#suggestions_container");
    var container2 = $("#keywordSuggest, #cmp-search-box");
    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
    {   
        if ($('#suggestions_container').length>0) {
		container.slideUp(400,function(){if($('#collegeCardDownList').length>0){$('#collegeCardDownList').css({'z-index':'9'});}});
	}
    }
});
</script>