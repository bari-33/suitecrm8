$(document).ready(function () {
    $('#flow_module').change(function () {
        getAggregatedFields();
    });
    getAggregatedFields();
    getAggregatedFunctions();
    setTimeout(function () {
        load_fileds(fields_data);
    }, 5000);
});

var condln_agg = 0;
var condln_agg_count = 0;
var fields = '';
var functions = '';
var fields_data = '';
var script_run = true;


function load_fileds(fields_data) {
    if (fields_data !== null && fields_data !== '' && fields_data !== undefined) {
        var decode = atob(fields_data);
        var data = JSON.parse(decode);
        $.each(data, function (key, value) {
            insertaggregatedField(value);
        });
    }
}

function getAggregatedFields() {
    var module = $('#flow_module').val();
    $.ajax({
        url: "index.php?module=ht_kanban_views&action=get_module_calculation_fields",
        cache: false,
        type: "POST",
        data: { module_name: module },
        success: function (json_data) {
            var data = JSON.parse(json_data);
            var options = '';
            $.each(data, function (key, value) {
                options += '<option value="' + key + '"> ' + value + ' </option>';
            });
            fields = options;
        }
    });
}
function getAggregatedFunctions() {
    $.ajax({
        url: "index.php?module=ht_kanban_views&action=get_aggreate_functions_list",
        cache: false,
        type: "POST",
        success: function (json_data) {
            var data = JSON.parse(json_data);
            var options = '';
            $.each(data, function (key, value) {
                options += '<option value="' + key + '"> ' + value + ' </option>';
            });
            functions = options;
        }
    });
}

function insertaggConditionHeader() {
    tablehead = document.createElement("thead");
    tablehead.id = "aggregatedField_head";
    document.getElementById('aggregatedFields').appendChild(tablehead);

    var x = tablehead.insertRow(-1);
    x.id = 'aggregatedField_head';

    var a = x.insertCell(0);

    var b = x.insertCell(1);
    b.style.color = "rgb(0,0,0)";
    b.innerHTML = SUGAR.language.get('ht_kanban_views', 'LBL_FUNCTIONS');

    var c = x.insertCell(2);
    c.style.color = "rgb(0,0,0)";
    c.innerHTML = SUGAR.language.get('ht_kanban_views', 'LBL_FIELD');

}

function insertaggregatedField(value = '') {
    if (document.getElementById('aggregatedField_head') == null) {
        insertaggConditionHeader();
    } else {
        document.getElementById('aggregatedField_head').style.display = '';
    }


    tablebody = document.createElement("tbody");
    tablebody.id = "aggregated_fields_body" + condln_agg;
    document.getElementById('aggregatedFields').appendChild(tablebody);


    var x = tablebody.insertRow(-1);
    x.id = 'product_line' + condln_agg;

    var a = x.insertCell(0);
    if (action_sugar_grp1 == 'EditView') {
        a.innerHTML = "<button type='button' id='aggregated_fields_delete_line" + condln_agg + "' class='button' value='-' tabindex='116' onclick='markAggregatedFieldDeletedupdated(" + condln_agg + ")'><span class='glyphicon glyphicon-minus'></span></button><br>";
        a.innerHTML += "<input type='hidden' name='aggregated_fields_deleted[" + condln_agg + "]' id='aggregated_fields_deleted" + condln_agg + "' value='0'><input type='hidden' name='aggregated_fields_id[" + condln_agg + "]' id='aggregated_fields_id" + condln_agg + "' value=''>";
    } else {
        a.innerHTML = condln_agg + 1;
    }

    var b = x.insertCell(1);
    var viewStyle = 'display:none';
    if (action_sugar_grp1 == 'EditView') { viewStyle = ''; }
    b.innerHTML = "<select class='agg-functions' style='" + viewStyle + "' name='aggregated_fields_functions[" + condln_agg + "]' id='aggregated_fields_functions" + condln_agg + "'  title='' tabindex='116'> " + functions + " </select>";

    var c = x.insertCell(2);
    var viewStyle = 'display:none';
    var additionalDiv = '<div style="padding: 5px;border: 1px solid skyblue;border-radius: 5px;margin-top: 5px;width: 95%;background: #a9a9a97d;">ID</div>';
    if (action_sugar_grp1 == 'EditView') { viewStyle = ''; additionalDiv = ''; }
    if (value && value.aggregated_fields_functions == 'Count') { viewStyle = 'display:none'; additionalDiv = '<div style="padding: 5px;border: 1px solid skyblue;border-radius: 5px;margin-top: 5px;width: 95%;background: #a9a9a97d;">ID</div>'; }
    c.innerHTML = "<select  style='" + viewStyle + "' name='aggregated_fields_fields[" + condln_agg + "]' id='aggregated_fields_fields" + condln_agg + "'  title='' tabindex='116'> " + fields + " </select>" + additionalDiv;

    if (value) {
        $('#aggregated_fields_functions' + condln_agg).val(value.aggregated_fields_functions);
        $('#aggregated_fields_fields' + condln_agg).val(value.aggregated_fields_fields);
    }

    $('#aggregated_fields_functions' + condln_agg).on('change', function () {
        var field_id = $(this).parent().next().children('select').attr('id');
        if ($(this).val() == 'Count') {
            $('#' + field_id).hide();
            $('#' + field_id).parent().append('<div style="padding: 5px;border: 1px solid skyblue;border-radius: 5px;margin-top: 5px;width: 95%;background: #a9a9a97d;">ID</div>');
        } else {
            $('#' + field_id).show();
            $('#' + field_id).parent().children('div').remove();
        }
    });

    condln_agg++;
    condln_agg_count++;

    return condln_agg - 1;
}

function markAggregatedFieldDeleted(ln) {
    document.getElementById('aggregated_fields_body' + ln).style.display = 'none';
    document.getElementById('aggregated_fields_deleted' + ln).value = '1';
    document.getElementById('aggregated_fields_delete_line' + ln).onclick = '';
    condln_agg_count--;
    if (condln_agg_count == 0) {
        document.getElementById('aggregatedField_head').style.display = "none";
    }
}
function markAggregatedFieldDeletedupdated(ln) {
    $('#aggregated_fields_body' + ln).remove();
    condln_agg_count--;
    if (condln_agg_count == 0) {
        $('#aggregatedField_head').remove();
    }
}

function isEmpty(obj) {
    if (obj == null) return true;
    if (obj.length > 0) return false;
    if (obj.length === 0) return true;
    if (typeof obj !== "object") return true;
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }
    return true;
}