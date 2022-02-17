<div class="middle-data">
		 <?php
     $k = $request->getOldKeyword();
     if(empty($k)){
      $k = $request->getSearchKeyword();
     }
		 if(empty($k)){
				  ?>
				  <h1>Find a college that's best fit for you!</h1>
				  <p class="modify-srch" style="cursor:pointer;"><a onclick="openSearchBox()">Perform Search</a></p>
				  <?php
		 } else {
				  ?>
				  <h1>Sorry no colleges found for &#8220;<?php echo htmlspecialchars($k); ?>&#8221;</h1>
				  <p class="modify-srch" style="cursor:pointer;"><a onclick="openSearchBox()">Modify your Search</a></p>
				  <?php
		 }
		 ?>
         <p class="modify-or">Or</p>
         <p class="view-by-stream">View colleges by stream</p>
     
    </div>

    <div class="middle-select">
   
        <div class="zero-custom"> 
         <!--frst custom drop down-->
        <select  id="zeroResultDropDown1" class="zeroResultDropDown" placeholder="Select Stream" onchange="populateSubCat(this)">
        <option disabled="disabled" selected="selected"></option>
        <?php 
        uasort($categories, function($a,$b){return strcmp($a["name"], $b["name"]);});
        foreach ($categories as $key => $value) {?>
        	<option value="<?=$value['id']?>"><?=$value['name']?></option>
      	<?php }?>
        </select>  
          
          <!--2nd custom drop down-->
         <select  id="zeroResultDropDown2" class="zeroResultDropDown" placeholder="Select Course Type" onchange="onChangeSubcategoryList()" disabled="disabled">
              <option disabled="disabled" selected="selected"></option>
        </select> 
        <!--submit button-->
        <input type="submit" id="submitZeroResultPage" onclick="submitZeroResultPage()" value="Go">
        <p class="find-clg">Didn’t find the college you were looking for? Let us know at <a href="mailto:site.feedback@shiksha.com" class="fbck-lnk">site.feedback@shiksha.com</a></p> 
      </div>
      <script>
      var categoryToSubCategoryMap = eval('('+'<?php echo json_encode($categories)?>'+')');
      <?php if($isAjax) {?>
      	$j('.zeroResultDropDown').SumoSelect();
      <?php } ?>
      </script>

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
        var url = SEARCH_PAGE_URL_PREFIX+'/trackSearchQuery?ts='+<?php echo $trackingSearchId; ?>+'&count=0';
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
