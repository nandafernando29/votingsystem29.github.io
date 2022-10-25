<?php
session_start();
date_default_timezone_set('Asia/Taiwan');
//error_reporting();
include_once '../../include/voting-class.php';
$user = new User();
$db = new Database();
$db->connectMySQL();
if (!$user->get_sesi()) {
	header("location:index.php");
}else{
	$mod = $_GET['mod'];
	$act = $_GET['act'];
	$validasi = new VotingValidasi;
	$pem = new DataKandidat();

	if ($mod=='pemilu' AND $act=='pilih') {
		$id_kandidat = $validasi->sql($_GET['kode']);
		$id_login = $_SESSION['id'];
		$waktu = date("Y-m-d")." ".date("H:i:s");
		$poin = get_poin(substr($_SESSION['username'], 0, 1));
		$query = $pem->pilihKandidat($id_kandidat,$id_login,$waktu,$poin);
		if($query){
			header('location:../../home.php?mod='.$mod);
		}else{
			echo mysql_error();
		}
	}
}
?>
