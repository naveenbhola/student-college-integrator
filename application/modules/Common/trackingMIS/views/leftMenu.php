<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo SHIKSHA_HOME; ?>" class="site_title"><i class="fa fa-paw"></i> <span>Shiksha MIS!</span></a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="<?=$avtarImg?>" alt="..." class="img-circle profile_img">
            </div> 
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $userDataArray['firstname']." ".$userDataArray['lastname'];?></h2>
            </div>
        </div>
        <!-- /menu prile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>&nbsp;</h3>
                <ul class="nav side-menu"><?php
                
                    foreach($leftMenuArray as $mainMenuItem => $subMenuArray) {
                    
                    ?>
                        <li><a><i class="fa <?=$subMenuArray['className']?>"></i> <?=$mainMenuItem?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu" style="display: none"><?php
                            foreach($subMenuArray['children'] as $subMenuItem => $subMenuUrl) { ?>
                                <li><a href="<?=$subMenuUrl?>"><?=$subMenuItem?></a></li><?php
                            }  ?>
                            </ul>
                        </li>
                        <?php     
                        
                    }   // End of foreach($leftMenuArray as $mainMenuItem => $subMenuArray).
                    ?>                
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>        