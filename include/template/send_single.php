<?php 
if ( ! defined( 'ABSPATH' ) )
exit;
?>
<script> 
	function nobesho_send_single(product_id){
		const request = async () => {
			const re = await jQuery(document).ready(function($) {
								$.ajax({
									url: ajaxurl,
									data: {
										'action':'single_post_products',
										'id' : product_id
									},
									type: "post",
									success:function(response) {
										alert("کالای مورد نظر به نوبشو ارسال شد");
										window.location=document.location.href;
									},
									error: function(errorThrown){
										alert(errorThrown);
									}
								});
							});
		}
		request();
	}
	
</script> 