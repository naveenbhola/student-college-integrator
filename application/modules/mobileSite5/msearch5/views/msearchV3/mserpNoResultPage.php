<div class="applyFilter-container rsult-bg"  >
	<div class="noResult-Page">
        <?php 
        if($product == "MsearchV3"){
          $text = 'Sorry no colleges <br/> found for &#8220;'.htmlspecialchars($keyword).'&#8221;';  
        }else{
          $text = "Sorry no results found!";
        }
        
        
        ?>
        <p class="noResult-msg"><?php echo $text; ?></p>
        <?php if($product == "MsearchV3"){
          ?>
          <div class="modify-sec">
            <a class="green-btn modify-btn" href="<?php echo(SHIKSHA_HOME . '/searchLayer') ?>" >MODIFY SEARCH</a>
          </div>
          <p class="or-text">OR</p>
          <?php
        }
        
        ?>
        

     <p>View colleges by stream</p>
        
        <div class="search-inner-container noResult-bg">
            <div class="search-field clearfix zerp-list">
              <form formname="nozrp" onsubmit="return false;">
                <ul>
                    <li>
                         <?php
                          
                          foreach ($streamsArray as $key => $value) {
                              //_p($value['name']);
                              $key=$value['id'];
                              $value1=$value['name'];
                              $category[$key]=$value1;
                          }
                          
                         
                          
                         $this->load->view('msearch5/msearchV3/mSelectWidget', array('id' => 'zeroResultDropDown1',
                                                                                     'onchange'=>"onchange=submitZeroResultPage(this)",
                                                                                     'data'=>$category,
                                                                                     'placeholder'=>"placeholder='Select Stream'")); ?>
                        <?php $category = array();?>
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
/*$trackingSearchId = $request->getTrackingSearchId();
$relevantResults  = $request->getRelevantResults();*/
$newKeyword = '';
/*if(!empty($relevantResults) && $relevantResults != 'relax'){
    $newKeyword       = $request->getSearchKeyword();
}
*/
if(0 && DO_SEARCHPAGE_TRACKING && !empty($k)){
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