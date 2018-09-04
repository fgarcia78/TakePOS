<?php
$res=@include("../../../../../main.inc.php");
if (! $res) $res=@include("../../../../../../main.inc.php");
header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);

class Products {
    public $categ_id = "";
    public $barcode  = "";
    public $taxes_id = "";
	public $pos_categ_id = "";
	public $uom_id = "";
	public $list_price = "";
	public $standard_price = "";
	public $lst_price = "";
	public $default_code = "";
	public $id = "";
	public $description_sale = "";
	public $display_name = "";
	public $description = "";
	public $tracking = "";
	public $product_tmpl_id = "";
	public $to_weight = "";
}

$sql = 'SELECT * FROM '.MAIN_DB_PREFIX.'product as p,';
	$sql.= ' ' . MAIN_DB_PREFIX . "categorie_product as c";
	$sql.= ' WHERE p.entity IN ('.getEntity('product').')';
	$sql.= ' AND c.fk_categorie = '.$category;
	$sql.= ' AND c.fk_product = p.rowid';
	$resql = $db->query($sql);
	$rows = array();
	while($row = $db->fetch_array ($resql)){
		$row['prettyprice']=price($row['price_ttc'], 1, '', 1, - 1, - 1, $conf->currency);
		$rows[] = $row;
	}

$categorie = new Categorie($db);
$categories = $categorie->get_full_arbo('product');
class Category {
    public $name = "";
    public $parent_id  = "";
    public $id = "";
	public $child_id = "";
}
$cats=array(); 
foreach($categories as $key => $val)
{
	$cat = new Category();
	$cat->name = $val['label'];
	$cat->parent_id  = false;
	$cat->id  = $val['id'];
	$cats[]=$cat;    
}
$catsjson= json_encode($cats);
?>
{"result": <?php echo $catsjson;?>, "jsonrpc": "2.0", "id": 672236527}