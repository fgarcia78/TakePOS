<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"name": "Kitchen Printer", "product_categories_ids": [1], "id": 1, "proxy_ip": "localhost"}], "jsonrpc": "2.0", "id": 212460193}