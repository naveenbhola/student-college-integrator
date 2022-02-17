<?php
    if(empty($interest_data['chart_data'])){
        return;
    }
?>
<div class="row">
    <div class="anl-card clearfix">
        <div class="col-md-12">
            <div class="row">
                <div class="anl-crd-blk clearfix">
                    <div class="col-md-8 col-sm-12">
                        <h2 class="pull-left">Interest Over Time <i class="t-icons t-info help-txt" helptext="Interest Over Time represents students' visits on Shiksha in the last 12 months on a given topic relative to the month with the maximum visits, which is signified by value of 100 (highest point on the chart). A value of 50 means that the topic is  visited half as many times in the given month than the most visited month. "></i></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row pb-15">
                <div class="col-sm-12"><div id="interestChart"></div></div>
            </div>
        </div>
    </div>
</div>