var kanbanData = "";
var users_options = "";
var users_options_values = "";
var current_user_image_id = "";
var comment_box_on = false;
var search_shown_records = 0;
$(document).ready(function () {
  $("#bootstrap-container > div:first-child").addClass("content-true");
  $(".module-title-text").hide();
  $(".favorite").hide();
  // $(".ht-title-body").draggable();
  var current_user_image_refrence = "";
  $(".content").click(function () {
    $(current_user_image_id).hide();
  });
  $("#dialog2").hide("");
  $("#dialog").hide("");
  getKanbanData();
  $("#kanban_type").on("keyup", function () {
    getKanbanData();
  });
  $("#show_more_records").click(function () {
    var current_module = $("#current_module").val();
    var kanban_type = $("#kanban_type").val();
    var kanban_project_class = $("#project_class").val();
    var project_manager = $("#project_manager").val();
    var project_accounts = $("#project_accounts").val();
    var record_id = $("#record_id").val();
    var record_ids = kanbanData.record_ids;
    data =
      "for_module=" +
      current_module +
      "&kanban_type=" +
      kanban_type +
      "&kanban_project_class=" +
      kanban_project_class +
      "&project_manager=" +
      project_manager +
      "&project_accounts=" +
      project_accounts +
      "&record_id=" +
      record_id +
      "&record_ids=" +
      record_ids;
    $.LoadingOverlay("show", { zIndex: 999999 });
    $.ajax({
      url: "index.php?module=ht_kanban_views&action=getKanbanData&sugar_body_only=true",
      type: "POST",
      dataType: "json",
      data: data,
      success: function (data) {
        $.LoadingOverlay("hide");
        ShowMoregenerateKanban(data);
        display_current_total_count();
      },
      error: function (data) {
        $.LoadingOverlay("hide");
      },
    });
  });
  $("#kanban_type_new").on("keyup", function () {
    var card_fields_count = parseInt($("#kanban_card_fields_lenght").val());
    var search = $(this).val();
    search = search.toLowerCase();
    var fields_to_judge = [];
    $(".search-data").each(function (i, obj) {
      var text = $(this).text();
      text = text.toLowerCase();
      fields_to_judge.push(text);
      if (fields_to_judge.length == card_fields_count) {
        var PATTERN = new RegExp("(.*)" + search + "(.*)");
        filtered = fields_to_judge.filter(function (str) {
          return PATTERN.test(str);
        });
        if (filtered.length > 0) {
          $(this).parent().parent().parent().show();
        } else {
          $(this).parent().parent().parent().hide();
        }
        fields_to_judge = [];
      }
    });
    if (search == "") {
      $(".search-data").parent().parent().parent().show();
    }
    search_shown_records = $(".card-ht-xt:visible").length;

    display_current_total_count(false);
  });
  $("#search_filter").click(function () {
    getKanbanData();
  });
  $.fn.slideFadeToggle = function (easing, callback) {
    return this.animate(
      { opacity: "toggle", height: "toggle" },
      "fast",
      easing,
      callback
    );
  };
});
function display_current_total_count(search = true) {
  if (search) {
    $(".shown_total_cards").html(
      "<span><span>" +
        kanbanData.record_ids.length +
        "</span><span> of </span><span><strong>" +
        kanbanData.total_count +
        "</strong></span>"
    );
    if (kanbanData.record_ids.length >= kanbanData.total_count) {
      $(".shown_total_cards").html(
        "<span><span><strong>" +
          kanbanData.record_ids.length +
          "</strong></span><span> of </span><span><strong>" +
          kanbanData.total_count +
          "</strong></span>"
      );
    }
  } else {
    $(".shown_total_cards").html(
      "<span><span><strong>" +
        search_shown_records +
        "</strong></span><span> of </span><span><strong>" +
        search_shown_records +
        "</strong></span>"
    );
  }
  var search_value = $("#kanban_type_new").val();
  if (
    search_value == "" &&
    search_shown_records < kanbanData.total_count &&
    search_shown_records != 0
  ) {
    $(".shown_total_cards").html(
      "<span><span>" +
        kanbanData.record_ids.length +
        "</span><span> of </span><span><strong>" +
        kanbanData.total_count +
        "</strong></span>"
    );
  }
}
function deselect(e, id) {
  $(".pop_" + id).slideFadeToggle(function () {
    e.removeClass("selected");
  });
}

