<?php
$res=@include("../../../../../main.inc.php");
if (! $res) $res=@include("../../../../../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/compta/facture/class/facture.class.php");
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
//print_r($json_obj);
$db->begin();
foreach ($json_obj->params->args[0] as &$invoice) {
	$obj = new Facture($db);
	$obj->ref_client     = $invoice->id;
	$obj->socid          = $conf->global->CASHDESK_ID_THIRDPARTY;
	$obj->date           = mktime();
	$obj->cond_reglement_id = 1;
	$line1=new FactureLigne($db);
	$line1->tva_tx=10.0;
	$line1->remise_percent=0;
	$line1->qty=1;
	$line1->total_ht=$json_obj->params->args[0][0]->data->amount_total-$json_obj->params->args[0][0]->data->amount_tax;
	$line1->total_tva=$json_obj->params->args[0][0]->data->amount_tax;
	$line1->total_ttc=$json_obj->params->args[0][0]->data->amount_total;
	$obj->lines[]=$line1;
	
	// Create invoice
	$idobject=$obj->create($user);
	if ($idobject > 0)
	{
		// Change status to validated
		$result=$obj->validate($user);
		if ($result > 0) print "OK Object created with id ".$idobject."\n";
		else
		{
			$error++;
			dol_print_error($db,$obj->error);
		}
	}
	else
	{
		$error++;
		dol_print_error($db,$obj->error);
	}


// -------------------- END OF YOUR CODE --------------------


}
if (! $error)
{
	$db->commit();
	print '--- end ok'."\n";
}
else
{
	print '--- end error code='.$error."\n";
	$db->rollback();
}
$db->close();
?>
{"result": [1], "jsonrpc": "2.0", "id": 780044868}