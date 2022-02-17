<style type="text/css">
    .pbttable td,.pbttable th{padding: 5px;}
    .grey{background-color: #eee;}
    .pbttable th{background-color: #d5d5d5;}
</style>
<div class="right_col clearfix" role="main" style="<?php echo ($skipNavigationSASales?'margin-left:0px;':''); ?>">          
    <!-- top tiles -->
    <!-- <div class="row x_title">
        <div class="col-md-5 pull-left form-group"> 
            <select class="form-control" name="courseType" id="courseType"> 
                <option value="all">All</option> 
                <?php
                    foreach ($uniquePBTs as $key => $value) {
                ?>
                        <option value="<?php echo $value['pixel_id'];?>"><?php echo $value['pixel_id'];?></option> 
                <?php
                    }
                ?>
            </select> 
        </div>            
        <div class="col-md-2 pull-right">
            <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </div> -->
    <h2>PBT Real-time Tracking</h2>
    <table class="pbttable" border="1">
        <tr>
            <th>#</th>
            <th>Pixeld</th>
            <th>Client</th>
            <th>Courses Mapped</th>
            <th>Landings: Total Form</th>
            <th>Landings: Shiksha Visitor + Non-Registered</th>
            <th>Landings: Shiksha Visitor + Registered</th>
            <th>Landings: Non-Shiksha Visitor</th>
            <th>Submission: Total Form</th>
            <th>Submission: Shiksha Visitor + Non-Registered</th>
            <th>Submission: Shiksha Visitor + Registered</th>
            <th>Submission: Non-Shiksha Visitor</th>
        </tr>
        <?php
            $count = 1;
            $topClients = array(206124159,780970969,507842146,195398743,885215787,330845928,122086021,550801794,101961996);
            foreach ($pbtData as $key => $value) {
                if(empty($pbtPixelData[$key]['clientname']) || (!in_array($key, $topClients) && $_REQUEST['top'] == 1) )
                    continue;
        ?>
                <tr>
                    <td><?php echo $count++;?>.</td>
                    <td><?php echo $key;?></td>
                    <td><?php echo $pbtPixelData[$key]['clientname']?></td>
                    <td title="<?php echo $pbtPixelData[$key]['courses']?>"><?php echo strlen($pbtPixelData[$key]['courses']) <= 10 ?$pbtPixelData[$key]['courses'] : substr($pbtPixelData[$key]['courses'], 0, 10)."..."; ?></td>
                    <td class="grey"><?php echo $value['landings']['total'] ? $value['landings']['total'] : "-";?></td>
                    <td><?php echo $value['landings']['nonRegisteredshikshaVisitors'] ? $value['landings']['nonRegisteredshikshaVisitors'] : "-";?></td>
                    <td><?php echo $value['landings']['registeredshikshaVisitors'] ? $value['landings']['registeredshikshaVisitors'] : "-";?></td>
                    <td><?php echo $value['landings']['nonShikshaVisitors'] ? $value['landings']['nonShikshaVisitors'] : '-';?></td>
                    <td class="grey"><?php echo $value['submitted']['total'] ? $value['submitted']['total'] : '-';?></td>
                    <td><?php echo $value['submitted']['nonRegisteredshikshaVisitors'] ? $value['submitted']['nonRegisteredshikshaVisitors'] : '-';?></td>
                    <td><?php echo $value['submitted']['registeredshikshaVisitors'] ? $value['submitted']['registeredshikshaVisitors'] : '-';?></td>
                    <td><?php echo $value['submitted']['nonShikshaVisitors'] ? $value['submitted']['nonShikshaVisitors'] : '-';?></td>
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
