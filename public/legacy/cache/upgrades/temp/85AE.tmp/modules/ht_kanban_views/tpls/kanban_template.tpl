<br>

	<div class="ht-title-body">
	
		<span style="font-family: Arial; "><b>Name:</b></span>
		<span style="font-family: Arial; ">{$name}</span>
		&nbsp;&nbsp;
		<span style="font-family: Arial; "><b>Module:</b></span>
		<span style="font-family: Arial; ">{$CURRENT_MODULE_LABEL}</span>
		&nbsp;&nbsp;
		<span style="font-family: Arial; "><b>Selected Field:</b></span>
		<span style="font-family: Arial;">{$field_label}</span>
		&nbsp;&nbsp;
		<input type="text" name="kanban_type_new" id="kanban_type_new" size="30" maxlength="100" value="" title=""
			tabindex="0" placeholder="Type a keyword to search..." />
		<input type="button" id="show_more_records" value="Show More"
			style="padding: 5px;margin: 0px 5px 5px 10px;height: 28px !important;border: none;box-sizing: border-box;border-radius: 3px;display: block;line-height: 20px;background-color: #f08377 !important; display:none;" />
		<span class="shown_total_cards"></span>
	</div>
	
	<div class="modal modal-heading" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="margin: 0 auto;
			max-width: 50%;
			margin-top: 30%;">
				<div class="modal-header" style="padding: 5px;">
					<button type="button" onclick="closemodal()" class="close" data-dismiss="modal" aria-label="Close">
						<span  aria-hidden="true">&times;</span>
					</button>
					<h4 style="letter-spacing: 0px;">Aggregate value</h4>
				</div>
				<div class="modal-body">
					<div class="header-agregate text-center"></div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="open_box_model" tabindex="-1" role="dialog" aria-labelledby="open_box_model"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content" >
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	{sugar_getscript file="modules/ht_kanban_views/js/loadingoverlay.min.js"}
	{sugar_getscript file="include/javascript/qtip/jquery.qtip.min.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/jquery.cookie.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/metro.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/select2.min.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/jss_css_files/slimselect.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/kanban.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/sweetalert.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/blockui.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/sweetalert2.js"}
	{sugar_getscript file="modules/ht_kanban_views/js/readmore.js"}
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/css/style.css'}" />
	<link rel="stylesheet"
		href="{sugar_getjspath file='//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'}" />
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/css/metro-icons.css'}" />
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/css/metro-accordion.css'}" />
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/js/jss_css_files/select2.css'}" />
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/js/jss_css_files/slimselect.css'}" />
	<link rel="stylesheet" href="{sugar_getjspath file='modules/ht_kanban_views/js/jss_css_files/trello.css'}" />
	
	
	
	<input type="hidden" name="current_module" id="current_module" value="{$CURRENT_MODULE}">
	<input type="hidden" name="selected_module" id="selected_module" value="{$selected_module}">
	<input type="hidden" name="field_name" id="field_name" value="{$field_name}">
	<input type="hidden" name="record_id" id="record_id" value="{$record_id}">
	<input type="hidden" name="kanban_card_fields_lenght" id="kanban_card_fields_lenght">
	<br>
	<br>
	<div id="tooltip_display"></div>
	<div class="task-grid-container"></div>
	<div style="display: inline-block;float: right;padding: 5px;border-radius: 5px;width: -webkit-fill-available;"><span
			class="shown_total_cards"></span></div>
	
	{literal}
		<style>
			.content-true{display:flex!important;z-index:unset;padding:0 3% 40px 3%!important}
			.blockOverlay{background-color:rgba(0,0,0,.08)!important}
			.modal-content{padding:10px}.pad-right{padding-right:5px}
			.modal-open .modal{overflow-y:hidden}
			#content{position:relative;overflow-y:unset;outline:0;overflow-x:unset}
			.modal{bottom:auto!important}
			.tooltip1{position:relative;display:inline-block;border-bottom:1px dotted #000}
			.tooltip1 .tooltiptext1{visibility:hidden;width:160px;height:50px;background-color:#778591;color:#fff;text-align:left;border-radius:6px;padding:10px 0;position:absolute;z-index:1;left:50%;margin-left:-60px;opacity:0;transition:opacity .3s}.tooltip1 .tooltiptext1::after{content:"";position:relative;top:-40%;left:50%;margin-left:-5px;border-width:5px;border-style:solid;border-color:#555 transparent transparent transparent}.tooltip1:hover .tooltiptext1{visibility:visible;opacity:1;padding:5px}.clear{display:none;visibility:hidden;height:0}.favorite{display:inline-flex}.moduleTitle .module-title-text{line-height:40px!important}.serverstats{display:none}#copyright_data{display:none}#kanban_type{background:#fff;border:1px solid #f08377;border-radius:3px;padding:12px;font-size:13px}.swal2-timer-progress-bar{background:#534d64!important}.swal2-timer-progress-bar-container{height:.5em!important}p.long-text-read-more-card+a{background:#534d64;border-radius:5px;text-align:center;color:#fff;font-size:10px;float:right;width:65px!important;font-weight:700;padding:5px}p.long-text-read-more-popup+a{background:#534d64;border-radius:5px;text-align:center;color:#fff;font-size:10px;float:right;width:65px!important;font-weight:700;padding:5px}.icon-sm{font-size:20px!important}div::-webkit-scrollbar-thumb{background:#534d64!important}.shown_total_cards{padding:5px;margin:0 5px 5px 10px;height:28px!important;border:none;box-sizing:border-box;border-radius:3px;display:block;line-height:20px}input{font-family:Lato,Arial,Sans-serif}#kanban_type_new:focus{border:2px solid #534d64!important;outline:1px}#kanban_type_new{background:#d8f5ee!important;padding:12px!important;border-radius:4px!important;height:30px!important}
			.ht-title-body{font-size:15px;margin-top:10px; z-index:1000!important ;margin-bottom:15px;display:inline-flex;background:#fff;position:fixed;top:4rem;border:2px solid #534d64;padding:15px 30px 40px 20px;border-radius:10px;height:0}
			.modal-custom-edit{margin-top:100px;z-index:10000!important;}
		</style>
		<style>
		
		</style>
		<script>
		   function closemodal() {
			location.reload();
            }
		</script>
	{/literal}