<script>
	var RANKING_PAGE_MODULE = "rankingV2";
	var OVERALL_PARAM = 'Overall Rank';
</script>
<?php
$js = array('ranking_cms_new', 'common');
if(isset($addStudyAbroadJS)) {
    array_push($js,'studyAbroadCMS');
}
	$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'common_new', 'ranking_cms','exampages_cms'),
        'js'    =>  $js,
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'Ranking Enterprise',
        'taburl' => site_url(RANKING_PAGE_MODULE.'/RankingEnterprise/index'),
        'jsFooter' => array('scriptaculous'),   
        'metaKeywords'  =>'',
        'isOldTinyMceNotRequired' => 1
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');