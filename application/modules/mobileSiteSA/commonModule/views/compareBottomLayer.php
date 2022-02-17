<div id="compareCourseBottomLayer" class="compare-bottom-sticky-layer" style="z-index:1;">
	<table>
		<tbody>
			<tr>
				<?php foreach($courseData as $courseId => $course){ ?>
					<td class="compare-full-list" style="height:88px;">
						<div class="sticky-compare-col">
							<p><strong><?=limitTextLength(htmlentities($course['universityName']),19)?></strong></p>
							<p class="sub-clr"><?=limitTextLength(htmlentities($course['courseName']),40)?></p>
							<a class="remove-college-mark" href="javascript:void(0);" onclick="addRemoveFromCompare('<?=$course['id']?>',compareOverlayTrackingKeyId);">&times;</a>
						</div>
						<input class="comparedCourse" type="hidden" value="<?=$course['id']?>">
					</td>
				<?php } ?>
				<?php if(count($courseData) < 2){ ?>
					<td id="recommendationDiv" style="display: none;">
						<p class="sub-clr"><strong>Compare with similar</strong></p>
						<ul class="similar-list">
						</ul>
					</td>
					<td id="lastCellDiv" style="display:none;vertical-align:middle;font-size:11px;color:#999;">
						<p>[ + ] Add another course</p>
					</td>
				<?php } ?>
			</tr>
			<?php if(count($courseData) == 2){ ?>
			<tr>
				<td style="padding:0px 8px 8px 8px" colspan="2">
					<a class="mbl-compare-btn" href="javascript:void(0);" onclick="compareCoursesCatergoryPage();"> <i class="sprite mbl-compare-icn"></i> Compare</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>