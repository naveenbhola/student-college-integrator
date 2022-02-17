<?php

$disable ='';
$displayElement = 'block';
$displayTableRow = 'table-row';
if(!is_null($navbarId))
{
    $disable = 'disabled';
    $displayElement = 'none';
    $displayTableRow = 'none';
    $navbarLinksTupleCount = count($navbarDetailsForEdit['navbarLinks']);
}else{
    $navbarLinksTupleCount = 2;
}
?>
<div class="abroad-cms-rt-box">
    <div class="abroad-cms-head" style="margin:0;">
        <h2 class="abroad-sub-title">Content Navbar Links</h2>
    </div>
    <div class="contentTypeRadio">
        <p><input name="ContentType" type="radio" value="article" <?php echo $disable; ?> <?php if($navbarDetailsForEdit['content_type']=='article'){ echo 'checked="checked"'; } ?>/>Article</p>
        <p><input name="ContentType" type="radio" value="guide"   <?php echo $disable; ?> <?php if($navbarDetailsForEdit['content_type']=='guide'){ echo 'checked="checked"'; } ?>/>Guide</p>
    </div>
    <form action="javascript:void(0)" name="form_<?php echo $formName; ?>" method="post" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <div class="search-section">
                    <div class="cms-form-wrap exm-links">
                        <ul>
                            <li style="">
                                <div style="width:100%;">
                                    <label>Title</label>
                                    <div class="cms-fields">
                                        <input type="hidden" name="navbarId" id="navbarId" value="<?php echo $navbarDetailsForEdit['navbar_id']; ?>">
                                        <input type="hidden" name="createdOn" id="createdOn" value="<?php echo $navbarDetailsForEdit['created_on']; ?>">
                                        <input type="hidden" name="createdBy" id="createdBy" value="<?php echo $navbarDetailsForEdit['created_by']; ?>">
                                        <input id="navbarTitle_<?php echo $formName; ?>" name="navbarTitle" value="<?php echo isset($navbarDetailsForEdit['title'])?htmlentities($navbarDetailsForEdit['title']):'';?>" type="text" class="universal-txt-field cms-text-field flLt"  minlength="3" maxlength="35"  validationtype="str" caption="Navbar Title" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" required="true">
                                        <div style="display: none" class="errorMsg clearFix titl-err" id="navbarTitle_<?php echo $formName; ?>_error"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <table border="1" cellpadding="0" cellspacing="0" id="navLinkstable" class="exam-linkTbl" >
                    <tbody>
                    <tr>
                        <th width="12%">Rank</th>
                        <th width="27%" align="left">Content ID</th>
                        <th width="61%" align="left">Title</th>
                    </tr>
                    <?php
                    for ($i=0;$i<$navbarLinksTupleCount;$i++)
                    {
                        $classIcon = 'rank-search-icon';
                        $disableField = false;
                        $examApplyContentId = '';
                        // if(isset($linksCount) && ($i<=$linksCount))
                        // {
                            $contentId = $navbarDetailsForEdit['navbarLinks'][$i]['content_id'];
                            $contentTitle = $navbarDetailsForEdit['navbarLinks'][$i]['content_title'];
                            if(!is_null($contentId) && $contentId>0)
                            {
                                $classIcon = 'edit-icon';
                                $disableField = true;
                            }else{
                                $classIcon = 'rank-search-icon';
                                $disableField = false;
                            }
                        // }
                        ?>
                        <tr class="rows">
                            <td align="center">
                                <strong class="font-14"><?php echo ($i+1);?></strong>
                            </td>
                            <td>
                                <input type="text" class="universal-txt-field flLt wd-exmCntId contentIdInput" value="<?php echo $contentId;?>" id="contentIds_<?php echo $i.'_'.$formName;?>" onblur="showErrorMessage(this,'showEditCourseForm');" validationtype="numeric" caption="Content Id" minlength="1" maxlength="10" required="true" <?php echo ($disableField ||!empty($disable))?'disabled="disabled"':'';?>>
                                <a href="javascript:void(0);" class="edit-search-box" onclick="validateContentSelection(this);">
                                    <i class="abroad-cms-sprite <?php echo $classIcon;?>"></i>
                                </a>
                                <div style="display: none;" id="contentIds_<?php echo $i.'_'.$formName;?>_error" class="errorMsg clearFix"></div>
                            </td>
                            <td>
                                <input class="universal-txt-field cms-text-field flLt wd-title" value="<?php echo htmlentities($contentTitle);?>" name="contentTitle[]" id="contentTitle_<?php echo $i.'_'.$formName;?>" caption="navbar content title" validationtype="str" required="true" type="text" minlength="3" maxlength="20" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" <?php echo (!$disableField)?'disabled="disabled"':'';?>>
                                <div style="display: none;" id="contentTitle_<?php echo $i.'_'.$formName;?>_error" class="errorMsg clearFix"></div>
                                <?php
                                if($i>=ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT && $i==$navbarLinksTupleCount-1)
                                {
                                    $displayDelete = '';
                                }else{
                                    $displayDelete = 'display:none;';
                                }
                                    ?>
                                    <a onclick="deleteContentNavbarLinkRow('navLinkstable');" href="javascript:void(0);" style="color:#333; margin-right:14px;<?php echo $displayDelete; ?>" class="flRt">
                                        <i class="abroad-cms-sprite remove-small-icon"></i>Delete
                                    </a>
                                    <?php
                                ?>
                            </td>

                        </tr>
                        <?php
                    }
                    if(($navbarLinksTupleCount >= ENT_SA_EXAM_NAVBAR_MAX_TUPLE_COUNT)) {
                        $displayTableRow = 'none';
                    }else{
                        $displayTableRow = '';
                    }
                        ?>
                        <tr style="display: <?php echo $displayTableRow;?>">
                            <td colspan="3" bgcolor="#ecebeb">
                                <a onclick="addContentNavbarLinkRow('navLinkstable');" href="javascript:void(0);" style="margin-left:20px;">[+] Add another row</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="exam-linkBtn button-wrap" style="">
                    <input type="button" id="bttnSave" name="bttnSave" value="Save" onclick="submitContentNavbar(this, '<?php echo $formName; ?>','<?php echo ENT_SA_CMS_NAVBARS_PATH.ENT_SA_CONTENT_NAVBARS; ?>')" class="orange-btn">
                </div>
            </div>
            <div style="display:none" id="deleteButtonHTML">
                <a onclick="deleteContentNavbarLinkRow('navLinkstable');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt">
                    <i class="abroad-cms-sprite remove-small-icon"></i>Delete
                </a>
            </div>
        </div>
        <div class="clearFix"></div>
    </form>
    <input type="hidden" id ="minRowCount" value="<?php echo ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT;?>">
    <input type="hidden" id ="maxRowCount" value="<?php echo ENT_SA_EXAM_NAVBAR_MAX_TUPLE_COUNT;?>">
</div>
<style>
.exam-linkTbl .wd-exmCntId {
    width: 120px;
}
</style>