function TaskListInitialize() {
  $(".grid-column").sortable({
    connectWith: ".grid-column",
    receive: function (e, ui) {
      var column_val = $(this).data("column_val");
      var frame_id = $(ui.item).data("frame_id");
      updateData(frame_id, column_val);
      return true;
    },
    stop: function (e, ui) {},
  });
}
function updateData(frame_id, column_val) {
  var current_module = $("#current_module").val();
  var field_name = $("#field_name").val();
  var selected_module = $("#selected_module").val();
  $.ajax({
    url: "index.php?module=ht_kanban_views&action=update_wo_status",
    type: "POST",
    contentType: "application/x-www-form-urlencoded",
    dataType: "text",
    data:
      "sugar_body_only=1&column_val=" +
      column_val +
      "&frame_id=" +
      frame_id +
      "&field_name=" +
      field_name +
      "&for_module=" +
      current_module +
      "&selected_module=" +
      selected_module,
    success: function (result) {
      $.Notify({
        caption: "Success",
        content: "Updated Successfully",
        type: "success",
      });
      var prev_obj = JSON.parse(
        decodeURIComponent(escape(atob($("#mock_" + frame_id).val())))
      );
      var prev_obj_agg = JSON.parse(
        decodeURIComponent(escape(atob($("#agg_" + frame_id).val())))
      );
      var prev_col = $("#list_item_" + frame_id).val();
      column_val = kanbanData.type_options_reverse[column_val];
      remove_card(prev_col, frame_id);
      add_card(column_val, prev_obj, prev_obj_agg, frame_id);
      $("#list_item_" + frame_id).val(column_val);
    },
  });
}
function add_card(coll, obj, obj_agg, id) {
  var type_options = kanbanData.type_options;
  var col = type_options[coll];
  var object1 = "";
  var object2 = "";
  $.each(obj, function (i, dd) {
    if (typeof dd != "undefined" && dd.id.value == id) {
      object1 = dd;
    }
  });
  $.each(obj_agg, function (i, dd) {
    if (typeof dd != "undefined" && dd.id.value == id) {
      object2 = dd;
    }
  });
  if (kanbanData.data[col]) {
    kanbanData.data[col].push(object1);
  } else {
    kanbanData.data[col] = [];
    kanbanData.data[col].push(object1);
  }
}
function remove_card(coll, id) {
  var type_options = kanbanData.type_options;
  var col = type_options[coll];
  if (typeof kanbanData.data[col] != "undefined") {
    $.each(kanbanData.data[col], function (i, content_body) {
      if (typeof content_body != "undefined" && content_body.id.value == id) {
        kanbanData.data[col].splice(i, 1);
        return;
      }
    });
  }
}
function getKanbanData() {
  var current_module = $("#current_module").val();
  var kanban_type = $("#kanban_type").val();
  var kanban_project_class = $("#project_class").val();
  var project_manager = $("#project_manager").val();
  var project_accounts = $("#project_accounts").val();
  var record_id = $("#record_id").val();
  data =
    "for_module=" +
    current_module +
    "&kanban_type=" +
    kanban_type +
    "&kanban_project_class=" +
    kanban_project_class +
    "&project_manager=" +
    project_manager +
    "&project_accounts=" +
    project_accounts +
    "&record_id=" +
    record_id;
  $.LoadingOverlay("show", { zIndex: 999999 });
  $.ajax({
    url: "index.php?module=ht_kanban_views&action=getKanbanData&sugar_body_only=true",
    type: "POST",
    dataType: "json",
    data: data,
    success: function (data) {
      $.LoadingOverlay("hide");
      users_options = data.users_images;
      users_options_values =
        '<option data-img_src="" value="" selected> Select User </option>';
      for (var key in users_options) {
        users_options_values +=
          '<option data-img_src="' +
          users_options[key].photo +
          '" value="' +
          key +
          '">' +
          users_options[key].tooltip +
          "</option>";
      }
      kanbanData = data;
      generateKanban(data);
      $(".long-text-read-more-card").readmore({
        speed: 100,
        collapsedHeight: 100,
        heightMargin: 16,
        moreLink: '<a href="#" class="popup-readmore-open">Read More</a>',
        lessLink: '<a href="#" class="popup-readmore-close">Close</a>',
        embedCSS: true,
        blockCSS: "display: block; width: 100%;",
        startOpen: false,
        blockProcessed: function () {},
        beforeToggle: function () {},
        afterToggle: function () {},
      });
      make_assign_user_options_alive();
      // run_smooth_scroller();
    },
    error: function (data) {
      $.LoadingOverlay("hide");
    },
  });
}

