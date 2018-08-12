<?php
/* Copyright (C) 2018	Andreu Bisquerra	<jove@bisquerra.com>
 * Thank you to Odoo for the best pos theme
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

define('NOCSRFCHECK',1);	// This is main home and login page. We must be able to go on it from another web site.
$res=@include("../main.inc.php");
if (! $res) $res=@include("../../main.inc.php");
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
require_once DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php';

$place = GETPOST('place');
if ($place=="") $place="0";
$action = GETPOST('action');

$langs->load("main");
$langs->load("bills");
$langs->load("orders");
$langs->load("commercial");

// Title
$title='TakePOS - Dolibarr '.DOL_VERSION;
if (! empty($conf->global->MAIN_APPLICATION_TITLE)) $title='TakePOS - '.$conf->global->MAIN_APPLICATION_TITLE;
top_htmlhead($head, $title, $disablejs, $disablehead, $arrayofjs, $arrayofcss);
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>TakePOS</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content=" width=1024, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="196x196" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-196.png">
        <link rel="shortcut icon" sizes="128x128" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-128.png">
        <link rel="apple-touch-icon" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="http://127.0.0.1:8069/point_of_sale/static/src/img/touch-icon-ipad-retina.png">

        <style> body { background: #222; } </style>

        <link rel="shortcut icon" href="http://127.0.0.1:8069/web/static/src/img/favicon.ico" type="image/x-icon">
        <link type="text/css" rel="stylesheet" href="./odoo_theme/point_of_sale.assets.0.css">
		<link href="./odoo_theme/chrome50.css" rel="stylesheet" type="text/css">
		
		<link rel="stylesheet" href="css/colorbox.css" type="text/css" media="screen" />
		<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
		<script type="text/javascript">
		var place="<?php echo $place;?>";
		var editnumber="";
		var editaction="qty";
		
		$(document).on('click', '.js-category-switch', function () {
			var catid=$(this).attr( "data-category-id" );
			LoadProducts(catid);
			$(".header-row").removeClass("selected");
		});
		
		$(document).on('click', '.product', function () {
			$(".content-row").removeClass("selected");
		});
		
		function LoadProducts(catid){
			var text="";
			$.getJSON('./ajax.php?action=getProducts&category='+catid, function(data) {
				$.each(data, function(i, obj) {
					price=Math.round(obj.price_ttc * 100) / 100;
					text+='<span class="product" onclick="ClickProduct('+obj.id+')"><div class="product-img"><img src="getimg/?query=pro&id='+obj.id+'"><span class="price-tag">'+price+' €</span></div><div class="product-name">'+obj.label+'</div></span>';
				});	
				$( "div.product-list" ).html(text);
				Refresh();
			});
		}
		
		function Refresh(){
			$("div.order").load("invoice.php?place="+place);
		}
		
		function CloseBill(){
			$.colorbox({href:"pay.php?place="+place, width:"80%", height:"90%", transition:"none", iframe:"true", title:"<?php echo $langs->trans("CloseBill");?>"});
		}
		
		function Customer(){
			$.colorbox({href:"customers.php?place="+place, width:"90%", height:"80%", transition:"none", iframe:"true", title:"<?php echo $langs->trans("Customer");?>"});
		}
		
		
		function Edit(number){
			var text=selectedtext+"<br> ";
			if (number=='c'){
				editnumber="";
				Refresh();
				return;
			}
			else if (number=='qty'){
				if (editaction=='qty' && editnumber!=""){
					$("div.order").load("invoice.php?action=updateqty&place="+place+"&idline="+selectedline+"&number="+editnumber, function() {
						editnumber="";
						$("#qty").html("<?php echo $langs->trans("Qty"); ?>");
					});
					return;
				}
				else {
					editaction="qty";
				}
			}
			else if (number=='p'){
				if (editaction=='p' && editnumber!=""){
					$("div.order").load("invoice.php?action=updateprice&place="+place+"&idline="+selectedline+"&number="+editnumber, function() {
						editnumber="";
						$("#price").html("<?php echo $langs->trans("Price"); ?>");
					});
					return;
				}
				else {
					editaction="p";
				}
			}
			else if (number=='r'){
				if (editaction=='r' && editnumber!=""){
					$("div.order").load("invoice.php?action=updatereduction&place="+place+"&idline="+selectedline+"&number="+editnumber, function() {
						editnumber="";
						$("#reduction").html("<?php echo $langs->trans("ReductionShort"); ?>");
					});
					return;
				}
				else {
					editaction="r";
				}
			}
			else {
				editnumber=editnumber+number;
			}
			if (editaction=='qty'){
				text=text+"<?php echo $langs->trans("Modify")." -> ".$langs->trans("Qty").": "; ?>";
				$("#qty").html("OK");
				$("#price").html("Price");
				$("#reduction").html("Disc");
			}
			if (editaction=='p'){
				text=text+"<?php echo $langs->trans("Modify")." -> ".$langs->trans("Price").": "; ?>";
				$("#qty").html("Qty");
				$("#price").html("OK");
				$("#reduction").html("Disc");
			}
			if (editaction=='r'){
				text=text+"<?php echo $langs->trans("Modify")." -> ".$langs->trans("ReductionShort").": "; ?>";
				$("#qty").html("Qty");
				$("#price").html("Price");
				$("#reduction").html("OK");
			}
			$('#'+selectedline).find("td:first").html(text+editnumber);
		}
		
		function deleteline(){
			$("#poslines").load("invoice.php?action=deleteline&place="+place+"&idline="+selectedline);
		}
        
        
        function ClickProduct(idproduct){
            $("div.order").load("invoice.php?action=addline&place="+place+"&idproduct="+idproduct);
        }
		
		function Search(){
			var text="";
			$.getJSON('./ajax.php?action=search&term='+$('#search').val(), function(data) {
				$.each(data, function(i, obj) {
					price=Math.round(obj.price_ttc * 100) / 100;
					text+='<span class="product" onclick="ClickProduct('+obj.rowid+')"><div class="product-img"><img src="genimg/?query=pro&w=220&h=200&id='+obj.rowid+'"><span class="price-tag">'+price+' €</span></div><div class="product-name">'+obj.label+'</div></span>';
				});	
				$( "div.product-list" ).html(text);
				Refresh();
			});
		}
		
		$( document ).ready(function() {
			var firstcategory=$('span:first', 'div.category-list').attr( "data-category-id" );
			LoadProducts(firstcategory);
		});
		
		</script>
		
		
	</head>
    <body class="o_touch_device">
        <div class="o_main_content"><div class="o_control_panel o_hidden"></div><div class="o_content"><div class="pos">
            <div class="pos-topheader">
                <div class="pos-branding">
                    <img class="pos-logo" src="./odoo_theme/logo.png">
                    <span class="username">
						<?php echo $user->login;;?>
					</span>
                </div>
                <div class="pos-rightheader">
                    <div class="order-selector">
            <span class="orders touch-scrollable">
			
			
			<?php
			if($conf->global->TAKEPOS_BAR_RESTAURANT){
				$sql="SELECT floor from ".MAIN_DB_PREFIX."takepos_floor_tables group by floor";
				$resql = $db->query($sql);
				$rows = array();
				while($row = $db->fetch_array ($resql)){
					echo '<span class="order-button select-order selected" data-uid="'.$row[0].'"><span class="floor-button">'.$langs->trans("Floor").' '.$row[0].'
                            </span>
                        </span>';
				
				}  
			}
			?>                   
                
            </span>
            <span class="order-button square neworder-button">
                <i class="fa fa-plus"></i>
            </span>
            <span class="order-button square deleteorder-button">
                <i class="fa fa-minus"></i>
            </span>
        </div>
                    
                <div class="oe_status js_synch">
            <span class="js_msg oe_hidden"></span>
            <div class="js_connected oe_icon oe_green">
                <i class="fa fa-fw fa-wifi"></i>
            </div>
            <div class="js_connecting oe_icon oe_hidden">
                <i class="fa fa-fw fa-spin fa-spinner"></i>
            </div>
            <div class="js_disconnected oe_icon oe_red oe_hidden">
                <i class="fa fa-fw fa-wifi"></i>
            </div>
            <div class="js_error oe_icon oe_red oe_hidden">
                <i class="fa fa-fw fa-warning"></i>
            </div>
        </div><div class="header-button">
            Close
        </div></div>
            </div>

            <div class="pos-content">

                <div class="window">
                    <div class="subwindow">
                        <div class="subwindow-container">
                            <div class="subwindow-container-fix screens">
                                
                            <div class="scale-screen screen oe_hidden">
            <div class="screen-content">
                <div class="top-content">
                    <span class="button back">
                        <i class="fa fa-angle-double-left"></i>
                        Back
                    </span>
                    <h1 class="product-name">Unnamed Product</h1>
                </div>
                <div class="centered-content">
                    <div class="weight js-weight">
                        0.000 Kg
                    </div>
                    <div class="product-price">
                        0.00 €/
                    </div>
                    <div class="computed-price">
                        123.14 €
                    </div>
                    <div class="buy-product">
                        Order
                        <i class="fa fa-angle-double-right"></i>
                    </div>
                </div>
            </div>
        </div><div class="product-screen screen">
            <div class="leftpane">
                <div class="window">
                    <div class="subwindow">
                        <div class="subwindow-container">
                            <div class="subwindow-container-fix">
                                <div class="order-container">
            <div class="order-scroller touch-scrollable">
                <div class="order" id="poslines">
                </div>
            </div>
        </div>
                            </div>
                        </div>
                    </div>

                    <div class="subwindow collapsed">
                        <div class="subwindow-container">
                            <div class="subwindow-container-fix pads">
                                <div class="control-buttons oe_hidden"></div>
                                <div class="actionpad">
            <button class="button set-customer" onclick="Customer();">
                <i class="fa fa-user"></i> 
                <?php echo $langs->trans("Customer"); ?>
            </button>
            <button class="button pay" onclick="CloseBill();">
                <div class="pay-circle">
                    <i class="fa fa-chevron-right"></i> 
                </div>
                <?php echo $langs->trans("CloseBill");?>
            </button>
        </div>
                                <div class="numpad">
            <button class="input-button number-char" onclick="Edit(1);">1</button>
            <button class="input-button number-char" onclick="Edit(2);">2</button>
            <button class="input-button number-char" onclick="Edit(3);">3</button>
            <button class="mode-button" id="qty" data-mode="quantity" onclick="Edit('qty');">Qty</button>
            <br>
            <button class="input-button number-char" onclick="Edit(4);">4</button>
            <button class="input-button number-char" onclick="Edit(5);">5</button>
            <button class="input-button number-char" onclick="Edit(6);">6</button>
            <button class="mode-button" id="reduction" data-mode="discount" onclick="Edit('r');">Disc</button>
            <br>
            <button class="input-button number-char" onclick="Edit(7);">7</button>
            <button class="input-button number-char" onclick="Edit(8);">8</button>
            <button class="input-button number-char" onclick="Edit(9);">9</button>
            <button class="mode-button" id="price" data-mode="price" onclick="Edit('p');">Price</button>
            <br>
            <button class="input-button numpad-minus" onclick="Edit('c');">C</button>
            <button class="input-button number-char" onclick="Edit(0);">0</button>
            <button class="input-button number-char" onclick="Edit('.');">.</button>
            <button class="input-button numpad-backspace" onclick="deleteline();">
                <img height="21" src="./odoo_theme/backspace.png" style="pointer-events: none;" width="24">
            </button>
        </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="rightpane">
                <table class="layout-table">

                    <tbody><tr class="header-row">
                        <td class="header-cell">
                            <div>
        <header class="rightpane-header">
            <div class="breadcrumbs">
                <span class="breadcrumb">
                    <span class=" breadcrumb-button breadcrumb-home js-category-switch">
                        <a href="takepos.php"><i class="fa fa-home"></i></a>
                    </span>
                </span>
                
            </div>
            <div class="searchbox">
                <input placeholder="Search Products" onkeyup="Search();" id="search">
                <span class="search-clear"></span>
            </div>
        </header>
        
            <div class="categories">
                <div class="category-list-scroller touch-scrollable">
                    <div class="category-list simple">
					
					<?php
					$categorie = new Categorie($db);
					$categories = $categorie->get_full_arbo('product');
					foreach($categories as $key => $val)
					{
						echo '<span class="category-simple-button js-category-switch" data-category-id="'.$val['id'].'">';
						echo $val['label'];
						echo '</span>';
					}
					?>
					
					</div>
                </div>
            </div>
        
        </div>
                        </td>
                    </tr>

                    <tr class="content-row">
                        <td class="content-cell">
                            <div class="content-container">
                                <div class="product-list-container">
            <div class="product-list-scroller touch-scrollable">
                <div class="product-list">
				</div>
            </div>
            <span class="placeholder-ScrollbarWidget"></span>
        </div>
                            </div>
                        </td>
                    </tr>

                </tbody></table>
            </div>
        </div><div class="clientlist-screen screen oe_hidden">
            <div class="screen-content">
                <section class="top-content">
                    <span class="button back">
                        <i class="fa fa-angle-double-left"></i>
                        Cancel
                    </span>
                    <span class="searchbox">
                        <input placeholder="Search Customers">
                        <span class="search-clear"></span>
                    </span>
                    <span class="searchbox"></span>
                    <span class="button new-customer">
                        <i class="fa fa-user"></i>
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="button next oe_hidden highlight">
                        Select Customer
                        <i class="fa fa-angle-double-right"></i>
                    </span>
                </section>
                <section class="full-content">
                    <div class="window">
                        <section class="subwindow collapsed">
                            <div class="subwindow-container collapsed">
                                <div class="subwindow-container-fix client-details-contents">
                                </div>
                            </div>
                        </section>
                        <section class="subwindow">
                            <div class="subwindow-container">
                                <div class="subwindow-container-fix touch-scrollable scrollable-y">
                                    <table class="client-list">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody class="client-list-contents">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div><div class="receipt-screen screen oe_hidden">
            <div class="screen-content">
                <div class="top-content">
                    <h1>Change: <span class="change-value">0.00</span></h1>
                    <span class="button next">
                        Next Order
                        <i class="fa fa-angle-double-right"></i>
                    </span>
                </div>
                <div class="centered-content touch-scrollable">
                    <div class="button print">
                        <i class="fa fa-print"></i> Print Receipt
                    </div>
                    <div class="pos-receipt-container">
                    </div>
                </div>
            </div>
        </div><div class="payment-screen screen oe_hidden">
            <div class="screen-content">
                <div class="top-content">
                    <span class="button back">
                        <i class="fa fa-angle-double-left"></i>
                        Back
                    </span>
                    <h1>Payment</h1>
                    <span class="button next">
                        Validate
                        <i class="fa fa-angle-double-right"></i>
                    </span>
                </div>
                <div class="left-content pc40 touch-scrollable scrollable-y">

                    <div class="paymentmethods-container">
                    <div class="paymentmethods">
            
                <div class="button paymentmethod" data-id="8">
                    Cash (EUR)
                </div>
            
        </div></div>

                </div>
                <div class="right-content pc60 touch-scrollable scrollable-y">

                    <section class="paymentlines-container"><div class="paymentlines-empty">
                <div class="total">
                    0.00 €
                </div>
                <div class="message">
                    Please select a payment method. 
                </div>
            </div></section>

                    <section class="payment-numpad">
                    <div class="numpad">
            <button class="input-button number-char" data-action="1">1</button>
            <button class="input-button number-char" data-action="2">2</button>
            <button class="input-button number-char" data-action="3">3</button>
            <button class="mode-button" data-action="+10">+10</button>
            <br>
            <button class="input-button number-char" data-action="4">4</button>
            <button class="input-button number-char" data-action="5">5</button>
            <button class="input-button number-char" data-action="6">6</button>
            <button class="mode-button" data-action="+20">+20</button>
            <br>
            <button class="input-button number-char" data-action="7">7</button>
            <button class="input-button number-char" data-action="8">8</button>
            <button class="input-button number-char" data-action="9">9</button>
            <button class="mode-button" data-action="+50">+50</button>
            <br>
            <button class="input-button numpad-char" data-action="CLEAR">C</button>
            <button class="input-button number-char" data-action="0">0</button>
            <button class="input-button number-char" data-action=".">.</button>
            <button class="input-button numpad-backspace" data-action="BACKSPACE">
                <img height="21" src="./odoo_theme/backspace.png" width="24">
            </button>
        </div></section>

                    <div class="payment-buttons">
                        <div class="button js_set_customer">
                            <i class="fa fa-user"></i> 
                            <span class="js_customer_name"> 
                                
                                
                                    Customer
                                
                            </span>
                        </div>
                        
                        
                        
                     </div>
                 </div>
             </div>
         </div></div>
                        </div>
                    </div>
                </div>

                <div class="keyboard_frame">
            <ul class="keyboard simple_keyboard">
                <li class="symbol firstitem row_qwerty"><span class="off">q</span><span class="on">1</span></li>
                <li class="symbol"><span class="off">w</span><span class="on">2</span></li>
                <li class="symbol"><span class="off">e</span><span class="on">3</span></li>
                <li class="symbol"><span class="off">r</span><span class="on">4</span></li>
                <li class="symbol"><span class="off">t</span><span class="on">5</span></li>
                <li class="symbol"><span class="off">y</span><span class="on">6</span></li>
                <li class="symbol"><span class="off">u</span><span class="on">7</span></li>
                <li class="symbol"><span class="off">i</span><span class="on">8</span></li>
                <li class="symbol"><span class="off">o</span><span class="on">9</span></li>
                <li class="symbol lastitem"><span class="off">p</span><span class="on">0</span></li>

                <li class="symbol firstitem row_asdf"><span class="off">a</span><span class="on">@</span></li>
                <li class="symbol"><span class="off">s</span><span class="on">#</span></li>
                <li class="symbol"><span class="off">d</span><span class="on">%</span></li>
                <li class="symbol"><span class="off">f</span><span class="on">*</span></li>
                <li class="symbol"><span class="off">g</span><span class="on">/</span></li>
                <li class="symbol"><span class="off">h</span><span class="on">-</span></li>
                <li class="symbol"><span class="off">j</span><span class="on">+</span></li>
                <li class="symbol"><span class="off">k</span><span class="on">(</span></li>
                <li class="symbol lastitem"><span class="off">l</span><span class="on">)</span></li>

                <li class="symbol firstitem row_zxcv"><span class="off">z</span><span class="on">?</span></li>
                <li class="symbol"><span class="off">x</span><span class="on">!</span></li>
                <li class="symbol"><span class="off">c</span><span class="on">"</span></li>
                <li class="symbol"><span class="off">v</span><span class="on">'</span></li>
                <li class="symbol"><span class="off">b</span><span class="on">:</span></li>
                <li class="symbol"><span class="off">n</span><span class="on">;</span></li>
                <li class="symbol"><span class="off">m</span><span class="on">,</span></li>
                <li class="delete lastitem">delete</li>

                <li class="numlock firstitem row_space"><span class="off">123</span><span class="on">ABC</span></li>
                <li class="space">&nbsp;</li>
                <li class="symbol"><span class="off">.</span><span class="on">.</span></li>
                <li class="return lastitem">return</li>
            </ul>
            <p class="close_button">close</p>
        </div>
            <div class="debug-widget oe_hidden">
            <h1>Debug Window</h1>
            <div class="toggle"><i class="fa fa-times"></i></div>
            <div class="content">
                <p class="category">Electronic Scale</p>
                <ul>
                    <li><input class="weight" type="text"></li>
                    <li class="button set_weight">Set Weight</li>
                    <li class="button reset_weight">Reset</li>
                </ul>

                <p class="category">Barcode Scanner</p>
                <ul>
                    <li><input class="ean" type="text"></li>
                    <li class="button barcode">Scan</li>
                    <li class="button custom_ean">Scan EAN-13</li>
                </ul>

                <p class="category">Orders</p>

                <ul>
                    <li class="button delete_orders">Delete Paid Orders</li>
                    <li class="button delete_unpaid_orders">Delete Unpaid Orders</li>
                    <li class="button export_paid_orders">Export Paid Orders</li>
                    <a><li class="button download_paid_orders oe_hidden">Download Paid Orders</li></a>
                    <li class="button export_unpaid_orders">Export Unpaid Orders</li>
                    <a><li class="button download_unpaid_orders oe_hidden">Download Unpaid Orders</li></a>
                    <li class="button import_orders" style="position:relative">
                        Import Orders
                        <input style="opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;margin:0;cursor:pointer" type="file">
                    </li>
                </ul>

                <p class="category">Hardware Status</p>
                <ul>
                    <li class="status weighing">Weighing</li>
                    <li class="button display_refresh">Refresh Display</li>
                </ul>
                <p class="category">Hardware Events</p>
                <ul>
                    <li class="event open_cashbox">Open Cashbox</li>
                    <li class="event print_receipt">Print Receipt</li>
                    <li class="event scale_read">Read Weighing Scale</li>
                </ul>
            </div>
        </div></div>

            <div class="popups">
                
            <div class="modal-dialog oe_hidden">
            <div class="popup popup-alert">
                <p class="title">Alert</p>
                <p class="body"></p>
                <div class="footer">
                    <div class="button cancel">
                        Ok
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-error">
                <p class="title">Error</p>
                <p class="body"></p>
                <div class="footer">
                    <div class="button cancel">
                        Ok
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-error">
                <p class="title">Error</p>
                <p class="body traceback"></p>
                <div class="footer">
                    <div class="button cancel">
                        Ok
                    </div>
                    <a><div class="button icon download_error_file oe_hidden">
                        <i class="fa fa-arrow-down"></i>
                    </div></a>
                    <div class="button icon download">
                        <i class="fa fa-download"></i>
                    </div>
                    <div class="button icon email">
                        <i class="fa fa-paper-plane"></i>
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-barcode">
                <p class="title">Unknown Barcode
                    <br>
                    <span class="barcode"></span>
                </p>
                <p class="body">
                    The Point of Sale could not find any product, client, employee
                    or action associated with the scanned barcode.
                </p>
                <div class="footer">
                    <div class="button cancel">
                        Ok
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-confirm">
                <p class="title">Confirm ?</p>
                <p class="body"></p>
                <div class="footer">
                    <div class="button confirm">
                        Confirm 
                    </div>
                    <div class="button cancel">
                        Cancel 
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-selection">
                <p class="title">Select</p>
                <div class="selection scrollable-y touch-scrollable">
                    
                </div>
                <div class="footer">
                    <div class="button cancel">
                        Cancel 
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-textinput">
                <p class="title"></p>
                <input type="text" value="">
                <div class="footer">
                    <div class="button confirm">
                        Ok 
                    </div>
                    <div class="button cancel">
                        Cancel 
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-textinput">
                <p class="title"></p>
                <textarea cols="40" rows="10"></textarea>
                <div class="footer">
                    <div class="button confirm">
                        Ok 
                    </div>
                    <div class="button cancel">
                        Cancel 
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-text">
                <p class="title"></p>
                <div class="packlot-lines">
                    
                </div>
                <div class="footer">
                    <div class="button confirm">
                        Ok
                    </div>
                    <div class="button cancel">
                        Cancel
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-number">
                <p class="title"></p>
                <div class="popup-input value active">
                    
                </div>
                <div class="popup-numpad">
                    <button class="input-button number-char" data-action="1">1</button>
                    <button class="input-button number-char" data-action="2">2</button>
                    <button class="input-button number-char" data-action="3">3</button>
                    
                        <button class="mode-button add" data-action="+10">+10</button>
                    
                    <br>
                    <button class="input-button number-char" data-action="4">4</button>
                    <button class="input-button number-char" data-action="5">5</button>
                    <button class="input-button number-char" data-action="6">6</button>
                    
                        <button class="mode-button add" data-action="+20">+20</button>
                    
                    <br>
                    <button class="input-button number-char" data-action="7">7</button>
                    <button class="input-button number-char" data-action="8">8</button>
                    <button class="input-button number-char" data-action="9">9</button>
                    
                        <button class="mode-button add" data-action="+50">+50</button>
                    
                    <br>
                    <button class="input-button numpad-char" data-action="CLEAR">C</button>
                    <button class="input-button number-char" data-action="0">0</button>
                    <button class="input-button number-char dot"></button>
                    <button class="input-button numpad-backspace" data-action="BACKSPACE">
                        <img height="21" src="./odoo_theme/backspace.png" style="pointer-events: none;" width="24">
                    </button>
                    <br>
                </div>
                <div class="footer centered">
                    <div class="button cancel">
                        Cancel 
                    </div>
                    <div class="button confirm">
                        Ok
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-number popup-password">
                <p class="title"></p>
                <div class="popup-input value active">
                    
                </div>
                <div class="popup-numpad">
                    <button class="input-button number-char" data-action="1">1</button>
                    <button class="input-button number-char" data-action="2">2</button>
                    <button class="input-button number-char" data-action="3">3</button>
                    
                        <button class="mode-button add" data-action="+10">+10</button>
                    
                    <br>
                    <button class="input-button number-char" data-action="4">4</button>
                    <button class="input-button number-char" data-action="5">5</button>
                    <button class="input-button number-char" data-action="6">6</button>
                    
                        <button class="mode-button add" data-action="+20">+20</button>
                    
                    <br>
                    <button class="input-button number-char" data-action="7">7</button>
                    <button class="input-button number-char" data-action="8">8</button>
                    <button class="input-button number-char" data-action="9">9</button>
                    
                        <button class="mode-button add" data-action="+50">+50</button>
                    
                    <br>
                    <button class="input-button numpad-char" data-action="CLEAR">C</button>
                    <button class="input-button number-char" data-action="0">0</button>
                    <button class="input-button number-char dot"></button>
                    <button class="input-button numpad-backspace" data-action="BACKSPACE">
                        <img height="21" src="./odoo_theme/backspace.png" style="pointer-events: none;" width="24">
                    </button>
                    <br>
                </div>
                <div class="footer centered">
                    <div class="button cancel">
                        Cancel 
                    </div>
                    <div class="button confirm">
                        Ok
                    </div>
                </div>
            </div>
        </div><div class="modal-dialog oe_hidden">
            <div class="popup popup-import">
                <p class="title">Finished Importing Orders</p>
                
                <div class="footer">
                    <div class="button cancel">
                        Ok
                    </div>
                </div>
            </div>
        </div></div>

            <div class="loader oe_hidden" style="opacity: 0;">
                <div class="loader-feedback oe_hidden">
                    <h1 class="message">Loading</h1>
                    <div class="progressbar">
                        <div class="progress" width="50%"></div>
                    </div>
                    <div class="oe_hidden button skip">
                        Skip
                    </div>
                </div>
            </div>

        </div></div></div>
    

<div class="o_notification_manager"></div><div class="o_loading" style="display: none;">Loading</div></body></html>