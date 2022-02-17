<?php

function showBrowseLinks($categoryData,$location,$categoryPageRequest,$studyAbroad = FALSE)
{
    $browseIndiaURL = '/categoryList/Browse/browseInIndia';
    $output = '';
    
	foreach($categoryData as $categoryDataItem) {
		
        /*
         * Display link for main category
         */ 
		$category = $categoryDataItem['category'];
		$categoryId = $category->getId();
		$categoryName = $category->getName();
		
		$categoryPageRequest->setData(array('categoryId' => $categoryId));
        $URL = $studyAbroad ? $categoryPageRequest->getURL() : $browseIndiaURL.'/category/'.$categoryId;
        
        $output .= "<ul><li>";
        $output .= "<strong>".getBrowseLink($categoryName,$location,$URL)."</strong>";
        $output .= "<p>";
        
        /*
         * Display links for sub categories
         */
		$subCategories = $categoryDataItem['subcategories'];
        $subCategoryLinks = array();
		foreach($subCategories as $subCategory) {
			$categoryPageRequest->setData(array('subCategoryId' => $subCategory->getId()));
            $URL = $studyAbroad ? $categoryPageRequest->getURL() : $browseIndiaURL.'/subcategory/'.$subCategory->getId();
            $subCategoryLinks[] = getBrowseLink($subCategory->getName(),$location,$URL);
		}
        
        $output .= implode(', ',$subCategoryLinks);
        $output .= "</p>";

        /*
         * If national page, display links for LDB courses
         */ 
		if(!$studyAbroad) {

            $output .= "<br /><p>";
            
            $LDBCourses = $categoryDataItem['ldbcourses'];
            $LDBCourseLinks = array();
            foreach($LDBCourses as $LDBCourse) {    
                if($courseName = $LDBCourse->getDisplayName()) {
                    $LDBCourseLinks[] = getBrowseLink($courseName,'India',$browseIndiaURL.'/ldbcourse/'.$LDBCourse->getId());
                }
            }
            
            $output .= implode(', ',$LDBCourseLinks);
            $output .= "</p>";
		}
        
        $output .= "</li></ul>";
	}
    
    return $output;
}

function getBrowseLink($course,$location = 'India',$url)
{
    return "<a href='$url'>$course courses in $location</a>";
}