function getFrameHTML(htframe, block, list_item, agg) {
  var amount = "";
  var selected_module = $("#selected_module").val();
  if (selected_module == "Opportunities") {
    amount = "$" + htframe.amount_usdollar.value;
  }
  const not_show_fields = ["isfavourite", "Assigned User (ID)"];
  var html = "";
  var html_read_more = "";
  var user_name = "";
  var targeted_id = 0;
  var assigned_to_user = "";
  var assigned_to_user_id = "";
  var date_created = "";
  var avatar_image_source = "";
  var html_modal_view_popup =
    "<div id='modal_popup_" +
    htframe.id.value +
    "' style='margin-top:30px ;margin-left:15px;'>";
  html +=
    "<div  class='popup-detail-view' style='margin-bottom: -8%;cursor: pointer;'><input type='hidden' id='mock_" +
    htframe.id.value +
    "' value='" +
    btoa(unescape(encodeURIComponent(JSON.stringify(block)))) +
    "' />";
  html +=
    "<input type='hidden' id='agg_" +
    htframe.id.value +
    "' value='" +
    btoa(unescape(encodeURIComponent(JSON.stringify(agg)))) +
    "' />";
  html +=
    "<input type='hidden' id='list_item_" +
    htframe.id.value +
    "' value='" +
    list_item.col_name +
    "' />";
  html +=
    "<input type='hidden' id='current_frame_id' value='" +
    htframe.id.value +
    "' />";
  data_count = 0;
  if (typeof htframe.assigned_user_id != "undefined") {
    assigned_to_user_id = htframe.assigned_user_id.value;
  }
  var card_field_count = 0;
  $.each(htframe, function (i, content_body) {
    if (content_body.label != "Type" && content_body.label != "frame_heading") {
      if (content_body.value == null) {
        content_body.value = "";
      }
      if (assigned_to_user_id != "") {
        if (!isEmpty(assigned_to_user_id)) {
          assigned_to_user =
            typeof kanbanData.users_images[assigned_to_user_id] == "undefined"
              ? ""
              : kanbanData.users_images[assigned_to_user_id].tooltip;
          user_name =
            typeof kanbanData.users_images[assigned_to_user_id] == "undefined"
              ? ""
              : kanbanData.users_images[assigned_to_user_id].user_name;
        }
      }
      targeted_id = htframe.id.value;
      if (content_body.label == "ID") {
      } else if (content_body.label == "Date Created") {
        date_created = content_body.value;
      } else {
        var test = content_body.value;
        var long_text_read_more_popup =
          test.length > 200 ? "long-text-read-more-popup" : "";
        var long_text_read_more_card =
          test.length > 200 ? "long-text-read-more-card" : "";
        if (data_count != 8) {
          if (!not_show_fields.includes(content_body.label)) {
            html +=
              "<b>" +
              content_body.label +
              '	:	</b><p class="inside-data search-data ' +
              long_text_read_more_card +
              '">' +
              $("<textarea />").html(content_body.value).val() +
              "</p><br>";
            html_modal_view_popup +=
              "<br><b>" +
              content_body.label +
              '	:	</b><p class="inside-data search-data ' +
              long_text_read_more_popup +
              '">' +
              $("<textarea />").html(content_body.value).val() +
              "</p>";
            data_count = data_count + 1;
            card_field_count = card_field_count + 1;
          }
        } else {
          if (!not_show_fields.includes(content_body.label)) {
            html_read_more +=
              "<b>" +
              content_body.label +
              ':</b><p class="inside-data search-data ' +
              long_text_read_more_card +
              '">' +
              $("<textarea />").html(content_body.value).val() +
              "</p><br>";
            card_field_count = card_field_count + 1;
          }
        }
      }
    }
  });
  $("#kanban_card_fields_lenght").val(card_field_count);
  html += "</div>";
  html_modal_view_popup += "</div>";
  kanbanData[htframe.id.value] = html_modal_view_popup;
  var username_box = user_name;
  var time_box = date_created;
  timestamp_date = new Date(time_box);
  time_box = timestamp_date.toDateString().replace(/^\S+\s/, "");

  if (assigned_to_user.length != 0) {
    var tooltip_assignedUser_title = assigned_to_user;
  } else {
    tooltip_assignedUser_title = '"' + "No Assigned User" + '"';
  }

  if (html_read_more.length == 0) {
    html_read_more = "<p>No remaining Data</p>";
  }

  var title_box = htframe.frame_heading.value;
  var box_len = title_box.length;
  var tooltip_title = '"' + htframe.frame_heading.value + '"';

  if (box_len > 15) {
    title_box = title_box.slice(0, 15);
  }
  var fav_class = "fa fa-star-o";
  var fav_style = "";
  if (htframe.isfavourite.value != "0") {
    fav_class = "fa fa-star";
    fav_style = "color:#f08377";
  }
  if (typeof kanbanData.users_images[assigned_to_user_id] != "undefined") {
    avatar_image_source = kanbanData.users_images[assigned_to_user_id].photo;
  } else {
    avatar_image_source = "https://www.w3schools.com/howto/img_avatar.png";
  }
  if ($("#selected_module").val() != "Users") {
    return $("<div/>", {
      class: "accordion margin10  no-margin-left no-margin-right no-margin-top",
      "data-frame_id": htframe.id.value,
      "data-parent_row_id": htframe.account_id,
    }).append(
      $("<div/>", { class: "frame active card-ht-xt" }).append(
        $("<div/>", { class: "heading" }).append(
          '<span  data-role= "accordion" class=' +
            htframe.id.value +
            '" data-toggle="tooltip_box" title=' +
            tooltip_title +
            ">" +
            title_box +
            "</span>" +
            "<br>" +
            "<p style='margin-left: 55px;'><i onclick='check_favorite(this);' class='" +
            fav_class +
            "' style='" +
            fav_style +
            "' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_box(this);' class='fa fa-eye' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_edit(this);'  class='fa fa-pencil' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_activity(this);'  class='fa fa-wechat' id='" +
            targeted_id +
            "'></i></p>"
        ),
        $("<div/>", { class: "content" }).append(
          html +
            "<br><a data-id=" +
            htframe.id.value +
            ' style="margin-left: 65%;" onclick="open_downwards(this);" href="javascript:void(0)" class=' +
            htframe.id.value +
            ' ><i class="fa fa-chevron-down" style="float: right;"></i> </a>'
        ),
        $(
          '<div class="content show_extra_content  show_content_' +
            htframe.id.value +
            '"  style=""/>'
        ).append(html_read_more),
        $("<div/>", { class: "box_footer_style" }).append(
          '<div class="line"></div><div class="line_next_part"></div><div style="font-weight: 700;" class="box_footer_style_span" ><i style="font-weight: bold;" class="fa fa-hand-o-up"></i><span class="fa-hand-user' +
            htframe.id.value +
            '">&nbsp;' +
            username_box +
            '&nbsp;</span><span style="font-size: 86%;">(' +
            time_box +
            ')</span></div><span  data-toggle="tooltip_box" id="tooltip_box_' +
            htframe.id.value +
            '" title="' +
            tooltip_assignedUser_title +
            '"><div class="messagepop pop_' +
            htframe.id.value +
            '"> <select class="user-assign-class" id="user_assing_' +
            htframe.id.value +
            '">' +
            users_options_values +
            '</select> </div><img id="contact' +
            htframe.id.value +
            '" class="avatar_styling zoom" style="" src="' +
            avatar_image_source +
            '"></span>'
        )
      )
    );
  } else {
    return $("<div/>", {
      class: "accordion margin10  no-margin-left no-margin-right no-margin-top",
      "data-role": "accordion",
      "data-frame_id": htframe.id.value,
      "data-parent_row_id": htframe.account_id,
    }).append(
      $("<div/>", { class: "frame active " }).append(
        $("<div/>", { class: "heading" }).append(
          '<span class="tooltip_style" data-toggle="tooltip_box" title=' +
            tooltip_title +
            ">" +
            title_box +
            "</span>" +
            "<p style='float: right;'><i onclick='check_favorite(this);' class='" +
            fav_class +
            "' style='" +
            fav_style +
            "' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_box(this);' class='fa fa-eye' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_edit(this);'  class='fa fa-pencil' id='" +
            targeted_id +
            "'></i>&nbsp;&nbsp;<i onclick='open_activity(this);'  class='fa fa-wechat' id='" +
            targeted_id +
            "'></i></p>"
        ),

        $("<div/>", { class: "content" }).append(
          html +
            "<br><a data-id=" +
            htframe.id.value +
            ' style="margin-left: 65%;" onclick="open_downwards(this);" href="javascript:void(0)" class=' +
            htframe.id.value +
            ' ><i class="fa fa-chevron-down" style="float: right;"></i> </a>'
        ),
        $(
          '<div class="content show_extra_content  show_content_' +
            htframe.id.value +
            '"  style="display: none;"/>'
        ).append(html_read_more),
        $("<div/>", { class: "box_footer_style_non_user" }).append("")
      )
    );
  }
}
function check_favorite(obj) {
  var fav_class = $(obj).attr("class");
  $(".frame");
  var del = false;
  var url = "";
  if (fav_class == "fa fa-star-o") {
    del = false;
    $(obj).attr("class", "");
    $(obj).toggleClass("fa fa-star");
    $(obj).css("color", "#f08377");
    var url = "index.php?module=Favorites&action=create_record&to_pdf=true";
  }
  if (fav_class == "fa fa-star") {
    del = true;
    $(obj).attr("class", "");
    $(obj).toggleClass("fa fa-star-o");
    $(obj).css("color", "");
    var url = "index.php?module=Favorites&action=remove_record&to_pdf=true";
  }
  var id = $(obj).attr("id");
  var module = $("#selected_module").val();
  $.ajax({
    type: "POST",
    enctype: "multipart/form-data",
    url: url,
    data: { record_id: id, record_module: module },
    async: true,
    beforeSend: function () {
      SUGAR.ajaxUI.showLoadingPanel();
    },
    success: function (data) {},
    complete: function () {
      SUGAR.ajaxUI.hideLoadingPanel();
    },
  });
}

