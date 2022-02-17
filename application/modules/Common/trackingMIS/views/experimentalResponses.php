<style type="text/css">
    .pbttable td,.pbttable th{padding: 5px;}
    .grey{background-color: #eee;}
    .pbttable th{background-color: #d5d5d5;}
</style>
<div class="right_col clearfix" role="main" style="<?php echo ($skipNavigationSASales?'margin-left:0px;':''); ?>">          
    <h2>Experimental Responses Data</h2>
    <h6>Last 30 mins Non-Viewed Response data for <?php echo implode(",", $misinstituteIds); ?> institutes</h6>
    <table class="pbttable" border="1">
        <tr>
            <th>#</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Response Type</th>
            <th>Response Time</th>
            <th>Course Id</th>
            <th>Course Name</th>
            <th>Institute Id</th>
            <th>Institute Name</th>
        </tr>
        <?php
            $count = 1;
            foreach ($responseData as $key => $value) {
        ?>
                <tr>
                    <td><?php echo $count++;?>.</td>
                    <td><?php echo $value['firstname'];?></td>
                    <td><?php echo $value['lastname'];?></td>
                    <td><?php echo $value['mobile'];?></td>
                    <td><?php echo $value['email'];?></td>
                    <td><?php echo $value['action'];?></td>
                    <td><?php echo $value['submit_date'];?></td>
                    <td><?php echo $value['listing_type_id'];?></td>
                    <td><?php echo $courseDetails[$value['listing_type_id']]['name'];?></td>
                    <td><?php echo $instuteDetails[$courseDetails[$value['listing_type_id']]['primary_id']]['listing_id'];?></td>
                    <td><?php echo $instuteDetails[$courseDetails[$value['listing_type_id']]['primary_id']]['name'];?></td>
                </tr>
        <?php   
            }
        ?>
    </table>
    <br/><br/>
    <div id="univErrorMsg" class="alert alert-danger" style="display: none;"></div>
    <div id="univerDetails" class="col-md-12 row" style="display: none;">
        <div style="float: left;" class="pull-left">
            <img id="univLogo" src="">
        </div>
        
    </div>

</div>
