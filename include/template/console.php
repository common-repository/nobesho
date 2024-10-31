<?php 
if ( ! defined( 'ABSPATH' ) )
exit;
?>
<br />
<br />
<button name="transfer" onclick="transfer_products_to_nobesho();" id="transfer"  style="background-color: #00a86b;color: #fff;border: 1px gray solid;border-top-color: gray;border-right-color: gray;border-bottom-color: gray;border-left-color: gray;border-color: #191919;width: 220px;height: 40px;font-size: 16px;border-radius: 5px;margin-right: 470px;" >ارسال محصولات به نوبشو</button>
<br />
<div id="console" name="console"  style="
    -moz-appearance: textfield-multiline;
    -webkit-appearance: textarea;
    border: 3px solid gray;
    font: medium -moz-fixed;
    font: -webkit-small-control;
    height: 28px;
    overflow: auto;
    padding: 2px;
    resize: both;
    width: 800px;
    height: 300px;
    background-color: #9dc183;
    color: white;
    margin-top: 20px;
    margin-right: 180px;
    " contenteditable="true">
 
</div>




<script> 
	function transfer_products_to_nobesho(){
		page = 1;
		count = 0;
		document.getElementById("console").innerHTML += ' در حال دریافت محصولات لطفاً تا پایان عملیات این صفحه را نبندید و اتصال را قطع نکنید<br />'
		document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
		
		const send_products = async (number,products) => {
			

			jQuery(document).ready(function($) {
			// This does the ajax request
			try{
				$.ajax({
					url: ajaxurl,
					data: {
						'action':'post_products',
						'id' : products[number].id,
						'data' : products[number]
					},
					type: "post",
					success:function(response) {
							document.getElementById("console").innerHTML += 'محصول "'+products[number].name+'" ارسال شد  <br />' ;
							document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
							console.log(products[number].name);
							number++;
							count++;
							console.log(send_products(number,products));
					   
	
						
					},
					error: function(errorThrown){
						console.log(errorThrown);
						document.getElementById("console").innerHTML += 'مشکل در ارسال <br />' ;
						document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
					}
				});   
			}catch{
				document.getElementById("console").innerHTML += '<h5 style="color: #ff7733;">  مجموع ارسال شده ها : '+count+'</h5>'+' <br />' 
				document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
			}
			
			});
		}
		
		const request = async () => {
				
				jQuery(document).ready(function($) {
				// This does the ajax request
					$.ajax({
						url: ajaxurl,
						data: {
							'action':'nobesho_get_product',
							'page' : page
						},
						success:function(response) {
							console.log(response.length)
							if(response.length > 0){
								document.getElementById("console").innerHTML += '<h5 style="color: #b3ffb3;">'+' دریافت 100 محصول در صفحه :'+ page +'</h5>'+' <br />' 
								document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
								page++;
								number = 0;
								console.log(response);
								result = send_products(number,response);
								console.log(result);
								if(result){
									console.log(request());
								}
							}
							
						},
						error: function(errorThrown){
							console.log(errorThrown);
							document.getElementById("console").innerHTML += 'مشکل در ارسال <br />' ;
							document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight
						}
					});   
				});
		}
		
		request();
	   
		
	}


	function extract_products(arr) {
		result = arr.forEach(do_it);
		return result;
		
		
		
	}
	
	function do_it(item, index) {
		
		document.getElementById("console").innerHTML += item.name+' --- ارسال شد  <br />' 
		
		return item;
		
		
		
	}
</script>