function get_record_id() {
  url = $(location).attr("href");
  parser = new URL(url);
  params = parser.searchParams + "<br>";
  params_to_array = params.split("&");
  record = params_to_array[5].slice(7);
  final_record_id = record.slice(0, record.length - 4);

  return final_record_id;
}
function deletecomment(obj) {
  var selected_module = $("#selected_module").val();
  var id = $(obj).attr("id");
  var paraent_id = $(obj).attr("data-id");
  $.LoadingOverlay("show", { zIndex: 9999999999999 });
  $.ajax({
    type: "GET",
    url: "index.php?module=ht_kanban_views&action=delete_comment",
    data: {
      record_id: id,
      paraent_id: paraent_id,
      selected_module: selected_module,
    },
    async: true,
    beforeSend: function () {
      SUGAR.ajaxUI.showLoadingPanel();
    },

    success: function (data) {
      $.LoadingOverlay("hide");

      var parsed = JSON.parse(data);

      var html = "";
      $.each(parsed, function (index, value) {
        if (value.subject) {
          html +=
            '<div class="js-list-actions mod-card-back"  ><div class="phenom mod-comment-type"><div class="phenom-creator"><div class="member js-show-mem-menu"><span class="member-initials" title="' +
            value.user_name +
            " (" +
            value.user +
            ')" aria-label="' +
            value.user_name +
            " (" +
            value.user +
            ')">' +
            value.user_name_initials +
            '</span></div></div><div class="phenom-desc"><span class="inline-member js-show-mem-menu" ><span class="u-font-weight-bold">' +
            value.user_name +
            '</span></span> <span class="inline-spacer"> </span><span class="phenom-date quiet">' +
            value.ago +
            '</span><div class="comment-container"><div class="action-comment can-edit markeddown js-comment" dir="auto"><div class="current-comment js-friendly-links js-open-card"><p>' +
            value.description +
            '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' +
            value.link +
            '" target="_blank" rel="noreferrer nofollow" style="border-bottom: 1px solid;">' +
            value.filename +
            "</a><button id=" +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="deletecomment(this);" class = "del" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 50px; ">DELETE</button><button id=' +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="editcomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 10px; ">EDIT</button></p></div></div></div></div></div></div>';
        }
      });
      $(".js-list-actions-madam").html(html);
    },
    complete: function () {
      SUGAR.ajaxUI.hideLoadingPanel();
    },
  });
}

function editcomment(obj) {
  var id = $(obj).attr("id");
  var paraent_id = $(obj).attr("data-id");
  $.LoadingOverlay("show", { zIndex: 9999999999999 });
  $.ajax({
    type: "GET",
    url: "index.php?module=ht_kanban_views&action=edit_comment",
    data: { record_id: id, paraent_id: paraent_id },
    async: true,
    beforeSend: function () {
      SUGAR.ajaxUI.showLoadingPanel();
    },
    success: function (data) {
      $.LoadingOverlay("hide");
      var parsed = JSON.parse(data);
      $("#comment_box_z").val(parsed.description);
      $(".placeholder-path-file").html(parsed.filename);
      $(".comment-controls").hide();
      var html = " ";
      html +=
        '<div class="comment-controls u-clearfix updaterecd"><button class=" primary confirm mod-no-top-bottom-margin js-add-comment" id=' +
        parsed.id +
        " data-id = " +
        parsed.parent_id +
        ' onclick="updatecomment(this);">Save</button></div>';
      $(".save_button").html(html);
    },
    complete: function () {
      SUGAR.ajaxUI.hideLoadingPanel();
    },
  });
}

