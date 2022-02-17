
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'Delete Tags', 'page' => 'delete'));
?>
<div class='main-wrapper'>

                    <div class="cms-form-wrap cms-accordion-div" style="width: 90%;margin-left:7%;border:1px solid #000;">
                        <table  cellpadding=5 cellspacing=0>
                            <tr><th style='width:40px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'>S.No</th><th style='border-bottom:1px solid #ccc;'>Text</th></tr>
                            <?php 
                                $i = 1;
                                foreach ($finalResult as $data) {
                                    ?>
                                        <tr>
                                            <td style='text-align:center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;'><?=$i++?></td>
                                            <td style='border-bottom:1px solid #ccc;'><?=$data['msgTxt'];?></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            
                        </table>
                        <br />
                        
                    </div>
        </div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'deletePage'));
?>