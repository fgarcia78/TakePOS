<?php
/* Copyright (C) 2018	Andreu Bisquerra	<jove@bisquerra.com>
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
$_GET['theme']="md"; // Force theme. MD theme provides better look and feel to TakePOS
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


$categorie = new Categorie($db);
$categories = $categorie->get_full_arbo('product');

foreach($categories as $key => $val)
					{
						if ($val['level'] < 2)
						{
							echo $val['label'];
						}	
					}
