<?php
$res=@include("../../../../../main.inc.php");
if (! $res) $res=@include("../../../../../../main.inc.php");
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);

if($conf->global->TAKEPOS_BAR_RESTAURANT) echo'{"result": [{"background_color": "rgb(136,137,242)", "name": "Main Floor", "table_ids": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], "sequence": 1, "id": 1}], "jsonrpc": "2.0", "id": 882565402}';
else echo '{"result": [], "jsonrpc": "2.0", "id": 212460193}';
?>