function updatecomment(obj) {
  var filesss = $("#note_attachement")[0].files[0];
  var id = $(obj).attr("id");
  var paraent_id = $(obj).attr("data-id");
  var text_comments = $("#comment_box_z").val();
  var selected_module = $("#selected_module").val();
  var form = $("#add_edit_form")[0];
  var data = new FormData(form);
  var files = $("#note_attachement")[0].files[0];
  data.append("file", files);
  data.append("record_id", id);
  data.append("paraent_id", paraent_id);
  data.append("text_comments", text_comments);
  data.append("selected_module", selected_module);
  data.append("filesss", filesss);
$.LoadingOverlay("show", { zIndex: 9999999999999 });
  $.ajax({
    type: "POST",
    url: "index.php?module=ht_kanban_views&action=update_comment",
    data: data,
    processData: false,
    contentType: false,
    cache: false,
    async: true,
    beforeSend: function () {
      SUGAR.ajaxUI.showLoadingPanel();
    },
    success: function (data) {
      $.LoadingOverlay("hide");
      $(".placeholder-path-file").html(" ");
      $(".comment-controls").show();
      $(".updaterecd").hide();
      var parsed = JSON.parse(data);
      var html = "";
      $.each(parsed, function (index, value) {
        if (value.subject) {
          html +=
            '<div class="js-list-actions mod-card-back"  ><div class="phenom mod-comment-type"><div class="phenom-creator"><div class="member js-show-mem-menu"><span class="member-initials" title="' +
            value.user_name +
            " (" +
            value.user +
            ')" aria-label="' +
            value.user_name +
            " (" +
            value.user +
            ')">' +
            value.user_name_initials +
            '</span></div></div><div class="phenom-desc"><span class="inline-member js-show-mem-menu" ><span class="u-font-weight-bold">' +
            value.user_name +
            '</span></span> <span class="inline-spacer"> </span><span class="phenom-date quiet">' +
            value.ago +
            '</span><div class="comment-container"><div class="action-comment can-edit markeddown js-comment" dir="auto"><div class="current-comment js-friendly-links js-open-card"><p>' +
            value.description +
            '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' +
            value.link +
            '" target="_blank" rel="noreferrer nofollow" style="border-bottom: 1px solid;">' +
            value.filename +
            "</a><button id=" +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="deletecomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 50px; ">DELETE</button><button id=' +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="editcomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 10px; ">EDIT</button></p></div></div></div></div></div></div>';
        }
      });
      $(".js-list-actions-madam").html(html);
    },
    complete: function () {
      document.getElementById("add_edit_form").reset();
      SUGAR.ajaxUI.hideLoadingPanel();
    },
  });
}
function open_activity(obj) {
  var selected_module = $("#selected_module").val();
  if (selected_module != "Users") {
    var id = $(obj).attr("id");
    $(".modal-custom-edit").modal("show");
    $(".modal-backdrop").remove();
    $("#record_id_to_save").val(id);
    $("#module_to_save").val($("#selected_module").val());
    $.ajax({
      type: "POST",
      enctype: "multipart/form-data",
      url: "index.php?module=ht_kanban_views&action=save_and_get_kanban_notes&sugar_body_only=true&notes_action=get_notes",
      data: {
        module_to_save: $("#selected_module").val(),
        record_id_to_save: id,
      },
      async: true,
      beforeSend: function () {
        SUGAR.ajaxUI.showLoadingPanel();
      },
      success: function (data) {
        var parsed = JSON.parse(data);
        var html = "";
        $.each(parsed, function (index, value) {
          html +=
            '<div class="js-list-actions mod-card-back" id = ' +
            value.id +
            '><div class="phenom mod-comment-type"><div class="phenom-creator"><div class="member js-show-mem-menu"><span class="member-initials" title="' +
            value.user_name +
            " (" +
            value.user +
            ')" aria-label="' +
            value.user_name +
            " (" +
            value.user +
            ')">' +
            value.user_name_initials +
            '</span></div></div><div class="phenom-desc"><span class="inline-member js-show-mem-menu" ><span class="u-font-weight-bold">' +
            value.user_name +
            '</span></span> <span class="inline-spacer"> </span><span class="phenom-date quiet">' +
            value.ago +
            '</span><div class="comment-container"><div class="action-comment can-edit markeddown js-comment" dir="auto"><div class="current-comment js-friendly-links js-open-card"><p>' +
            value.description +
            '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' +
            value.link +
            '" target="_blank" rel="noreferrer nofollow" style="border-bottom: 1px solid;">' +
            value.filename +
            "</a><button id=" +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="deletecomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 50px; ">DELETE</button><button  id=' +
            value.id +
            " data-id = " +
            value.parent_id +
            ' onclick="editcomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 10px; ">EDIT</button></p></div></div></div></div></div></div>';
        });
        $(".js-list-actions-madam").html(html);
        var fields_html = kanbanData[id];

        $(".catch-detail-content").html(fields_html);

        $(".modal-custom-edit").draggable();
        comment_box_on = true;
        $(".long-text-read-more-popup").readmore({
          speed: 100,
          collapsedHeight: 100,
          heightMargin: 16,
          moreLink: '<a href="#" class="popup-readmore-open">Read More</a>',
          lessLink: '<a href="#" class="popup-readmore-close">Close</a>',
          embedCSS: true,
          blockCSS: "display: block; width: 100%;",
          startOpen: false,
          blockProcessed: function () {},
          beforeToggle: function () {},
          afterToggle: function () {},
        });
      },
      complete: function () {
        SUGAR.ajaxUI.hideLoadingPanel();
      },
    });
  }
}

function open_box(obj) {
  var frame_id = $(obj).attr("id");
  window.open(
    "index.php?module=" +
      $("#selected_module").val() +
      "&action=DetailView&record=" +
      frame_id,
    "_blank"
  );
  if ($("." + frame_id).hasClass("active") == true) {
    $("." + frame_id).addClass("active");
  } else {
    $("." + frame_id).addClass("active");
    $("." + frame_id).removeClass("active");
  }
}
$(function () {
  $('[data-toggle="tooltip_box"]').tooltip({
    container: "body",
    tooltipClass: "check_tooltip",
  });

  var outer_scrool_width = $(window).outerWidth();
  var window_width = $(window).width();
  var total = outer_scrool_width + window_width;

  //Change box color background
  $("#content").css("border", "#f5f5f5").change();
  $("#content").css("background", "#f5f5f5").change();
  $("#content").css("box-shadow", "0px 0px 0px").change();

  $(".footer_right").css("visibility", "hidden");
  $(".expandedSidebar").css("background-color", "#f5f5f5");
});
function getColumnHTML(frame_list, columnHTML, list_item, agg) {
  if (typeof frame_list != "undefined") {
    $.each(frame_list, function (i, frame_item) {
      columnHTML.append(getFrameHTML(frame_item, frame_list, list_item, agg));
    });
  }
  return columnHTML;
}

