<div class="container">
    <div class="breadcrumb2">
        <h1 class="searchHead">
            <?php 
            $keyword = $request->getOldKeyword();
            if(empty($keyword)){
                $keyword = $request->getSearchKeyword();
            }
            if($relevantResults){
                echo 'Sorry, we could not find any results ';
            }
            else{
                echo "<span>{$totalInstituteCount}</span>";
                echo ($totalInstituteCount == 1)? ' college':' colleges';
            }
            ?> for <?php echo '&#8220;'.htmlentities($keyword).'&#8221;';?><?php
            ?>
        </h1>
        <a  href="javascript:void(0)" class="modify-search-head" >Modify Search</a>
        <?php 
        if($relevantResults){
            $headingStr = 'You may also be interested in the following colleges:';
            if($relevantResults != 'relax'){
                $headingStr = 'Showing results for &#8220;'.htmlentities($request->getSearchKeyword()).'&#8221;';
            }
            ?>
            <div class="breadcrumb2-sugestn">
                <p class="sugstn-p1">Suggestion: Please enter only a college/course name or check spellings</p>
                <p class="sugstn-p2"><?php echo $headingStr; ?></p>
            </div>
            <?php
        }
        ?>
        <?php
        $showSelectedFiltersArray = $selectedFilters['selectedFilters'];
        unset($showSelectedFiltersArray['subCategory']);
        unset($showSelectedFiltersArray['catId']);
        unset($showSelectedFiltersArray['locality']);
        if(!empty($showSelectedFiltersArray)){
        ?>
        <div class="slctd-fltrtags">
            <label>Selected Filters :</label>
            <span>
            <?php
            foreach($showSelectedFiltersArray as $filterName => $filterValues) {
                switch($filterName) {
                    case 'locations':
                        foreach($filterValues as $locationType => $locationValues) {
                            foreach($locationValues as $cityKey =>  $details) { ?>
                                <a href="javascript:void(0);" data-section="<?=$filterName;?>" data-val="<?=$cityKey?>"><?php echo $details['name'];?><i>x</i></a>
                            <?php
                            }
                        }
                    break;
                    case 'fees':
                        foreach($filterValues as $feesValue => $feesRange) {
                        ?>
                            <a href="javascript:void(0);" data-section="<?=$filterName;?>"  data-val="<?=$feesValue?>"><?php echo $feesRange['name'];?><i>x</i></a>
                        <?php
                        }
                    break;
                    case 'exams':
                        foreach($filterValues as $examKey => $examName) {
                            $exam = explode("_", $examName['name']);
                        ?>
                            <a href="javascript:void(0);"   data-section="<?=$filterName;?>" data-val="<?=$examKey?>"><?php echo $exam[0];?><i>x</i></a>
                        <?php
                        }
                    break;
                    case 'specialization':
                        foreach($filterValues as $specKey =>$specializationDetails) {
                            if(strtolower($specializationDetails['name']) == 'all'){
                                continue;
                            }
                        ?>
                            <a href="javascript:void(0);"   data-section="specialisation" data-val="<?=$specKey?>" ><?php echo $specializationDetails['name'];?><i>x</i></a>
                        <?php
                        }
                    break;
                    case 'facilities':
                        foreach ($filterValues as $key => $value) {?>
                            <a href="javascript:void(0);"   data-section="<?=$filterName;?>" data-val="<?=$key?>" ><?php echo $value['name'];?><i>x</i></a>
                        <?php }
                    break;
                    case 'degreePref':
                    foreach ($filterValues as $key => $value) {?>
                            <a href="javascript:void(0);"   data-section="<?=$filterName;?>" data-val="<?=$key?>" ><?php echo $value['name'];?><i>x</i></a>
                        <?php
                        }
                    break;
                    case 'affiliation':
                    case 'classTimings':
                    foreach ($filterValues as $key => $value) {?>
                            <a href="javascript:void(0);"   data-section="<?=$filterName;?>" data-val="<?=$key?>" ><?php echo $value['name'];?><i>x</i></a>
                        <?php
                        }
                    break;
                    default:
                     foreach ($filterValues as $key => $value) {?>
                            <a href="javascript:void(0);"   data-section="<?=$filterName;?>" data-val="<?=$key?>" ><?php echo $value['name'];?><i>x</i></a>
                        <?php
                        }
                    break;
                    }
            }
        
        ?>
            </span>
            <a class="clearAll">Clear All</a>
        </div>
        <?php } ?>
    </div>
</div>
