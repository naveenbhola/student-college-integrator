 
<BODY>    
 <form method="POST" action="enterprise/LDBSearchTabs/storeLDBCategories" id ="ldbSearchTabs" name ="ldbSearchTabs" >
 
<input type="hidden" id="clientId" name="clientId" value="<?php echo $clientId; ?>" />
<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Check/uncheck the below option for LDB Tabs Access for the chosen client.   The LDB-Search Tabs access set already are checked by default.</div>
<div class="grayLine"></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>

 <table width="100%" class="table-style2" border="0.5" cellpadding="0" cellspacing="0" >                
                <?php
                foreach($LDBCourseList as $category=>$values){
                ?>
                  
                    <tr>
		 <th> <input type="checkbox" class="parentCheckBox" id='tab_<?php echo $category; ?>' value="<?=$category;?>" onclick="toggleSearchTabs(this);" /></th>
		 <th><?php echo $values['name']; ?></th>
		 </tr>
                    
		 <?php
		 $courses = $values['courses'];
		 if($values['name'] == 'Study Abroad') {
		  $courses = array();
		  $courses[] = 'All';
		   $courses = array_merge($courses, $values['courses']['popular'], $values['courses']['category']);
		 }
		 
		 foreach($courses as $key=>$value){
			 ?>
				 <?php if(!isset($ldbTabsAccessSet[$values['id']][$value])){  ?>		 
					 <tr>
						 <td align="center"><input type="checkbox" class="childCheckBox_<?=$category;?>" onclick="toggleParentSearchTab('<?php echo $category; ?>');" value="<?=$value."_".$values['id'];?>" name="category[]" /></td>
						 <td><?=$value;?></td>
					 </tr>
						 <?php }
                                    else{                 
                                    ?>
					 <tr>
					 <td align="center"><input type="checkbox" class="childCheckBox_<?=$category;?>" onclick="toggleParentSearchTab('<?php echo $category; ?>');" checked="checked" value="<?=$value."_".$values['id'];?>" name="category[]" /></td>
					 <td><?=$value;?></td>
					 </tr>
				    
                                    <?php
                                 } ?>
                    
                   
                    
			
                 
 <?php } ?>
  
<?php } ?>
<?php if(count($other_array)>0) :?>
                <tr>
		 <th> <input type="checkbox" class="parentCheckBox" id='tab_others' value="others" onclick="toggleSearchTabs(this);" /></th>
		 <th>Others</th>
		 </tr>
<?php endif;?>
                <?php
		$j = 0;
		foreach($other_array as $other_course => $category_id) {
		?>
                               
                               
                                <?php if(!isset($ldbTabsAccessSet[$category_id][$other_course])){  ?>		 
					 <tr>
						 <td align="center"><input type="checkbox" class="childCheckBox_<?=$category_id;?>" onclick="toggleParentSearchTab('othersval');" value="<?=$other_course."_".$category_id;?>" name="category[]" /></td>
						 <td><?=$other_course;?></td>
					 </tr>
						 <?php }
                                    else{                 
                                    ?>
					 <tr>
					 <td align="center"><input type="checkbox" class="childCheckBox_<?=$category_id;?>" onclick="toggleParentSearchTab('othersval');" checked="checked" value="<?=$other_course."_".$category_id;?>" name="category[]" /></td>
					 <td><?=$other_course;?></td>
					 </tr>
				    
                                    <?php
                                 } ?>
                



                <?php
                }
                ?>


</table>
    
	 
            <div class="r2_2">
                <button class="btn-submit19" onclick = "formSubmit();"  type="button" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Submit</p></div>
                </button>
            </div>
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>

     <div class="lineSpace_28">&nbsp;</div>
     
        <div id="results" class="" ></div>
        <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>
  
</form>
</BODY>

    
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script language="javascript">


function formSubmit() {
    var formStringData = $j("#ldbSearchTabs").serialize();
                        
    $j.post("/enterprise/LDBSearchTabs/storeLDBCategories", formStringData, function(response)    {
            $j('#results').html(response);
    });
}

function toggleSearchTabs(cb) {
    var parentName = cb.value;
    $j('.childCheckBox_'+parentName).each(function() {
        $j(this).prop('checked',cb.checked); 
    });
}

function toggleParentSearchTab(parentName) {
    var allChecked = true;
    $j('.childCheckBox_'+parentName).each(function() {
        if(!$j(this).prop('checked')) {
            allChecked = false;
        }
    });
    $j('#tab_'+parentName).prop('checked',allChecked);
}

$j(document).ready(function() {
   $j('.parentCheckBox').each(function() {
        toggleParentSearchTab($j(this).val()); 
   });
});

</script> 
