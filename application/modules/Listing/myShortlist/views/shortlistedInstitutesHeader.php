<?php
if(in_array($examName,$examNamesForSortLayer)) {
    $class = array();
    foreach($examNamesForSortLayer as $exam) {
        $class[$exam.'-up'] = '';
        $class[$exam.'-dwn'] = '';
        if($exam == $examName && $order == 'asc') {
            $class[$exam.'-up'] = '-a';
        }
        else if($exam == $examName && $order == 'desc') {
            $class[$exam.'-dwn'] = '-a';
        }
    }
}
else {
    $class =  array('salary-up' => '',
                    'salary-dwn' => '',
                    'fees-up' => '',
                    'fees-dwn' => '',
                    'form_submission-up' => '',
                    'form_submission-dwn' => '',
                    );
    switch($sortField) {
        case 'salary'           : if($order == 'asc') $class[$sortField.'-up'] = '-a';
                                  else if($order == 'desc') $class[$sortField.'-dwn'] = '-a';
                                  break;
        case 'fees'             : if($order == 'asc') $class[$sortField.'-up'] = '-a';
                                  else if($order == 'desc') $class[$sortField.'-dwn'] = '-a';
                                  break;
        case 'form_submission'  : if($order == 'asc') $class[$sortField.'-up'] = '-a';
                                  else if($order == 'desc') $class[$sortField.'-dwn'] = '-a';
                                  break;
        case 'eligibility'      : if($order == 'asc') $class[$sortField.'-up'] = '-a';
                                  else if($order == 'desc') $class[$sortField.'-dwn'] = '-a';
                                  break;
    }
}

?>

<tr>
    <th class="first" width="15" style="text-align:center"></th>
    <th style="width:205px">College</th>
    <th style="position: relative;width:170px;">
    <div onclick='$j("#sort_exam_layer").toggle("slow");' id="eligibilitySortLayerDiv">
        <span>Exams Accepted</span>
        <div class="sorting-col">
            <a href="javascript:void(0);" class="shortlist-sprite sorting-up"></a>
            <a href="javascript:void(0);" class="shortlist-sprite sorting-dwn"></a>
        </div>
    </div>
<?php if(count($examNamesForSortLayer) > 0) { ?>
    <div style="border: 1px solid rgb(164, 196, 235); display: none;" class="sorting-layer" id="sort_exam_layer">
        <?php 
        foreach($examNamesForSortLayer as $exam) { ?>
            <div style="padding: 5px 0;" class="sort-items">
                <a class="flLt" href="javascript:void(0);"><?php echo $exam;?></a>
                    <div class="sorting-col">
                        <a href="javascript:void(0);" onclick="refreshShortlistedTuples({examName: '<?php echo $exam;?>', sortField: 'eligibility', order: 'asc'}); trackSortingMyShortlist({examName: '<?php echo $exam;?>', sortField: 'eligibility', order: 'asc'});" title="Low to High" class="shortlist-sprite sorting-up<?php echo $class[$exam.'-up'];?>"></a>
                        <a href="javascript:void(0);"onclick="refreshShortlistedTuples({examName: '<?php echo $exam;?>', sortField: 'eligibility', order: 'desc'}); trackSortingMyShortlist({examName: '<?php echo $exam;?>', sortField: 'eligibility', order: 'desc'});" title="High to Low" class="shortlist-sprite sorting-dwn<?php echo $class[$exam.'-dwn'];?>"></a>
                    </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
    </th>
    <th style="text-align:center;width:145px;">
        <span>Rank</span>
    </th>
    <th style="width:85px">
        <span class="header-label">Average <br/>Salary</span>
        <div class="sorting-col">
            <a href="javascript:void(0);" class="shortlist-sprite sprite sorting-up<?php echo $class['salary-up'];?>" onclick="refreshShortlistedTuples({sortField: 'salary', order: 'asc'}); trackSortingMyShortlist({sortField: 'salary', order: 'asc'});" title="Low to High"></a>
            <a href="javascript:void(0);" class="shortlist-sprite sorting-dwn<?php echo $class['salary-dwn'];?>" onclick="refreshShortlistedTuples({sortField: 'salary', order: 'desc'}); trackSortingMyShortlist({sortField: 'salary', order: 'desc'});" title="High to Low"></a>
        </div>
    </th>
    <th style="width:104px">
        <span class="header-label">Total<br/> Fees</span>
        <div class="sorting-col">
            <a href="javascript:void(0);" class="shortlist-sprite sprite sorting-up<?php echo $class['fees-up'];?>" onclick="refreshShortlistedTuples({sortField: 'fees', order: 'asc'}); trackSortingMyShortlist({sortField: 'fees', order: 'asc'});" title="Low to High"></a>
            <a href="javascript:void(0);" class="shortlist-sprite sorting-dwn<?php echo $class['fees-dwn'];?>" onclick="refreshShortlistedTuples({sortField: 'fees', order: 'desc'}); trackSortingMyShortlist({sortField: 'fees', order: 'desc'});" title="High to Low"></a>
        </div>
    </th>
    <th style="width:auto" class="last">
        <span class="header-label">Application<br/>Deadlines</span>
        <div class="sorting-col">
            <a href="javascript:void(0);" onclick="refreshShortlistedTuples({sortField: 'form_submission', order: 'asc'}); trackSortingMyShortlist({sortField: 'form_submission', order: 'asc'});" class="shortlist-sprite sprite sorting-up<?php echo $class['form_submission-up'];?>" title="Low to High"></a>
            <a href="javascript:void(0);" onclick="refreshShortlistedTuples({sortField: 'form_submission', order: 'desc'}); trackSortingMyShortlist({sortField: 'form_submission', order: 'desc'});" class="shortlist-sprite sorting-dwn<?php echo $class['form_submission-dwn'];?>" title="High to Low"></a>
        </div>    
    </th>
    <!--<th width="70" class="add-col" style="display:none0;"><big>+</big> <span>Add<br>Criteria</span></th>-->

</tr>