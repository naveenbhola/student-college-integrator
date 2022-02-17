   <section class="filter-applied" style="padding-bottom:5px;padding-top:5px;display: none; cursor: pointer" id="subheading" onClick="showSearchForm('<?php echo $examName;?>');">
	  <strong id="subheadingtext"></strong>
	  <?php if(strtoupper($examName) != 'JEE-MAINS'){ ?>
	  <div class="filter-arr"></div>
	  <?php } ?>
   </section>
