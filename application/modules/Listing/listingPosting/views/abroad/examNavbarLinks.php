<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 31/7/18
 * Time: 12:25 PM
 */
$disable ='';
$displayElement = 'block';
$displayTableRow = 'table-row';
if(!$contentTypeId)
{
    $disable = 'disabled';
    $displayElement = 'none';
    $displayTableRow = 'none';
}
?>
<div class="abroad-cms-rt-box">
    <div class="abroad-cms-head" style="margin:0;">
        <h2 class="abroad-sub-title">Content Navbar Links</h2>
    </div>
    <div class="contentTypeRadio">
        <p><input name="ContentType" type="radio" value="exam_content" onchange="performActionOnContentTypeChange(this,'<?php echo ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS; ?>')" <?php if($contentType=='exam_content'){ echo 'checked="checked"'; } ?>/>Exam Content</p>
        <p><input name="ContentType" type="radio" value="apply_content" onchange="performActionOnContentTypeChange(this,'<?php echo ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS; ?>')" <?php if($contentType=='apply_content'){ echo 'checked="checked"'; } ?>/>Apply Content</p>
    </div>
    <form action="javascript:void(0)" name="form_<?php echo $formName; ?>" method="post" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <div class="search-section">
                    <div class="cms-form-wrap exm-links">
                        <ul>
                            <li style="">
                                <div>
                                    <label>Topic</label>
                                    <div class="cms-fields">
                                        <select class="universal-select cms-field" id="contentTypeId" name="examId" style="width:130px" onchange="performActionForExamApplyChange(this,'<?php echo ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS; ?>')">
                                            <option value="">SELECT</option>
                                            <?php
                                            if($contentType=='exam_content') {
                                                foreach ($contentTypeList as $examArr) {
                                                    $selected = '';
                                                    if ($contentTypeId == $examArr['examId']) {
                                                        $selected = 'SELECTED="SELECTED"';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $examArr['examId']; ?>" <?php echo $selected; ?>><?php echo $examArr['exam']; ?></option>
                                                    <?php
                                                }
                                            }
                                            elseif ($contentType=='apply_content'){
                                                foreach ($contentTypeList as $key=>$applyContentArr) {
                                                    $selected = '';
                                                    if ($contentTypeId == $key) {
                                                        $selected = 'SELECTED="SELECTED"';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $applyContentArr['type']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label>Title</label>
                                    <div class="cms-fields">
                                        <input id="examTitle_<?php echo $formName; ?>" name="examTitle" value="<?php echo isset($examTitle)?htmlentities($examTitle):'';?>" type="text" class="universal-txt-field cms-text-field flLt" <?php echo $disable;?>  minlength="3" maxlength="35"  validationtype="str" caption="Content Title" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" required="true">
                                        <div style="display: none" class="errorMsg titl-err" id="examTitle_<?php echo $formName; ?>_error"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <table border="1" cellpadding="0" cellspacing="0" id="examNavLinkstable" class="exam-linkTbl" >
                    <tbody>
                    <tr>
                        <th width="12%">Rank</th>
                        <th width="27%" align="left">Content ID</th>
                        <th width="61%" align="left">Title</th>
                    </tr>
                    <?php
                    for ($i=1;$i<=$examApplyContentTupleCount;$i++)
                    {
                        $classIcon = 'rank-search-icon';
                        $disableField = false;
                        $examApplyContentId = '';
                        if(isset($linksCount) && ($i<=$linksCount))
                        {
                            $examApplyContentId = key($linksData);
                            $examApplyContentTitle = current($linksData);
                            next($linksData);
                            $classIcon = 'edit-icon';
                            $disableField = true;
                        }
                        ?>
                        <tr>
                            <td align="center">
                                <strong class="font-14"><?php echo $i;?></strong>
                            </td>
                            <td>
                                <input type="text" class="universal-txt-field flLt wd-exmCntId" value="<?php echo $examApplyContentId;?>" id="examContentIds_<?php echo $i.'_'.$formName;?>" onblur="showErrorMessage(this,'showEditCourseForm');" validationtype="numeric" caption="Content Id" minlength="1" maxlength="10" required="true" <?php echo ($disableField ||!empty($disable))?'disabled="disabled"':'';?>>
                                <a href="javascript:void(0);" class="edit-search-box" onclick="validateExamApplyContentId(this);">
                                    <i class="abroad-cms-sprite <?php echo $classIcon;?>"></i>
                                </a>
                                <div style="display: none;" id="examContentIds_<?php echo $i.'_'.$formName;?>_error" class="errorMsg"></div>
                            </td>
                            <td>
                                <input class="universal-txt-field cms-text-field flLt wd-title" value="<?php echo htmlentities($examApplyContentTitle);?>" name="examContentTitle[]" id="examContentTitle_<?php echo $i.'_'.$formName;?>" caption="Title" validationtype="str" required="true" type="text" minlength="3" maxlength="20" onblur="showErrorMessage(this, '<?php echo $formName; ?>');" examcontentid="<?php echo $examApplyContentId;?>" <?php echo (!$disableField)?'disabled="disabled"':'';?>>
                                <div style="display: none;" id="examContentTitle_<?php echo $i.'_'.$formName;?>_error" class="errorMsg"></div>
                                <?php
                                if($i>ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT && $i==$examApplyContentTupleCount)
                                {
                                    ?>
                                    <a onclick="deleteTableRow('examNavLinkstable');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt">
                                        <i class="abroad-cms-sprite remove-small-icon"></i>Delete
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>

                        </tr>
                        <?php
                    }
                    if(($examApplyContentTupleCount >= ENT_SA_EXAM_NAVBAR_MAX_TUPLE_COUNT)) {
                        $displayTableRow = 'none';
                    }
                        ?>
                        <tr style="display: <?php echo $displayTableRow;?>">
                            <td colspan="3" bgcolor="#ecebeb">
                                <a onclick="addTableRow('examNavLinkstable');" href="javascript:void(0);" style="margin-left:20px;">[+] Add another row</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="exam-linkBtn button-wrap" style="display: <?php echo $displayElement;?>">
                    <input type="button" id="bttnSave" name="bttnSave" value="Save" onclick="validateExamLinkFormOnSave(this, '<?php echo $formName; ?>','<?php echo ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS; ?>')" class="orange-btn">
                </div>
            </div>
            <div style="display:none" id="deleteButtonHTML">
                <a onclick="deleteTableRow('examNavLinkstable');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt">
                    <i class="abroad-cms-sprite remove-small-icon"></i>Delete
                </a>
            </div>
        </div>
        <div class="clearFix"></div>
    </form>
    <input type="hidden" id ="minRowCount" value="<?php echo ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT;?>">
    <input type="hidden" id ="maxRowCount" value="<?php echo ENT_SA_EXAM_NAVBAR_MAX_TUPLE_COUNT;?>">
</div>
