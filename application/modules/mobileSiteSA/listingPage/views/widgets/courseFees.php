<?php
    $currencySymbol = $this->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
    if(!empty($currencySymbol[$courseObj->getFees()->getCurrencyEntity()->getId()])){
	$fromCurrencyUnit = $currencySymbol[$courseObj->getFees()->getCurrencyEntity()->getId()];
    }
	else{
	$fromCurrencyUnit = $courseFeeData["fromCurrenyObj"]->getCode();
    }
?>

<section class="detail-widget navSection" data-enhance="false" id="feeSection">
<div style="padding:0;" class="detail-widegt-sec">
            	<div style="padding:0;" class="detail-info-sec clearfix">
                	<div style="padding:10px 15px;">
                        <strong class="flLt">1st year tuition fees</strong>
                           <div class="flRt custom-dropdown">
                            <select id="fees-currency-select" <?=(($courseCurrency != 1)?'onchange="showFeeTab(this)"':'disabled')?> style="width:98px;" class="universal-select">
                               <option value="indian-currency" >INR</option>
                               <?php if($courseCurrency != 1){?>
							   <option value="foreign-currency"><?=$courseFeeData["fromCurrenyObj"]->getCode()?></option>
		    					<?php }?>
                            </select>
                         </div>
                       
                        <div class="clearfix"></div>
                    </div>
                    <ul class="fee-list fees-living-cost-indian-currency">
                    	<li>
                        	<strong>Tution &amp; Fees</strong>
                            <p class="fee-detail">INR <?=$courseFeeData['toFormattedFees']?></p>
                        </li>
                    </ul>
                     <ul class="fee-list fees-living-cost-foreign-currency" style="display:none">
                    	<li>
                        	<strong>Tution &amp; Fees</strong>
                            <p class="fee-detail"><?=$fromCurrencyUnit?> <?=$courseFeeData['fromFormattedFees']?></p>
                        </li>
                    </ul>
                    <?php if(!empty($customFees)) { ?>
                    <div class="other-expanses-table other-fees-cost-indian-currency">
                     	<strong class="flLt">Other expenses in 1st year</strong>
                        <table><tbody>
                        	<?php foreach ($customFees as $cellData){?>
                    		<tr>
                        	<td><label><?=htmlentities($cellData['caption'])?></label></td>
                            <td>INR <?=$cellData['toFormattedValue']?></td>
                        	</tr>
                        	<?php } ?>
                        </tbody></table>
                        <table class="total-table">
                        	<tbody><tr>
                            	<td><label>Total</label></td>
                                <td><strong>INR <?= $totalConvertedFees;?></strong></td>
                            </tr>
                        </tbody></table>
                    </div>
                    <?php  } ?>

                    <?php if(!empty($customFees)) { ?>
                    <div class="other-expanses-table other-fees-cost-foreign-currency" style ="display:none;">
                     	<strong class="flLt">Other expenses in 1st year</strong>
                        <table><tbody>
                        	<?php foreach ($customFees as $cellData){?>
                    		<tr>
                        	<td><label><?=htmlentities($cellData['caption'])?></label></td>
                            <td><?=$fromCurrencyUnit?> <?=$cellData['fromFormattedValue']?></td>
                        	</tr>
                        	<?php } ?>
                        </tbody></table>
                        <table class="total-table">
                        	<tbody><tr>
                            	<td><label>Total</label></td>
                                <td><strong><?=$fromCurrencyUnit?> <?= $totalFormattedFees;?></strong></td>
                            </tr>
                        </tbody></table>
                    </div>
					<?php  } ?>
            	</div>
            </div>
</section>
<script>

function showFeeTab(elem)
{
	 if($j(elem).val() == 'indian-currency')
    {
		$j('.fees-living-cost-foreign-currency').css('display','none');
		$j('.fees-living-cost-indian-currency').css('display','block');
		//check if the view more button is not hidden then switch the tables
		$j('#placement-currency-select').val('indian-currency').change();
		$j('.other-fees-cost-foreign-currency').css('display','none');
		$j('.other-fees-cost-indian-currency').css('display','block');
    
    }
   
    else if($j(elem).val() == 'foreign-currency')
    {
		$j('.fees-living-cost-indian-currency').css('display','none');
		$j('.fees-living-cost-foreign-currency').css('display','block');
       	$j('#placement-currency-select').val('foreign-currency').change();
		$j('.other-fees-cost-indian-currency').css('display','none');
		$j('.other-fees-cost-foreign-currency').css('display','block');
    }
}
</script>
