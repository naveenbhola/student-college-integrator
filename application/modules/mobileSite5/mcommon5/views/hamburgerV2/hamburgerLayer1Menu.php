<div class="search-nav">
    <?php 
        if($selectedStreamId == 0){
            ?>
            <p class="search-h1">Select education stream to personalize menu</p>
            <?php
        }
        else{
            ?>
            <p class="search-h1">Change education stream to personalize menu</p>
            <?php
        }
    ?>
    <div class="main-slct" id="hamburgerStreamsSelect_input"><?=$dropDownText;?></div>
    <div class="select-Class">
        <select name="hamburgerStreamsSelect" id="hamburgerStreamsSelect" style="display:none;" onchange="HBE.handleStreamChange(this);">
            <option value="0">Select</option>
            <?php 
                foreach($tabsContentByCategory as $streamId => $streamData) {
                    echo '<option value="'.$streamId.'">'.$streamData['name'].'</option>';
                }
            ?>
        </select>                     
    </div>
</div>
<div id='l1Layer' class="cat-menu">
    <?php 
    foreach ($menuContent['layer1'] as $groupName => $menu) { ?>
        <div class="sectionHeading"><?php echo $groupName; ?></div>
        <div class="cat-wrapper">
            <?php foreach ($menu as $menuId => $menuItem) {
                foreach ($menuItem as $key => $linkHTML) {
                    echo $linkHTML;
                }
            } ?>
       </div>
    <?php } ?>
   <a href="<?php echo SHIKSHA_STUDYABROAD_HOME;?>" class="abroadHeading">Go to study abroad website <span class="abroadLink-icn"></span></a>                   
</div>