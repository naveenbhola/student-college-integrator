<?php

class AbroadDemo extends MX_Controller{

	public function aman(){
		       $lib =  $this->load->library('listing/AbroadListingCommonLib');
		       _p($lib->getHighestRankOfListing(212638,'course'));die;

	}


	public function a(){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$abroadUniversityRepository 	= $listingBuilder->getUniversityRepository(false);	
		$universityData 			= $abroadUniversityRepository->findMultiple(array(2356));
		_p($universityData);		
	}

	public function b(){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$abroadUniversityRepository = $listingBuilder->getUniversityRepository(true);	
		$universityData 			= $abroadUniversityRepository->findMultiple(array(12));
		_p($universityData);die;
	}

	public function c(){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$abroadUniversityRepository = $listingBuilder->getCurrencyRepository();	
		$universityData 			= $abroadUniversityRepository->findCurrency(2);
		_p($universityData);die;

	}


	public function test($universityId){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder                = new ListingBuilder;
		$abroadUniversityRepositoryOld = $listingBuilder->getUniversityRepository(false);	
		$abroadUniversityRepositoryNew = $listingBuilder->getUniversityRepository(true);

		$universitiesIds = array($universityId);

		$universityObjOld 			= $abroadUniversityRepositoryOld->findMultiple($universitiesIds);	
		$universityObjNew 			= $abroadUniversityRepositoryNew->findMultiple($universitiesIds);	

		foreach ($universitiesIds as  $universityId) {
			$oldObj = $universityObjOld[$universityId];
			$newObj = $universityObjNew[$universityId];

			echo "<html>";
			echo "<body>";
			echo "<div class='test'>";
			echo "Value from Old Object(UniversityId): ".$oldObj->getId().'<br/>';
			echo "Value from New Object(UniversityId): ".$newObj->getId().'<br/>';

			echo "Value from Old Object(UniversityName): ".$oldObj->getName().'<br/>';
			echo "Value from New Object(UniversityName): ".$newObj->getName().'<br/>';

			echo "Value from Old Object(Acronym): ".$oldObj->getAcronym().'<br/>';
			echo "Value from New Object(Acronym): ".$newObj->getAcronym().'<br/>';

			echo "Value from Old Object(EstablishedYear): ".$oldObj->getEstablishedYear().'<br/>';
			echo "Value from New Object(EstablishedYear): ".$newObj->getEstablishedYear().'<br/>';

			echo "Value from Old Object(Logolink): ".$oldObj->getLogoLink().'<br/>';
			echo "Value from New Object(Logolink): ".$newObj->getLogoLink().'<br/>';

			echo "Value from Old Object(FundingType): ".$oldObj->getTypeOfInstitute().'<br/>';
			echo "Value from New Object(FundingType): ".$newObj->getTypeOfInstitute().'<br/>';

			echo "Value from Old Object(Type): ".$oldObj->getTypeOfInstitute2().'<br/>';
			echo "Value from New Object(Type): ".$newObj->getTypeOfInstitute2().'<br/>';

			echo "Value from Old Object(Affiliation): ".$oldObj->getAffiliation().'<br/>';
			echo "Value from New Object(Affiliation): ".$newObj->getAffiliation().'<br/>';

			echo "Value from Old Object(Accreditation): ".$oldObj->getAccreditation().'<br/>';
			echo "Value from New Object(Accreditation): ".$newObj->getAccreditation().'<br/>';

			echo "Value from Old Object(BrochureLink): ".$oldObj->getBrochureLink().'<br/>';
			echo "Value from New Object(BrochureLink): ".$newObj->getBrochureLink().'<br/>';

			// echo "Value from Old Object(PercentageProfile): ".$oldObj->getPercentageProfileCompletion().'<br/>';
			// echo "Value from New Object(PercentageProfile): ".$newObj->getPercentageProfileCompletion().'<br/>';

			// echo "Value from Old Object(UniversityName): ".$oldObj->getStatus().'<br/>';
			// echo "Value from New Object(UniversityName): ".$newObj->getStatus().'<br/>';

			echo "Value from Old Object(Location Object): <br/>";
			_p($oldObj->getLocation());
			echo "Value from New Object(Location Object): <br/>";
			_p($newObj->getLocation());

			echo "Value from Old Object(LocationId): ".$oldObj->getLocation()->getLocationId().'<br/>';
			echo "Value from New Object(LocationId): ".$newObj->getLocation()->getLocationId().'<br/>';


			echo "Value from Old Object(LocationAddress): ".$oldObj->getLocation()->getAddress().'<br/>';
			echo "Value from New Object(LocationAddress): ".$newObj->getLocation()->getAddress().'<br/>';

			echo "Value from Old Object(Location City Object): <br/>";
			_p($oldObj->getLocation()->getCity());
			echo "Value from New Object(Location City Object): <br/>";
			_p($newObj->getLocation()->getCity());

			echo "Value from Old Object(Location Country Object): <br/>";
			_p($oldObj->getLocation()->getCountry());
			echo "Value from New Object(Location Country Object): <br/>";
			_p($newObj->getLocation()->getCountry());

			echo "Value from Old Object(Location Region Object): <br/>";
			_p($oldObj->getLocation()->getRegion());
			echo "Value from New Object(Location Region Object): <br/>";
			_p($newObj->getLocation()->getRegion());

			echo "Value from Old Object(Location State Object): <br/>";
			_p($oldObj->getLocation()->getState());
			echo "Value from New Object(Location State Object): <br/>";
			_p($newObj->getLocation()->getState());


			echo "Value from Old Object(Locations State Object): <br/>";
			_p($oldObj->getLocations());
			echo "Value from New Object(Locations State Object): <br/>";
			_p($newObj->getLocations());

			echo "Value from Old Object(Admission Contact): <br/>";
			_p($oldObj->getAdmissionContact());
			echo "Value from New Object(Admission Contact): <br/>";
			_p($newObj->getAdmissionContact());

			echo "Value from Old Object(main header Image): <br/>";
			_p($oldObj->getMainHeaderImage());

			echo "Value from New Object(main header Image): <br/>";
			_p($newObj->getMainHeaderImage());


			echo "Value from Old Object(photos): <br/>";
			_p($oldObj->getPhotos());

			echo "Value from New Object(photos): <br/>";
			_p($newObj->getPhotos());


			echo "Value from Old Object(PhotoCount): ".$oldObj->getPhotoCount().'<br/>';
			echo "Value from New Object(PhotoCount): ".$newObj->getPhotoCount().'<br/>';

			echo "Value from Old Object(videos): <br/>";
			_p($oldObj->getVideos());

			echo "Value from New Object(videos): <br/>";
			_p($newObj->getVideos());


			echo "Value from Old Object(VideosCount): ".$oldObj->getVideoCount().'<br/>';
			echo "Value from New Object(VideosCount): ".$newObj->getVideoCount().'<br/>';			
			echo "Value from Old Object(Accommodation): <br/>";
			_p($oldObj->getCampusAccommodation());
			
			echo "Value from New Object(Accommodation): <br/>";
			_p($newObj->getCampusAccommodation());

			echo "Value from Old Object(hasAccommodation): ".$oldObj->hasCampusAccommodation().'<br/>';
			echo "Value from New Object(hasAccommodation): ".$newObj->hasCampusAccommodation().'<br/>';

			echo "Value from Old Object(campuses): <br/>";
			_p($oldObj->getCampuses());
			echo "Value from New Object(campuses): <br/>";
			_p($newObj->getCampuses());


			echo "Value from Old Object(contact details): <br/>";
			_p($oldObj->getContactDetails());
			echo "Value from New Object(contact details): <br/>";
			_p($newObj->getContactDetails());

			echo "Value from Old Object(FacebookPage): ".$oldObj->getFacebookPage().'<br/>';
			echo "Value from New Object(FacebookPage): ".$newObj->getFacebookPage().'<br/>';

			echo "Value from Old Object(WebsiteLink): ".$oldObj->getWebsiteLink().'<br/>';
			echo "Value from New Object(WebsiteLink): ".$newObj->getWebsiteLink().'<br/>';

			echo "Value from Old Object(IndianConsultant): ".$oldObj->getIndianConsultantsPageLink().'<br/>';
			echo "Value from New Object(IndianConsultant): ".$newObj->getIndianConsultantsPageLink().'<br/>';

			echo "Value from Old Object(InternationalStudents): ".$oldObj->getInternationalStudentsPageLink().'<br/>';
			echo "Value from New Object(InternationalStudents): ".$newObj->getInternationalStudentsPageLink().'<br/>';

			echo "Value from Old Object(WhyJoin): ".$oldObj->getWhyJoin().'<br/>';
			echo "Value from New Object(WhyJoin): ".$newObj->getWhyJoin().'<br/>';

			echo "Value from Old Object(getURL): ".$oldObj->getURL().'<br/>';
			echo "Value from New Object(getURL): ".$newObj->getURL().'<br/>';

			echo "Value from Old Object(Metadata): <br/>";
			_p($oldObj->getMetaData());
			echo "Value from New Object(Metadata): <br/>";
			_p($newObj->getMetaData());


			echo "Value from Old Object(isPublicalyFunded): ".$oldObj->isPublicalyFunded().'<br/>';
			echo "Value from New Object(isPublicalyFunded): ".$newObj->isPublicalyFunded().'<br/>';

			echo "Value from Old Object(getCumulativeViewCount): ".$oldObj->getCumulativeViewCount().'<br/>';
			echo "Value from New Object(getCumulativeViewCount): ".$newObj->getCumulativeViewCount().'<br/>';


			echo "Value from Old Object(Media): <br/>";
			_p($oldObj->getMedia());
			echo "Value from New Object(Media): <br/>";
			_p($newObj->getMedia());



			echo "Value from Old Object(getAnnouncement): ".$oldObj->getAnnouncement().'<br/>';
			echo "Value from New Object(getAnnouncement): ".$newObj->getAnnouncement().'<br/>';

			echo "</div>";
			echo "</body>";
			echo "</html>";

		}				
	}
}
