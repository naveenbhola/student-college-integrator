<?php if($totalTupleCount > 0) { ?>
    <div class="n-pagination n-left">
        <ul>
            <?php if(!empty($paginationURLS['leftArrow']['url'])) { ?>
                <li class="prev linkpagination">
                    <a href="<?php echo $paginationURLS['leftArrow']['url']; ?>"><i class="icons ic_left-gry"></i></a>
                </li>
            <?php } else { ?>
                <li class="prev linkpagination">
                    <a class="disable-nav" href="javascript:void(0);"><i class="icons ic_left-gry"></i></a>
                </li>
            <?php } ?>

            <?php if($paginationURLS[0]['isActive'] == true) { ?>
                <li class="linkpagination actvpage">
                    <a><?php echo $paginationURLS[0]['text'] ?></a>
                </li>
            <?php } else { ?>
                <li class="linkpagination">
                    <a href="<?php echo $paginationURLS[0]['url'] ?>"><?php echo $paginationURLS[0]['text'] ?></a>
                </li>
            <?php } ?>

            <?php if(!empty($paginationURLS[1]['text'])) { ?>
                <?php if($paginationURLS[1]['isActive'] == true){ ?>
                    <li class="linkpagination actvpage">
                        <a><?php echo $paginationURLS[1]['text'] ?></a>
                    </li>
                <?php } else { ?>
                    <li class="linkpagination">
                        <a href="<?php echo $paginationURLS[1]['url'] ?>"><?php echo $paginationURLS[1]['text'] ?></a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if(!empty($paginationURLS[2]['text'])) { ?>
                <?php if($paginationURLS[2]['isActive'] == true){ ?>
                    <li class="linkpagination actvpage">
                        <a><?php echo $paginationURLS[2]['text'] ?></a>
                    </li>
                <?php } else { ?>
                    <li class="linkpagination">
                        <a href="<?php echo $paginationURLS[2]['url'] ?>"><?php echo $paginationURLS[2]['text'] ?></a>
                    </li>
                <?php } ?>
            <?php } ?>

            <?php if(!empty($paginationURLS[3]['text'])) { ?>
                <?php if($paginationURLS[3]['isActive'] == true){ ?>
                    <li class="linkpagination actvpage">
                        <a><?php echo $paginationURLS[3]['text'] ?></a>
                    </li>
                <?php } else { ?>
                    <li class="linkpagination">
                        <a href="<?php echo $paginationURLS[3]['url'] ?>"><?php echo $paginationURLS[3]['text'] ?></a>
                    </li>
                <?php } ?>
            <?php } ?>
            
            <?php if(!empty($paginationURLS['rightArrow']['url'])) { ?>
                <li class="next linkpagination">
                    <a href="<?php echo $paginationURLS['rightArrow']['url']; ?>"><i class="icons ic_right-gry"></i></a>
                </li>
            <?php } else { ?>
                <li class="next linkpagination">
                    <a class="disable-nav" href="javascript:void(0);"><i class="icons ic_right-gry"></i></a>
                </li>
            <?php } ?>
        </ul>

        <p class="clr"></p>
    </div>
<?php } ?>