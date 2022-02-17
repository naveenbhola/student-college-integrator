<div class="exam-right-col">
<div id ="examPageRightBanner" style="z-index:1">
<?php 
$duplicateShikshaCriteria = array('MAT'=>'SHK_EXAM1','CUSAT_CAT'=>'SHK_CUSAT','PESSAT_MBA'=>'SHK_PST_MBA');
$bannerProperties = array(
		'pageId'=>'EXAM',
		'pageZone'=>'RIGHT',
		'examPageShikshaCriteria' => (array_key_exists($shikshaCriteria, $duplicateShikshaCriteria)) ? $duplicateShikshaCriteria[$shikshaCriteria] : $shikshaCriteria
);
$this->load->view('common/banner',$bannerProperties);
?>
</div>

<!-- Related Articles Widget-->
<?php
    if(in_array($pageType, array('discussion','home','article')))
    {
        //echo Modules::run('blogs/blog_server/relatedArticlesWidget',$examName);
    }
?>
<!-- Related Articles Widget end-->

<!--calendar widget-->
<?php
$fromWhereForCalendar = 'examPageRightSection';
$categoryName = $examPageData->getCategoryName();
if($streamCheck == 'fullTimeMba' || $streamCheck == 'beBtech')
{
    if(in_array($pageType, array('imp_dates','home')))
    {
        echo Modules::run('event/EventController/getCalendarWidget',$fromWhereForCalendar, $eventCalfilterArr);
    }
}
?>
<!--calendar widget end-->  
  
<?php
if($subcatId == 23) { 
$this->load->view('examPages/widgets/examPageShortlistWidget');
}
?>
<script></script>
</div>
