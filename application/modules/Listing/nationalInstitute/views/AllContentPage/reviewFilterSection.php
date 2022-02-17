    <?php if(!empty($sortingOptions) && count($sortingOptions) > 0) {?>
                <div class="tableFlat">
                    <div class="sortBy">Sort By :</div>
                    <?php foreach($sortingOptions as $sortKey => $sortValue ){ ?>    
                        <div ga-attr="<?php echo strtoupper(str_replace(' ','',$sortValue))."_".$GA_Tap_On_Sort;?>" class = "<?php echo strtoupper($selectedSortOption) == strtoupper($sortValue) ?  "active": " ";?>" onclick="updateReviewsBySort(this,'<?php echo $sortValue;?>');"> <?php echo $sortValue;?><span class="arwEntity">&#8595;</span></div>
                    <?php } ?>  
                </div>
            <?php }  ?>