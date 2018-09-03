<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"pos_security_pin": false, "name": "Administrator", "barcode": "0410100000006", "id": 1, "groups_id": [3, 36, 76, 37, 35, 8, 79, 65, 1, 26, 94, 42, 32, 14, 20, 22, 34, 45, 49, 52, 67, 69, 71, 73, 58, 83, 86, 88, 18, 47, 21, 44, 66, 68, 46, 64, 25, 93, 4, 77, 7, 17, 13, 23, 70, 72, 41, 33, 19, 57, 85, 87, 51, 50, 48, 31, 30]}, {"pos_security_pin": false, "name": "Demo User", "barcode": false, "id": 6, "groups_id": [36, 76, 35, 8, 79, 1, 26, 20, 34, 52, 25, 93, 77, 7, 17, 13, 51, 72, 48, 41, 33, 57, 85, 87, 19, 30]}, {"pos_security_pin": false, "name": "John doe", "barcode": false, "id": 8, "groups_id": [36, 76, 35, 79, 1, 26, 32, 21, 64, 25, 93, 77, 7, 17, 19, 13, 51, 72, 57, 85, 87, 31, 30]}], "jsonrpc": "2.0", "id": 686395686}