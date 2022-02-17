
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'Add Tags Impact', 'page' => 'addImpact'));
?>
<div class='main-wrapper'>
            <form action='/Tagging/TaggingCMS/addTags' method='POST' id='add_impact_form'>
                    <div class="cms-form-wrap cms-accordion-div">
                        <b style='margin-left:100px;'>Click On tag to view the impact</b><br />
                    <ul>                   
                        <?php
                        $count = 1;
                        
                        foreach ($tags as $value) { 
                            list(,$tagName) = explode(":", $value);
                            ?>
                            <li style='margin-left:100px;'>
                            <input type='radio' name='tag_add' value='<?php echo htmlspecialchars($tagName,ENT_QUOTES)?>'> 
                                <a href='javascript:void(0)' class='tag_name' onclick="viewDetailTagAdditionImpact(<?php echo htmlspecialchars(json_encode($tagName))?>);"><?=$tagName?></a></li>
                            <?php    
                        }
                        ?>     
                        <li>
                            <label>Tag Question Automatically  </label> &nbsp;&nbsp;&nbsp;<input type='checkbox' name='tag_ques_auto' > 
                        </li>
                        <li>
                                
                                <div class="cms-fields">
                                    <a class="orange-btn" onclick="submitTagsDataAddImpactForm();" href="javaScript:void(0);">Submit</a>
                                </div> 
                                
                            </li>
                    </ul>


                    </div>
               
       </form>
            
    <form action="/Tagging/TaggingCMS/viewDetailAdditionImpact"  method="POST" id="tag_form" target="_new">               
        <input type='hidden' name='tag' id='tag' value=''>        
    </form>
</div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'addPage'));
?>
<?php
if(isset($msg) && trim($msg)!=""){

    if($msg == "addedToDB"){
        echo "<script>alert('Tag Added to Database.');</script>";    
    }

    
}
?>