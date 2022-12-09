<?php
function display_aggregated_fields($focus, $field, $value, $view)
{
    global $locale, $app_list_strings, $mod_strings;

    $html .= '<script src="modules/ht_kanban_views/js/aggregatedFields.js"></script>';
    $html .= "<table border='0' cellspacing='4' width='100%' id='aggregatedFields'></table>";

    $html .= "<div style='padding-top: 10px; padding-bottom:10px;'>";
    $html .= "<input type=\"button\" tabindex=\"116\" class=\"button\" value=\"Add Field\" id=\"btn_aggregatedFields\" onclick=\"insertaggregatedField()\"/>";
    $html .= "</div>";

    if (isset($focus->flow_module) && $focus->flow_module != '') {
        if ($focus->id != '') {
            $html .= "<script>";
            $sql = "SELECT aggregated_fields_data FROM ht_kanban_views WHERE id = '{$focus->id}' AND deleted = 0";
            $result = $focus->db->query($sql);
            while ($row = $focus->db->fetchByAssoc($result)) {
                $html .= "fields_data = \"".$row['aggregated_fields_data']."\";";
            }
            $html .= "</script>";
        }
    }
    return $html;
}