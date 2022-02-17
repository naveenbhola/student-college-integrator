<tbody>
<?		$i=1;
		$width = array('4%','28%','17%','5%','13%','10%','7%','7%','10%');
		$headingArr = array("#","Query","Calling Script", "Line No.", "URL", "Error Message", "Error Code", "Occurences", "Last Occurence Time");
		foreach($headingArr as $value)
		{
			echo "<th width='".$width[$i-1]."' style='word-break: break-all;background: none repeat scroll 0 0 #D7D6D6;border: 1px solid #898383;'>";
			echo $value;
			echo "</th>";
			$i++;
		}
		
		$i=1;
		if(empty($rs)){
			echo "<tr><td colspan='".count($width)."'>No Rows Found !!!</td></tr>";
		}
		foreach($rs as $key=>$value)
		{
			echo "<tr>";
			foreach($value as $key1=>$value1)
			{
				
				if($key1 == 'callingScript')
				{
						$html = "";
						$data = explode ("@@@",$value1);
		
						$html .= "<ol style='margin:0px;padding-left:20px;'>";
						foreach($data as $val)
						{
							$val = explode("$$", $val);
							$text = array();
							foreach($val as $val1)
							{
								$text[] = (trim($val1) == "") ? 'none' : $val1;
							}
							$text = implode(" :: ",$text);
							$html .= "<li title='".$text."'>";
							$html .= $text;
							$html .= "</li>";
						}
						$html .= "</ol>";
						$value1 = $html;
				 //$value1 = $this->getCallingScript($value1);
				}
				else if($key1 == 'id')
				{
					$value1 = $i++;
				}
				else if($key1 == 'OccurenceTime')
				{
					$value1 =  date("d-M-Y \n ( h:i:s )",strtotime($value1));
				}

				$style = "";
				if($key1 == 'Query' || $key1 == 'Occurences')
					$style = "font-weight:bold;background : #FBFBFB";
					
				echo "<td style='word-break: break-all;".$style ."' title=\"".html_escape($value1)."\">";
				echo (strlen(html_escape($value1)) > 150) ? substr(html_escape($value1),0,150)."..." : html_escape($value1);;
				
				if($key1 == 'Query' && trim($value1) != "")
					echo "<br/><a href='#' onclick='copyQuery(this)' class='copyQueryLink'>Copy</a>";
				
				echo "</td>";
			}
			echo "</tr>";
		}
?>
	</tbody>