<?php
include_once 'include/voting-class.php';
$user = new User();
if (!$user->get_sesi()){
	header("location:index.php");
}
	$mod=htmlentities(@$_GET['mod']);
	$halaman="./app/$mod/$mod.php";

if(!file_exists($halaman) || empty($mod)){
		include "utama.php";
	}else{
		include "$halaman";
}
?>