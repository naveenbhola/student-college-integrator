<?php
	class ListingCommon {
		function getMyListings($appID,$categoryId, $username, $listingType, $startFrom,$countOffset){
	       
	        $ListingClientObj = new Listing_client();
			$filters = array();
	        $filters['listing_type'] = $listingType;
	        $filters['username'] = $username;
		$filters['start'] = $startFrom;
		$filters['saved'] = true;
	        $filters['number_of_results'] = $countOffset;

			error_log_shiksha(print_r($filters,true));
	        
	        
	        $response = $ListingClientObj->getListingsByFilters($appID,$filters);
	        error_log_shiksha(print_r($response,true));
	    /*    echo "<pre>";
	        print_r($response);
	        echo "</pre>";*/
			$listingsList = array(
							'results' => $this->createListingsList($response[0]['listingsArr']), 
							'totalCount'=> $response[0]['totalListings']
						);
	        error_log_shiksha(print_r($listingsList,true));
	        /*echo "<pre>";
	        print_r($listingsList);
	        echo "</pre>";*/
			return json_encode($listingsList);
	    }

	    function getMyInstituteListings($appID,$categoryId, $username,  $startFrom =0, $countOffset=15) {
	    error_log_shiksha("$appID,$categoryId,$username,$startFrom,$countOffset");
		    echo $this->getMyListings($appID,$categoryId, $username, 'institute', $startFrom, $countOffset);
		}

		function getMyCourseListings($appID,$categoryId, $username,  $startFrom =0, $countOffset=15) {
	    error_log_shiksha("$appID,$categoryId,$username,$startFrom,$countOffset");
		    echo $this->getMyListings($appID,$categoryId, $username, 'course', $startFrom, $countOffset);
		}
		

	    function getMyScholarshipListings($appID,$categoryId, $username,  $startFrom =0, $countOffset=15) {
	    error_log_shiksha("$appID,$categoryId,$username,$startFrom,$countOffset");
		    echo $this->getMyListings($appID,$categoryId, $username, 'scholarship', $startFrom, $countOffset);
		}
		
	    function createListingsList($ratings) {
	        $ratingsList = array();
	        $i = 0;
	        if(is_array($ratings)){
	            foreach($ratings as $rating) {
	                $ratingsList[$i]['Listing'] = $rating;
	                $ratingsList[$i]['Listing']['IUG'] = 100;
//                $ratingsList[$i]['Listing']['Views'] = 3;
	                $i++;
	            }
	        }
	        return 	$ratingsList;
	    }
	}
?>
