<?php

class Institute
{
	private $institute_id;
	private $establish_year;
	private $logo_link;
	private $institute_name;
	private $abbreviation;
	private $institute_type;
	private $aima_rating;
	private $usp;

	private $photo_count;
	private $video_count;
	private $alumni_rating;
	private $ratings_json;
	private $listing_seo_url;
	private $listing_seo_title;
	private $listing_seo_description;
	private $listing_seo_keywords;
	private $cumulativeViewCount;
	private $pack_type;

	private $viewCount;

	private $sticky = FALSE;
	private $main = FALSE;

	private $header_images;
	private $locations;
	private $courses;
	private $ranking;
	private $media;
	private $institute_description_attributes;
	private $joinReason;
	private $instituteViewCount;
    private $last_modify_date;
    /*Newly added for search*/
    private $wiki_content;
    private $institute_request_brochure_link;
    //adding institute facilities
    private $institute_facilities;
    
    
	function __construct()
	{

	}

	public function addInstituteFacility(InstituteFacility $facility){
		$this->institute_facilities[] = $facility;
	}

	public function getInstituteFacilities(){
		return $this->institute_facilities;
	}

	public function getInstituteFacilityById($id){
		foreach($institute_facilities as $index => $obj){
			if($obj->getFacilityId() == $id){
				return $obj;
			}
		}
		return false;
	}
	
	public function getRequestBrochure() {
		return $this->institute_request_brochure_link;
	}
	
	public function getLastUpdatedDate(){
		return $this->last_modify_date;
	}
	
	public function addViewCount(ListingViewCount $count) {
		$this->instituteViewCount = $count;
	}
	
	public function getAbbreviation(){
		return $this->abbreviation;
	}
	
	public function setDescriptionAttribute(InstituteDescriptionAttribute $attribute) {
		
		$this->institute_description_attributes[] = $attribute;
	}
	
	public function setJoinReason(InstituteJoinReason $join_reson) {
		
		$this->joinReason = $join_reson;
	}
	
	public function addLocation(InstituteLocation $location)
	{
		$this->locations[$location->getLocationId()] = $location;
	}

	public function addHeaderImage(HeaderImage $header_image)
	{
		$this->header_images[] = $header_image;
	}

	public function addCourse(Course $course)
	{
		$this->courses[] = $course;
	}

	public function addMedia(ListingMedia $media)
	{
		$this->media[] = $media;
	}

	public function setCourses($courses)
	{
		$this->courses = $courses;
	}
	
	public function setAdditionalURLParams($additionalURLParams)
	{
		$this->additionalURLParams = $additionalURLParams;
	}

	public function getURL()
	{
		if($this->listing_seo_url)
		{
			return $this->listing_seo_url.$this->additionalURLParams;
		}
		else
		{
			$locationArray = array();
			$locationArray[0] = $this->getMainLocation()->getCity()->getName()."-".$this->getMainLocation()->getCountry()->getName();

			$optionalArgs = array();
			$optionalArgs['location'] = $locationArray;
			$optionalArgs['institute'] = $this->institute_name;
			return getSeoUrl($this->institute_id,'institute',$this->institute_name,$optionalArgs,'old').$this->additionalURLParams;
		}
	}
	
	public function getMetaData(){
		return array(
					 'seoTitle' => $this->listing_seo_title,
					 'seoKeywords' => $this->listing_seo_keywords,
					 'seoDescription' => $this->listing_seo_description
					 );
	}
	
	public function getInstituteType(){
		return $this->institute_type;
	}

	public function isPaid()
	{
		return ($this->pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID);
	}

	public function isApplyable()
	{
		return ($this->pack_type > 0 && $this->pack_type != 7);
	}

	public function setSticky()
	{
		$this->sticky = TRUE;
	}
	public function isSticky()
	{
		return $this->sticky;
	}
	public function setMain()
	{
		$this->main = TRUE;
	}
	public function isMain()
	{
		return $this->main;
	}

	public function getCourses()
	{
		if(!is_array($this->courses) || count($this->courses) == 0) {
			return FALSE;
		}

		/*
		 * Sort by course order
		 */
		$tempCourses = array();
		foreach($this->courses as $course){
			if(is_object($course) && get_class($course) == 'Course') {
				$id = $course->getId();
				if(!empty($id)){
					$tempCourses[] = $course;
				}
			}
		}
		$this->courses = $tempCourses;
		$courses = $this->courses;
		usort($courses,array('Institute','sortCoursesByCourseOrder'));
		return $courses;
	}
	
