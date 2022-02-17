<?php 
            if(is_array($examList) && count($examList) > 0){ ?>
            <div class="search-section">
            	<table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0">
            		<tr>
            			<th>S.No.</th>
            			<th>Exam Name</th>
                        <th>No of Course Groups</th>                        
            			<th>Is Exam Published?</th>
            			<th>Action</th>
            		</tr>
            		<?php 
            		$num = 0;
            		foreach ($examList as $key => $value) {
            			$num++;
            		?>
            		<tr>
            			<td><?php echo $num;?></td>
            			<td><?php echo $value['examName'];?></td>
                        <td><?php echo $value['groupCount'];?></td>                    
            			<td><?php echo ($value['exampage_id'] > 0)? 'Yes':'No';?></td>
            			<td><a href="/examPages/ExamMainCMS/showMainExamList/<?php echo $value['examId']?>">Edit</a></td>
            		</tr>
            		<?php 
            		}	?>
            	</table>
            </div>
            <?php } 
            else { ?>
                <div class="search-section">
                <table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0">
                <tr>
                <td colspan="10">No exam found.</td>
                </tr>
                </table>
                </div>
            <?php } ?>