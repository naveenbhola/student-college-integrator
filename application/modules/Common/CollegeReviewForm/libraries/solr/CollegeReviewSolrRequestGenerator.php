<?php 
class CollegeReviewSolrRequestGenerator {

    function getAggregateReviewUrlForMultipleCourses($courseIds){
        if(empty($courseIds)){
            return;
        }
        $urlComponents = array();
        $urlComponents[] = 'q=*%3A*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = "rows=0";

        $urlComponents[] = "fq=reviewStatus:published";
        $urlComponents[] = "fq=courseId:(".urlencode(implode(" ",$courseIds)).")";

        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.range={!tag=r1}averageRating';
        $urlComponents[] = 'f.averageRating.facet.range.start=0';
        $urlComponents[] = 'f.averageRating.facet.range.end=5';
        $urlComponents[] = 'f.averageRating.facet.range.gap=1';
        $urlComponents[] = 'f.averageRating.facet.range.include=upper';

        $urlComponents[] = 'facet.range={!tag=r1}avgSalaryPlacementRating';
        $urlComponents[] = 'f.avgSalaryPlacementRating.facet.range.start=0';
        $urlComponents[] = 'f.avgSalaryPlacementRating.facet.range.end=5';
        $urlComponents[] = 'f.avgSalaryPlacementRating.facet.range.gap=1';
        $urlComponents[] = 'f.avgSalaryPlacementRating.facet.range.include=upper';

        $urlComponents[] = "facet.pivot={!stats=piv1 key=statspivot}courseId";
        $urlComponents[] = "facet.pivot={!range=r1 key=countpivot}courseId";

        $urlComponents[] = "stats=true";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}averageRating";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}facultyRating";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}campusFacilitiesRating";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}avgSalaryPlacementRating";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}crowdCampusRating";
        $urlComponents[] = "stats.field={!tag=piv1 count=true sum=true mean=true}moneyRating";

        $solrUrl = implode('&',$urlComponents);
        return $solrUrl;
    }

	function getAggregateReviewsUrl($solrRequestData) {
		$urlComponents = array();
        $urlComponents[] = 'q=*%3A*';
        $urlComponents[] = 'wt=phps';

        $urlComponents[] = "stats=true";
        $urlComponents[] = "fq=reviewStatus:published";

        //adding stats on following fields
        $urlComponents[] = "stats.field=averageRating";
        $urlComponents[] = "stats.field=facultyRating";
        $urlComponents[] = "stats.field=campusFacilitiesRating";
        $urlComponents[] = "stats.field=avgSalaryPlacementRating";
        $urlComponents[] = "stats.field=crowdCampusRating";
        $urlComponents[] = "stats.field=moneyRating";
        
        $urlComponents[] = "rows=0";

        //adding filters to get percent share
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.interval=averageRating';
        $urlComponents[] = 'f.averageRating.facet.interval.set=[1,2]';
        $urlComponents[] = 'f.averageRating.facet.interval.set=(2,3]';
        $urlComponents[] = 'f.averageRating.facet.interval.set=(3,4]';
        $urlComponents[] = 'f.averageRating.facet.interval.set=(4,5]';
        if(!empty($solrRequestData['courseId'])) {
        	//breaking course ids into chunk to counter solr maxboolean limit
        	if(count($solrRequestData['courseId']) > 500) {
        		$str = '';
        		$courseIdsChunk = array_chunk($solrRequestData['courseId'],500);
        		foreach ($courseIdsChunk as $value) {
        			$str .= '('.implode(' ', $value).') ';
        		}
        		$str = urlencode('courseId:('.$str.')');
        	}
        	else {
        		$str = urlencode('courseId:('.implode(' ', $solrRequestData['courseId']).')');
        	}
        	$urlComponents[] = 'fq='.$str;
        }
		else if(!empty($solrRequestData['instituteId'])) {
        	$str = urlencode('instituteId:('.implode(' ', $solrRequestData['instituteId']).')');
        	$urlComponents[] = 'fq='.$str;
        }
        else {
        	return '';
        }

        $solrUrl = implode('&',$urlComponents);
        return $solrUrl;
	}	

    function getCollegeReviewPlacementTagsUrl($solrRequestData){
       

        $urlComponents = array();
        $urlComponents[] = 'q=*%3A*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = "rows=0";
        //adding filters to get percent share
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.field=placements_topics';
        $urlComponents[]='facet.mincount=1';
        $urlComponents[]='facet.limit=-1';
        $urlComponents[]='facet.sort=true';
        
        
        
        if(!empty($solrRequestData['courseId'])) {
            $str = urlencode('courseId:('.$solrRequestData['courseId'].')');
            $urlComponents[] = 'fq='.$str;
        } else if(!empty($solrRequestData['instituteId'])) {
            $str = urlencode('instituteId:('.implode(' ', $solrRequestData['instituteId']).')');
            $urlComponents[] = 'fq='.$str;
        } else {
            return '';
        } 

        if(!empty($solrRequestData['averageRating'])){
            $ratingValue = $solrRequestData['averageRating'];
            $str = '';
            if($ratingValue > 5 || $ratingValue< 1){
                break;
            } else if($ratingValue == 5) {
                $str = urlencode('averageRating:['.($ratingValue-4).' TO '.$ratingValue.']');
            } else {
                $str = urlencode('averageRating:{'.$ratingValue.' TO '.($ratingValue+1).']');
            }
            $urlComponents[] = 'fq='.$str;
        }

        $solrUrl = implode('&',$urlComponents);
       
        return $solrUrl;
    }
}