	/*
	public function getCourses()
	{
		if(!is_array($this->courses) || count($this->courses) == 0) {
			return FALSE;
		}

		
		// Sort by course order
		
		$courses = $this->courses;
		usort($courses,array('Institute','sortCoursesByCourseOrder'));
		return $courses;
	}
	*/

	public function getFlagshipCourse()
	{
		$courses = $this->getCourses();
		return $courses[0];
	}

	public static function sortCoursesByCourseOrder($course1,$course2)
	{
		if(!empty($course1) && !empty($course2)){
			return intval($course1->getOrder()) - intval($course2->getOrder());
		} else if(!empty($course1) && empty($course2)){
			return intval($course1->getOrder());
		} else if(empty($course1) && !empty($course2)){
			return intval($course2->getOrder());
		} else {
			return -1;
		}
	}

	public function setRanking(Ranking $ranking)
	{
		$this->ranking = $ranking;
	}

	public function getRanking()
	{
		return $this->ranking;
	}

	public function getPhotos()
	{
		return $this->_getMedia('photo');
	}

	public function getVideos()
	{
		return $this->_getMedia('video');
	}

	private function _getMedia($type)
	{
		$mediaList = array();
		foreach($this->media as $media) {
			if($media->getType() == $type) {
				$mediaList[] = $media;
			}
		}
		return $mediaList;
	}


	/*
	 * Getters
	 */
	public function getId()
	{
		return $this->institute_id;
	}
	
	public function getName()
	{
		return $this->institute_name;
	}
	
	public function getMainLocation()
	{
		foreach($this->locations as $location) {
			if($location->isFlagshipCourseLocation()) {
				$flagship_location = $location;
				return $flagship_location;
			}
		}
		return reset($this->locations);
		
	}
	
	public function getLocations()
	{
		return $this->locations;
	}
	
	public function getMainHeaderImage()
	{
		return $this->header_images[0];
	}
	
	public function getHeaderImages()
	{
		return $this->header_images;
	}
	
	public function getAlumniRating()
	{
		return $this->alumni_rating;
	}
        
	public function getRatingsJson()
	{
		return json_decode($this->ratings_json,true);
	}
	
	public function getAIMARating()
	{
		return $this->aima_rating;
	}
	
	public function getViewCount()
	{
		return $this->viewCount;
	}
	
	public function getUsp()
	{
		return $this->usp;
	}
	
	public function getPhotoCount()
	{
		return $this->photo_count;
	}
	
	public function getVideoCount()
	{
		return $this->video_count;
	}
	
	public function getJoinReason() {
		return $this->joinReason;
	}
	
	public function getDescriptionAttributes() {
		return $this->institute_description_attributes;
	}
	
	public function getEstablishedYear() {
		return $this->establish_year;
	}
	
	public function getLogo() {
		return $this->logo_link;
	}
	
	public function getCumulativeViewCount() {
		
		return $this->instituteViewCount;
	}
    
    /*-- newly added --*/
    public function getPackType(){
        return $this->pack_type;
    }
    
    public function getInstituteDisplayLogo(){
        return $this->getMainHeaderImage()->getThumbURL();
    }
    
    public function getWikiContent(){
        return $this->wiki_content;
    }
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
        
        function getCourse($courseId) {
            if(!is_array($this->courses) || count($this->courses) == 0) {
                    return FALSE;
            }
            $tempCourses = array();
            foreach($this->courses as $course){
                    if(is_object($course) && get_class($course) == 'Course') {
                            $id = $course->getId();
                            if(!empty($id) && $id == $courseId){
                                return $course;
                            }
                    }
            }
            return $tempCourses;
        }

        function getAllCourseIds() {
        	if(!is_array($this->courses) || count($this->courses) == 0) {
                    return FALSE;
            }
            $courseId = array();
            foreach($this->courses as $course){
                    if(is_object($course) && get_class($course) == 'Course') {
                            $courseId[] = $course->getId();
                    }
            }
            return $courseId;
        }
}
