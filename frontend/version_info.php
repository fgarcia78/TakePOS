<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": {"server_version_info": ["saas~11", 3, 0, "final", 0, "e"], "server_version": "saas~11.3+e", "protocol_version": 1, "server_serie": "saas~11.3"}, "jsonrpc": "2.0", "id": <?php echo $json_obj->id;?>}