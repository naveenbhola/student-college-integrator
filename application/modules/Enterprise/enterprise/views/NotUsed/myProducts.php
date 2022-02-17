
<div style="width:20%; float:right;">
    <div style="float:left; width:49%">
        <b>Product</b> <br/>
        <?php 
            foreach($productDetails as $packs)
            { 
                echo $packs['productName'].'<br/>';
            }
        ?></div>
    <div style="float:left; width:49%">
        <b>Remaining</b> <br/>
        <?php 
            foreach($productDetails as $packs)
            { 
                echo $packs['remaining'].'<br/>';
            }
        ?></div>
</div>
