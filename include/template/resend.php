<?php 
if ( ! defined( 'ABSPATH' ) )
exit;
?>
<table>
  <tr>
	<tr>
		<svg height="100" width="100">
			 <circle cx="50" cy="50" r="7" stroke="none"  fill="green" />
			 Sorry, your browser does not support inline SVG.  
		 </svg> 
	</tr>
	<tr> 
		<div class="components-button is-button is-default is-primary" onclick="nobesho_send_single('<?php echo $post->ID; ?>');" > ارسال دوباره </div> 
	</tr>
   
  </tr>
</table>