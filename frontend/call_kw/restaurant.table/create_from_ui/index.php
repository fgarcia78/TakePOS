<?php
$res=@include("../../../../../main.inc.php");
if (! $res) $res=@include("../../../../../../main.inc.php");
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);

//print_r($json_obj->params->args[0]);
$sql="UPDATE ".MAIN_DB_PREFIX."takepos_floor_tables set label='".$json_obj->params->args[0]->name."', leftpos=".$json_obj->params->args[0]->position_h.", toppos=".$json_obj->params->args[0]->position_v." where rowid=".$json_obj->params->args[0]->id;
$db->query($sql);
?>
{"result": 5, "jsonrpc": "2.0", "id": 452746803}