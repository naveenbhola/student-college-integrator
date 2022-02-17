<?php
	class ShikshaApplyCache extends Cache
	{
		private $standardCacheTime = 86400; //24 hours
		
		function __construct()
		{
			parent::__construct();
		}
		
		/*
		 * function to get response types for shiksha apply courses by counsellor
		 */
		public function getResponseTypesForShikshaApplyCourses($counsellor) {
			$data = unserialize($this->get('ShikshaApplyResponseTypes', $counsellor));
			return $data;
		}
		/*
		 * function to get response types for shiksha apply courses by counsellor
		 */
		public function storeResponseTypesForShikshaApplyCourses($data, $counsellor) {
			if(!empty($data)) {
				$data = serialize($data);
				$this->store('ShikshaApplyResponseTypes', $counsellor, $data, $this->standardCacheTime, NULL, 1);
			}
		}
	}