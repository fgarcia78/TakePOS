<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"name": "Fruits and Vegetables", "parent_id": false, "id": 1, "child_id": []}, {"name": "Partner Services", "parent_id": false, "id": 2, "child_id": []}], "jsonrpc": "2.0", "id": 672236527}