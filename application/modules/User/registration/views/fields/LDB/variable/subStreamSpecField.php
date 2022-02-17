<?php $subStreamSpec = $fields['subStreamSpecializations']->getValues(array('streamIds'=>array($streamId), 'baseCourseIds'=>$baseCourses, 'customSubStreamSpecializations'=>$customFieldValueSource['subStreamSpecs']));
if(!empty($subStreamSpec[$streamId]['substreams']) || !empty($subStreamSpec[$streamId]['specializations'])){ ?>
	<?php if(count($subStreamSpec[$streamId]['substreams']) > 0){?>
		<ul>
	<?php } ?>
		<?php foreach($subStreamSpec[$streamId]['substreams'] as $subStreamId=>$subStreamValues){ ?>
			<li>
				<div class="Customcheckbox nav-checkBx cP">
					<input type="checkbox" id="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" class="pLevel sSS_<?php echo $regFormId; ?>" name="subStream[]" value="<?php echo $subStreamId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" match="sS_<?php echo $subStreamId.'_'.$regFormId; ?>" norest = 'yes'>
					<label for="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>"><?php echo $subStreamValues['name']; ?></label>
				</div>
				<?php if(!empty($subStreamValues['specializations'])){ ?>
					<div class="nav-cont">
						<ul class="lyr-sblst2">
							<?php foreach($subStreamValues['specializations'] as $SubSpecKey=>$subSpecValues){ ?>
								<li>
									<div class="Customcheckbox nav-checkBx">
										<input type="checkbox" id="spec_<?php echo $subSpecValues['id'].'_'.$subStreamId.'_'.$regFormId; ?>" class="cLevel sSS_<?php echo $regFormId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" name="specializations[]" value="<?php echo $subSpecValues['id']; ?>" match="sS_<?php echo $subStreamId.'_'.$regFormId; ?>" parentId="subStream_<?php echo $subStreamId.'_'.$regFormId; ?>" norest = 'yes'>
										<label for="spec_<?php echo $subSpecValues['id'].'_'.$subStreamId.'_'.$regFormId; ?>"><?php echo $subSpecValues['name']; ?></label>
									</div>
								</li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</li>
					<?php } ?>

				<?php if(!empty($subStreamSpec[$streamId]['specializations'])){ ?>
					<ul>
						<?php 
						foreach ($subStreamSpec[$streamId]['specializations'] as $specId => $specValues) { ?>
							<li>
								<div class="Customcheckbox nav-checkBx">
									<input type="checkbox" id="spec_<?php echo $specId.'_'.$regFormId; ?>" class="unmp cLevel sSS_<?php echo $regFormId; ?>" name="specializations[]" value="<?php echo $specId; ?>" classHolder="sSS_<?php echo $regFormId; ?>" match="spec_<?php echo $specId.'_'.$regFormId; ?>" norest = 'yes'>

									<label for="spec_<?php echo $specId.'_'.$regFormId; ?>"><?php echo $specValues['name']; ?></label>
								</div>
							</li>
							<?php }
							?>
							<li class='nsf'><span>No Results Found</span></li>

						</ul>
					<?php } ?>
				<?php if(count($subStreamSpec[$streamId]['substreams']) > 0){?>
					</ul>
				<?php } ?>
			<?php } ?>