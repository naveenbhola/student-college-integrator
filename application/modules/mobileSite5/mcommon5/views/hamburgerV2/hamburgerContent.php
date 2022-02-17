<!-- Layer 1 HTML -->
<div id="MHMenu" class="menuwrapper" data-enhance="false">
    <div class="inner-menu">
        <?php $this->load->view('mcommon5/hamburgerV2/hamburgerLayer1Header'); ?>
        <div id="hamburgerStreamSection">
        	<?php $this->load->view('mcommon5/hamburgerV2/hamburgerLayer1Menu'); ?>
        </div>
    </div>
</div>

<!-- Layer 2 HTML -->
<?php foreach ($menuContent['layer2'] as $menuId => $layerHtml) {
	echo $layerHtml;
} ?>

<!-- Layer 3 HTML -->
<?php foreach ($menuContent['layer3'] as $menuId => $layerHtml) {
	echo $layerHtml;
} ?>
<!-- Layer 4 HTML -->
<?php foreach ($menuContent['layer4'] as $menuId => $layerHtml) {
	echo $layerHtml;
} ?>