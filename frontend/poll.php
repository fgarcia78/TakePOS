<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"id": <?php echo $json_obj->id;?>, "jsonrpc": "2.0", "result": []}