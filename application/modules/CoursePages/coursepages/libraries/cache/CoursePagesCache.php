<?php
class CoursePagesCache extends Cache
{
	private $caching = TRUE;
	private $AnAWidgetstimeToStore = 21600; // 6 Hours storage.
	private $tabs_hide_count_time_to_store = 86400; // 24 Hours
	private $CPGSproduct = "coursePages";
	private $slide_slot_duration = 1800;
	private $slide_slot_id_duration = 2100;
	private $time_to_store_faq = 1800;
    private $courseHomeDictionaryStoreTime=86400;
                	function __construct()
	{
		parent::__construct();
	}

	function isCPGSCachingOn() {
		return $this->caching;
	}

	function disableCPGSCaching() {
		$this->caching = FALSE;
	}
        
    public function getCourseHomePageDictionary() {
        return $this->get('courseHomeDictionary', 0);
   	}

    public function setCourseHomePageDictionary($data) {
        $this->store('courseHomeDictionary', 0, $data, $this->courseHomeDictionaryStoreTime, $this->CPGSproduct, 1);
    }

    public function deleteCourseHomePageDictionary(){
    	$this->delete('courseHomeDictionary',0);
    }

    /*
	 * Questions
	 */
	public function getQuestionsData($courseHomePageId)
	{
		$data = $this->get('topQuestionsCPGS', $courseHomePageId);
		return unserialize($data);
	}

	public function storeQuestionsData($courseHomePageId, $data)
	{
		$timeToStore = 21600; // 6 Hours storage.
		$obj = new stdClass();
		$obj->data = $data;
		$obj->count = count($data);
		$this->store('topQuestionsCPGS', $courseHomePageId, serialize($obj), $timeToStore, $this->CPGSproduct, 1);
	}

	/*
	 * Discussions
	 */
	public function getDiscussionsData($courseHomePageId)
	{
		$data = $this->get('topDiscussionsCPGS', $courseHomePageId);
		return unserialize($data);
	}

	public function storeDiscussionsData($courseHomePageId, $data)
	{
		$obj = new stdClass();
		$obj->data = $data;
		$obj->count = count($data);
		$this->store('topDiscussionsCPGS', $courseHomePageId, serialize($obj), $this->AnAWidgetstimeToStore, $this->CPGSproduct, 1);
	}

	/*
	 * Articles
	 */
	public function getArticlesData($courseHomePageId)
	{
		return $this->get('latestNewsCPGS', $courseHomePageId);
	}

	public function storeArticlesData($courseHomePageId, $data)
	{
		$this->store('latestNewsCPGS', $courseHomePageId, $data, $this->AnAWidgetstimeToStore, $this->CPGSproduct, 1);
	}

	/*
	 * Widget List for Subcategory
	 */
	public function getWidgetListForCoursePage($courseHomePageId)
	{
		return unserialize($this->get('widgetListCPGS', $courseHomePageId));
	}

	public function storeWidgetListForCoursePage($courseHomePageId, $data)
	{
		$this->store('widgetListCPGS', $courseHomePageId, serialize($data), $this->AnAWidgetstimeToStore, $this->CPGSproduct, 1);
	}

	public function deleteWidetList($courseHomePageId) {
		$this->delete('widgetListCPGS',$courseHomePageId);
	}
	/*
	 * Questions count
	 */
	public function getQuestionsCount()
	{
		return $this->get('questionsCountCategorywise', 1);
	}

	public function storeQuestionsCount($count_array)
	{
		$this->store('questionsCountCategorywise', 1, $count_array, $this->tabs_hide_count_time_to_store, $this->CPGSproduct, 1);
	}

	/*
	 * Discussions count
	 */
	public function getDiscussionsCount()
	{
		return $this->get('discussionsCountCategorywise', 1);
	}

	public function storeDiscussionsCount($count_array)
	{
		$this->store('discussionsCountCategorywise', 1, $count_array, $this->tabs_hide_count_time_to_store, $this->CPGSproduct, 1);
	}


	/*
	 * Articles count by category
	 */
	public function getArticlesCount()
	{
		return $this->get('articlesCountCategorywise', 1);
	}

	public function storeArticlesCount($count_array)
	{
		$this->store('articlesCountCategorywise', 1, $count_array, $this->tabs_hide_count_time_to_store, $this->CPGSproduct, 1);
	}

	public function getFaqWidgetData($courseHomePageId) {
		return $this->get('faqWidgetDataCPGS', $courseHomePageId);
	}

	public function storeFaqWidgetData($courseHomePageId,$faq_data) {
		$this->store('faqWidgetDataCPGS', $courseHomePageId, $faq_data, $this->time_to_store_faq, $this->CPGSproduct, 1);
	}

	public function deleteFaqWidgetData($courseHomePageId) {
		$this->delete('faqWidgetDataCPGS',$courseHomePageId);
	}
	
	public function getSlideSlotInfo($courseHomePageId) {
		return $this->get('slideSlotCPGS', $courseHomePageId);
	}
	
	public function setSLideSlotInfo($courseHomePageId) {
		$this->store('slideSlotCPGS', $courseHomePageId, $courseHomePageId, $this->slide_slot_duration, $this->CPGSproduct, 1);
	}
	
	public function getSlideSlotId($courseHomePageId) {
		return $this->get('slideSlotIdCPGS', $courseHomePageId);
	}
	
	public function setSlideSlotId($courseHomePageId,$data) {
		$this->store('slideSlotIdCPGS', $courseHomePageId, $data, $this->slide_slot_id_duration, $this->CPGSproduct, 1);
	}
	
	public function deleteSlideInfo($courseHomePageId) {
		$this->delete('slideSlotCPGS',$courseHomePageId);
	}
	
	public function deleteSlideSlotId($courseHomePageId) {
		$this->delete('slideSlotIdCPGS',$courseHomePageId);
	}
	
	public function deleteQuestionsData($courseHomePageId){
		return $this->delete('topQuestionsCPGS', $courseHomePageId);
	}
	
	public function deleteDiscussionsData($courseHomePageId){
		return $this->delete('topDiscussionsCPGS', $courseHomePageId);
	}
 	
	public function deleteArticlesData($courseHomePageId){
		return $this->delete('latestNewsCPGS', $courseHomePageId);
	}
}
