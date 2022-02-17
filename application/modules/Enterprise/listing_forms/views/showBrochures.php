            <!--Start_Brochures-->

            <a name="documents"></a>
            <div style="margin-top:10px">
                <div class="contentBT" style="padding:0 0 0 10px;line-height:25px">
                <div class="float_R txt_align_r" style="width:50px;padding-right:10px">&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo $mediaUrldocs; ?>" class="fontSize_12p">Edit</a> ]</span></div>
                    <div class="fontSize_14p"><b>Brochures, Presentations &amp; Other Documents:</b></div>
                </div>
                <div class="lineSpace_7">&nbsp;</div>
                <div class="row">
                        <div class="mar_full_10p">
                        <ul class="row bWordIcon">
                        <?php for($i = 0; $i < count($data['value']); $i++) {
                                $file_ext = ShowFileExtension($data["value"][$i]["url"]);
                                if ($file_ext == 'pdf') {
                                    $icon = 'class ="pdf"';
                                } elseif($file_ext == 'txt') {
                                    $icon = 'class ="txt"';
                                }  elseif($file_ext == 'ppt') {
                                    $icon = 'class ="ppt"';
                                }  elseif($file_ext == 'ps') {
                                    $icon = 'class ="ps"';
                                }  elseif($file_ext == 'xls') {
                                    $icon = 'class ="xls"';
                                }  elseif($file_ext == 'doc') {
                                    $icon = 'class ="doc"';
                                }  else {
                                    $icon = 'class ="txt"';
                                }
                        ?>
                                <li <?php echo $icon; ?> id='main_doc_<?php echo $i;?>' >
                                    <div class="grayFont">Title: <a id='main_doc_title_name_<?php echo $i;?>'  href="/enterprise/ShowForms/downloadfile/<?php echo base64_encode($data["value"][$i]["url"]);?>/<?php echo $data["value"][$i]["name"]; ?>" title="<?php echo $data['value'][$i]['name']; ?>"><?php echo (strlen($data['value'][$i]['name']) > 30 ? substr($data['value'][$i]['name'],0,30) .'...'  : $data['value'][$i]['name'] ); ?></a></div>
                                        <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="javascript:void(0);" onclick="renameAction('main_doc_div_<?php echo $data['value'][$i]['media_id']; ?>','documents');"  >Rename</a> ] | [ <a href="javascript:void(0);" onclick="deleteFile('institute','<?php echo $institute_id; ?>','<?php echo $data['value'][$i]['media_id']; ?>','documents','<?php echo $i;?>','documents');" >Delete</a> ]</span>
                                        <div style="display:none;" id='error_doc_msg_<?php echo $i;?>'></div>
                                        <div style="display:none;" id="main_doc_div_<?php echo $data['value'][$i]['media_id']; ?>">
                                                Rename Document Title:<br />
                                                <input type="text" id="main_doc_title_<?php echo $data['value'][$i]['media_id']; ?>" style="width:105px" value="<?php echo (strpos($data['value'][$i]['name'], ".") > 1 ? substr($data['value'][$i]['name'], 0, strpos($data['value'][$i]['name'], ".")) : $data['value'][$i]['name']); ?>" maxlength="50" /><br />
                                                <div class="lineSpace_3">&nbsp;</div>
                                                <input type="button" onclick="EditImgMedia('institute','<?php echo $institute_id; ?>','<?php echo $data['value'][$i]['media_id']; ?>','documents','main_doc_title_<?php echo $data['value'][$i]['media_id']; ?>','<?php echo $i;?>','main_doc_div_<?php echo $data['value'][$i]['media_id']; ?>','<?php $docNameSplitArray = explode('.', $data['value'][$i]['name']); echo (count($docNameSplitArray) > 1 ? end($docNameSplitArray) : ''); ?>','documents');return false" value="" class="gUpdateBtn" />
                                                <input type="button" onclick="CancleImgMedia('<?php echo $i; ?>','main_doc_div_<?php echo $data['value'][$i]['media_id']; ?>','documents');return false" value="" class="gCancelBtn" />
                                        </div>
                                    </li>
                        <?php } ?>
                        </ul>
                        </div>
                        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
                </div>
            </div>
            <!--End_Brochures-->
