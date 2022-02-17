<div class="article">
	<?php
		$breadCrumbText = "Add New Article";
		$displayData["pageTitle"]  	= "Add New Article Details";
		if($action == 'edit')
		{
			$breadCrumbText = "Edit Article";
			$displayData["pageTitle"]  	= "Edit Article";
			$displayData["pageTitle"] 	.= "<label style='color:red;'>".($content['basic_info']['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
		}
		$displayData["breadCrumb"] 	= array(array("text" => "All Articles", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT),
									array("text" => $breadCrumbText, "url" => "") );
		if($action == 'edit') {
			$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($lastModified)),
								"username" => $lastModifiedBy);
		}
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
</div>

<div class="guide" style="display: none">
	<?php
		$breadCrumbText = "Add New Guide";
		$displayData["pageTitle"]  	= "Add New Guide Details";
		if($action == 'edit')
		{
			$breadCrumbText = "Edit Guide";
			$displayData["pageTitle"]  	= "Edit Guide Details";
			$displayData["pageTitle"] 	.= "<label style='color:red;'>".($content['basic_info']['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
		}
		$displayData["breadCrumb"] 	= array(array("text" => "All Guides", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT),
									array("text" => $breadCrumbText, "url" => "") );
		if($action == 'edit') {
			$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($lastModified)),
								"username" => $lastModifiedBy);
		}
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
</div>

<div class="examPage" style="display: none">
	<?php
		$breadCrumbText = "Add Exam Page";
		$displayData["pageTitle"]  	= "Add Exam Page Details";
		if($action == 'edit')
		{
			$breadCrumbText = "Edit Exam Page";
			$displayData["pageTitle"]  	= "Edit Exam Page Details";
			$displayData["pageTitle"] 	.= "<label style='color:red;'>".($content['basic_info']['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
		}
		$displayData["breadCrumb"] 	= array(array("text" => "All Exam Page", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT),
									array("text" => $breadCrumbText, "url" => "") );
		if($action == 'edit') {
			$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($lastModified)),
								"username" => $lastModifiedBy);
		}
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
</div>

<div class="applyContent" style="display: none">
	<?php
		$breadCrumbText = "Add Apply Content";
		$displayData["pageTitle"]  	= "Add Apply Content Details";
		if($action == 'edit')
		{
			$breadCrumbText = "Edit Apply Content";
			$displayData["pageTitle"]  	= "Edit Apply Content Details";
			$displayData["pageTitle"] 	.= "<label style='color:red;'>".($content['basic_info']['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
		}
		$displayData["breadCrumb"] 	= array(array("text" => "All Apply Content", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT),
									array("text" => $breadCrumbText, "url" => "") );
		if($action == 'edit') {
			$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($lastModified)),
								"username" => $lastModifiedBy);
		}
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
</div>
<div class="examContent" style="display: none">
	<?php
		$breadCrumbText = "Add Exam Content";
		$displayData["pageTitle"]  	= "Add Exam Content Details";
		if($action == 'edit')
		{
			$breadCrumbText = "Edit Exam Content";
			$displayData["pageTitle"]  	= "Edit Exam Content Details";
			$displayData["pageTitle"] 	.= "<label style='color:red;'>".($content['basic_info']['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
		}
		$displayData["breadCrumb"] 	= array(array("text" => "All Exam Content", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT),
									array("text" => $breadCrumbText, "url" => "") );
		if($action == 'edit') {
			$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($lastModified)),
								"username" => $lastModifiedBy);
		}
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
</div>