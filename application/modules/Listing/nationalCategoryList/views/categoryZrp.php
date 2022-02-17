
<div class="middle-data">
  <?php
  if($product == "Category" || $product == "AllCoursesPage"){
    ?>
    <h1>Sorry no results found!</h1>
    <?php
  }else{
    ?>
    <h1>Sorry no colleges found for &quot;<?php echo $keyword;?>&quot;</h1>
    <?php
  }
  ?>
	
  <?php if($product == "SearchV2" && 0){
    ?>
      <p class="modify-srch" style="cursor:pointer;"><a onclick="openSearchBox()">Modify your Search</a></p>
      <p class="modify-or">Or</p>
    <?php
  }
  ?>
	
    
    <p class="view-by-stream">View colleges by stream</p>
</div>

    <div class="middle-select">
   
        <div class="zero-custom"> 
         <!--frst custom drop down-->
        <select  id="zeroResultDropDown1" class="zeroResultDropDown" placeholder="Select Stream" onchange="submitZeroResultPage(this)">
        <option disabled="disabled" selected="selected"></option>
        <?php 
        
        foreach ($streamsArray as $key => $value) {?>
        	<option value="<?=$value['id']?>"><?=$value['name']?></option>
      	<?php }?>
        </select>  
          
        <!--submit button-->
        
        <p class="find-clg">Didn’t find the college you were looking for? Let us know at <a href="mailto:site.feedback@shiksha.com" class="fbck-lnk">site.feedback@shiksha.com</a></p> 
      </div>
      <script>
      <?php if($isAjax) {?>
      	$j('.zeroResultDropDown').SumoSelect();
      <?php } ?>
      </script>

