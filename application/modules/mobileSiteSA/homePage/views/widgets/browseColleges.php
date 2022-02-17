<section id="findColleges" class="explore-pannel clearfix">
		<script>
			var previousSelection = '';
			var curSelection = 'Masters';
		</script>
        <div class="explore-title clearfix">
            <h1 class="flLt">Start Your College Search</h1>
        </div>
        <nav id="courseSectionNavBar" style="overflow:hidden;position:relative;">
            <ul id="ldbCourseSection" style="position:relative;overflow:visible;">
                <li class="nav-title"><h1>1. Choose Courses</h1></li>
                <li class="active">
                    <a id="1508" href="javascript:void(0)" onclick="repopulateCountries(1508,1,'Bachelors');" class="tab-1">MBA <span class="sprite pointer"></span></a>
                </li>
                <li><a id="1509" href="javascript:void(0)" onclick="repopulateCountries(1509,1,'Bachelors');" class="tab-3">MS <span class="sprite pointer"></span></a></li>
                <li><a id="1510" href="javascript:void(0)" onclick="repopulateCountries(1510,1,'Bachelors');" class="tab-2">B.E. /<br>B.TECH <span class="sprite pointer"></span></a></li>
                <li class="other-item">
                    <div class="child-box">
                        <a href="javascript:void(0);" onclick="scrollCourseSectionUp();repopulateCountries(1,curCategory = 239,curSelection = 'Masters');">other courses
                            <i class="sprite box-angle"></i>
                            <i class="sprite up-arr"></i>
                        </a>
                    </div>
                </li>
            </ul>
            <ul id="categoryCourseSection" class="course-tabs" style="position:relative;overflow:visible;">
                    <li class="nav-title"><h1>1. Choose Courses</h1></li>
                <li><a href="javascript:void(0)" onclick="scrollCourseSectionDown();previousSelection = 'Bachelors';repopulateCountries(1508,1,curSelection = 'Masters');" class="other-tab">Other Courses <i class="sprite dwn-arr"></i></a></li>
                <li class="active">
					<a id="239" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 239,curSelection = 'Masters');;">Business <span class="sprite pointer"></span></a>
				</li>
				<li><a id="240" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 240,curSelection = 'Masters');">Engineering <span class="sprite pointer"></span></a></li>
				<li><a id="242" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 242,curSelection = 'Masters');">Science <span class="sprite pointer"></span></a></li>
                
                
                <li><a id="241" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 241,curSelection = 'Masters');">Computers <span class="sprite pointer"></span></a></li>
                <li><a id="243" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 243,curSelection = 'Masters');">Medicine <span class="sprite pointer"></span></a></li>
                <li><a id="244" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 244,curSelection = 'Masters');">Humanities <span class="sprite pointer"></span></a></li>
                <li><a id="245" href="javascript:void(0)" onclick="previousSelection = 'Bachelors';repopulateCountries(1,curCategory = 245,curSelection = 'Masters');" style="border-bottom: 0 none">Law <span class="sprite pointer"></span></a><i class="sprite box-angle2"></i></li>
                
            </ul>
        </nav>
        
        <article class="explore-details customInputs">
			<div id="browseCollegesCountryList"><?php $this->load->view("widgets/browseCollegesCountryList"); ?></div>
        </article>
	
	<?php $this->load->view('widgets/searchBar'); ?>
	
</section>