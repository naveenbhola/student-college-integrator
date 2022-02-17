<?php
    if(!empty($currencyMapping[$livingExpenseCurrencyObj->getId()])) {
	$fromCurrencyUnit = $currencyMapping[$livingExpenseCurrencyObj->getId()];
    }
	else if(!empty($livingExpenseCurrencySign)){
		$fromCurrencyUnit = $livingExpenseCurrencySign;
	}
	else{
		$fromCurrencyUnit = $livingExpenseCurrencyObj->getCode();
    }
?>
<section class="detail-widget" data-enhance="false">
    <div class="detail-widegt-sec" >
        <div class="detail-info-sec">
            <strong>Cost of Living</strong>
            <?php
                if(isset($livingExpenseInRupees) && $livingExpenseInRupees > 0){
            ?>
            <div>
                <div class="flRt custom-dropdown">
                    <select class="universal-select" <?=(($livingExpenseCurrencyObj->getId() != 1)?'onchange="showLivingExpenseTab(this)"':'disabled')?> style="width:120px;">
                        <option value="indian-currency">INR</option>
                        <?php if($livingExpenseCurrencyObj->getId() != 1){?>
                            <option value="foreign-currency"><?=$livingExpenseCurrencyObj->getCode()?></option>
                        <?php }?>
                    </select>
                 </div>
                <div class="clearfix"></div>
            </div>
            <ul class="fee-list salary-info-list">
                <li>
                    <strong>Yearly Cost of Living in <?php echo htmlentities($universityObj->getName()); ?>, <?php echo $universityObj->getMainLocation()->getCountry()->getName(); ?></strong>
                    <p style="margin-top:5px" class="livingExpenseIndianCurrency"><span>INR </span><?=$livingExpenseInRupees?></p>
                    <p style="margin-top:5px;display: none" class="livingExpenseForeignCurrency"><span><?=$fromCurrencyUnit?> </span><?=$livingExpense?></p>
                </li>
            </ul>
            <?php }
                if(isset($livingExpenseURL)){
            ?>
                <div class="tac">
                    <a href="<?=$livingExpenseURL?>" class="ui-link" rel="nofollow" target="_blank">Living expenses<i class="sprite arrow-icon"></i></a>
                </div>
            <?php }?>
        </div>
    </div>
    <?php if($livingExpenseCurrencyObj->getId() != 1){?>
        <script>
            function showLivingExpenseTab(elem){
                if($j(elem).val() == 'indian-currency'){
                    $j('.livingExpenseIndianCurrency').css('display','block');
                    $j('.livingExpenseForeignCurrency').css('display','none');
                }else if($j(elem).val() == 'foreign-currency'){
                    $j('.livingExpenseForeignCurrency').css('display','block');
                    $j('.livingExpenseIndianCurrency').css('display','none');
                }
            }
        </script>
    <?php }?>
</section>