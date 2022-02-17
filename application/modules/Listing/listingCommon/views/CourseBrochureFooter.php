<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' />
<style type="text/css">
  .footer-col, .footer-col *{
    font-family:'Open Sans';
  }
  .end-col{
  background: #00a5b5;
  padding: 15px;
  }
  .logo-col{
  border-bottom: 5px solid #f9b34e;
  padding: 10px 34px 0px 29px;
  height: 91px;
  }
  .logo-col:after,
  .logo-col:before{
  content: '';
  display: table;
  }
  .logo-col:after{
  clear: both;
  }
  .logo-footer{
  display: inline-block;
  float: left;
  height: 62px;
  overflow: hidden;
  }
  .disclaimer-p{
  float: right;
  font-size: 9px;
  text-align: right;
  line-height: 12px;
  display:block;
  height:100%;
  width:100%;
  margin-top:0px;
  }
  .pad-2{
  padding: 0 10px;
  }
  * {
  margin: 0 auto;
  padding: 0;
  box-sizing: border-box;
  }
  .footer-col {
  height: 131px;
  }
  table td{
  vertical-align: bottom;
  }
</style>

<div class="footer-col">
	<div class="logo-col">
		<table border="0" width="100%">
			<tr>
				<td width="25%">
					<div class="logo-footer">
					<img src="http://<?=IMGURL;?>/public/images/brochureLogo.png" />
					</div>
				</td>
				<td width="75%" >
					<p class="disclaimer-p"><strong>Disclaimer:</strong> This is not the official brochure of this course. It is auto-generated based on the information available on Shiksha as on <?=$createdDate?>.
					</p>
				</td>
			</tr>
		</table>

	</div>
	<div class="end-col"></div>
</div>