<?php 

class Logger
{	
	private $_log;	

	function log($heading,$value,$format='')
	{
		$message = "<tr><th>$heading</td><td>";
		
		$formatted_message = '';
		
		if($format == 'array')
		{
			$formatted_message = implode(', ',$value); 
		}
		else if($format == 'seed_data')
		{
			$formatted_message = "<table>";
			
			if(isset($value['applied_courses']) && is_array($value['applied_courses']))
			{
				$formatted_message .= "<tr><th>Applied Courses</th><td>";	
			
				foreach($value['applied_courses'] as $a)
				{
					$formatted_message .= $a['course_id'].", ";
				}
			
				$formatted_message .= "</td></tr>";
			}
			
			
			if(isset($value['applied_institutes']) && is_array($value['applied_institutes']))
			{
				$formatted_message .= "<tr><th>Applied Institutes</th><td>";	
			
				foreach($value['applied_institutes'] as $a)
				{
					$formatted_message .= $a['institute_id'].", ";
				}
			
				$formatted_message .= "</td></tr>";
			}
			
			if(isset($value['clicked_institutes']) && is_array($value['clicked_institutes']))
			{
				$formatted_message .= "<tr><th>Clicked Institutes</th><td>";	
			
				foreach($value['clicked_institutes'] as $a)
				{
					$formatted_message .= $a['institute_id'].", ";
				}
			
				$formatted_message .= "</td></tr>";
			}
			
			if(isset($value['clicked_courses']) && is_array($value['clicked_courses']))
			{
				$formatted_message .= "<tr><th>Clicked Courses</th><td>";	
			
				foreach($value['clicked_courses'] as $a)
				{
					$formatted_message .= $a['course_id'].", ";
				}
			
				$formatted_message .= "</td></tr>";
			}
			
			$formatted_message .= "</table>";
		}
		else if($format == 'recommendations_object')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v->institute_id.", ".$v->course_id.(isset($v->view_count)?", ".$v->view_count:"")."<br />";
				}
			}
		}
		else if($format == 'recommendations_array')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v[0].", ".$v[1]."<br />";
				}
			}
		}
		else if($format == 'alsoviewed_seed_data')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v['institute_id'].", ".$v['country_id']."<br />";
				}
			}
		}
		else if($format == 'alsoviewed_listings')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v->also_viewed_id.", ".$v->also_viewed_listing_type.", ".$v->weight."<br />";
				}
			}
		}
		else if($format == 'alsoviewed_merged')
		{
			if(is_array($value))
			{
				foreach($value as $k=>$v)
				{
					$formatted_message .= $k.", ".$v."<br />";
				}
			}
		}
		else if($format == 'similarinstitutes_seed_data')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v['course_id'].", ".$v['city_id']."<br />";
				}
			}
		}
		else if($format == 'userinfo')
		{
			$user_info = $value;
			$formatted_message .= "<table border='1'>";
			$formatted_message .= "<tr><th>Location</th><td>
									Residence City: ".$user_info['residence_city'].", 
									Preferred Cities: ".print_r($user_info['preferred_cities'],true).", 
									Preferred Countries: ".print_r($user_info['preferred_countries'],true).",
									Preferred Localities: ".print_r($user_info['preferred_localities'],true)."
									</td></tr>";
			$formatted_message .= "<tr><th>Course Details</th><td>
									Desired Course: ".$user_info['desired_course'].", 
									Shiksha Courses: ".print_r($user_info['shiksha_courses'],true).",
									Shiksha Course Category: ".$user_info['shiksha_course_category'].", 
									Extra Flag: ".$user_info['extra_flag']."
									</td></tr>";
			$formatted_message .= "<tr><th>Degree Preferences</td><td>
									AICTE: ".$user_info['degree_pref_aicte'].",
									UGC: ".$user_info['degree_pref_ugc'].",  
									International: ".$user_info['degree_pref_internations'].",
									Mode: ".print_r($user_info['mode_of_learning'],true).",
									</th></tr>";
			$formatted_message .= "<tr><th>Exams</td><td>".print_r($user_info['exam'],true)."</th></tr>";
			$formatted_message .= "<tr><th>Registration</td><td>".$user_info['registration_source']."</th></tr>";
			$formatted_message .= "</table><br />";
		}
		else if($format == 'profilebased_results')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= $v['institute_id'].", [".implode(', ',$v['course_ids'])."]<br />";
				}
			}
		}
		else if($format == 'collaborative_results')
		{
			if(is_array($value))
			{
				foreach($value as $v)
				{
					$formatted_message .= "institute id: " . $v['institute_id'].", course id: " . $v['course_id'] . "<br />";
				}
			}
		}
		else if($format == 'key_value_array')
		{
			if(is_array($value))
			{
				foreach($value as $tempK => $tempV)
				{
					if(is_array($tempV))
					{
						$tempV = implode(', ',$tempV); 
					}
					$formatted_message .= $tempK . " -- " . $tempV .  "<br />";
				}
			}
		}
		else if($format == 'empty_value_array')
		{
			if(is_array($value) && !empty($value))
			{
				$formatted_message .= implode(', ',$value); 
			}
			else
			{
				$formatted_message .= "None<br/>";
			}
		}
		else
		{
			$formatted_message = $value;
		}
		
		$message .= $formatted_message."</td></tr>";
		$this->_write($message);
	}
	
	function addSeparatorRow($size='small')
	{
		$line_height = 2;
		$color = 'orange';
		
		if($size == 'medium')
		{
			$line_height = 2;
			$color = 'red';
		}
		
		$this->_write("<tr><td colspan='2' style='background:$color; padding:0; font-size:5px; line-height:".$line_height."px;'>&nbsp;</td></tr>");
	}
	
	private function _write($message)
	{		
		$this->_log .= $message;
	}
	
	function getLog()
	{
		return $this->_log;
	}
		
	function reset()
	{	
		$this->_log = '';
	}
		
	function logToFile($message,$separater=false)
	{
		if($fh = fopen(RECO_LOG_FILE,"a"))
		{
			if($separater == 1)
			{
				fwrite($fh,"================================================================\n");
			}
			else if($separater == 2)
			{
				fwrite($fh,"----------------------------------------------------------------\n");
			}
	        fwrite($fh,date("j M Y H:i:s").": ".$message."\n");
	        fclose($fh);
		}
	}
}
