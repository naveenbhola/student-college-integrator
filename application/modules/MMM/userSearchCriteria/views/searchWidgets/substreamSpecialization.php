<?php 
global $noSpecId;
global $noSpecName;
?>
<td class="nxt-td">
    <div class="nxt">
        <label class="label">Specialization:</label>
    </div>
</td>

<td class="">
    <div class="ul-block">
        <ul class="">

            <?php
            if(!empty($subStreamSpecializations['substreams'])) { 

                foreach($subStreamSpecializations['substreams'] as $substreamsSpecialization) { ?>

                    <li>
                        <div class="Customcheckbox2">
                            <input type="checkbox" id="<?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?>" value="<?php echo $substreamsSpecialization['id'];?>" class="loadCourses clone substream_<?php echo $criteriaNo;?>" entityType="subStream" isParentEntity="1"/>
                            <label for="<?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?>" class="clone"><?php echo $substreamsSpecialization['name'];?></label>
                        </div> 
                        
                        
                        <div class="l-col">
                            <ul class="">

                                <li>
                                    <div class="Customcheckbox2">
                                        <input type="checkbox" id="<?php echo 'substream'.$substreamsSpecialization['id'].'_subSpecialization'.$noSpecId.'_'.$criteriaNo;?>" value="<?php echo $noSpecId;?>" parentId="<?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?>" class="loadCourses <?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?> clone specialization_<?php echo $criteriaNo;?>"  entityType="specialization" isParentEntity="0"/>
                                        <label for="<?php echo 'substream'.$substreamsSpecialization['id'].'_subSpecialization'.$noSpecId.'_'.$criteriaNo;?>" class="clone"><?php echo $noSpecName['noSpecMapping'];?></label>
                                    </div> 
                                </li>

                                <?php
                                if(!empty($substreamsSpecialization['specializations'])) {
                                    
                                    foreach($substreamsSpecialization['specializations'] as $subspecializations) { ?>
                                <li>
                                    <div class="Customcheckbox2">
                                        <input type="checkbox" id="<?php echo 'substream'.$substreamsSpecialization['id'].'_subSpecialization'.$subspecializations['id'].'_'.$criteriaNo;?>" value="<?php echo $subspecializations['id'];?>" parentId="<?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?>" class="loadCourses <?php echo 'subStream'.$substreamsSpecialization['id'].'_'.$criteriaNo;?> clone specialization_<?php echo $criteriaNo;?>"  entityType="specialization" isParentEntity="0"/>
                                        <label for="<?php echo 'substream'.$substreamsSpecialization['id'].'_subSpecialization'.$subspecializations['id'].'_'.$criteriaNo;?>" class="clone"><?php echo $subspecializations['name'];?></label>
                                    </div> 
                                </li>
                                <?php } ?>
                            <?php } ?>
                            
                            </ul>
                        </div> 

                    </li>
          
            <?php 
                } 

            } ?>

            <?php
            if(!empty($subStreamSpecializations['specializations'])) { ?>

                <li>
                    <div class="Customcheckbox2">
                        <input type="checkbox" id="<?php echo 'ungroupedSpecialization_'.$criteriaNo;?>" class="loadCourses clone" entityType="ungroupedSpecialization" isParentEntity="1" />
                        <label for="<?php echo 'ungroupedSpecialization_'.$criteriaNo;?>" class="clone"><?php echo UNGROUPED_SPECIALIZATIONS_NAME;?></label>
                    </div> 

                    <div class="l-col">
                        <ul class="">
                        
                        <?php
                        foreach($subStreamSpecializations['specializations'] as $specializations) { ?>

                            <li>
                                <div class="Customcheckbox2">
                                    <input type="checkbox" id="<?php echo 'specialization'.$specializations['id'].'_'.$criteriaNo;?>" value="<?php echo $specializations['id'];?>" entityType="ungroupedSpecializationChild" class="loadCourses clone <?php echo 'ungroupedSpecialization_'.$criteriaNo;?> specialization_<?php echo $criteriaNo;?>" parentId="<?php echo 'ungroupedSpecialization_'.$criteriaNo;?>" isParentEntity="0"/>
                                    <label for="<?php echo 'specialization'.$specializations['id'].'_'.$criteriaNo;?>" class="clone"><?php echo $specializations['name'];?></label>
                                </div>
                            </li>
                  
                        <?php 
                        } ?>

                        </ul>
                    </div>
                </li>

            <?php

            } ?>

        </ul>
    </div>
</td>
