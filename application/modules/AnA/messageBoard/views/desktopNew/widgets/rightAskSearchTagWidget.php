<div class="search-widget">
     <div class="search-block">
      <h3 class="col-heading">Didn't find the answer you were looking for?</h3>
      <div class="find-que top-m">
        <p class="sub-title">Search from Shiksha's 1 lakh+ Topics</p>
        <div class="find-input">
            <input type="text" placeholder="Find Topics" class="srch-input top-m field-ip" id="tagSearchQDP" value="" spellcheck="false" onfocus="showSuggestedTags();">
            <span class="sb-icon-search" id="tagSearchIconQDP"><i></i></span>
            <div class="search-college-layer" id="tagSearchQDP_container" style="display: none;">
            </div>
            <div  id="tagSearchQDP_suggested_container" style="display: none;">
              <span class="group-select">POPULAR TOPICS</span>
              <?php foreach($tags as $tag){?>
                <li class="suggestion-box-normal-option tgListClk" style="cursor:pointer" data-tagName="<?=$tag['tag_name'];?>" url="<?=$tag['url']?>"><span><?=$tag['tag_name'];?><span></li>
              <?php }?>
              
            </div>
            <p class="tagSearchQDP_error" id="tagSearchQDP_error" style="display:none">Please select a topic from suggestions</p>
        </div>
        <p class="background"><span>or</span></p>
        <p class="sub-title">Ask Current Students, Alumni & our Experts</p>
        <div class="ask-experts top-m">
          <div class="txt-col">
           <textarea rows="3" class="search-txt" placeholder="Type your question here..." caption="Question" class="qstn-input" id="qstn-input-demo" ga-attr="ASK_WIDGET_QUESTIONDETAIL_DESKAnA"></textarea>
          </div> 
          <div class="lft-btn-col">
           <a class="btn btn-prime" id="qstn-input-Button" ga-attr="ASK_WIDGET_QUESTIONDETAIL_BUTTON">Ask Now</a> 
          </div>           
        </div>
      </div>
     </div>
   </div>

  <script>
  var pageName = "searchTagQDP";
   function showSuggestedTags(dict, autosuggestorObj) {
      if(typeof dict == 'undefined'){
          createSuggestedSearch();
    $j('#tagSearchQDP_container').show();
      }
   }

   function createSuggestedSearch() {
      $j('#tagSearchQDP_container').html($j('#tagSearchQDP_suggested_container').html());
   }
  </script>
