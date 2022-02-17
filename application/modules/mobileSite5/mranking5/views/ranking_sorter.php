<?php
$rankingPageData = $ranking_page->getRankingPageData();

//Check if Exam needs to be displayed
$showExamFilters = false;
foreach($rankingPageData as $pageRow){
        if(count($pageRow->getExams()) > 0){
                $showExamFilters = true;
        }
        if($pageRow->getFeesValue() > 0){
                $showFeesColoumn = true;
        }
        if($showExamFilters && $showFeesColoumn){
                break;
        }
}
$showExamColoumn = $showExamFilters;

//Check Sort functionality 
$enableSortFunctionality = false;

$sortKey                =  "rank";
$sortKeyValue   =  "";
$sortOrder              =  "asc";
if(!empty($sorter) && count($rankingPageData) > 1){
        $enableSortFunctionality = true;
        $sortKey                =  $sorter->getKey();
        $sortKeyValue   =  $sorter->getKeyValue();
        $sortOrder              =  $sorter->getOrder();
}


//Check if Exam Id is Set or Not
$examId = "";
if($result_type == "main"){
        $examId = $request_object->getExamId();
} else if($result_type == "exam"){
        if(!empty($main_request_object)){
                $examId = $main_request_object->getExamId();
        } else {
                $examId = $request_object->getExamId();
        }
}


if($enableSortFunctionality){
                //Display the Sort institute Link
                echo '&nbsp;<a href="#popupMenu" data-rel="popup" data-inline="true" data-transition="slide" data-icon="gear" class="change-loc" id="lId" style="margin-left:0px;font-size:0.9em">Sort Institutes<i class="icon-right"></i></a>';

                //Now, check which all Values needs to be displayed inside Sorting popup
                echo '<div data-role="popup" id="popupMenu" data-theme="d">';
                echo '<ul data-role="listview" data-inset="true" style="min-width:200px;" data-theme="d">';
                echo '<li data-role="divider" data-theme="e">Sort By</li>';
                ?>

                                                <?php
                                                if($sortKey == "rank" && $sortOrder == "desc"){
                                                        ?>
                                                        <li>Rank Desc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'asc');">Rank Asc</span></li>
                                                <?php
                                                } else if($sortKey == "rank" && $sortOrder == "asc"){
                                                        ?>
                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'desc');">Rank Desc</span></li>
                                                        <li>Rank Asc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                <?php
                                                } else {
                                                        ?>
                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'desc');">Rank Desc</span></li>
                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'asc');">Rank Asc</span></li>
                                                        <?php
                                                }
                                                ?>
                                                <div id="sort_loader_rank" style="float:left;margin-top:5px;margin-left:5px;"></div>

                                                <?php
                                                if($result_type  == "main"){ ?>

                                                                        <?php
                                                                        if($sortKey == "fees" && $sortOrder == "desc"){
                                                                                ?>
                                                                                <li>Fees Desc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                                                <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'asc');">Fees Asc</span></li>
                                                                        <?php
                                                                        } else if($sortKey == "fees" && $sortOrder == "asc"){
                                                                                ?>
                                                                                <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'desc');">Fees Desc</span></li>
                                                                                <li>Fees Asc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                                        <?php
                                                                        } else {
                                                                                ?>
                                                                                <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'desc');">Fees Desc</span></li>
                                                                                <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'asc');">Fees Asc</span></li>
                                                                                <?php
                                                                        }
                                                                        ?>
                                                                        <div id="sort_loader_fees" style="float:left;margin-top:5px;margin-left:5px;"></div>
                                                                        
                                                                        <?php
                                                                        if($showExamColoumn && !empty($examId)){ ?>
                                                                                <?php
                                                                                if($sortKey == "marks" && $sortOrder == "desc"){
                                                                                        ?>
                                                                                        <li>Marks Desc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'asc');">Marks Asc</span></li>
                                                                                <?php
                                                                                } else if($sortKey == "marks" && $sortOrder == "asc"){
                                                                                        ?>
                                                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'desc');">Marks Desc</span></li>
                                                                                        <li>Marks Asc <i class="icon-check" style="margin-top: 15px;"></i></li>
                                                                                <?php
                                                                                } else {
                                                                                        ?>
                                                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'desc');">Marks Desc</span></li>
                                                                                        <li><span style="cursor:pointer;" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'asc');">Marks Asc</span></li>
                                                                                        <?php
                                                                                }
                                                                                ?>
                                                                                <div id="sort_loader_marks" style="float:left;margin-top:5px;margin-left:5px;"></div>
                                                                            <?php
                                                                        }
                                                }
                echo '</ul>';
                echo '</div>';
                                                    
}
?>

<script>
var sortResults = function(resultBlockType, keyName, sortOrder){
    if(typeof resultBlockType != "undefined" && typeof keyName != "undefined" && typeof sortOrder != "undefined"){
        var request = false;
        switch(resultBlockType){
            case 'main':
                request = Ranking.getPageRequest();
                break;
            case 'exam':
                request = Ranking.getExamPageRequest();
                break;
            case 'location':
                request = Ranking.getLocationPageRequest();
                break;
        }
        if(typeof request != "undefined"){
                $('#rtc_main').html('<img src="/public/images/loader_small_size.gif" width="11px" height="11px"/>');
                request['sort_keyname']         = keyName;
                request['sort_order']           = sortOrder;
                request['resultBlockType']      = resultBlockType;
                makeAjaxCall("getSortedResultsForRequest", request, 'handleSortedResults');
        }
    }
}

var handleSortedResults = function(response, resultContainerType){
    if(typeof response.html != "undefined"){
        var html = response.html;
        if(resultContainerType != ""){
            var ele = $("rtc_" + resultContainerType);
            if(ele){
                ele.innerHTML = html;
            }
        }
    }
}

</script>