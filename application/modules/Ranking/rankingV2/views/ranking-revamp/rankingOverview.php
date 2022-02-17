<?php
if($isAjaxCall){
    $tableHtml      = $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageComparitiveRankingSec','',true);
    $tableHeaderHtml= $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageComaritiveRankingTableHeaderSticky','',true);
    $result     = array("tableHtml" => $tableHtml, "tableHeaderHtml" => $tableHeaderHtml);
    $result     = json_encode($result);
    echo $result;
    exit();
}
// Set $breadcrumbHtml and pass as data to rankingHeader
$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingHeader');
$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageContent');
$this->load->view('myShortlist/shortlistOnHoverMsg');
$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageHidden');
$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/rankingPageFooter');
?>