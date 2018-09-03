<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"position": "before", "rounding": 0.01, "id": 3, "symbol": "$", "name": "USD", "rate": 1.5289}, {"position": "before", "rounding": 0.01, "id": 3, "symbol": "$", "name": "USD", "rate": 1.5289}], "jsonrpc": "2.0", "id": 394782647}