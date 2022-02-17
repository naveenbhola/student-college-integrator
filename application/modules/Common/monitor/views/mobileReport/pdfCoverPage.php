<html>
	<head>
		<link href="http://<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("ebrochureAbroad"); ?>" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<table cellspacing="0" cellpadding="0" class="grid-table">
			<tbody>
				<tr>
					<td style="background-color:#a0779f">&nbsp;</td>
					<td style="background-color:#ae9bae">&nbsp;</td>
					<td style="background-color:#b2b0bd">&nbsp;</td>
					<td style="background-color:#b4aec6">&nbsp;</td>
					<td style="background-color:#bcbbb7">&nbsp;</td>
					<td style="background-color:#c0cda1">&nbsp;</td>
					<td style="background-color:#e8bd55">&nbsp;</td>
					<td style="background-color:#fab746">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#cf7b8a">&nbsp;</td>
					<td style="background-color:#000; color:#fff; font-size:18pt; text-align:center; border:1px solid #fff;" colspan="6">Mobile Website Performance <br>Report </td>
					<td style="background-color:#ecc65b">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#f48188">&nbsp;</td>
					<td style="background-color:#fdc8c2">&nbsp;</td>
					<td style="background-color:#ffdabd">&nbsp;</td>
					<td style="background-color:#ffe8d0">&nbsp;</td>
					<td style="background-color:#f7efed">&nbsp;</td>
					<td style="background-color:#e8f3ef">&nbsp;</td>
					<td style="background-color:#d5f1e3">&nbsp;</td>
					<td style="background-color:#d7c873">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#fe8c96">&nbsp;</td>
					<td valign="top" style="background-color:#000" rowspan="4" colspan="6">
						<table cellspacing="0" cellpadding="0" border="1" style="color:#fff; border:1px solid #fff; border-collapse:collapse; width:100%; font-size:20px; text-align:center" class="">
							<tbody>
								<tr>
									<td colspan="4">Site Usage on <?=date("d-m-Y",time()-60*60*24)?></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><strong>Total Visits</strong></td>
									<td><strong>Page Views</strong></td>
									<td><strong>Pages/Visit</strong></td>
								</tr>
								<tr>
									<td><strong>Domestic</strong></td>
									<td><?=$domestic['totalVisits']?></td>
									<td><?=$domestic['pageViews']?></td>
									<td><?=round($domestic['pagesPerVisit'],3)?></td>
								</tr>
								<tr>
									<td><strong>Study Abroad</strong></td>
									<td><?=$abroad['totalVisits']?></td>
									<td><?=$abroad['pageViews']?></td>
									<td><?=round($abroad['pagesPerVisit'],3)?></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="background-color:#f3cad0">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#f89ba6">&nbsp;</td>
					<td style="background-color:#95b8be">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#e9a7b3">&nbsp;</td>
					<td style="background-color:#e1c1ce">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#d6acba">&nbsp;</td>
					<td style="background-color:#ebcac3">&nbsp;</td>	
				</tr>
				<tr>
					<td style="background-color:#f5cb99">&nbsp;</td>
					<td style="background-color:#f9d7b2">&nbsp;</td>
					<td style="background-color:#ebbea7">&nbsp;</td>
					<td style="background-color:#dab996">&nbsp;</td>
					<td style="background-color:#ebbea7">&nbsp;</td>
					<td style="background-color:#dab996">&nbsp;</td>
					<td style="background-color:#ebbea7">&nbsp;</td>
					<td style="background-color:#dab996">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#fac87f">&nbsp;</td>
					<td style="background-color:#fcda9d">&nbsp;</td>
					<td style="background-color:#fff8ce">&nbsp;</td>
					<td style="background-color:#fef1bc">&nbsp;</td>
					<td style="background-color:#fef5cc">&nbsp;</td>
					<td style="background-color:#f8e9ca">&nbsp;</td>
					<td style="background-color:#d2d2b8">&nbsp;</td>
					<td style="background-color:#cfc6a7">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#e9b77a">&nbsp;</td>
					<td style="background-color:#f5e2a8">&nbsp;</td>
					<td style="background-color:#e1c59e">&nbsp;</td>
					<td style="background-color:#fee69e">&nbsp;</td>
					<td style="background-color:#ebdba8">&nbsp;</td>
					<td style="background-color:#ebdba8">&nbsp;</td>
					<td style="background-color:#bdd6c1">&nbsp;</td>
					<td style="background-color:#c4d7b7">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:#fac87f">&nbsp;</td>
					<td style="background-color:#fcda9d">&nbsp;</td>
					<td style="background-color:#fff8ce">&nbsp;</td>
					<td style="background-color:#fef1bc">&nbsp;</td>
					<td style="background-color:#fef5cc">&nbsp;</td>
					<td style="background-color:#f8e9ca">&nbsp;</td>
					<td style="background-color:#d2d2b8">&nbsp;</td>
					<td style="background-color:#cfc6a7">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>