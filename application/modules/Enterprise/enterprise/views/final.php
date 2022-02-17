<?php if(count($finalInstituteContactCount)>0){?>

<div class="showListing-cont">
	
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="showList-table">
    	<tr>
        	<th>Listing</th>
            <th>Views</th>
            <th>Contact No. View</th>
        </tr>
        <?php $flag =0; $i =0;$cssClass="";
	
	foreach($finalInstituteContactCount as $instituteContact){
		
		$cssClass = $flag ?"alt-bgColor":" ";
	?>
        <tr class = "<?php echo $cssClass;?>" >
        	<td valign="top" width="28%"><a href="#"><?=$instituteContact['institute_title']?></a></td>
		<td valign="top" width="40%"><a onclick ="$j(this).next().toggle(); changeIcon('span_<?=$i?>')">
            	<span id = "<?php echo 'span_'.$i;?>" class="closed-icn"></span><?php echo $contactCountSumArray[0][$instituteContact['institute_id']]['totalView'];?></a>
            	<div id = "<?php echo 'view_'.$i;?>"class="listing-item-box" style="display:none;">
                	<ul>
                    	<li>
			      <p><?=$instituteContact['institute_title']?></p>		
                             <span><?php echo $instituteContact['viewCount'] ? $instituteContact['viewCount'] :0;?></span>
                        </li>
			<?foreach($finalCourseContactCount[$instituteContact['institute_id']] as $c){
			?>                       
			 <li>
                        	<p><?php echo $instituteContact['institute_title']." ". $c['courseTitle'] ?></p>
                            <span><?php echo $c['viewCount'] ? $c['viewCount']:0 ;?></span>
                        </li>
                       <?php }?>
                    </ul>
                </div>    
            </td>

	   <td valign="top" width="40%"><a onclick ="$j(this).next().toggle(); changeIcon('span_contact_<?=$i?>')" >
                <span id = "<?php echo 'span_contact_'.$i;?>" class="closed-icn"></span><?php echo $contactCountSumArray[0][$instituteContact['institute_id']]['totalCount'];?></a>
                <div id = "<?php echo 'contactDetails_'.$i;?>" class="listing-item-box" style="display:none;">
                        <ul>
                        <li>
                             <p><?=$instituteContact['institute_title']?></p>
                             <span><?php echo $instituteContact['contactCount'] ? $instituteContact['contactCount'] :0;?></span>
                        </li>
                        <?foreach($finalCourseContactCount[$instituteContact['institute_id']] as $c){
                        ?>                       
                                 <li>
                                <p><?php echo $instituteContact['institute_title']." ". $c['courseTitle'] ?></p>
                            <span><?php echo $c['contact_count'] ? $c['contact_count']:0 ;?></span>
                        </li>
                       <?php }?>
                    </ul>
                </div>    
            </td>

        </tr>
        <?php $flag = $flag?0:1; $i++;}?>
        
    </table>
   <?php }?> 
</div>

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
