<?php if(count($tableData)>0){
$style = count($tableData) > 9 ?"width:98%; height:450px; overflow-y:scroll" : '';
?>
<!-- style="width:98%; height:100%; overflow-y :auto" -->
<div class="showListing-cont"   style = "<?php echo $style; ?>">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="showList-table">
    	<tr>
            <th>Listing Name</th>
            <th>Listing Views</th>
            <th>Contact Number Views</th>
        </tr>
        <?php
	    $flag =0; $i =0;$cssClass="";		
	?>
      
	<?php
	    foreach($tableData as $university){
	    $cssClass = $flag ?"alt-bgColor":" ";
	?>
	<tr class = "<?php echo $cssClass;?>" >
            <td valign="top" width="28%"><?php echo $university['universityTitle'];?></td>
	    <td valign="top" width="40%">
		<a onclick ="$j(this).next().toggle(); changeIcon('span_<?=$i?>')">
		    <span id = "<?php echo 'span_'.$i;?>" class="closed-icn"></span><?php echo $university['totalSubViewCount'];?>
		</a>
            	<div id = "<?php echo 'view_'.$i;?>"class="listing-item-box" style="display:none;">
                    <ul>
			<li>
			    <p><?=$university['universityTitle']?></p>		
                            <span><?=$university['viewCount']?></span>
			    <ol style="padding-left:0;">
				<?php
				foreach($university['departments'] as $department){
				    if(strpos($department['departmentTitle'],'_DUMMYDEPARTMENT')===false){
				?>
	
				    <li style="list-style:none !important;margin-bottom:0;">
					<p style="width:220px !important"><?=$department['departmentTitle']?></p>
					<span style="float:none !important"><?=$department['viewCount']?></span>
					<ol style="padding-left:0;">
					<?php
					foreach($department['Courses'] as $course){
					?>
					
					    <li style="list-style:none !important;margin-bottom:0;">
						<p style="width:205px !important"><?=$course['courseTitle']?></p>
						<span style="float:none !important"><?=$course['viewCount']?></span>
						
					    </li>
					
					<?php
					}
					?>
					</ol>
				    </li>
				    <?php
				    }
				    else{
					foreach($department['Courses'] as $course){
					?>
					
					    <li style="list-style:none !important;margin-bottom:0;">
						<p style="width:220px !important"><?=$course['courseTitle']?></p>
						<span style="float:none !important"><?=$course['viewCount']?></span>
						
					    </li>
					
					<?php
					}
				    }
				}
				?>
			    </ol>
			</li>
			
                    </ul>
                </div>    
            </td>
            <td valign="top" width="40%">
		<a onclick ="$j(this).next().toggle(); changeIcon('span_contact_<?=$i?>')" >
		    <span id = "<?php echo 'span_contact_'.$i;?>" class="closed-icn"></span><?php echo $university['totalSubContactCount']?>
		</a>
            	<div id = "<?php echo 'contactDetails_'.$i;?>" class="listing-item-box" style="display:none;">
                    <ul>
			<li>
			    <p><?=$university['universityTitle']?> </p>
			    <span><?=$university['contactCount']?></span>
			    <ol style="padding-left:0;">
				<?php
				foreach($university['departments'] as $department){
				    if(strpos($department['departmentTitle'],'_DUMMYDEPARTMENT')===false){
				?>
				    <li style="list-style:none !important;margin-bottom:0;">
					<p style="width:220px !important"><?=$department['departmentTitle']?> </p>
					<span style="float:none !important"><?=$department['contactCount']?></span>
					<ol style="padding-left:0;">
					<?php
					foreach($department['Courses'] as $course){
					?>
					    <li style="list-style:none !important;margin-bottom:0;">
						<p style="width:205px !important"><?=$course['courseTitle']?> </p>
						<span style="float:none !important"><?=$course['contactCount']?></span>
					    </li>
					<?php
					}
					?>
					</ol>
				    </li>
				    <?php
				    }
				    else{
					foreach($department['Courses'] as $course){
					?>
					    <li style="list-style:none !important;margin-bottom:0;">
						<p style="width:220px !important"><?=$course['courseTitle']?> </p>
						<span style="float:none !important"><?=$course['contactCount']?></span>
					    </li>
					<?php
					}
				    }
				}
				?>
			    </ol>
			</li>
                    </ul>
                </div>    
            </td>
	</tr>
	<?php
	 $flag = $flag?0:1; $i++;
	}
	?>
        
        
        
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
