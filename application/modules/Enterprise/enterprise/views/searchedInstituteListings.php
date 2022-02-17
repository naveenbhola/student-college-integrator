<?php
                $location_contact_container_html = '<table cellpadding="6" cellspacing="0" border="0" width="100%" class="search-inst-table">';
                $Instcount = count($result_array);
                if($Instcount == 0)
                {
                    $location_contact_container_html .=  '<tr><td height="30">No Institute exists matching to <strong>'.$searchedKeyword.'</strong> keyword!</td></tr>';
                }
                else
                {
                    for($i = 0; $i < $Instcount; $i++) {
                       if($i == 0) {
                            $location_contact_container_html .=  '<tr><th>Institute Name</th></tr>';
                       }
                       $location_contact_container_html .=  '<tr><td><strong><a href="javascript: void(0);" onclick="showLocationContactDetails('.$result_array[$i]["id"].');">'.$result_array[$i]["title"].'</a></strong></td></tr>';
                    }
                }

                $location_contact_container_html .=  '</table>';
                echo $location_contact_container_html;