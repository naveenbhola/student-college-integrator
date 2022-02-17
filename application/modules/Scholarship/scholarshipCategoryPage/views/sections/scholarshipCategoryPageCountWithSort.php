<?php 
global $queryStringFieldNameToAliasMapping;
?>
<div id="countWithSort">
<?php if($totalTupleCount>0){ ?>
        
	<div class="sectn__col">
	    <div class="sc-txt"><h2><?php echo $totalTupleCount;?> Scholarship<?php if($totalTupleCount>1)echo 's'; ?> found</h2>  <span class="right__optns"><span class="srt__by">Sort by:</span>
	            <select class="show__val selectChk" name="scholarshipSorting" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipSorting'];?>">
	                <option <?php echo ($request->getScholarshipSorting() == 'popularity')?'selected':''?> value="popularity">Popularity</option>  
	                <option <?php echo ($request->getScholarshipSorting() == 'deadline')?'selected':''?> value="deadline">Earliest Deadline</option>  
	                <option <?php echo ($request->getScholarshipSorting() == 'amount')?'selected':''?> value="amount">Amount</option>  
	                <option <?php echo ($request->getScholarshipSorting() == 'awards')?'selected':''?> value="awards">No. of Awards</option>  
	            </select><i class="custm__ico"></i> </span>
	    </div>
	</div>
<?php } ?>
</div>