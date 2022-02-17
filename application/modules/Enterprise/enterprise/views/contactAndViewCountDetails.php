<?php if(count($finalInstituteContactCount)>0){
$style = count($finalInstituteContactCount) > 9 ?"width:98%; height:450px; overflow-y:scroll" : '';
?>
<!-- style="width:98%; height:100%; overflow-y :auto" -->
<div class="showListing-cont"   style = "<?php echo $style; ?>">
	
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="showList-table">
    	<tr>
            <th>Listing Name</th>
            <th>Listing Views</th>
            <th>Contact Number Views</th>
        </tr>
        <?php $flag =0; $i =0;$cssClass="";
	
	foreach($finalInstituteContactCount as $instituteContact){
		
		$cssClass = $flag ?"alt-bgColor":" ";
	?>
        <tr class = "<?php echo $cssClass;?>" >
        	<td valign="top" width="28%"><?php echo $instituteContact['institute_title'];?></td>
            
		<td valign="top" width="40%"><a onclick ="$j(this).next().toggle(); changeIcon('span_<?=$i?>')">
            	<span id = "<?php echo 'span_'.$i;?>" class="closed-icn"></span><?php echo $contactCountSumArray[0][$instituteContact['institute_id']]['totalView'];?></a>
            	<div id = "<?php echo 'view_'.$i;?>"class="listing-item-box" style="display:none;">
                	<ul>
                    	<li>
			      <p><?=$instituteContact['institute_title']?></p>		
                             <span><?php echo $instituteContact['viewCount'] ? $instituteContact['viewCount'] :0;?></span>
                        </li>
			<?php foreach($finalCourseContactCount[$instituteContact['institute_id']] as $c){
			?>                       
			 <li>
                        	<p><?php echo $c['courseTitle'] ?></p>
                            <span><?php echo $c['viewCount'] ? $c['viewCount']:0 ;?></span>
                        </li>
                       <?php }?>
                    </ul>
                </div>    
            </td>
            
            <td valign="top" width="40%"><a onclick ="$j(this).next().toggle(); changeIcon('span_contact_<?=$i?>')" >
            	<span id = "<?php echo 'span_contact_'.$i;?>" class="closed-icn"></span><?php echo $contactCountSumArray[0][$instituteContact['institute_id']]['totalCount'];?><!-- sum should be displayed --></a>
            	<div id = "<?php echo 'contactDetails_'.$i;?>" class="listing-item-box" style="display:none;">
                	<ul>
                    	<li>
                             <p><?=$instituteContact['institute_title']?></p>
                             <span><?php echo $instituteContact['contactCount'] ? $instituteContact['contactCount'] :0;?></span>
                        </li>
			<?php foreach($finalCourseContactCount[$instituteContact['institute_id']] as $c){
			?>                       
				 <li>
                        	<p><?php echo $c['courseTitle'] ?></p>
                            <span><?php echo $c['contact_count'] ? $c['contact_count']:0 ;?></span>
                        </li>
                       <?php }?>
                    </ul>
                </div>    
            </td>
            
        </tr>
        <?php $flag = $flag?0:1; $i++;}?>
        
    </table>
</div>
<?php }?> 

<script>

function changeIcon(id){
		
	if($j('#'+id).attr('class') == 'opened-icn'){
	
		$j('#'+id).removeClass('opened-icn');
		$j('#'+id).addClass('closed-icn');
	}
	else{
	
		$j('#'+id).removeClass('closed-icn');
		$j('#'+id).addClass('opened-icn');
	}
}
</script>
