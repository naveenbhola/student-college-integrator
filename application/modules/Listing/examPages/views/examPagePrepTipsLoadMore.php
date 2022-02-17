 <?php 
 $count = 1;
 foreach($examPagePrepTips['articles'] as $prepTip) {?>	
  <li class = "<?=count($examPagePrepTips['articles'])== $count ? 'last' : "" ?>" >
  <?php 
   $this->load->view ( 'examPages/examPagePrepTipsTuple',array('prepTip'=>$prepTip));
	?>
  </li>
  <?php $count++; }?>
  
  		<?php 
  		
  		if($examPagePrepTips['totalNumRows'] > ($offset+$noOfRows) ) {
         ?>
	    <div class="show-more" onclick="loadMorePrepTips('<?=$params?>',<?=($offset+$noOfRows)?>, '<?=$examArticleTags?>',  this);">
	        <div style="float: left;visibility: hidden;" id="pagination-loder"><img style="height:18px;" src="/public/images/loader_hpg.gif"></div>
        	<a href="javascript:void(0);">Show More</a>
	    </div>
<?php
		} ?>
        