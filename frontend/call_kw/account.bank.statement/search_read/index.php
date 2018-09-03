<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"id": 2, "account_id": [27, "101501 Cash"], "state": "open", "pos_session_id": [1, "POS/2018/08/30/01"], "name": "POS/2018/08/30/01", "user_id": [1, "Administrator"], "currency_id": [3, "USD"], "journal_id": [6, "Cash (USD)"]}], "jsonrpc": "2.0", "id": 391178768}