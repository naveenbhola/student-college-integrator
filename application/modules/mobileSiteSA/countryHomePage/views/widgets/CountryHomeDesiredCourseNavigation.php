<div class="cntry-home-nav cntry-tab" id="tab-section">
    <div class="nav-List cntry-quickLink expanded">
        <span class="expnd-circle"><span class="ib-circle"><i class="expnd-switch"></i></span></span>
        <ul class="navList-items" id="navBarUl"
            style="transition: transform 150ms ease-out 0s; transform: translate3d(0px, 0px, 0px);">
            <?php foreach ($coursesOnPage as $courseId) { ?>
                <li
                    <?php echo ($activeCourseId == $courseId?'class="active"':''); ?> >
                    <a href="javascript:void(0)" class="cNavButton" courseid="<?php echo $courseId;?>">
                        <?php echo $courseNames[$courseId]; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>