function getTitleHTML(title_list) {
  var titleHTML = $("<tr/>", { class: "grid-row header" });
  var tooltip = "";
  var col_name = "";
  $.each(title_list, function (i, item) {
    if (item.col_name) {
      col_name = item.col_name;
    }
    var html =
      '<th class="kanban_heading"   data-toggle="modal" data-target="#myModal" id = ' +
      (col_name || "null") +
      " data-id = " +
      (col_name || "null") +
      ">";
    if (i != "") {
      html +=
        '<span  data-toggle="tooltip_box_span" title="" id="' +
        make_id_function(item.col_name) +
        'ht">' +
        item.col_name +
        '&nbsp;<i  class="fa fa-info-circle"></i></span><input type="hidden" id="' +
        make_id_function(item.col_name) +
        'ht_" value="' +
        item.col_name +
        '"></span>';
      ("</th><br><br>");
    } else {
      html +=
        '<span   data-toggle="tooltip_box_span" title="" id="' +
        make_id_function("empty") +
        'ht">' +
        "" +
        '&nbsp;<i class="fa fa-info-circle"></i></span><input type="hidden" id="' +
        make_id_function("empty") +
        'ht_" value="' +
        "" +
        '"></span>';
      ("</th><br><br>");
    }
    titleHTML.append(html);
    $("#tooltip_display").html("");
    $("#tooltip_display").append(tooltip);
  });
  return titleHTML;
}

function open_downwards(obj) {
  $(".show_content_" + $(obj).attr("data-id")).toggle();

  if ($(".show_content_" + $(obj).attr("data-id")).is(":visible")) {
    $(obj).html('<i class="fa fa-chevron-up" style="float: right;"></i>');
  } else {
    $(obj).html('<i class="fa fa-chevron-down" style="float: right;"></i>');
  }
}

function ShowMoregenerateKanban(show_more_kanban_data) {
  //POPULATING CARDS
  var title_list = show_more_kanban_data.title_list;
  var block_data = show_more_kanban_data.data;
  var block_data_agg = show_more_kanban_data.aggregated_record_data;
  $.each(title_list, function (i, list_item) {
    if (block_data[i]) {
      var columnHTML = $("<td/>", {
        class: "grid-column tasks ui-sortable  ui-droppable",
        "data-column_val": i,
        style: "text-align: left;",
      });
      var card = getColumnHTML(
        block_data[i],
        columnHTML,
        list_item,
        block_data_agg[i]
      );
      $('#kanbanData tr td[data-column_val="' + i + '"]').append(card.html());
    }
  });
  //POPULATING KANABAN OBJECT
  //ADDING NEW REDCORDS IDS
  kanbanData.record_ids = [].concat(
    kanbanData.record_ids,
    show_more_kanban_data.record_ids
  );

  //ADDING AGGREGATED DATA
  var tempKanbanData = kanbanData;
  $.each(
    tempKanbanData.aggregated_record_data,
    function (old_title, old_data_array) {
      $.each(
        show_more_kanban_data.aggregated_record_data,
        function (new_title, new_data_array) {
          if (new_title == old_title) {
            var merged_array = [].concat(old_data_array, new_data_array);
            kanbanData.aggregated_record_data[old_title] = merged_array;
          }
        }
      );
    }
  );
  //ADDING DATA
  $.each(tempKanbanData.data, function (old_title, old_data_array) {
    $.each(show_more_kanban_data.data, function (new_title, new_data_array) {
      if (new_title == old_title) {
        var merged_array = [].concat(old_data_array, new_data_array);
        kanbanData.data[old_title] = merged_array;
      }
    });
  });
  //TRIGGEREING NOTES SECTION
  trigger_notes_section();
}

