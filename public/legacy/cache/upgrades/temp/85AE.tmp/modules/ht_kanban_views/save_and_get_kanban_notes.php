<?php
global $sugar_config,$curren_user,$timedate,$log;
$action = $_REQUEST['notes_action'];
$id = $_REQUEST['record_id_to_save'];
$module = $_REQUEST['module_to_save'];

$bean = BeanFactory::getBean($module, $id);
$data = array();
if($action=='save_notes'){
    $note = BeanFactory::newBean('Notes');
    $note->created_by = $curren_user->id;
    $note->description = $_REQUEST['comment_box_z'];
    $note->parent_type = $module;
    $note->name = "This is a auto-genertated note from kanaban.";
    if($module=="Contacts"){
        $note->contact_id = $id;
    }
    $note->parent_id = $id;
    $note->processed = true;
    $note->save();
    $is_saved = save_file($_FILES,$note->id);
    $data[$note->id]['subject'] = $note->name;
    $data[$note->id]['subject'] = $note->id;
    $data[$note->id]['parent_id'] = $note->parent_id;
    $data[$note->id]['description'] = $note->description;
    $data[$note->id]['ago'] = 'moments ago';
    $user = BeanFactory::getBean('Users', $note->created_by);
    $data[$note->id]['user_name'] = $user->first_name .' '.$user->last_name;
    $data[$note->id]['user_name_initials'] = get_intials($user);
    $data[$note->id]['user'] = $user->user_name;
    if($is_saved){
        $data[$note->id]['filename'] = $note->filename;
        $data[$note->id]['link'] = $sugar_config['site_url']."/index.php?entryPoint=download&id=".$note->id."&type=Notes&preview=yes";
    }else{
        $data[$note->id]['filename'] = '';
        $data[$note->id]['link'] = '';
    }
    echo json_encode($data);die;
}elseif($action=='get_notes'){
    if($bean->load_relationship('notes')){
        $notes = $bean->get_linked_beans(
            'notes',
            'Notes',
            'date_entered DESC',
            0,
            '',
            0,
            "");
        foreach($notes as $key=>$note){
        
            $data[$key]['subject'] = $note->name;
            $data[$key]['parent_id'] = $note->parent_id;
            $data[$key]['id'] = $note->id;
            $data[$key]['description'] = $note->description;
            $data[$key]['ago'] = timeago($note->date_entered);
            $data[$key]['filename'] = $note->filename;
            $user = BeanFactory::getBean('Users', $note->created_by);
            $data[$key]['user_name'] = $user->first_name .' '.$user->last_name;
            $data[$key]['user_name_initials'] = get_intials($user);
            $data[$key]['user'] = $user->user_name;
            $data[$key]['link'] = $sugar_config['site_url']."/index.php?entryPoint=download&id=".$note->id."&type=Notes&preview=yes";
        }
    }
    echo json_encode($data);die;
}
function save_file($files,$id){
    if ($files['file']){
        require_once('include/upload_file.php');
        $note = BeanFactory::getBean('Notes', $id);
        $mime_type = $files['file']['type'];
        $filename= $files['file']['name'];
        $contents = file_get_contents($files['file']['tmp_name']);
        $note->filename_file = $contents;
        $note->filename = $filename;
        $note->processed = true;
        $note->save();
        $uploadfile = 'upload://'.$note->id;
        $res = move_uploaded_file($files['file']['tmp_name'],$uploadfile);
        $destination = ('upload/'.$note->id);
        $fp = fopen($destination, 'w+');
        if (!fwrite($fp,  $contents)) {
            die("ERROR: can't save file to $destination");
        }
        return true;
    }
    return false;
}
function get_intials($user){
   $first = strtoupper(mb_substr($user->first_name, 0, 1, "UTF-8"));
   $last = strtoupper(mb_substr($user->last_name, 0, 1, "UTF-8"));
   return $first.''.$last;
}
function timeago($date){
    $timestamp = strtotime($date);
    $strTime = array("second", "minute", "hour", "day", "month", "year");
    $length = array("60","60","24","30","12","10");
    $currentTime = time();
    if($currentTime >= $timestamp){
        $diff     = time()- $timestamp;
        for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++){
            $diff = $diff / $length[$i];
        }
        $diff = round($diff);
        if($strTime[$i]=='second'){
            return " moments ago ";
        }
        return $diff . " " . $strTime[$i] . "(s) ago ";
    }
}
function file_upload_max_size(){
    static $max_size = -1;
  
    if ($max_size < 0){
      $post_max_size = parse_size(ini_get('post_max_size'));
      if ($post_max_size > 0){
        $max_size = $post_max_size;
      }
      $upload_max = parse_size(ini_get('upload_max_filesize'));
      if ($upload_max > 0 && $upload_max < $max_size){
        $max_size = $upload_max;
      }
    }
    return $max_size;
  }
  
function parse_size($size){
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit){
      return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    else{
      return round($size);
    }
}
function check_file_size_is_less_than_server_file_size($files){
    $server_allowed_size = file_upload_max_size();
    if ($files['file']){
        $file_size = $files['file']['size'];
        if($file_size < $server_allowed_size){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
