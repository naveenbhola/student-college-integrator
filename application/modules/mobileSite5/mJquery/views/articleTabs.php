<section id="tab" class="clearfix"> 
	<table width="100%" cellpadding="0" cellspacing="0">
   	    <tr>
			<td <?php echo ($displayType == 'latest')?'class="active"':''; ?>>
				<a value="latest" class="arttabs" href="javascript:void(0);">
					<i class="icon-latest"></i>
				Latest
				</a>
        	</td>
            <td <?php echo ($displayType  == 'popular')?'class="active"':''; ?>>
				<a value="popular" class="arttabs" href="javascript:void(0);"> 
				<i class="icon-popular"></i>
				Popular
				</a>
			</td>       	    	
        </tr>
    </table>
</section>