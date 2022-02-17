<?php if($courseSelectionWidgetData['type'] == 'ldb') {?>
    <div class="destination-details">
        <h2 class="explore-sub-title" style="margin-bottom: 0">2. Choose Study Destination</h2>
        <ul>
            <?php foreach($courseSelectionWidgetData['importantCountries'] as $impCountry){ ?>
                <li>
                    <a href="<?=$impCountry['url']?>" rel="external"><span class="sprite <?=str_replace(' ','',strtolower($impCountry['name']))?>-small"></span><?=$impCountry['name']?><i class="sprite rt-arr"></i>
                    </a>
                </li>
            <?php } ?>
            <?php if(count($courseSelectionWidgetData['importantCountries']) != count($courseSelectionWidgetData['countryList'])) { ?>
                <li><a href="#courseSectionCountrySelectionLayer" data-rel="dialog" data-inline="true" data-transition="slide" class="tac more-destination" style="color: #566ec2 !important;">View all Countries &nbsp;<span>&rsaquo;</span></a></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
<?php if($courseSelectionWidgetData['type'] == 'category') {?>
    <div class="course-details">
        <h2 class="explore-sub-title">2. Choose A Stream</h2>
        <div id="courseLevelSelectionSection" class="course-tab" style="overflow:visible;position:relative;">
            <ol>
                <li <?=($courseSelectionWidgetData['courseLevel'] == 'Bachelors')?"class='active'":"" ?>><a href="javascript:void(0)" onclick="previousSelection = curSelection;repopulateCountries(1,curCategory,curSelection = 'Bachelors');">Bachelors</a><span class="sprite pointer"></span></li>
                <li <?=($courseSelectionWidgetData['courseLevel'] == 'Masters')?"class='active'":"" ?>><a href="javascript:void(0)" onclick="previousSelection = curSelection;repopulateCountries(1,curCategory,curSelection = 'Masters');">Masters</a><span class="sprite pointer"></span></li>
                <li <?=($courseSelectionWidgetData['courseLevel'] == 'Certificate - Diploma')?"class='active'":"" ?>><a href="javascript:void(0)" onclick="previousSelection = curSelection;repopulateCountries(1,curCategory,curSelection = 'Certificate - Diploma');">Certification</a><span class="sprite pointer"></span></li>
            </ol>
        </div>
        <h2 class="explore-sub-title" style="margin-bottom:10px">3. Choose Study Destination</h2>
        <ul>
            <?php foreach($courseSelectionWidgetData['importantCountries'] as $impCountry){ ?>
                <li>
                    <a href="<?=$impCountry['url']?>" rel="external"><span class="sprite <?=str_replace(' ','',strtolower($impCountry['name']))?>-small"></span><?=$impCountry['name']?><i class="sprite rt-arr"></i>
                    </a>
                </li>
            <?php } ?>
            <?php if(count($courseSelectionWidgetData['importantCountries']) != count($courseSelectionWidgetData['countryList'])) { ?>
                <li><a href="#courseSectionCountrySelectionLayer" data-rel="dialog" data-inline="true" data-transition="slide" class="tac more-destination" style="color: #566ec2 !important;">View all Countries &nbsp;<span>&rsaquo;</span></a></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
    
<div class="sprite box-angle3"></div>
<script>
    if ((previousSelection == 'Bachelors' || previousSelection == 'Masters') && curSelection == 'Certificate - Diploma') {
        scrollCourseLevelSelection(1);
    }else if (previousSelection == 'Certificate - Diploma' && (curSelection == 'Bachelors' || curSelection == 'Masters')) {
        scrollCourseLevelSelection(0);
    }
</script>