<?php 
if ( ! defined( 'ABSPATH' ) )
exit;
?>
<form action='' method='POST'>
	<div style="border-style: ridge; border-width: medium;margin-left:150px;margin-right:150px;padding-right:50px;margin-bottom:20px;margin-top:20px; padding-bottom:20px; padding-top:20px;background-color:#f26b8a;border-color:#999999 color:#000;"> 
		<p style="word-break: normal; font-size:18px; color:#191919;">
		این افزونه به منظور ارسال سریع و اتوماتیک محصولات شما به نوبشو طراحی شده است . کار با افزونه بسیار
		ساده است و فقط مراحل زیر را اجرا نمائید :
		</p><p style="word-break: normal; font-size:18px; color:#191919;">
		1.کد احراز هویت دریافتی در زمان ثبت نام در سایت نوبشو را در کادر زیر وارد نمائید.
		</p>
		<p style="word-break: normal; font-size:18px; color:#191919;">
		2.بر روی دکمه ارسال محصولات به نوبشو کلیک نمائید .
		</p>
		<p style="word-break: normal; font-size:18px; color:#191919;">
		فرآیند ارسال محصولات به سرعت انجام می شود و شما پس از مراجعه به سایت نوبشو و ورود به پنل می توانید
		ارتباط محصولات خود را برقرار نمائید .
		</p>
		<p></p>
	</div>
	<input id='nobesho-secret' name='nobesho-secret' type='password' size='60' placeholder='کد احراز هویت' value='<?php echo $secret; ?>' style="padding-right:20px; padding-left:10px;margin-right:200px;margin-left:10px;height:40px;"/>
	<?php if(get_option( 'nobesho_ck' ) !== false){ ?>
		<button  style="background-color:#00a86b;color:#fff;border:1px gray solid;border-color:#191919;width:120px;height:40px;font-size:16px;border-radius: 5px;" type='submit' > تایید </button>
	<?php }else{ ?>
		<button  style="background-color:#00a86b;color:#fff;border:1px gray solid;border-color:#191919;width:120px;height:40px;font-size:16px;border-radius: 5px;" type='submit' > تایید </button>
	<?php } ?>
</form>