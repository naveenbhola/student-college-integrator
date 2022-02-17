<?php
    if($request->isFilterAjaxCall() === true)
    {   // filter genration on page load
        $filterView = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageFilters',array(),true);
        $topBar = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuplesHead',array(),true);
        echo json_encode(array('filters'=>$filterView,'topBar'=>$topBar));
    }
    else if($request->isAjaxCall() === true)
    {
        // filter application
        $filterView = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageFilters',array(),true);
        $topBar     = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuplesHead',array(),true);
        $tuples    = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuples',array(),true);
        $countWithSort = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageCountWithSort',array(),true);
        $breadcrumb = $this->load->view('listing/abroad/widget/breadCrumbs',array('breadcrumbData'=>$breadcrumbData),true);
        if($totalTupleCount>0){
            $paginationSection = $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPagePagination',array(),true);
        }
        $requestCounter = $request->getRequestCounter();
        echo json_encode(array('filters'=>$filterView,
                                'topBar'=>$topBar,
                                'tuples'=>$tuples,
                                'pagination'=>$paginationSection,
                                'requestCounter'=>$requestCounter,
                                'countWithSort'=>$countWithSort,
				'totalCount'=>$totalTupleCount,
                                'newSeoTitle'=>$seoDetails['seoTitle'],
                                'h1TagString'=>$seoDetails['h1TagString'],
                                'desc'      => $seoDetails['seoDescription'],
                                'breadcrumb'=>$breadcrumb,
                                'canonical'  => $seoDetails['url']
                                )
                );
    }else{ // default page load
        $this->load->view('scholarshipCategoryPage/scholarshipCategoryPageHeader');
        $this->load->view('scholarshipCategoryPage/scholarshipCategoryPageContent');
        $this->load->view('scholarshipCategoryPage/scholarshipCategoryPageFooter');
        $this->load->view('scholarshipCategoryPage/sections/scholarshipCategoryPageTuplePlaceHolder');
    }
?>