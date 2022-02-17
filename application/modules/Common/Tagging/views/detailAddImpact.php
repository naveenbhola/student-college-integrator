
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'Add Tags Impact', 'page' => 'addImpact'));
?>
<div class='main-wrapper'>

<div class="cms-form-wrap cms-accordion-div" style="width: 90%;margin-left:7%;border:1px solid #000;">
    <table  cellpadding=5 cellspacing=0>
        <tr><th style='width:40px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'>S.No</th><th style='border-bottom:1px solid #ccc;'>Text</th><th>Tags</th></tr>
 <?php
 if(isset($error)){
    echo "No Tagged Question";
 }
 $count = 1;
 foreach ($data as $key => $value) {
     ?>
           <tr>
                <td style='text-align:center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'><?=($count++).'('.($value['threadId']).')'?></td>
                <td style='text-align:center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'><?=$value['msgTxt']?></td>
                <td style='text-align:center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'>
                    <?php
                        $tag_string = "";
                        foreach ($value['tags'] as $key => $value) {
                            $tag_string .= "<b>".$key."</b> - ".implode(",", $value)."<br />";
                        }
                        echo $tag_string;
                    ?>

                </td>
            </tr>
     <?php
 }
 ?>
 </table>
</div>
      </div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'addPage'));
?>                
