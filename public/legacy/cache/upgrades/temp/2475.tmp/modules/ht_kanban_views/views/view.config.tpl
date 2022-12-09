<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
{literal}
<style type="text/css">
.range-slider{display:inline-flex!important}.range-slider__range{-webkit-appearance:none;height:10px!important;border-radius:5px;background:#d7dcdf!important;margin-top:10px!important}.range-slider__range::-webkit-slider-thumb{appearance:none;width:20px!important;height:20px!important;border-radius:50%;background:#778591!important;cursor:pointer;transition:background .15s ease-in-out}.range-slider__range:hover{background:#534d64!important}.range-slider__range:focus{outline:0}.range-slider__range:hover{background:#534d64!important}.range-slider__range:active::-moz-range-thumb{background:#d66c60!important}.range-slider__value{display:inline-block;position:relative;width:60px!important;color:#fff;line-height:20px;text-align:center;border-radius:3px;background:#534d64!important;padding:5px 10px;margin-left:8px}.range-slider__value:after{position:absolute;top:8px;left:-7px;width:0;height:0;border-top:7px solid transparent;border-right:7px solid #534d64!important;border-bottom:7px solid transparent;content:''}table{width:100%!important}table th div{width:100%!important;background:#534d64!important;padding:10px 0 5px 0!important;color:#fff;border:2px solid;border-radius:3px;text-align:center}table td div{width:100%!important;padding:10px 0 5px 0!important}
</style>



{/literal}
<form name="ConfigureAjaxUI" method="POST"  method="POST" action="index.php">
	<input type="hidden" name="module" value="ht_kanban_views">
	<input type="hidden" name="action" value="UpdateKanbanUI">
	<input type="hidden" id="enabled_modules" name="enabled_modules" value="">
	<input type="hidden" id="disabled_modules" name="disabled_modules" value="">
	<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
	<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary"
		   onclick="SUGAR.saveConfigureTabs();" type="submit" name="saveButton"
		   value="{$APP.LBL_SAVE_BUTTON_LABEL}" />
	<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button"
		   onclick="this.form.action.value='index'; this.form.module.value='Administration';" type="submit" name="CancelButton"
		   value="{$APP.LBL_CANCEL_BUTTON_LABEL}"/>
	<div class="container col-md-12" style="display: inline-grid">
	<h3>{sugar_translate label="LBL_CONFIG_KANABAN_HELP"}</h3>
		<div class='add_table col-md-6' style='margin-bottom:5px'>
			<table id="ConfigureTabs" class="themeSettings col-md-6" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width='1%'>
						<div id="enabled_div" class="enabled_tab_workarea">
						</div>
					</td>
					<td>
						<div id="disabled_div" class="disabled_tab_workarea">
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-md-4" style="padding: 0px 10px 10px 10px;">
			<h3>RECORDS LIMIT</h3>
			<div class="range-slider">
				<input class="range-slider__range" name="kanban_record_limit" type="range" value="{$kanban_record_limit}" min="1" max="100" style="width: 780px;" />
				<span class="range-slider__value">{$kanban_record_limit}</span>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="col-md-4" style="padding: 0px 10px 10px 10px;margin-bottom: 30px;">
				<h3>Enable/Disable smooth scrolling</h3>
				<select id="enable_disable_smooth_scrolling" name="enable_disable_smooth_scrolling" style="width: 250px;">
					<option value="disable" {$disable_smooth_scrolling}>Disable</option>
					<option value="enable" {$enable_smooth_scrolling}>Enable</option>
				</select>
			</div>
			<div class="col-md-4" style="padding: 0px 10px 10px 10px;margin-bottom: 30px;">
				<h3>Show/Hide Description in Cards</h3>
				<select id="show_hide_description_in_cards" name="show_hide_description_in_cards" style="width: 250px;">
					<option value="show" {$show_description_in_cards}>Show</option>
					<option value="hide" {$hide_description_in_cards}>Hide</option>
				</select>
			</div>
			<div class="col-md-4" style="padding: 0px 10px 10px 10px;margin-bottom: 30px;">
				<h3>Sort Cards in ASC/DESC Order</h3>
				<select id="sort_cards_in_asc_desc" name="sort_cards_in_asc_desc" style="width: 250px;">
					<option value="ASC" {$sort_cards_in_asc}>ASC</option>
					<option value="DESC" {$sort_cards_in_desc}>DESC</option>
				</select>
			</div>
			<div class="col-md-4" style="padding: 0px 10px 10px 10px;margin-bottom: 30px;">
				<h3>Enable/Disable Aggregate Data Popup</h3>
				<select id="enable_disable_aggregate_data_popup" name="enable_disable_aggregate_data_popup" style="width: 250px;">
					<option value="enable" {$enable_disable_aggregate_data_popup_enable}>Enable</option>
					<option value="disable" {$enable_disable_aggregate_data_popup_disable}>Disable</option>
				</select>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
var enabled_modules = {$enabled_mods};
var disabled_modules = {$disabled_mods};
var lblDisabled = '{sugar_translate label="LBL_ACTIVE_MODULES"}';
var lblEnabled = '{sugar_translate label="LBL_DISABLED_MODULES"}';
{literal}
SUGAR.enabledModsTable = new YAHOO.SUGAR.DragDropTable(
	"enabled_div",
	[{key:"label",  label: lblEnabled, width: 200, sortable: false},
		{key:"module", label: lblEnabled, hidden:true}],
	new YAHOO.util.LocalDataSource(enabled_modules, {
		responseSchema: {
			resultsList : "modules",
			fields : [{key : "module"}, {key : "label"}]
		}
	}), 
	{
		height: "300px",
		group: ["enabled_div", "disabled_div"]
	}
);
SUGAR.disabledModsTable = new YAHOO.SUGAR.DragDropTable(
	"disabled_div",
	[{key:"label",  label: lblDisabled, width: 200, sortable: false},
		{key:"module", label: lblDisabled, hidden:true}],
	new YAHOO.util.LocalDataSource(disabled_modules, {
		responseSchema: {
			resultsList : "modules",
			fields : [{key : "module"}, {key : "label"}]
		}
	}),
	{
		height: "300px",
		group: ["enabled_div", "disabled_div"]
		}
);
SUGAR.enabledModsTable.disableEmptyRows = true;
SUGAR.disabledModsTable.disableEmptyRows = true;
SUGAR.enabledModsTable.addRow({module: "", label: ""});
SUGAR.disabledModsTable.addRow({module: "", label: ""});
SUGAR.enabledModsTable.render();
SUGAR.disabledModsTable.render();
SUGAR.saveConfigureTabs = function(){
	var disabledTable = SUGAR.disabledModsTable;
	var modules = [];
	for(var i=0; i < disabledTable.getRecordSet().getLength(); i++){
		var data = disabledTable.getRecord(i).getData();
		if (data.module && data.module != '')
			modules[i] = data.module;
	}
	YAHOO.util.Dom.get('disabled_modules').value = YAHOO.lang.JSON.stringify(modules);
}
var rangeSlider = function(){
	var slider = $('.range-slider'),
	range = $('.range-slider__range'),
	value = $('.range-slider__value');
	slider.each(function(){
			value.each(function(){
				var value = $(this).prev().attr('value');
				$(this).html(value);
			});
			range.on('input', function(){
				$(this).next(value).html(this.value);
			});
	});
};
rangeSlider();
{/literal}
</script>