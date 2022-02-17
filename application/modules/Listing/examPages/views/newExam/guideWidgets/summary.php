<div class="clg-blok dflt__card ">
  <h2><?php echo ucwords($sectionName)?></h2>
  <div>
    <?php 
    $wikiLabel = new tidy ();
    $wikiLabel->parseString (htmlspecialchars_decode($data) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();?>
    <p class="f14__clr3">
      <?php echo $wikiLabel;?>
    </p><br/>
    <?php if($sectionName == 'Contact Information'){
      $this->load->view('examPages/newExam/contactInfo'); 
    }?>
  </div>
</div> 