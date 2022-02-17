<div id="countries" onMouseOver="MM_showHideLayers('countries','','show');" onMouseOut="MM_showHideLayers('countries','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
		<a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index" title="All events list" >All</a>
                <?php
                        global $countriesForStudyAbroad;
                        foreach($countriesForStudyAbroad as $countryId => $country) {
                                $countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
                                $countryname = isset($country['name']) ? $country['name'] : '';
                                $linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
                ?>
                <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index/1/<?php echo $country['id']; ?>/<?php echo $countryname; ?>/1/All" title="Events Information - <?php echo $countryname;?>" class="shikIcons"><?php echo $countryname; ?></a>
                <?php } ?>

</div>
<div id="eventCategories" onMouseOver="MM_showHideLayers('eventCategories','','show');" onMouseOut="MM_showHideLayers('eventCategories','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
			<a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/<?php echo $fromOthers; ?>/1/All/<?php echo $location_id?>/<?php echo $location_name?>/0/10" title="All events list" >All</a>
                <?php
                        global $categoryParentMap;
                        foreach($categoryParentMap as $categoryName => $category) {
                ?>
                        <a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName; ?>/<?php echo $fromOthers; ?>/<?php echo $category['id']; ?>/<?php echo $categoryName; ?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10" title="<?php echo $categoryName." events lists";?>"><?php echo $categoryName; ?></a>
                <?php } ?>
</div>
<div id="eventCountries" onMouseOver="MM_showHideLayers('eventCountries','','show');" onMouseOut="MM_showHideLayers('eventCountries','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
                <?php
                        foreach($countriesForStudyAbroad as $countryId => $country) {
                                $countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
                                $countryname = isset($country['name']) ? $country['name'] : '';
                                $linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
                ?>
                <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/<?php echo $fromOthers; ?>/1/All/<?php echo $country['id']; ?>/<?php echo $countryname; ?>/0/10" title="Events list - <?php echo $countryname;?>" class="shikIcons"><?php echo $countryname; ?></a>
                <?php } ?>
</div>
<div id="eventTypes" onMouseOver="MM_showHideLayers('eventTypes','','show');" onMouseOut="MM_showHideLayers('eventTypes','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
                         <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/0/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">Application Submission Deadline</a>
                         <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/3/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">Examination Date</a>
                         <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/4/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">Form Issuance</a>
			 <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/2/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">Result Declaration</a>
			 <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/1/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">Course Commencement</a>
			 <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryName?>/5/<?php echo $category_id?>/<?php echo $category_name?>/<?php echo $location_id?>/<?php echo $location_name?>/0/10/All" class="shikIcons">General</a>

</div>
<div id="impDeadlines" onMouseOver="MM_showHideLayers('impDeadlines','','show');" onMouseOut="MM_showHideLayers('impDeadlines','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
                        <a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index/1/<?php echo $countryName; ?>/<?php echo $location_id?>/<?php echo $location_name?>#important_deadlines" title="All events list" >All</a>
                <?php
                        global $categoryParentMap;
                        foreach($categoryParentMap as $categoryName => $category) {
                ?>
                        <a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index/1/<?php echo $countryName?>/<?php echo $location_id?>/<?php echo $location_name?>/<?php echo $category['id'] ?>/<?php echo $categoryName; ?>#important_deadlines" title="<?php echo $categoryName." events list";?>"><?php echo $categoryName; ?></a>
                <?php } ?>

</div>
<div id="importantDeadlines" onMouseOver="MM_showHideLayers('importantDeadlines','','show');" onMouseOut="MM_showHideLayers('importantDeadlines','','hide');/*MM_showHideLayers('drpDown','','hide');*/">
			<a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index" title="All events list" >All</a>
                <?php
                        global $categoryParentMap;
                        foreach($categoryParentMap as $categoryName => $category) {
                ?>
                        <a class="shikIcons" href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/index/1/india/All/All/<?php echo $category['id'] ?>/<?php echo $categoryName; ?>" title="<?php echo $categoryName." events list";?>"><?php echo $categoryName; ?></a>
                <?php } ?>

</div>
