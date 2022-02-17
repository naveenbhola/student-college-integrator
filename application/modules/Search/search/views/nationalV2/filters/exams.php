<?php
$appliedExamFiltersWithScore = $appliedFilters['exams'];
$appliedExamFiltersWithOutScore = array();
$appliedExamFiltersWithOnlyScore = array();
if(!empty($appliedExamFiltersWithScore)){
    foreach($appliedExamFiltersWithScore as $res){
        $examStringSplit = explode("_", $res);
        $appliedExamFiltersWithOutScore[] = $examStringSplit[0];
        $appliedExamFiltersWithOnlyScore[$examStringSplit[0]]    = $examStringSplit[1];
    }
}
?>

<li><a class="activeLeftNav">Exams Accepted<p id="clearex" style="<?php echo (count($appliedExamFiltersWithOutScore) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
    <div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
                <ul class="srpsubmenu" data-section="exams">
                    <?php foreach ($data as $exam => $info) {
                        $count = $info['count'];
                        $checkedExamString = "";
                        $showScoreDiv = 0;
                        if($info['checked']){
                            $checkedExamString = "checked ='chechked'";
                            $showScoreDiv = 1;
                        }
                        $exId = str_replace(" ", "-",$exam);
                        ?>
                        <li>
                            <a class="checkboxnav">
                                <div class="nav-checkBx">
                                    <input type="checkbox" class="nav-inputChk" <?=$checkedExamString;?> data-val="<?=$exam?>" id="<?=$exId?>">
                                    <label for="<?=$exId?>" class="nav-heck">
                                        <i class="icons ic_checkdisable1"></i><?=$info['name']?> (<?=$count?>)
                                    </label>
                                </div>
                            </a>
                            <?php 
                             if(in_array($request->getSubCategoryId(), array(23,56)) && $showScoreDiv == 1)
                                $this->load->view('nationalV2/filters/examsScore',array('exam'=>$info['name'],'score'=>$appliedExamFiltersWithOnlyScore)); 
                            ?>
                            
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</li>