<div class="applyFilter-container rsult-bg"  >
	<div class="noResult-Page">
        <?php 
        $k = $request->getOldKeyword();
        if(empty($k)){
          $k = $request->getSearchKeyword();
        }
        $text = 'Sorry, no colleges <br/> found for &#8220;'.htmlspecialchars($k).'&#8221;';
        if(empty($k)){
          $text = "Find a college that's best fit for you!";
        }
        ?>
        <p class="noResult-msg"><?php echo $text; ?></p>
        <div class="modify-sec">
	        <a class="green-btn modify-btn" >MODIFY SEARCH</a>
        </div>
        <p class="or-text">OR</p>

     <p>View colleges by stream</p>
        
        <div class="search-inner-container noResult-bg">
            <div class="search-field clearfix zerp-list">
              <form formname="nozrp" onsubmit="return false;">
                <ul>
                    <li>
                         <?php
                          uasort($categories, function($a,$b){return strcmp($a["name"], $b["name"]);});
                          foreach ($categories as $key => $value) {
                              //_p($value['name']);
                              $key=$value['id'];
                              $value1=$value['name'];
                              $category[$key]=$value1;
                          }
                          
                        //  _p($category);
                          
                         $this->load->view('msearch5/msearchV2/mSelectWidget', array('id' => 'zeroResultDropDown1',
                                                                                     'onchange'=>"onchange=populateSubCat(this)",
                                                                                     'data'=>$category,
                                                                                     'placeholder'=>"placeholder='Select Stream'")); ?>
                    </li>
                    <li>
                        <?php $this->load->view('msearch5/msearchV2/mSelectWidget', array('id' => 'zeroResultDropDown2',
                                                                                          'onchange'=>"onchange=submitZeroResultPage()",
                                                                                          'placeholder'=>"placeholder='Select Course Type'",
                                                                                          'disabled' => true )); ?>
                    </li>
                </ul>
                </form>
            </div>
        </div>
    <script type="text/javascript">
        var categoryToSubCategoryMap = eval('('+'<?php echo json_encode($categories)?>'+')');
      <?php if(!$totalInstituteCount) {?>   
        var isZRPPage=true; <?php } ?>
    </script>
    
      </script>
        <p class="clearfix mb50">Didn't find the college you were looking for?<br>
			Let us know at <a href="mailto:site.feedback@shiksha.com" class="feedbck-info">site.feedback@shiksha.com</a></p>
    </div>
</div>


<?php 
$trackingSearchId = $request->getTrackingSearchId();
$relevantResults  = $request->getRelevantResults();
$newKeyword = '';
if(!empty($relevantResults) && $relevantResults != 'relax'){
    $newKeyword       = $request->getSearchKeyword();
}

if(DO_SEARCHPAGE_TRACKING && !empty($k)){
  ?>
  <script type="text/javascript">
  var img = new Image();
  var url = SEARCH_PAGE_URL_PREFIX+'/trackSearchQuery?ts='+<?php echo $trackingSearchId; ?>+'&count=0&newSearch=1';
  <?php 
  if(!empty($newKeyword)){
    ?>
    url += "&newKeyword=<?php echo $newKeyword; ?>";
    <?php
  }
  if(!empty($relevantResults)){
    ?>
    url += "&criteriaApplied=<?php echo $relevantResults; ?>";
    <?php
  }
  ?>
  img.src = url;
  </script>
  <?php
}
?>