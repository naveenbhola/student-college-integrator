<?php
$this->load->view('Tagging/includes/header', array('title' => 'Tags Pending Actions', 'page' => 'tagpendingactions', 'tagpendingactions' => 'active'));
?>
<style type="text/css">
.tagTable {
    border-collapse: collapse;
    margin-left: 40px;
    width: 90%;
}
.tagTable td,.tagTable th {
    padding: 5px;
}
.already-exist{color:red;}
.align-center{text-align: center;}
</style>
<div class='main-wrapper' style="background:white;">
<br/><br/>
                        <table class="tagTable" border="1">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Entity Type( Entity ID)</th>
                                <th width="50%">Entity Name</th>
				<th width="10%">Action on Entity</th>
                                <th width="35%">Action</th>
                                <th width="10%">Discard</th>
                            </tr>
                            <?php 
                                foreach ($entities as $key=>$pendingTagRow) {

                                    $entityName = $entityData[$pendingTagRow['entityType']][$pendingTagRow['entityId']]['name'];
                                    $entityStatus = $entityData[$pendingTagRow['entityType']][$pendingTagRow['entityId']]['status'];
                                    $alreadyExistsText = "";
                                    if(array_key_exists(strtolower($entityName), $tagsAlreadyExistsMapping)){
                                        $alreadyExistsText = " <span class='already-exist'> (Tag with same name already exists with id = ".$tagsAlreadyExistsMapping[strtolower($entityName)].")</span>";
                                    }
                            ?>
                            <tr>
                                <td><?php echo $key+1; ?>
                                <td><?php echo $pendingTagRow['entityType']."(".$pendingTagRow['entityId'].")";?></td>
                                <td><?php echo "(<b>DB ROW = ".$pendingTagRow['id']."</b> ) ".$entityName.$alreadyExistsText;?></td>                            
				<td><?=$pendingTagRow['action']?></td>
                                <td class="align-center">
                                <?php 
                                    if(strtolower($entityStatus) == "draft"){
                                        echo "<span class='already-exist'>IN DRAFT MODE</span>";
                                    }
                                    else if($pendingTagRow['action'] == 'Add'){
                                ?>
                                <a href='javascript:void(0);' onclick='redirectToForm(<?php echo $pendingTagRow['id']; ?>);'>Add New Tag</a>
                                <form id="form_<?php echo $pendingTagRow['id'];?>" action="/Tagging/TaggingCMS/showAddTagsForm" method="post" style="display:none;">
                                    <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                    <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                    <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                </form>
                                <?php if($alreadyExistsText != ""){
                                    ?>
                                     / <a href='javascript:void(0);' onclick="redirectToForm('<?php echo $pendingTagRow['id']."_1"; ?>');">Edit Mapping</a>
                                    <form id="form_<?php echo $pendingTagRow['id']."_1";?>" action="/Tagging/TaggingCMS/showEditTagsForm" method="post" style="display:none;">
                                        <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                        <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                        <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                        <input type="hidden" name="mappedTagName" value="<?php echo $entityName;?>" />
                                        <input type="hidden" name="mappedTagId" value="<?php echo $tagsAlreadyExistsMapping[strtolower($entityName)];?>" />

                                        <input type="hidden" name="action" value="edit_mapping" />
                                    </form>
                                    <?php
                                }
                                ?>
                                <?php
                                    }
                                    else if($pendingTagRow['action'] == 'Delete'){
                                        if($shikshaEntityTagDataMapping[$pendingTagRow['entityType']][$pendingTagRow['entityId']]){
                                            $tagId = $shikshaEntityTagDataMapping[$pendingTagRow['entityType']][$pendingTagRow['entityId']]['tag_id']; 

                                            if(isset($tagsMapping[$tagId]) && count($tagsMapping[$tagId]) > 1){
                                                echo "<span class='already-exist'>Mapped to Multiple Case.</span>";
                                            }
                                    ?>

                                    <a href='javascript:void(0);' onclick='redirectToForm(<?php echo $pendingTagRow['id']; ?>);'>Delete</a>
                                    
                                <form id="form_<?php echo $pendingTagRow['id'];?>" action="/Tagging/TaggingCMS/showDeleteTagsForm" method="post" style="display:none;">
                                    <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                    <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                    <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                </form>

                                    <?php
                                        $tagId = $shikshaEntityTagDataMapping[$pendingTagRow['entityType']][$pendingTagRow['entityId']]['tag_id']; 
                                        if(isset($tagId) && $tagId > 0){
                                            ?>
    <a href='javascript:void(0);' onclick="deleteMapping(this,<?php echo $tagId;?>,<?php echo $pendingTagRow['entityId'];?>, <?php echo "'".$pendingTagRow['entityType']."'";?>,<?php echo  $pendingTagRow['id'];?>)"> / Delete Mapping</a>            
                                            <?php
                                        }
                                    ?>

                                    <?php
                                }
                                else{
                                    echo "<span class='already-exist'>No mapping of this entity exists with any tag. Hence, you can't delete it.</span>";
                                }
                                    }
                                    else if($pendingTagRow['action'] == 'Edit'){
                                        if($shikshaEntityTagDataMapping[$pendingTagRow['entityType']][$pendingTagRow['entityId']]){
                                ?>
                                <a href='javascript:void(0);' onclick='redirectToForm(<?php echo $pendingTagRow['id']; ?>);'>Edit</a>
                                <form id="form_<?php echo $pendingTagRow['id'];?>" action="/Tagging/TaggingCMS/showEditTagsForm" method="post" style="display:none;">
                                    <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                    <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                    <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                    <input type="hidden" name="action" value="rename_tag" />
                                    <input type="hidden" name="additionalParams" value="<?php echo base64_encode($pendingTagRow['additionsParams']);?>" />
                                </form>
                                <?php
                                }
                                else{

                                    ?>
                               
                                <a href='javascript:void(0);' onclick='redirectToForm(<?php echo $pendingTagRow['id']; ?>);'>Add New Tag</a>
                                <form id="form_<?php echo $pendingTagRow['id'];?>" action="/Tagging/TaggingCMS/showAddTagsForm" method="post" style="display:none;">
                                    <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                    <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                    <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                </form>
                               
                                <?php if($alreadyExistsText != ""){
                                    ?>
                                     / <a href='javascript:void(0);' onclick="redirectToForm('<?php echo $pendingTagRow['id']."_1"; ?>');">Edit Mapping</a>
                                    <form id="form_<?php echo $pendingTagRow['id']."_1";?>" action="/Tagging/TaggingCMS/showEditTagsForm" method="post" style="display:none;">
                                        <input type="hidden" name="pendingMappingTableId" value="<?php echo $pendingTagRow['id'];?>" />
                                        <input type="hidden" name="shikshaEntity" value="<?php echo $pendingTagRow['entityType'];?>" />
                                        <input type="hidden" name="shikshaEntityId" value="<?php echo $pendingTagRow['entityId'];?>" />
                                        <input type="hidden" name="mappedTagName" value="<?php echo $entityName;?>" />
                                        <input type="hidden" name="mappedTagId" value="<?php echo $tagsAlreadyExistsMapping[strtolower($entityName)];?>" />

                                        <input type="hidden" name="action" value="edit_mapping" />
                                    </form>
                                    <?php
                                }
                                    echo "<br /><span class='already-exist'>No mapping of this entity exists with any tag.</span>";   
                                }
                            }
                                ?>
                                </td>
                                <td class="align-center"><a href="javascript:void(0);" onclick="discardPendingTagAction(<?php echo $pendingTagRow['id']; ?>);">Discard</a></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </table>
                    </div>
<?php $this->load->view('Tagging/includes/footer',array());?>
