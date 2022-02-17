<a href="JavaScript:void(0);" id="<?=$secName?>-bt" onclick="openGroupsLayer('<?=$secName;?>');" class="button button--orange" style="<?=$style;?>">Save & Publish to Multiple Groups</a>
<span id="<?=$secName?>-bt-txt" class="b-tn"></span>
<div id="<?=$secName;?>-gl" class="layer" style="display: none">
                        <a href="javascript:void(0);" class="layer-cls" onclick="hideLayer('<?=$secName;?>')">×</a>
                        <ul>
                                <li>
                                    <div class="Customcheckbox">
                                        <input type="checkbox" class="groupMultiSelect slctAll" id="<?=$secName;?>_gl">
                                        <label for="<?=$secName;?>_gl">All</label>
                                    </div>
                                </li>
                            <?php foreach($groupsLiveList  as $gKey => $gValue) { ?>
                                <li>
                                    <div class="Customcheckbox">
                                        <input type="checkbox" class="groupMultiSelect <?=$secName;?>_gl" id="<?=$secName;?>_gl_<?=$gKey;?>" value="<?=$gKey;?>">
                                        <label for="<?=$secName;?>_gl_<?=$gKey;?>"><?=$gValue;?></label>
                                    </div>
                                </li>
                            <?php } ?>
                            
                        </ul>

</div>
