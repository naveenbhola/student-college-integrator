<?php 

if(($totalInstituteCount > 0 && $totalPages > $lazyLoadsPerPage) || ($totalCourseCount > 0 && $totalPages > $lazyLoadsPerPage) || $totalTupleCount > 0) { ?>
    <div id="searchPagination" class="pagnation-col">
        <ul class="pagniatn-ul">
            <li>
                <?php if(!empty($paginationURLS['leftArrow']['url'])) { ?>
                    <a class='leftarrow' href="<?php echo $paginationURLS['leftArrow']['url']; ?>"> ❮ </a>
                <?php } else { ?>
                    <a class="leftarrow disable-link"> ❮ </a>
                <?php } ?>
            </li>
            
            <li>
                <?php if($paginationURLS[0]['isActive'] == true){
                    ?>
                      <a class="active"><?php echo $paginationURLS[0]['text'] ?></a>
                    <?php
                }else{
                    ?>
                      <a href="<?php echo $paginationURLS[0]['url'] ?>"><?php echo $paginationURLS[0]['text'] ?></a>
                    <?php
                }
                ?>  
            </li>
            <?php if(!empty($paginationURLS[1]['text'])){
                ?>
                <li>
                <?php if($paginationURLS[1]['isActive'] == true){
                    ?>
                      <a class="active"><?php echo $paginationURLS[1]['text'] ?></a>
                    <?php
                }else{
                    ?>
                      <a href="<?php echo $paginationURLS[1]['url'] ?>"><?php echo $paginationURLS[1]['text'] ?></a>
                    <?php
                }
                ?>  
            </li>
                <?php
            }
            ?>
            
            <?php if(!empty($paginationURLS[2]['text'])){
                ?>
                <li>
                    <?php if($paginationURLS[2]['isActive'] == true){
                        ?>
                          <a class="active"><?php echo $paginationURLS[2]['text'] ?></a>
                        <?php
                    }else{
                        ?>
                          <a href="<?php echo $paginationURLS[2]['url'] ?>"><?php echo $paginationURLS[2]['text'] ?></a>
                        <?php
                    }
                    ?>  
                </li>
                <?php
            }
            ?>

            
            <?php if(!empty($paginationURLS[3]['text'])){
                ?>
                <li>
                <?php if($paginationURLS[3]['isActive'] == true){
                    ?>
                      <a class="active"><?php echo $paginationURLS[3]['text'] ?></a>
                    <?php
                }else{
                    ?>
                      <a href="<?php echo $paginationURLS[3]['url'] ?>"><?php echo $paginationURLS[3]['text'] ?></a>
                    <?php
                }
                ?>  
                </li>
                <?php
            } ?>
            
            <li>
                <?php if(!empty($paginationURLS['rightArrow']['url'])) { ?>
                    <a class='rightarrow' href="<?php echo $paginationURLS['rightArrow']['url']; ?>"> ❯ </a>
                <?php } else { ?>
                    <a class="rightarrow disable-link"> ❯ </a>
                <?php } ?>
            </li>
            <p class="clr"></p>
        </ul>
    </div>
<?php } ?>