function generateKanban(kanban_data) {
  var kanbanHTML = $("<table/>", { class: "task-grid", id: "kanbanData" });
  var title_list = kanban_data.title_list;
  kanbanHTML.append($("<thead/>").append(getTitleHTML(title_list, kanbanData)));
  var kanbanTbodyHTML = $("<tbody/>");
  var block_data = kanban_data.data;
  var block_data_agg = kanban_data.aggregated_record_data;
  var kanbanRowHTML = $("<tr/>", { class: "grid-row", id: "" });
  $.each(title_list, function (i, list_item) {
    var columnHTML = $("<td/>", {
      class: "grid-column tasks ui-sortable  ui-droppable",
      "data-column_val": i,
      style: "text-align: left;",
    });
    kanbanRowHTML.append(
      getColumnHTML(block_data[i], columnHTML, list_item, block_data_agg[i])
    );
  });
  kanbanTbodyHTML.append(kanbanRowHTML);
  kanbanHTML.append(kanbanTbodyHTML);
  $("div.task-grid-container").html(kanbanHTML);
  TaskListInitialize();
  $(".kanban_heading").on("click", function () {
    if (!comment_box_on) {
      var id = this.id;
      var aggregate_records_ids = [];
      var type_options = kanbanData.type_options;
      var data = kanbanData.data;
      var titles = $("#" + id).attr("data-id");
      if (titles != "null") {
        $(
          '#kanbanData tr td[data-column_val="' +
            titles +
            '"] .accordion .card-ht-xt:visible , #kanbanData tr td[data-column_val="' +
            type_options[titles] +
            '"] .accordion .card-ht-xt:visible'
        ).each(function (i, obj) {
          var id = $(this).parent().attr("data-frame_id");
          aggregate_records_ids.push(id);
        });
      }
      if (titles == "null") {
        $.each(data, function (i, list_item) {
          $.each(list_item, function (i, item) {
            if (isEmpty(item["account_type"]["value"])) {
              var id = item["id"]["value"];
              aggregate_records_ids.push(id);
            }
          });
        });
      }
      var aggregated_combination = kanbanData.aggregated_data;
      var aggregated_combination_lenght = Object.keys(aggregated_combination);
      if (
        aggregate_records_ids.length > 0 &&
        aggregated_combination_lenght.length > 0 &&
        kanbanData.enable_disable_aggregate_data_popup == "enable"
      ) {
        $.ajax({
          url: "index.php?module=ht_kanban_views&action=getKanbaAggregatedValues&sugar_body_only=true",
          type: "POST",
          dataType: "json",
          data: {
            data: data,
            aggregate_records_ids: aggregate_records_ids,
            aggregate_data: aggregated_combination,
            kanban_record_id: $("#record_id").val(),
            selected_module: $("#selected_module").val(),
          },
          async: true,
          beforeSend: function () {},
          success: function (data) {
            console.log(data);
            $(".modal-heading").modal("show");
            var title = "";
            if (data.status) {
              $.each(data.data, function (i, j) {
                if (i != "COUNT") {
                  var sub_title = "";
                  //   title += "<strong>" + i + "</strong><br>";
                  $.each(j, function (k, l) {
                    sub_title += "<strong>" + k + " = " + l + "</strong><br>";
                  });
                  title += sub_title;
                } else {
                  title += "<strong>" + i + " = " + j + "</strong><br>";
                }
              });
            } else {
              title = "No Data";
            }
            $(".header-agregate").html(title);
          },
          complete: function () {
            $.unblockUI();
          },
        });
      }
    }
  });
  $(".avatar_styling").on("click", function () {
    var id_wit_text = $(this).attr("id");
    var id_text = "contact";
    var id = id_wit_text.replace(id_text, "");
    if ($(this).hasClass("selected")) {
      deselect($(this), id);
    } else {
      $(this).addClass("selected");
      $(".pop_" + id).slideFadeToggle();
      $("#user_assing_" + id).select2("open");
    }
    current_user_image_id = ".pop_" + id;
    current_user_image_refrence = $(this);
    return false;
  });
  trigger_notes_section();
}
$("#tooltip_box_span").tooltip();
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
function make_id_function(str) {
  return str
    .toLowerCase()
    .replace(/\s/g, "")
    .replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, "");
}
function make_assign_user_options_alive() {
  $(".user-assign-class").on("change", function () {
    var id_wit_text = $(this).attr("id");
    var id_text = "user_assing_";
    var id = id_wit_text.replace(id_text, "");
    var value = $(this).val();
    var new_src = users_options[value].photo;
    var module = $("#selected_module").val();
    $.ajax({
      type: "POST",
      enctype: "multipart/form-data",
      url: "index.php?module=ht_kanban_views&action=save_assigned_user",
      data: { record_id: id, record_module: module, user_id: value },
      async: true,
      beforeSend: function () {
        SUGAR.ajaxUI.showLoadingPanel();
      },
      success: function (data) {},
      complete: function () {
        SUGAR.ajaxUI.hideLoadingPanel();
      },
    });

    $("#contact" + id).click();
    $("#contact" + id).attr("src", new_src);
    $(".fa-hand-user" + id).html(users_options[value].user_name);
    $("#tooltip_box_" + id).attr("title", users_options[value].tooltip);
  });
  function custom_template(obj) {
    var data = $(obj.element).data();
    var text = $(obj.element).text();
    if (data && data["img_src"]) {
      img_src = data["img_src"];
      template = $(
        "<div><img  src='" +
          img_src +
          "' style=width:40px;height:30px;/> <span style=text-align:center;>" +
          text +
          "</span></div>"
      );
      return template;
    }
  }
  var options = {
    templateSelection: custom_template,
    templateResult: custom_template,
    placeholder: "Select a User",
  };
  $(".user-assign-class").select2(options);
}

function autosize() {
  var el = this;
  setTimeout(function () {
    el.style.cssText = "height:auto; padding:0";
    el.style.cssText = "height:" + el.scrollHeight + "px";
  }, 0);
}
function open_edit(obj) {
  var id = $(obj).attr("id");
  window.open(
    "index.php?module=" +
      $("#selected_module").val() +
      "&action=EditView&record=" +
      id,
    "_blank"
  );
}
function showname() {
  var name = document.getElementById("note_attachement");
  var file_name = name.files.item(0).name;
  if (name.files.item(0).size > 5000000) {
    $("#note_attachement").val("");
    swal(
      "Oops",
      "File size is to large, please make sure it is less that 5MB.",
      "error"
    );
  } else {
    $(".placeholder-path-file").html(file_name);
  }
}

