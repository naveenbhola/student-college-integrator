<?php
if($categoryPageRequest->isSolrFilterAjaxCall())
{
    $this->load->view("categoryList/abroad/widget/categoryPageSolrFilterForSponsored");
}
if($isAjaxCall == 1)
{
    $this->load->view("categoryList/abroad/widget/categoryPageOnFilterApply");
}

if($isSortAJAXCall == 1)
{
    $this->load->view("categoryList/abroad/widget/categoryPageOnSorterApply");
}

//load page header
$this->load->view('categoryList/abroad/categoryPageHeader');
echo jsb9recordServerTime('SA_CATEGORY_PAGE',1);

//load course/country layer
$this->load->view('abroad/widget/changeCourseCountry');

//load other components on page
$this->load->view('categoryList/abroad/widget/categoryPageBreadCrumb');
$this->load->view('categoryList/abroad/categoryPageContent');
$this->load->view('categoryList/abroad/categoryPageFooter');
?>