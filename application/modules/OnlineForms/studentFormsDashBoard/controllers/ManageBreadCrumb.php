<?php
/**
 * This class manages bredcrumb details for student dashboard.
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class ManageBreadCrumb
{
	/**
	 * Renders BreadCrumb details
	 *
	 * @param none
	 * @return void
	 */
	public function renderBredCrumbDetails() {
		// Logic for breadCrumb Starts here
		$uri_part = uri_string();
		$uri_array = preg_split("/\//", $uri_part);
		// we can make below code more generic dependign upon the need
		if(is_array($uri_array) && !empty($uri_array[1])) {
			if(strcasecmp($uri_array[1], "FindInstitute") == 0) {
				$bread_crumb_string =
				'';
			} else if(strcasecmp($uri_array[1], "StudentDashBoard") == 0) {
				$bread_crumb_string =
				 '<div id="breadcrumb">
				 <ul>
        			 <li><a href="'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."".'" title="Application Forms">Application Forms</a></li>
            		 <li class="last">My Dashboard</li>
        		 </ul></div>';
			} else if(strcasecmp($uri_array[1], "MyDocuments") == 0) {
				$bread_crumb_string =
				 '<div id="breadcrumb">
				 <ul>
        			 <li><a href="'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."".'" title="Application Forms">Application Forms</a></li>
        			 <li><a href="'.SHIKSHA_HOME."/studentFormsDashBoard/StudentDashBoard/index".'" title="My Dashboard">My Dashboard</a></li>
            		 <li class="last">My Documents</li>
        		 </ul></div>';
			} else if(strcasecmp($uri_array[1], "MyForms") == 0) {
				$bread_crumb_string =
				 '<div id="breadcrumb">
				 <ul>
        			 <li><a href="'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."".'" title="Application Forms">Application Forms</a></li>
        			 <li><a href="'.SHIKSHA_HOME."/studentFormsDashBoard/StudentDashBoard/index".'" title="My Dashboard">My Dashboard</a></li>
            		 <li class="last">My Forms</li>
        		 </ul></div>';
			}else {
				$bread_crumb_string =
				'<div id="breadcrumb">
				<ul>
					<li><a href="'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."".'" title="Application Forms">Application Forms</a></li>
					<li class="last">My Dashboard</li>
				</ul></div>';
			}
		}
		if(strcasecmp($uri_array[2], "myProfile") == 0) {
			$bread_crumb_string =
				 '<div id="breadcrumb">
				 <ul>
        			 <li><a href="'.$GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']."".'" title="Application Forms">Application Forms</a></li>
        			 <li><a href="'.SHIKSHA_HOME."/studentFormsDashBoard/StudentDashBoard/index".'" title="My Dashboard">My Dashboard</a></li>
            		 <li class="last">My Profile</li>
        		 </ul></div>';
		}
		// Logic for breadCrumb Ends here
		return $bread_crumb_string;
	}
}
?>
