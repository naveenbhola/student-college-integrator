<?php

class Failurematrixmodel extends MY_Model
{
	function __construct()
	{
		parent::__construct('AppMonitor');
    }

	function save($data)
	{
		$db = $this->getWriteHandle();

		$id = intval($data['id']);
		
		$dbData = array(
				'host' => $data['host'],
				'service' => $data['service'],
				'failure_type' => $data['failureType'],
				'outage_type' => $data['outageType'],
				'impact_desc' => $data['impact_desc'],
				'failover_type' => $data['failoverType'],
				'failover_desc' => $data['failover_desc'],
				'estimated_time' => $data['estimated_time'],
				'post_recovery' => $data['post_recovery']
			);
		
		if($id) {
			$db->where('id', $id);
			$db->update('failure_matrix', $dbData);
			return $id;
		}
		else {
			$db->insert('failure_matrix', $dbData);
			return $db->insert_id();
		}
	}
	
	function get($id)
	{
		$db = $this->getWriteHandle();
		$sql = "SELECT * FROM failure_matrix WHERE id = ?";
		$query = $db->query($sql, array($id));
		return $query->row_array();
	}
	
	function fetch($filters)
	{
		$db = $this->getWriteHandle();
		$clauses = array();
		foreach($filters as $filterKey => $filterVal) {
			$clauses[] = $filterKey." = '".$filterVal."'";
		}
		
		$sql = "SELECT * FROM failure_matrix ".(count($clauses) > 0 ? " WHERE ".implode(' AND ', $clauses) : "");
		$query = $db->query($sql);
		return $query->result_array();
	}
}
