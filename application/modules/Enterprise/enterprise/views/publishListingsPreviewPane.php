<?php
                $listingsCount = count($listings);
                $previewPaneHtml =  "";
                if($listingsCount > 0) {
                    switch($listingsCount) {
                        case 1:
                            $divHeight = "70px";
                            break;
                        case 2:
                            $divHeight = "100px";
                            break;
                        case 3: case 4:
                            $divHeight = "130px";
                            break;
                        default:
                            $divHeight = "150px";
                            break;
                    }

                    $previewPaneHtml .= '<div style="margin-left:10px;margin-top:15px;width:95%;"><div style="overflow:auto; height:'.$divHeight.';"><table width="99%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;background:url(/public/images/bgTable.gif) repeat-x left bottom;margin-left:1px" bordercolor="#cfcfcf">';
                    $previewPaneHtml .= '<tr><td height="25" width="70%" align="left" valign="middle" style="padding-left:10px;"><strong>Listing Title</strong> </td><td width="15%" align="left" valign="middle" style="padding-left:10px"><strong>Listing Type</strong> </td><td width="15%" align="left" valign="middle" style="padding-left:10px"><strong>Action</strong> </td></tr></table>';
                    $previewPaneHtml .= '<table width="99%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;margin-left:1px" bordercolor="#cfcfcf">';

                    for($i = 0; $i < $listingsCount; $i++) {
                        $currentListingArray[0]['type'] = $listings[$i]['type'];
                        $currentListingArray[0]['typeId'] = $listings[$i]['typeId'];
                        $currentListingArray[0]['title'] = $listings[$i]['title'];
                        $encodedListingData = base64_encode(serialize($currentListingArray));
                        $previewPaneHtml .= '<tr><td width="70%" valign="top" style="border-left:1px solid #cfcfcf">'.$listings[$i]['title'].'</td>';
                        $previewPaneHtml .= '<td width="15%" valign="top">'.$listings[$i]['type'].'</td>';
                        $previewPaneHtml .= '<td width="15%" valign="top"><input type="button" class="gray-button" id="bttnPublish" name="bttnPublish" value="Publish" onClick="publishListing(\''.$encodedListingData.'\')" /></td></tr>';
                    }

                    $encodedListingData = base64_encode(serialize($listings));
                    $previewPaneHtml .= '</table></div>';
                    $previewPaneHtml .= '<div style="margin:5px;"><input type="button" class="orange-button" id="bttnPublishAll" name="bttnPublishAll" value="Publish All" onClick="publishListing(\''.$encodedListingData.'\')" /></div>';
                    $previewPaneHtml .= '</table></div></div>';

                }   // End of if($listingsCount > 0).

                echo $previewPaneHtml;