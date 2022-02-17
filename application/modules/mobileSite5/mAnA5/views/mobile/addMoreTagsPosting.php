  <div class="qstn-container">
      <!--comment secion heading-->
       <div class="qstn-col">
          <div class="q-box"><p class="q-titl">Add More Tags</p></div>
          <div class="q-cls"><a href="javascript:void(0);" data-enhance="false" data-rel="back">&times;</a></div>
       </div>
      <!--comment section card view--> 
       <div class="qstn-block">
          <p class="search-tags">Search and add tags that describe your <span class='qdTitleHead lowerCaseClass'>question</span></p>
          <div class="selected-tags" style='display:block;' data-enchance="false">
            <div class="tagsContainer_AutoSuggest">
           
            </div>
            <p class="clr"></p>
          </div>
          <!--div class="input-col">
             <input type="text" class="add-tags" placeholder="Search tags" id="tagSearch">
             <i class="search-ico"></i>
             <div class="tags-auto-sugstr">
                <ul class="tags-ul suggestion-box tag-suggestions" id="tagSearch_container">
                  
                </ul>
             </div>
           </div-->
           <div class="select-list1" style="position:relative">
              <div class="auto-wrapper input-col">
                <input type="text" name="txtSelectOption" value=""  placeholder="Search tags" class="drp-txt add-tags" id="tagSearch"  spellcheck="false">
                <i class="search-ico"></i>
                <ul id="tagSearch_container" style="list-style: none; display: none;" class="suggestion-box tag-suggestions tags-ul"></ul>
              </div>
          </div>
       </div>
  
       <a href="javascript:void(0);" class="p-btn u-btns" data-rel="back" data-enhance="false" onclick='prepareManualTags();'>Done</a> 
 </div>
<script type="text/javascript">
  
  addMoreTagsLayerInitializer();

</script>
