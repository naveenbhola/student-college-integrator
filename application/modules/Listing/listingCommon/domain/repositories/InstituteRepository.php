<?php

class InstituteRepository extends EntityRepository {
	protected $caching = false;
	function __construct($cache,$model) {
		parent::__construct(null,$cache,$model);
		$this->model = $model;
        $this->cache = $cache;
        $this->CI->load->entities(array('Institute'), 'nationalListing');
	}

	/*
	 * Find an institute using institute id
	 */
	function find($instituteId) {
		Contract::mustBeNumericValueGreaterThanZero($instituteId,'Institute ID');
		if($this->caching && $cachedInstitute = $this->cache->getInstitute($instituteId)) {
			return $cachedInstitute;
		}
	}

	/**
	 * [getInstituteAutosuggestor this function institute names and their ids based on input values]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2016-07-04
	 * @return [type]     [description]
	 */
	function getInstituteSuggestions($keyword, $limit = 5, $returnType = 'array') {
		$data = $this->model->getInstituteSuggestions($keyword, $limit);
		if($returnType == 'json') {
			$data = json_encode($data);
		}
		return $data;
	}

}