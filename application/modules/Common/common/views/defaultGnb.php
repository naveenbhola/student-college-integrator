 <div id="top-nav">
		<ul>
        	<?php if(getClass("all") == ''){?>
            <li class="nav-home-tab"><a onclick="trackEventByGA('topnavhometabclick',this.innerHTML)" title="Home" id="hometab" href="<?php echo SHIKSHA_HOME; ?>" tabindex="8" class="<?php if(getClass("all") != ''){ echo getClass("all"); } ?>"><strong class="home-icon">Home</strong></a></li>
                <?php }else{?>
				<li class="nav-home-tab"><a onclick="trackEventByGA('topnavhometabclick',this.innerHTML)" title="Home" id="hometab" href="<?php echo SHIKSHA_HOME; ?>" tabindex="8" class="<?php if(getClass("all") != ''){ echo getClass("all"); } ?>"><strong class="home-icon">Home</strong></a></li>
                <?php } ?>
				<li><a href="javascript:void(0);" tabindex="9" class = "<?php echo getClass("MBA");?>" onmouseover="drpdwnOpen(this, 'MBA')" onmouseout="MM_showHideLayers('MBA','','hide');"><strong id="eventName">MBA</strong> <span></span></a></li>
                
                <li><a href="javascript:void(0);"  tabindex="10" class = "<?php echo getClass("gradHeader");?>" onmouseover="drpdwnOpen(this, 'gradOption')" onmouseout="MM_showHideLayers('gradOption','','hide');"><strong>After 12th</strong> <span></span></a>
                </li>
                <li><a id="all_courses_menu" href="javascript:void(0);"  tabindex="11" class = "<?php echo getClass("categoryHeader");?>" onmouseover="drpdwnOpen(this, 'careerOption')" onmouseout="MM_showHideLayers('careerOption','','hide');"><strong>All Courses</strong> <span></span></a>
				</li>
                <li style="position: relative;">
			      <i class="common-sprite new-icon-1"></i>
			      <a id="study_abroad_nav_menu" onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>"  tabindex="12" class="<?php echo getClass("foreign"); ?>"  onmouseover="drpdwnOpen(this, 'countryOption');" onmouseout="MM_showHideLayers('countryOption','','hide');"><strong>Study Abroad</strong> <span></span></a>
                </li>
                <!--<li>
                <a href="javascript:void(0);"  tabindex="13" class = "<?php echo getClass("testprep");?>" onmouseover="drpdwnOpen(this, 'testprep')" onmouseout="MM_showHideLayers('testprep','','hide');">
                <strong>Test Preparation</strong> <span></span></a>
				</li>-->
                <li><a id="" href="javascript:void(0);"  tabindex="13" onmouseover="drpdwnOpen(this, 'entranceExamMenu')" onmouseout="MM_showHideLayers('entranceExamMenu','','hide');"><strong>Entrance Exams</strong> <span></span></a>
		
		<!--<li><a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo CAREER_HOME_PAGE; ?>"  tabindex="13" class="<?php echo getClass("CareerProduct");?>"><strong>Entrance Exams</strong></a></li>-->
				<!--<li>
                <a href="javascript:void(0);"  tabindex="13" class = "<?php echo getClass("ranking");?>" onmouseover="drpdwnOpen(this, 'testprep')" onmouseout="MM_showHideLayers('testprep','','hide');">
                <strong>Rankings</strong> <span></span></a>
				</li>-->
                <li>
                <a href="javascript:void(0);"  tabindex="14" class = "<?php echo getClass("ranking");?>" onmouseover="drpdwnOpen(this, 'testprep')" onmouseout="MM_showHideLayers('testprep','','hide');">
                <strong>Top Colleges</strong> <span></span></a>
                </li>
                <li>
				<a onclick="trackEventByGA('topnavlinkclick',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME; ?>"  tabindex="15" class="<?php echo getClass("forums"); ?>"onmouseover="drpdwnOpen(this, 'cafeOption');" onmouseout="MM_showHideLayers('cafeOption','','hide');"><strong>Shiksha Caf&eacute;</strong> <span></span></a>
				</li>
                <li class="last">
			  <a href="javascript:void(0);"  tabindex="14" class = "<?php echo getClass("online");?>" onmouseover="drpdwnOpen(this, 'onlineOption')" onmouseout="MM_showHideLayers('onlineOption','','hide');">
                <strong>Application Forms</strong> <span></span></a>                </li>
                </ul>
    </div>
