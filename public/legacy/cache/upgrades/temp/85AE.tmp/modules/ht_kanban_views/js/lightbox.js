$(document).ready(function() {
    $('#save_edit').on('click',function(e){
        e.preventDefault();
        var form = $('#add_edit_form')[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "index.php?module=ht_kanban_views&action=save_notes_and_assigned_user&sugar_body_only=true",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            async: true,
            beforeSend: function(){
                SUGAR.ajaxUI.showLoadingPanel();
            },
            success: function(data){
                $('.modal-custom-edit').modal("hide");
            },
            complete:function(){
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });
    });
});
function open_edit(obj){
    var id = $(obj).attr("id");
    $('.modal-custom-edit').modal("show");
    $('.modal-backdrop').remove();
    $('#record_id_to_save').val(id);
    $('#module_to_save').val($('#selected_module').val());
}