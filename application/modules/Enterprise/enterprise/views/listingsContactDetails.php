<?php
                $instituteName = $result_array[0];
                $instituteLocationArray = $result_array[1];
                $courseNameArray = $result_array[2];
                $courseLocationArray = $result_array[3];
                $location_contact_container_html = '<form name="update_contact_form"><table cellpadding="6" cellspacing="0" border="0" width="100%" class="search-inst-table">';
                $Instcount = count($instituteLocationArray);
                if($Instcount == 0)
                {
                    $location_contact_container_html .=  '<tr><td height="30">No Institute exists matching to <strong>'.$searchedKeyword.'</strong> keyword!</td></tr>';
                }
                else
                {
                       $location_contact_container_html .=  '<tr><th>Institute Name</th><th>Name</th><th>Main Ph.</th><th>Mob Ph.</th><th>Email</th></tr>';

                       // Institute Name..
                       $location_contact_container_html .=  '<tr><td colspan="5"><strong>'.$instituteName.'</strong></td></tr>';

                       // Institute's Location..
                       $locCount = count($instituteLocationArray);
                       $location_contact_container_html .=  '<tr><td colspan="5"><strong class="left-vspace">Location</strong></td></tr>';
                       for($i = 0; $i < $locCount; $i++) {
                            if($instituteLocationArray[$i]["locality_name"] != "") {
                                $location = $instituteLocationArray[$i]["city_name"].' -- '.$instituteLocationArray[$i]["locality_name"];
                            } else {
                                $location = $instituteLocationArray[$i]["city_name"];
                            }

                            if($instituteLocationArray[$i]["contact_person"] == "" || $instituteLocationArray[$i]["contact_person"] == NULL) {
                                $contact_person = "--";
                            } else {
                                $contact_person = $instituteLocationArray[$i]["contact_person"];
                            }
                            if($instituteLocationArray[$i]["contact_main_phone"] == "" || $instituteLocationArray[$i]["contact_main_phone"] == NULL) {
                                $contact_main_phone = "--";
                            } else {
                                $contact_main_phone = $instituteLocationArray[$i]["contact_main_phone"];
                            }

                            if($instituteLocationArray[$i]["contact_cell"] == "" || $instituteLocationArray[$i]["contact_cell"] == NULL) {
                                $contact_cell = "--";
                            } else {
                                $contact_cell = $instituteLocationArray[$i]["contact_cell"];
                            }
                            if($instituteLocationArray[$i]["contact_email"] == "" || $instituteLocationArray[$i]["contact_email"] == NULL) {
                                $contact_email = "--";
                            } else {
                                $contact_email = $instituteLocationArray[$i]["contact_email"];
                            }

                            $location_contact_container_html .=  '<tr><td><div class="left-vspace"><input type="checkbox" name="contact_details_chkbox[]" id="contact_details_chkbox_institute_'.$instituteId.'" value="'.$instituteLocationArray[$i]["institute_location_id"].'" /> '.$location.'</div></td><td>'.$contact_person.'</td><td>'.$contact_main_phone.'</td><td>'.$contact_cell.'</td><td>'.$contact_email.'</td></tr>';
                       }

                       // Courses and thier locations..
                       $location_contact_container_html .=  '<tr><td colspan="5"><strong class="left-vspace">Courses</strong></td></tr>';

                       foreach($courseNameArray as $courseID => $courseName) {
                           $course_contact_html = ""; $course_row_done = 0;
                           $currentCourseLocArray = $courseLocationArray[$courseID];
                           $locCount = count($currentCourseLocArray);
                           for($i = 0; $i < $locCount; $i++) {
                                if($currentCourseLocArray[$i]["contact_person"] == "" || $currentCourseLocArray[$i]["contact_person"] == NULL) {
                                    $contact_person = "--";
                                } else {
                                    $contact_person = $currentCourseLocArray[$i]["contact_person"];
                                }
                                if($currentCourseLocArray[$i]["contact_main_phone"] == "" || $currentCourseLocArray[$i]["contact_main_phone"] == NULL) {
                                    $contact_main_phone = "--";
                                } else {
                                    $contact_main_phone = $currentCourseLocArray[$i]["contact_main_phone"];
                                }

                                if($currentCourseLocArray[$i]["contact_cell"] == "" || $currentCourseLocArray[$i]["contact_cell"] == NULL) {
                                    $contact_cell = "--";
                                } else {
                                    $contact_cell = $currentCourseLocArray[$i]["contact_cell"];
                                }
                                if($currentCourseLocArray[$i]["contact_email"] == "" || $currentCourseLocArray[$i]["contact_email"] == NULL) {
                                    $contact_email = "--";
                                } else {
                                    $contact_email = $currentCourseLocArray[$i]["contact_email"];
                                }


                               if($currentCourseLocArray[$i]["institute_location_id"] == 0) { # Course Name row with empty Global Contact details (if it is set)..
                                    $course_contact_html .=  '<tr><td><div class="left-vspace"><input type="checkbox" name="contact_details_chkbox[]" id="contact_details_chkbox_course_'.$courseID.'" value="'.$currentCourseLocArray[$i]["institute_location_id"].'" /> '.$courseName.'</div></td><td>'.$contact_person.'</td><td>'.$contact_main_phone.'</td><td>'.$contact_cell.'</td><td>'.$contact_email.'</td></tr>';
                                    $course_row_done = 1;
                               } else {
                                    if($currentCourseLocArray[$i]["locality_name"] != "") {
                                        $location = $currentCourseLocArray[$i]["city_name"].' -- '.$currentCourseLocArray[$i]["locality_name"];
                                    } else {
                                        $location = $currentCourseLocArray[$i]["city_name"];
                                    }
                                    $course_contact_html .=  '<tr><td><div class="loc-vspace"><input type="checkbox" name="contact_details_chkbox[]" id="contact_details_chkbox_course_'.$courseID.'" value="'.$currentCourseLocArray[$i]["institute_location_id"].'" /> '.$location.'</div></td><td>'.$contact_person.'</td><td>'.$contact_main_phone.'</td><td>'.$contact_cell.'</td><td>'.$contact_email.'</td></tr>';
                               }
                           }

                           if($course_row_done != 1) {  # Append the Course Name row with empty Global Contact details (If it is not set yet)..
                                $location_contact_container_html .= '<tr><td><div class="left-vspace"><input type="checkbox" name="contact_details_chkbox[]" id="contact_details_chkbox_course_'.$courseID.'" value="0" /> '.$courseName.'</div></td><td>--</td><td>--</td><td>--</td><td>--</td></tr>';
                           }

                           $location_contact_container_html .= $course_contact_html;

                       }

                }
                
                if($instituteLocationArray[0]["country_id"] == 2) {
                                $isAbroadListing = 0;
                } else {
                                $isAbroadListing = 1;
                }
                
                $location_contact_container_html .=  '</table><input type="hidden" name="isAbroadListing" id="isAbroadListing" value="'.$isAbroadListing.'"></form>';

                echo $location_contact_container_html;