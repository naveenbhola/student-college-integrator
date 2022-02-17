<?php

class MailerCache extends Cache {

	private $caching = TRUE;
	private $time_to_store_collaborative_cache = 86400;
	private $mailer_product = "mailercache";

	function isCPGSCachingOn() {
		return $this->caching;
	}

	function disableCPGSCaching() {
		$this->caching = FALSE;
	}

	/*
	 * Store collaborative filter data
	*/
	public function getCollaborativeFilterUsers($app_id) {

		return $this->get('collaborative_filter_data', $app_id);
	}

	public function setCollaborativeFilterUsers($app_id, $data) {

		$this->store('collaborative_filter_data', $app_id, $data, $this->time_to_store_collaborative_cache, $this->mailer_product, 1);
	}

}