$(document).ready(function () {
  //saving notes
  $("#save_edit").on("click", function (e) {
    e.preventDefault();
    var form = $("#add_edit_form")[0];
    var data = new FormData(form);
    var files = $("#note_attachement")[0].files[0];
    data.append("file", files);
    $.LoadingOverlay("show", { zIndex: 9999999999999 });
    $.ajax({
      type: "POST",
      enctype: "multipart/form-data",
      url: "index.php?module=ht_kanban_views&action=save_and_get_kanban_notes&sugar_body_only=true&notes_action=save_notes",
      data: data,
      processData: false,
      contentType: false,
      async: true,
      cache: false,
      timeout: 30000,
      success: function (data) {
        $.LoadingOverlay("hide");

        var parsed = JSON.parse(data);
        var html = "";
        $.each(parsed, function (index, value) {
          html +=
            '<div class="js-list-actions mod-card-back id = ' +
            value.subject +
            '"><div class="phenom mod-comment-type"><div class="phenom-creator"><div class="member js-show-mem-menu"><span class="member-initials" title="' +
            value.user_name +
            " (" +
            value.user +
            ')" aria-label="' +
            value.user_name +
            " (" +
            value.user +
            ')">' +
            value.user_name_initials +
            '</span></div></div><div class="phenom-desc"><span class="inline-member js-show-mem-menu" ><span class="u-font-weight-bold">' +
            value.user_name +
            '</span></span> <span class="inline-spacer"> </span><span class="phenom-date quiet">' +
            value.ago +
            '</span><div class="comment-container"><div class="action-comment can-edit markeddown js-comment" dir="auto"><div class="current-comment js-friendly-links js-open-card"><p>' +
            value.description +
            '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' +
            value.link +
            '" target="_blank" rel="noreferrer nofollow" style="border-bottom: 1px solid;">' +
            value.filename +
            "</a><button id=" +
            value.subject +
            " data-id = " +
            value.parent_id +
            ' onclick="deletecomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 50px; ">DELETE</button><button id=' +
            value.subject +
            " data-id = " +
            value.parent_id +
            ' onclick="editcomment(this);" style = "float:right; background: #a94442; color: #f3f3f3; margin-right: 10px; ">EDIT</button></p></div></div></div></div></div></div>';
        });
        $(".js-list-actions-madam").prepend(html);
      },
      complete: function () {
        document.getElementById("add_edit_form").reset();
        $(".placeholder-path-file").html("");
      },
    });
  });
  //getting notes
  setTimeout(function () {
    trigger_notes_section();
    display_current_total_count();
  }, 3000);

  //reached at the end
  // $(window).scroll(function () {
  //   if (
  //     Math.floor($(window).scrollTop() + $(window).height()) ==
  //     Math.floor($(document).height())
  //   ) {
  //     if (kanbanData.record_ids.length < kanbanData.total_count) {
  //       var current_module = $("#current_module").val();
  //       var kanban_type = $("#kanban_type").val();
  //       var kanban_project_class = $("#project_class").val();
  //       var project_manager = $("#project_manager").val();
  //       var project_accounts = $("#project_accounts").val();
  //       var record_id = $("#record_id").val();
  //       var record_ids = kanbanData.record_ids;
  //       data =
  //         "for_module=" +
  //         current_module +
  //         "&kanban_type=" +
  //         kanban_type +
  //         "&kanban_project_class=" +
  //         kanban_project_class +
  //         "&project_manager=" +
  //         project_manager +
  //         "&project_accounts=" +
  //         project_accounts +
  //         "&record_id=" +
  //         record_id +
  //         "&record_ids=" +
  //         record_ids;
  //       $.LoadingOverlay("show", { zIndex: 999999 });
  //       $.ajax({
  //         url: "index.php?module=ht_kanban_views&action=getKanbanData&sugar_body_only=true",
  //         type: "POST",
  //         dataType: "json",
  //         data: data,
  //         success: function (data) {
  //           $.LoadingOverlay("hide");
  //           ShowMoregenerateKanban(data);
  //           display_current_total_count();
  //           setTimeout(function () {
  //             $("#kanban_type_new").keyup();
  //           }, 1000);
  //         },
  //         error: function (data) {
  //           $.LoadingOverlay("hide");
  //         },
  //       });
  //     }
  //   }
  // });
});

function trigger_notes_section() {
  $(".modal-custom-edit").on("hide.bs.modal", function () {
    $("#add_edit_form")[0].reset();
    $(".placeholder-path-file").html("");
    comment_box_on = false;
  });
}

//Smooth Scrolling
var edgeSize = 100;
var timer = null;
function run_smooth_scroller() {
  if (kanbanData["enable_disable_smooth_scrolling"] == "enable") {
    window.addEventListener("mousemove", handleMousemove, false);
    function handleMousemove(event) {
      if (!comment_box_on) {
        var viewportX = event.clientX;
        var viewportY = event.clientY;

        var viewportWidth = document.documentElement.clientWidth;
        var viewportHeight = document.documentElement.clientHeight;

        var edgeTop = edgeSize;
        var edgeLeft = edgeSize;
        var edgeBottom = viewportHeight - edgeSize;
        var edgeRight = viewportWidth - edgeSize;

        var isInLeftEdge = viewportX < edgeLeft;
        var isInRightEdge = viewportX > edgeRight;
        var isInTopEdge = viewportY < edgeTop;
        var isInBottomEdge = viewportY > edgeBottom;

        if (!(isInLeftEdge || isInRightEdge || isInTopEdge || isInBottomEdge)) {
          clearTimeout(timer);
          return;
        }

        var documentWidth = Math.max(
          document.body.scrollWidth,
          document.body.offsetWidth,
          document.body.clientWidth,
          document.documentElement.scrollWidth,
          document.documentElement.offsetWidth,
          document.documentElement.clientWidth
        );
        var documentHeight = Math.max(
          document.body.scrollHeight,
          document.body.offsetHeight,
          document.body.clientHeight,
          document.documentElement.scrollHeight,
          document.documentElement.offsetHeight,
          document.documentElement.clientHeight
        );

        var maxScrollX = documentWidth - viewportWidth;
        var maxScrollY = documentHeight - viewportHeight;

        (function checkForWindowScroll() {
          clearTimeout(timer);

          if (adjustWindowScroll()) {
            timer = setTimeout(checkForWindowScroll, 30);
          }
        })();

        function adjustWindowScroll() {
          var currentScrollX = window.pageXOffset;
          var currentScrollY = window.pageYOffset;

          var canScrollUp = currentScrollY > 0;
          var canScrollDown = currentScrollY < maxScrollY;
          var canScrollLeft = currentScrollX > 0;
          var canScrollRight = currentScrollX < maxScrollX;

          var nextScrollX = currentScrollX;
          var nextScrollY = currentScrollY;

          var maxStep = 10;

          if (isInLeftEdge && canScrollLeft) {
            var intensity = (edgeLeft - viewportX) / edgeSize;
            nextScrollX = nextScrollX - maxStep * intensity;
          } else if (isInRightEdge && canScrollRight) {
            var intensity = (viewportX - edgeRight) / edgeSize;
            nextScrollX = nextScrollX + maxStep * intensity;
          }

          if (isInTopEdge && canScrollUp) {
            var intensity = (edgeTop - viewportY) / edgeSize;
            nextScrollY = nextScrollY - maxStep * intensity;
          } else if (isInBottomEdge && canScrollDown) {
            var intensity = (viewportY - edgeBottom) / edgeSize;
            nextScrollY = nextScrollY + maxStep * intensity;
          }

          nextScrollX = Math.max(0, Math.min(maxScrollX, nextScrollX));
          lY = Math.max(0, Math.min(maxScrollY, nextScrollY));
          if (
            nextScrollX !== currentScrollX ||
            nextScrollY !== currentScrollY
          ) {
            window.scrollTo(nextScrollX, nextScrollY);
            return true;
          } else {
            return false;
          }
        }
      }
    }
  }
}
