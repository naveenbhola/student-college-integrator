<table cellpadding="0" cellspacing="0" class="enterprise-table">
            	<tr>
                	<th width="10%">Task Name </th>
                    <th width="10%">Start Date </th>
                    <th width="10%">End Date </th>
                    <th width="20%">No. of. Submission</th>
                    <th width="10%">Status</th>
                    <th width="40%">Actions</th>
                </tr>
                <?php foreach($myTasks as $val) { ?>
                <tr>
                    <td><a target="_blank" href="/CAEnterprise/CampusAmbassadorEnterprise/getSubmissions/<?php echo $val['id'];?>"><?php echo htmlspecialchars($val['name'])?></a></td>
                    <td><?php echo date('d-m-Y',strtotime($val['start_date']));?></td>
                    <td><?php if(isset($val['end_date'])) echo date('d-m-Y',strtotime($val['end_date']));?></td>
                    <td><?php echo $val['totalSubmittedTasks'];?></td>
                    <td><?php echo isset($val['status']) ? $val['status'] : 'N/A'; ?></td>
                    <td><a href="<?php echo '/CAEnterprise/CampusAmbassadorEnterprise/myTask/getTask/'.$val['id'];?>" class="orange-btn">Edit</a>
                        <?php if($val['status'] == 'live') { ?>
                        <a href="javascript:void(0);" class="orange-btn" onclick="return task.changeStatus(1,'Already Live');" >Make Live</a>
                        <?php } else { ?>
                        <a href="<?php echo '/CAEnterprise/CampusAmbassadorEnterprise/myTask/live/'.$val['id'];?>" class="orange-btn" onclick="return task.changeStatus(0,'make it live');" >Make Live</a>
                        <?php } ?>
                        <?php if($val['status'] == 'draft') { ?>
                        <a href="javascript:void(0);" class="orange-btn" onclick="alert('Draft task cannot not be stopped');">Stop</a>
                        <?php } else if($val['status'] == 'stop') {?>
                        <a href="javascript:void(0);" class="orange-btn" onclick="return task.changeStatus(1,'Already Stopped');">Stop</a>
                        <?php } else { ?>
                        <a href="<?php echo '/CAEnterprise/CampusAmbassadorEnterprise/myTask/stop/'.$val['id'];?>" class="orange-btn" onclick="return task.changeStatus(0,'stop it');">Stop</a>        
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>  