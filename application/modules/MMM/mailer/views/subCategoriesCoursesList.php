<div id="<?php echo $property; ?>_all_subcats_holder<?php echo $selectedSubCategories?'_courses':''; ?>">
    <div style="height: 300px; overflow-y: auto;">
        <?php foreach($categories as $key => $value){
        ?>
        <div id="<?php echo $property; ?>_cat_subcat_holder_<?php echo $key; ?>">
            <input type="checkbox" id ="<?php echo $property; ?>_parent_category_<?php echo $key; ?>" metacommon='category' name="<?php if($property == 'registration') echo 'registration[category][]'; else echo 'response['.$property.'][category][]'; ?>" value="<?php echo $key; ?>" onchange="checkUncheckChilds1(this, '<?php echo $property; ?>_subcats_holder_<?php echo $key; ?>'); saveSubCategoriesAndCourses('<?php echo $property; ?>'); <?php echo $selectedSubCategories?'':'subcategoryOnChange(\''.$property.'\');'; ?>"><b><?php echo $value['categoryName']; ?></b><br/>
            <div id="<?php echo $property; ?>_subcats_holder_<?php echo $key; ?>" style="padding-left:18px">
                <?php foreach($value['subCat'] as $subcatId => $subcatDetails){
                ?>
                    <input type="checkbox" id ="<?php echo $property; ?>_parent_subcategory_<?php echo $subcatId; ?>" metacommon='subcategory' name="<?php if($property == 'registration') echo 'registration[subcategory][]'; else echo 'response['.$property.'][subcategory][]'; ?>" value="<?php echo $subcatId; ?>" onchange="<?php if(!empty($subcatDetails['courses'])){ ?>checkUncheckChilds1(this, '<?php echo $property; ?>_courses_holder_<?php echo $subcatId; ?>');<?php } ?> uncheckElement1(this,'<?php echo $property; ?>_parent_category_<?php echo $key; ?>','<?php echo $property; ?>_subcats_holder_<?php echo $key; ?>'); saveSubCategoriesAndCourses('<?php echo $property; ?>'); <?php echo $selectedSubCategories?'':'subcategoryOnChange(\''.$property.'\');'; ?>"> <?php echo $subcatDetails['name']; ?><br/>
                    <?php if(!empty($subcatDetails['courses'])){ ?>
                            <div id="<?php echo $property; ?>_courses_holder_<?php echo $subcatId; ?>" style="padding-left:36px">
                            <?php    foreach($subcatDetails['courses'] as $courseId => $courseName){
                            ?>
                                        <input type="checkbox" id ="<?php echo $courseId; ?>" metacommon='ldbcourse' name="<?php if($property == 'registration') echo 'registration[ldbcourse][]'; else echo 'response['.$property.'][ldbcourse][]'; ?>" value="<?php echo $courseId; ?>" onchange="uncheckElement1(this,'<?php echo $property; ?>_parent_subcategory_<?php echo $subcatId; ?>','<?php echo $property; ?>_courses_holder_<?php echo $subcatId; ?>'); uncheckElement1(this,'<?php echo $property; ?>_parent_category_<?php echo $key; ?>','<?php echo $property; ?>_subcats_holder_<?php echo $key; ?>'); saveSubCategoriesAndCourses('<?php echo $property; ?>')"> <?php echo $courseName; ?><br/>
                            <?php } ?>
                            </div>
                    <?php }
                }?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>