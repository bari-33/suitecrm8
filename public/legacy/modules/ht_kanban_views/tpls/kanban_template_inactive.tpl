{literal}               
<style>  
.face{width:300px;height:300px;border:4px solid #383a41;border-radius:10px;background-color:#fff;margin:0 auto;margin-top:50px;}@media screen and (max-width:400px){.face{transform:scale(.8)}}.face .band{width:350px;height:27px;border:4px solid #383a41;border-radius:5px;margin-left:-25px;margin-top:50px}.face .band .red{height:calc(100% / 3);width:100%;background-color:#eb6d6d}.face .band .white{height:calc(100% / 3);width:100%;background-color:#fff}.face .band .blue{height:calc(100% / 3);width:100%;background-color:#5e7fdc}.face .band:before{content:"";display:inline-block;height:27px;width:30px;background-color:rgba(255,255,255,.3);position:absolute;z-index:999}.face .band:after{content:"";display:inline-block;width:30px;background-color:rgba(56,58,65,.3);position:absolute;z-index:999;right:0;margin-top:-27px}.face .eyes{width:100px;margin:0 auto;margin-top:40px}.face .eyes:before{content:"";display:inline-block;width:30px;height:15px;border:7px solid #383a41;margin-right:20px;border-top-left-radius:22px;border-top-right-radius:22px;border-bottom:0}.face .eyes:after{content:"";display:inline-block;width:30px;height:15px;border:7px solid #383a41;margin-left:20px;border-top-left-radius:22px;border-top-right-radius:22px;border-bottom:0}.face .dimples{width:180px;margin:0 auto;margin-top:15px}.face .dimples:before{content:"";display:inline-block;width:10px;height:10px;margin-right:80px;border-radius:50%;background-color:rgba(235,109,109,.4)}.face .dimples:after{content:"";display:inline-block;width:10px;height:10px;margin-left:80px;border-radius:50%;background-color:rgba(235,109,109,.4)}.face .mouth{width:40px;height:5px;border-radius:5px;background-color:#383a41;margin:0 auto;margin-top:25px}h1{color:#383a41;text-align:center;font-size:2.5em;margin-top: 30px !important;}@media screen and (max-width:400px){h1{padding-left:20px;padding-right:20px;font-size:2em;margin-top: 30px !important;}}.btn{font-family:"Open Sans";font-weight:400;padding:20px;background-color:#534d64 !important;color:#fff;width:320px;margin:0 auto;text-align:center;font-size:1.5em;border-radius:5px;cursor:pointer;transition:all .2s linear;display:inherit;margin-top: 30px !important;}@media screen and (max-width:400px){.btn{margin:0 auto;width:200px;margin-top: 30px !important;}}.btn:hover{background-color:#eb6d6d;transition:all .2s linear}#bootstrap-container{width: 100% !important;margin: auto;}
</style>
{/literal}
<div class="face">
	<div class="band">
		<div class="red"></div>
		<div class="white"></div>
		<div class="blue"></div>
	</div>
	<div class="eyes"></div>
	<div class="dimples"></div>
	<div class="mouth"></div>
</div>

<h1>Oops! <strong>"{$name}"</strong> board is inactive, to view this please make it active.</h1>
<div class="btn btn-return-home">Return to Home</div>
{literal}
<script>
$(document).ready(function(){
	$('.navbar').hide();
	$('#sidebar_container').hide();
	$('.footer_right').parent().hide();
	$('.moduleTitle').hide();
	$('.btn-return-home').on('click',function(){
		window.location.href = "index.php?module=Home&action=index";
	});
});
</script>
{/literal}