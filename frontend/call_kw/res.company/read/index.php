<?php
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
?>
{"result": [{"vat": false, "phone": "+1 555 123 8069", "id": 1, "tax_calculation_rounding_method": "round_per_line", "partner_id": [1, "Demo Company"], "country_id": [233, "United States"], "name": "Demo Company", "currency_id": [3, "USD"], "email": "info@yourcompany.example.com", "website": "http://www.example.com", "company_registry": false}], "jsonrpc": "2.0", "id": 409393882}