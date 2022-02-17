<?php
/**
 * Class to test web services
 * 
 */
class testtarget extends MX_Controller {
	function init() {
		$this->load->library('Sums_targetInput');
	}
	function index() { 
		$this->init();
		$this->load->library('Sums_targetInput');
		$Sums_targetInput = new Sums_targetInput();
		$result = $Sums_targetInput->updateTargetDetails('12','12','12','12','4,5,6','2009','12000');
		var_dump($result);
	}
	/*
	 CREATE TABLE `target_sums` (
	 `id` int(10) unsigned NOT NULL auto_increment,
	 `branch_id` int(10) unsigned NOT NULL,
	 `executive_id` int(10) unsigned NOT NULL,
	 `assigned_by` int(10) unsigned NOT NULL,
	 `quarter` varchar(50) NOT NULL,
	 `year` varchar(50) NOT NULL,
	 `target` double NOT NULL,
	 `logged` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	 `is_active` int(1) NOT NULL default '1',
	 PRIMARY KEY  (`id`)
	 ) ENGINE=MyISAM;
	 insert into shiksha.tabNames values(26,"TargetInput", 26 ,"/sums/targetInput/collection");
	 update shiksha.usergroupTabs set tabs=concat(tabs,",26") where selected_tab=16;
	 */
}
?>
