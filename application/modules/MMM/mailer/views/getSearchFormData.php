<?php
echo "<fieldset><legend>Search Form</legend>";
if (is_array($form_array) && (count($form_array) > 0 )) {
	foreach ($form_array as $key=>$value) {
		if($key == 'MultiSelect') {
			foreach ($value as $k=>$combo_details) {
				foreach ($combo_details as $key_combo=>$value_combo) {
					echo "<b>".$key_combo."</b>&nbsp;";
					$id = $k . "_combo_search";
					echo "&nbsp;<select multiple name=".$id."[] id=" . $id . ">";
					foreach ($value_combo as $make_option) {
						foreach($make_option as $option) {

							foreach($option as $opt_key=>$options) {
							if ($options == 'Delhi') {
//							$selected = 'selected';
							} else {
							$selected = '';
							}

							echo "<option value=" . $opt_key . " $selected >" .$options . "</option>" ;
							}
						}
					}
					echo "</select><br>";
				}
			}

		} elseif ($key == 'Bool') {
			foreach ($value as $k=>$combo_details) {
				foreach ($combo_details as $key_combo=>$value_combo) {
					echo "&nbsp;<b>".$key_combo."</b>&nbsp;";
					echo "&nbsp;<input  type=checkbox ";
					foreach ($value_combo as $make_option) {
						foreach($make_option as $option) {
							foreach($option as $opt_key=>$options) {
							echo "name =".$k."_checkbox_search[]   value=".$opt_key." id=" . $opt_key .">&nbsp;".$options ;
							}
						}
					}
					echo "<br>";
				}
			}
		} elseif ($key == 'Range') {
			foreach ($value as $k=>$combo_details) {
				foreach ($combo_details as $key_combo=>$value_combo) {
					echo "&nbsp;<b>".$key_combo."</b>&nbsp;";

					foreach ($value_combo as $make_option) {
						foreach($make_option as $option) {
							foreach($option as $opt_key=>$options) {
							echo "&nbsp;<input  type=text ";
							echo "name =".$k."_range_search[] id=" . $opt_key .">&nbsp;&nbsp;".$options ;
							}
						}
					}
					echo "<br>";
				}
			}

		} elseif ($key == 'Date') {
			foreach ($value as $k=>$combo_details) {
				foreach ($combo_details as $key_combo=>$value_combo) {
					echo "&nbsp;<b>".$key_combo."</b>&nbsp;";
					echo "&nbsp;<input  type=text ";
					foreach ($value_combo as $make_option) {
						foreach($make_option as $option) {
							foreach($option as $opt_key=>$options) {
							echo "name =".$k."_date_search[] id=" . $opt_key .">&nbsp;<img src=\"/public/images/eventIcon.gif\" style=\"cursor:pointer\" align=\"absmiddle\" id=\"ed\" onClick=\"calMain.select($('".$opt_key."'),'ed','yyyy-MM-dd','1980-01-01');\" />&nbsp;".$options ;
							}
						}
					}
					echo "<br>";
				}
			}
		}
	}
}
echo "</fieldset>";
?>
