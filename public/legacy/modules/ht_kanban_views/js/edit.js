$(document).ready(function(){ 
	getRelatedFields();
	getRelatedModuleFields();
	getSelectedFieldOptions();
	get_module_calculation_fields();
   $('#flow_module').attr('onchange','getRelatedFields();getRelatedModuleFields(); get_module_calculation_fields();');
   $('#module_fields').attr('onchange','getSelectedFieldOptions();');
   var is_new_record = $('#is_new_record').val();
   if(is_new_record=="1"){
	   showModuleFields();
   }
});
function getRelatedFields(){
  var module_name = $('#flow_module').val();
	   $.ajax({
		  type: "POST",
		  async: false,
		  url: 'index.php?module=ht_kanban_views&action=getRelatedFields&sugar_body_only=true',
		  data: {module_name: module_name},
		  success: function(response){
			  var field_options = JSON.parse(response);
			  $("#module_fields").html('');
			  $("#target_values").html('');
			 
			  $.each(field_options, function( index, value ){
				   $('#module_fields').append( $('<option>').val(index).html(value));
			   });
			   getSelectedFieldOptions();
		   }
			
	   });
   getSelectedRelateFieldValue();	   
}

function getSelectedRelateFieldValue(){
   if(typeof(module_field_value) != 'undefined' && module_field_value != ''){
	   $("#module_fields").val(module_field_value);
   }
}

function getRelatedModuleFields(){
	var module_name = $('#flow_module').val();
	$.ajax({
		type: "POST",
		async: false,
		url: 'index.php?module=ht_kanban_views&action=getRelatedModuleFields&sugar_body_only=true',
		data: {module_name: module_name},
		success: function(response){
			var field_options = JSON.parse(response);
			$("#body_fields").html('');
			$.each(field_options, function( index, value ){
				$('#body_fields').append( $('<option>').val(index).html(value));
			});
		}
	});
	getRelatedModuleFieldsValue();
}

function getRelatedModuleFieldsValue(){
   if(typeof(selectedRelateFieldValue) != 'undefined' && selectedRelateFieldValue != ''){
	   $.each(selectedRelateFieldValue, function( index, value ){
		   $("#body_fields option[value='" + value + "']").prop("selected", true);
	   });
   }
}

function get_module_calculation_fields(){
	var module_name = $('#flow_module').val();
	$.ajax({
		type: "POST",
		async: false,
		url: 'index.php?module=ht_kanban_views&action=get_module_calculation_fields&sugar_body_only=true',
		data: {module_name: module_name},
		success: function(response){
			var field_options = JSON.parse(response);
			$("#module_calculation_fields").html('');
			$.each(field_options, function( index, value ){
				$('#module_calculation_fields').append( $('<option>').val(index).html(value));
			});
		}
	});
	get_module_calculation_fieldsValue();
}

function get_module_calculation_fieldsValue(){
   if(typeof(module_calculation_fields_value) != 'undefined' && module_calculation_fields_value != ''){
	   $("#module_calculation_fields").val(module_calculation_fields_value);
   }
}

function getSelectedFieldOptions(){
	var module_name = $('#flow_module').val(); 
	var module_fields = $('#module_fields').val();
	$.ajax({
		type: "POST",
		async: false,
		url: 'index.php?module=ht_kanban_views&action=getSelectedFieldOptions&sugar_body_only=true',
		data: {module_name: module_name, module_fields: module_fields},
		success: function(response){
			var field_options = JSON.parse(response);
			$("#target_values").html('');
			$.each(field_options, function( index, value ){
				$('#target_values').append( $('<option>').val(index).html(value));
			});
		}
	});
	getSelectedFieldOptionsValue();
}

function getSelectedFieldOptionsValue(){
   if(typeof(SelectedFieldOptionsValue) != 'undefined' && SelectedFieldOptionsValue != ''){
	   $.each(SelectedFieldOptionsValue, function( index, value ){
		   $("#target_values option[value='" + value + "']").prop("selected", true);
	   });
   }
}