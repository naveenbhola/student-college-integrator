<section class="content-section">
    <article>          
        <ul class="criteria-cards" id="criteria-cards">
            <li style="overflow: hidden;">
                <div class="criteria-title"  onclick="NaukriToolComponent.hideLayer('0','1-2');" id="criteria-title0">
                    <p class="flLt" id="jobFuncHeading"><strong>Select Job Function</strong></p>
                    <p class="flRt" id="totalJobFunctionCount">
                        <?php
                        $totalJobFunctions = 0;
                        foreach($jobFuncData as $key=>$arr){
                            $totalJobFunctions += $arr['totalEmployee'];
                        }
                        echo $totalJobFunctions;
                        ?>
                    </p>
                    <div class="clearfix"></div>
                </div>
                <div class="criteria-detail" id="section0">
                    <strong class="select-job-title" id="jobFuncPieChartTitle">Job functions of alumni</strong>
                    <div id="piechart1" style="text-align: center;"></div>
                </div>
            </li>
            <li style="overflow: hidden;">
                <div class="criteria-title" onclick="NaukriToolComponent.hideLayer('1','0-2');"  id="criteria-title1">
                    <p class="flLt" id="companyHeading"><strong>Select Company</strong></p>
                    <p class="flRt" id="totalCompanyCount">
                    <?php
                    $totalCompanies = 0;
                    foreach($companiesData as $key=>$arr){
                        $totalCompanies += $arr['totalEmployee'];
                    }
                    echo $totalCompanies;
                    ?>
                    </p>
                    <div class="clearfix"></div>
                </div>
                <div class="criteria-detail" id="section1">
                    <strong class="select-job-title" id="companyPieChartTitle">Companies where alumni are working</strong>
                    <div id="piechart2"></div>
                </div>
            </li>
            <li style="overflow: hidden;">
                <div class="criteria-title" onclick="NaukriToolComponent.hideLayer('2','0-1');"  id="criteria-title2">
                    <p class="flLt" id="locationHeading"><strong>Select College Location</strong></p>
                    <p class="flRt" id="totalLocationCount">
                    <?php
                    $totalLocations = 0;
                    foreach($citiesData as $key=>$arr){
                        $totalLocations += $arr['totalInst'];
                    }
                    echo $totalLocations;
                    ?>
                    </p>
                    <div class="clearfix"></div>
                </div>
                <div class="criteria-detail" id="section2">
                    <strong class="select-job-title" id="locationPieChartTitle">College locations where alumni studied</strong>
                    <div id="piechart3"></div>
                </div>
            </li>
        </ul>
    </article>
</section>
<script>
    //$('#criteria-title1').css({'padding-bottom':'0px'});
    //$('#criteria-title2').css({'padding-bottom':'0px'});
</script>