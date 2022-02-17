<div class="abroad-cms-rt-box" style="width:820px">
    <div style="margin-top:0;" class="abroad-cms-head">
        <h2 class="abroad-sub-title">Client Delivery Dashboard</h2>
    </div>
    <div class="search-section">
        <div class="adv-search-sec clear-width" style="box-sizing:border-box;border-bottom: 1px solid #ccc;position:relative">
        <div style="width:750px; margin-top:5px;margin-bottom:6px;" class="cms-adv-box">
            <select id="countryFilter" class="universal-select" style="width:125px; padding:5px;" multiple="multiple">
                <?php foreach($filterData['countries'] as $country){ ?>
                    <option value="<?php echo  $country; ?>"><?php echo  $country; ?></option>
                <?php } ?>
            </select>
            <select id="counsellorFilter" class="universal-select" style="width:140px; padding:5px;" multiple="multiple">
                <?php foreach($filterData['counsellors'] as $counsellor){ ?>
                    <option value="<?php echo  $counsellor; ?>"><?php echo  $counsellor; ?></option>
                <?php } ?>
            </select>
            <select id="salesRepFilter" class="universal-select" style="width:125px; padding:5px;" multiple="multiple">
                <?php foreach($filterData['salesReps'] as $salesRep){ ?>
                    <option value="<?php echo  $salesRep; ?>"><?php echo  $salesRep; ?></option>
                <?php } ?>
            </select>
            <a style="padding:4px 10px;" class="orange-btn" href="Javascript:void(0);" id="filterButton">Go</a>
        </div>
        <div class="cms-search-box">
            <i class="abroad-cms-sprite search-icon"></i>
            <input type="text" class="search-field" placeholder="Search" defaulttext="Search" style="" id="searchBox"/>
            <a class="search-btn" id="searchButton" href="javascript:void(0);">Search</a>
        </div>
            <table id="clientDeliveryDatatable" class="cms-table-structure client-delivery-list" border="1" cellpadding="0" cellspacing="0" style="font-size:11px; float:left;
            ">
                <thead>
                    <tr>
                        <th width="5%" align="center" rowspan="2">Univ <br />Id</th>
                        <th width="10%" rowspan="2">
                            <span class="flLt">University Name</span>
                        </th>
                        <th width="10%" colspan="2">
                            <span class="flLt">Campaign</span>
                        </th>
                        <th width="5%" rowspan="2">
                            <span class="flLt">No. of Paid<br/> Courses</span>
                        </th>
                        <th width="15%" colspan="2">
                            <span class="flLt">Responses</span>
                        </th>
                        <th width="10%" rowspan="2">
                            <span class="flLt">University <br/>Finalised</span>
                        </th>
                        <th width="10%" rowspan="2">
                            <span class="flLt">SOP under <br/>review</span>
                        </th>
                        <th width="20%" colspan="3">
                            <span class="flLt">Application</span>
                        </th>
                        <th width="15%">
                            <span class="flLt">Sales Rep</span>
                        </th>
                        <th rowspan="2">Country</th>
                        <th rowspan="2">Counsellor</th>
                    </tr>
                    <tr>
                        <td class="dshbrd-bg">
                            <span class="flLt">Start Date</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">End Date</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">All <br/>Paid</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">RMC<br/> Exam</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">Submitted</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">Accepted</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">Rejected</span>
                        </td>
                        <td class="dshbrd-bg">
                            <span class="flLt">Sales Rep</span>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1; 
                        foreach($data as $id => $row){ 
                    ?>
                        <tr>
                            <td class="univId"><?php echo  $id; ?></td>
                            <td><?php echo  htmlentities($row['name']); ?></td>
                            <td><?php echo  $row['campaignStartDate']; ?></td>
                            <td><?php echo  $row['campaignEndDate']; ?></td>
                            <td><?php echo  $row['paidCoursesCount']; ?></td>
                            <td><span id="paid<?php echo $id; ?>"><?php echo  $row['paidResponsesCount']; ?></span></td>
                            <td><span id="exam<?php echo $id; ?>"><?php echo  $row['rmcExamResponses']; ?></span></td>
                            <td><span id="finalized<?php echo $id; ?>"><?php echo  $row['finalizedCourses']; ?></span></td>
                            <td><span id="sop<?php echo $id; ?>"><?php echo  $row['sopUnderReview']; ?></span></td>
                            <td><span id="submitted<?php echo $id; ?>"><?php echo  $row['submitted']; ?></span></td>
                            <td><span id="accepted<?php echo $id; ?>"><?php echo  $row['accepted']; ?></span></td>
                            <td><span id="rejected<?php echo $id; ?>"><?php echo  $row['rejected']; ?></span></td>
                            <td><?php echo  htmlentities($row['salesPerson']); ?></td>
                            <td><?php echo  $row['country']; ?></td>
                            <td><?php echo  $row['counsellorName']; ?></td>
                        </tr>
                    <?php 
                            $i++;
                        } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style> 
    table.dataTable {
        border-collapse: collapse;
    }
    .dataTables_wrapper .dataTables_length {
        float: right; position:relative; top:-36px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: none;  text-align: left;
    }
    .dataTables_wrapper .dataTables_filter input {
        /*margin-left: 0.5em;*/
        border: medium none;
        color: #ccc;
        float: left;
        height: 25px;
        margin: 0;
        outline: none;
        width:225px;
    }
    .dataTables_wrapper .dataTables_info {
        position:absolute; top:-32px; right:0;float: right; margin:28px 7px 15px 15px; padding:0px !important;
    }
    .dataTables_wrapper .dataTables_paginate {
        background: #eaeaea none repeat scroll 0 0;  margin-top: 10px;  padding:4px; float:right;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 2px 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #fff !important;
        background: #999 none repeat scroll 0 0;  
    }
    table.dataTable tbody th, table.dataTable tbody td{
        padding: 5px;
    }
</style>
<script>
    var univPaidResponses = {};
    var univRMCExamUsers = {};
</script>