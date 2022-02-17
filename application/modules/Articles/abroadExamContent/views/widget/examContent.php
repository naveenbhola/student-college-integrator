 <div itemprop="articleBody">
<?php 
echo $contentDetails['sections']['0']['details']; ?>
     
<?php $this->load->view('widget/relatedArticlesWidget'); 

?>
<?php $this->load->view('widget/structureDataMarkupMeta'); 

?>
<?php echo $contentDetails['description2']; ?>
</div>