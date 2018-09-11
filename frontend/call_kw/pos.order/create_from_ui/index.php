<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
//print_r($json_obj);
//echo $json_obj->method;
//print_r($json_obj->params->args[0]); good
print_r($json_obj->params->args[0][0]->id);
//echo $json_obj->params['args'][0][0]['id'];

?>
{"result": [1], "jsonrpc": "2.0", "id": 780044868}