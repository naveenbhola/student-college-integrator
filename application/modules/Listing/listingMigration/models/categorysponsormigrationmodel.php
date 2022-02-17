<?php

/**
 * Class categorysponsormigrationmodel
 *
 * This class provides model service for the purpose of Category Sponsor Migration
 *
 */
class categorysponsormigrationmodel extends MY_Model {

	private static $oldSubscriptionTable = 'tlistingsubscription';
	private static $oldCategoryBanners = 'tbannerlinks';
	private static $baseEntityMapping = 'base_entity_mapping';
	private static $categoryPageSeo = 'category_page_seo';
	private static $categorySubscriptionCriteria = 'category_subscription_criteria';
	private static $categoryProducts = 'category_products';
	private static $categoryBanners = 'category_banners';

    private static $oldMainInstitutes = 'PageCollegeDb';
    private static $oldCriteria = 'tPageKeyCriteriaMapping';


	/**
     *
     *
	 * @param string $mode
	 */
	private function initiateModel($mode = "write") {
		if($this->dbHandle && $mode == 'write') {
		    return;
		}
		
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

	/**
	 * @param int        $subcategoryId
	 * @param bool|false $connection
	 *
	 * @return bool
	 */
	public function findSubcategoryMapping($subcategoryId=0){
        return;
        /*
		$selectFields = array(
			'stream_id',
			'substream_id',
			'base_course_id',
			'credential',
			'education_type',
			'delivery_method',
		);
		$whereClause =array(
			'oldsubcategory_id' => $subcategoryId,
			'oldspecializationid' => 0,
//			'oldcategory_id' => 0
		);

		return $this->getData(categorysponsormigrationmodel::$baseEntityMapping, $selectFields, $whereClause, 1);
        */
	}

    public function findCategorySubcategoryMapping($categoryId=0, $subcategoryId=0){
        return;
        /*
		$selectFields = array(
			'stream_id',
			'substream_id',
			'base_course_id',
			'credential',
			'education_type',
			'delivery_method',
		);
		$whereClause =array(
			'oldcategory_id' => $categoryId,
			'oldsubcategory_id' => $subcategoryId,
			'oldspecializationid' => 0
		);

		return $this->getData(categorysponsormigrationmodel::$baseEntityMapping, $selectFields, $whereClause, 1);
        */
	}

	/**
	 * @param array      $categoryDetails
	 * @param bool|false $connection
	 *
	 * @return bool
	 */
	public function categoryPageExistsCheck($categoryDetails = array(), $connection = false){
        return;
        /*
		$selectFields = array(
			'count(1) categoryPages',
		);
		$whereClause =array(
			'stream_id' => $categoryDetails->stream_id,
			'substream_id' => $categoryDetails->substream_id,
			'base_course_id' => $categoryDetails->base_course_id,
			'credential' => $categoryDetails->credential,
			'education_type' => $categoryDetails->education_type,
			'delivery_method' => $categoryDetails->delivery_method,
			'status' => 'live',
		);

		return $this->getData(categorysponsormigrationmodel::$categoryPageSeo, $selectFields, $whereClause, 1);
        */
	}

	/**
	 * @param array      $criterion
	 * @param bool|false $connection
	 *
	 * @return bool|int
	 */
	public function createCriterion($criterion=array()){
        return;
        /*
		try{

            $keys = array();
            $values = array();
			foreach($criterion as $key => $value){
				$keys[] = $key;
				$values[] = $value;
			}


			$insertQuery = "INSERT INTO ".categorysponsormigrationmodel::$categorySubscriptionCriteria. "(".implode(", ", $keys).") VALUES (".implode(", ", $values). ") ON DUPLICATE KEY UPDATE criterion_id = criterion_id;";

			$this->initiateModel('write');
			$this->dbHandle->query($insertQuery, true);
			return $this->dbHandle->insert_id() ? $this->dbHandle->insert_id() : 0;
		} catch(Exception $exception){
			error_log($exception->getMessage());
			return false;
		}
        */
	}

	/**
	 * @param array      $categoryDetails
	 * @param bool|false $connection
	 *
	 * @return bool
	 */
    public function findCriterionId($categoryDetails = array(), $connection = false){
        return;
        /*
        $selectFields = array('criterion_id');
        return $this->getData(categorysponsormigrationmodel::$categorySubscriptionCriteria, $selectFields, $categoryDetails, 1);
        */
    }

	/**
	 * @param string     $type
	 * @param bool|false $connection
	 *
	 * @return array|bool
	 */
    public function getActiveData($type='stickyListing')
    {
        return;
        /*
        $INDIA = 2;

        $selectFields = array();


        switch($type){
            case 'stickyListing':

                $whereClause = array(
                    'status' => 'live',
                    'countryid' => $INDIA
                );

                $data = $this->getData(categorysponsormigrationmodel::$oldSubscriptionTable, $selectFields, $whereClause, 0);


                if ($data) {
                    return array(
                        'data'     => $data,
                    );
                }
                return false;

            case 'banner':
                $whereClause = array(
                    'status' => 'live',
                    'countryid' => $INDIA
                );

				$data = $this->getData(categorysponsormigrationmodel::$oldCategoryBanners, $selectFields, $whereClause, 0);

                if ($data) {
                    return array(
                        'data'     => $data
                    );
                }
                return false;
                break;

            case 'mainListing':

                $oldCriteria = categorysponsormigrationmodel::$oldCriteria . " old_criterion";
                $oldMainInstitutes = categorysponsormigrationmodel::$oldMainInstitutes . " old_institute";

                $selectFields = array(
                    "old_criterion.stateId",
                    "old_criterion.cityId",
                    "old_criterion.categoryId",
                    "old_criterion.subCategoryId",
                    "old_institute.listing_type_id",
                    "old_institute.StartDate",
                    "old_institute.EndDate",
                    "old_institute.subscriptionId",
                    "old_institute.id",
                    "old_institute.status",
                    "old_institute.lastModificationDate",
                );
                $joinConditions = array(
                    "old_criterion.keyPageId = old_institute.KeyId",
                    "status = 'live'",
                    "countryid = 2",
                    "old_institute.listing_type = 'institute'"
                );

				$this->initiateModel('read');
                $this->dbHandle->select($selectFields);
				$this->dbHandle->from($oldCriteria);
				$this->dbHandle->join($oldMainInstitutes, implode(" AND ", $joinConditions), "inner");

                $data = $this->dbHandle->get()->result();
                if($data){
                    return array(
                        'data'     => $data,
                    );
                }
                return false;


            break;

            default:
                return false;
        }
        */
    }

	/**
	 * @param string     $type
	 * @param array      $details
	 * @param bool|false $connection
	 *
	 * @return bool|int
	 */
	public function saveDetails($type='product', $details = array(), $connection = false){
        return;
        /*
        switch($type){
            case 'product':
                return $this->setData(categorysponsormigrationmodel::$categoryProducts, $details);
            case 'banner':
                return $this->setData(categorysponsormigrationmodel::$categoryBanners, $details);

        }
        */
	}

	/**
	 * @param string     $tableName
	 * @param array      $data
	 *
	 * @return bool|int
	 */
	private function setData($tableName = '', $data = array()){
        return;
        /*
		try{
			$this->initiateModel('write');
			$this->dbHandle->insert($tableName, $data);

			return $this->dbHandle->insert_id() ? $this->dbHandle->insert_id() : 0;
		} catch(Exception $exception){
			error_log($exception->getMessage());
			return false;
		}
        */
	}

	/**
	 * @param string     $tableName
	 * @param array      $select
	 * @param array      $conditions
	 * @param int        $limit
	 *
	 * @return bool
	 */
	private function getData($tableName='', $select = array(), $conditions=array(), $limit = 0) {
        return;
        /*
		try{
			$this->initiateModel('read');
			$this->dbHandle->select($select);
			$this->dbHandle->from($tableName);
			$this->dbHandle->where($conditions);
			if($limit > 0){
				$this->dbHandle->limit($limit);
			}
			return $this->dbHandle->get()->result();
		}catch (Exception $exception){
			error_log($exception->getMessage());
			return false;
		}
        */
	}
}