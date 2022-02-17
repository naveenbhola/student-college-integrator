<!--<div id="breadcrumb">
     <span>
       <a href="#">
       <span>Home</span>
       </a> <span class="breadcrumb-arr">&rsaquo;</span>
     </span>
     <span>
       <a href="#">
         <span>All Universities</span>
       </a><span class="breadcrumb-arr">&rsaquo;</span>
     </span>
     <span>
         <span>School of Engineering</span>
     </span>
</div>-->
<div id="breadcrumb">
     <?php
          foreach($breadCrumbData as $key=>$breadCrumb){
     ?>
     <span itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
          <?php
               if($breadCrumb['url'] != "") {?>
                    <a href="<?=$breadCrumb['url']?>" itemprop="url"><span itemprop="title"><?=htmlentities(str_replace("in abroad","abroad",$breadCrumb['title']))?></span></a>
                    </a> <span class="breadcrumb-arr">&rsaquo;</span>
          <?php }else{?>
                    <span itemprop="title"><?=htmlentities(str_replace("in abroad","abroad",$breadCrumb['title']))?></span>
          <?php }?>
     </span>
     <?php }
     ?>
</div>