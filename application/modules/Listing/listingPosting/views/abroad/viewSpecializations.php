<div class="abroad-cms-rt-box">
    <div style="margin-top:0;" class="abroad-cms-head">
        <h2 class="abroad-sub-title">All Specializations</h2>
        <div class="flRt"><a style="padding:6px 7px 8px" class="orange-btn" href="/listingPosting/AbroadListingPosting/addEditSpecializations/">+ Add Specialization</a></div>
    </div>
    <div class="search-section">
        <div class="adv-search-sec clear-width" style="box-sizing:border-box;border-bottom: 1px solid #ccc;position:relative">
            <table id="specializationTable" class="cms-table-structure" border="1" cellpadding="0" cellspacing="0">
                <thead>
                    <th>S.No.</th>
                    <th>Specialization Name</th>
                    <th>Sub category</th>
                    <th>Parent category</th>
                    <th>Updated On</th>
                </thead>
                <tbody>
                    <?php
                        $i = 1; 
                        foreach($tableData as $row){ 
                    ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <p style="margin-bottom:4px;"><?=htmlentities($row['name'])?></p>
                                <div class="edit-del-sec">
                                    <a href="/listingPosting/AbroadListingPosting/addEditSpecializations/<?=$row['id']?>">Edit</a>&nbsp;&nbsp;
                                    <a href="javascript:void(0);" onclick="delete_row('deleteSpecializationListing',<?php echo $row['id'];?>)">Delete</a>
                                </div>
                            </td>
                            <td><?=$row['subcategory']?></td>
                            <td><?=$row['category']?></td>
                            <td><?=date_format(date_create($row['date']),'d M Y')?></td>
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
        float: right;
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
        position:absolute; top:0; right:0;float: right; margin:28px 7px 15px 15px; padding:0px !important;
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