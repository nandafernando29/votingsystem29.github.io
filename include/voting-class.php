<?php
class Database{
	private $dbHost="localhost"; //nama server
	private $dbUser="root"; //username database
	private $dbPass=""; //password database
	private $dbName="evoting"; //nama database

	function connectMySQL() {
		mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
		mysql_select_db($this->dbName) or die ("Database Tidak Ditemukan di Server");
	}
}

class User{
	function cek_login($username, $password) {
		$resultpassword = md5($password);
		$result = mysql_query("SELECT * FROM tbl_login WHERE username='$username' AND password='$resultpassword'");
		$user_data = mysql_fetch_array($result);
		$no_rows = mysql_num_rows($result);
		if ($no_rows == 1) {
			$_SESSION['login'] = TRUE;
			$_SESSION['id'] = $user_data['id_login'];
			$_SESSION['nama'] = $user_data['nama'];
            $_SESSION['username'] = $user_data['username'];
			$_SESSION['level'] = $user_data['level'];
			return TRUE;
		}
		else {
		  return FALSE;
		}
	}

	function sesi(){
		return $_SESSION['level'];
	}

	function get_sesi(){
		return $_SESSION['login'];
	}

	function logout(){
		$_SESSION['login'] == FALSE;
		session_destroy();
	}
}

class DataPemilih {
	function tampilPemilihSemua(){
		$query = mysql_query("SELECT * FROM tbl_login WHERE level=2 ORDER BY id_login DESC");
		while($row=mysql_fetch_array($query))
		  $data[]=$row;
	    return $data;
	}

	function tambahDataPemilih($username, $nama, $password, $jurusan, $prodi, $level){
		$query = "INSERT INTO tbl_login SET username='$username', nama='$nama', password='$password', jurusan='$jurusan', prodi='$prodi', level='$level'";
		return mysql_query($query);
	}

	function deleteDataPemilih($id){
		$query = "DELETE FROM tbl_login WHERE id_login='".$id."'";
		return mysql_query($query);
	}

	function bacaDataPemilih($field, $id){
		$query = mysql_query("SELECT * FROM tbl_login WHERE id_login='$id'");
		$data = mysql_fetch_array($query);
		if($field == 'id_login') return $data['id_login'];
		elseif($field == 'username') return $data['username'];
		elseif($field == 'nama') return $data['nama'];
		elseif($field == 'password') return $data['password'];
		elseif($field == 'jurusan') return $data['jurusan'];
		elseif($field == 'prodi') return $data['prodi'];
	}

	function updateDataPemilih($username, $nama, $password, $jurusan, $prodi, $id){
		$query = "UPDATE tbl_login SET username='$username', nama='$nama', password='$password', jurusan='$jurusan', prodi='$prodi' WHERE id_login='$id'";
		return mysql_query($query);
	}

	function updateDataPemilih2($username, $nama, $jurusan, $prodi, $id){
		$query = "UPDATE tbl_login SET username='$username', nama='$nama', jurusan='$jurusan', prodi='$prodi' WHERE id_login='$id'";
		return mysql_query($query);
	}
}

class DataKandidat{
	function tampilKandidatSemua(){
		$query = mysql_query("SELECT * FROM tbl_kandidat ORDER BY id_kandidat ASC");
		while($row=mysql_fetch_array($query))
		  $data[]=$row;
	    return $data;
	}

	function tambahDataKandidat($ketua, $visi, $misi, $image){
		$query = "INSERT INTO tbl_kandidat SET ketua='$ketua', visi='$visi', misi='$misi', foto='$image'";
		return mysql_query($query);
	}

	function updateDataKandidatFoto($ketua, $visi, $misi, $image, $id){
		$query = "UPDATE tbl_kandidat SET ketua='$ketua', visi='$visi', misi='$misi', foto='$image' WHERE id_kandidat='$id'";
		return mysql_query($query);
	}
	function updateDataKandidat($ketua, $visi, $misi, $id){
		$query = "UPDATE tbl_kandidat SET ketua='$ketua', visi='$visi', misi='$misi' WHERE id_kandidat='$id'";
		return mysql_query($query);
	}

	function deleteDataKandidat($id){
		$query = "DELETE FROM tbl_kandidat WHERE id_kandidat='".$id."'";
		return mysql_query($query);
	}

	function bacaDataKandidat($field, $id){
		$query = mysql_query("SELECT * FROM tbl_kandidat WHERE id_kandidat='$id'");
		$data = mysql_fetch_array($query);
		if($field == 'id_kandidat') return $data['id_kandidat'];
		elseif($field == 'ketua') return $data['ketua'];
		elseif($field == 'visi') return $data['visi'];
		elseif($field == 'misi') return $data['misi'];
		elseif($field == 'foto') return $data['foto'];
	}

	function pilihKandidat($id_kandidat,$id_login,$waktu,$poin){
		$query = "INSERT INTO tbl_voting SET id_kandidat='$id_kandidat', id_login='$id_login', waktu='$waktu', poin='$poin'";
		return mysql_query($query);
	}

	function validasiPilihKandidat($session){
		$query = mysql_query("SELECT * FROM tbl_voting WHERE id_login='$session'");
		return mysql_num_rows($query);
	}
}

class Hasil{
	function tampilHasilSemua($id){
		$query = mysql_query("SELECT a.*, b.username, b.jurusan, b.prodi FROM tbl_voting a left join tbl_login b on a.id_login=b.id_login WHERE id_kandidat='$id'");
		while($row=mysql_fetch_array($query))
		  $data[]=$row;
	    return $data;
	}

	function reset() {
		$query = "TRUNCATE TABLE `tbl_voting`";
		return mysql_query($query);
	}

	function jumlahSuaraVoting($id_kandidat){
		$query = mysql_query("SELECT SUM(poin) as jumlah FROM tbl_voting WHERE id_kandidat='$id_kandidat'");
		$row = mysql_fetch_array($query);
		return $row;
	}
}

class VotingValidasi{
	function __construct(){}
	function xss($str){
		$str = htmlspecialchars($str);
		return $str;
	}
	function sql($str){
		$rms = array("'","`","=",'"',"@","<",">","*");
		$str = str_replace($rms, '', $str);
		$str = stripcslashes($str);
		$str = htmlspecialchars($str);
		return $str;
	}
}

function DateToIndo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
   // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
$BulanIndo = array("Januari", "Februari", "Maret",
   "April", "Mei", "Juni",
   "Juli", "Agustus", "September",
   "Oktober", "November", "Desember");
$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
return($result);
}

function UploadImages($fupload_name){
  //direktori gambar
  $vdir_upload = "asset/img/kandidat/";
  $vfile_upload = $vdir_upload . $fupload_name;
  $imageType = $_FILES["fupload"]["type"];

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  switch($imageType) {
    case "image/gif":
      $im_src=imagecreatefromgif($vfile_upload);
      break;
      case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
      $im_src=imagecreatefromjpeg($vfile_upload);
      break;
      case "image/png":
    case "image/x-png":
      $im_src=imagecreatefrompng($vfile_upload);
      break;
  }

  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi besar 400 pixel
  //Set ukuran gambar hasil perubahan

  $dst_width = 300;
  $dst_height = 325;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width,$dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  switch($imageType) {
    case "image/gif":
        imagegif($im,$vdir_upload.$fupload_name);
      break;
      case "image/pjpeg":
    case "image/jpeg":
    case "image/jpg":
        imagejpeg($im,$vdir_upload.$fupload_name);
      break;
      case "image/png":
    case "image/x-png":
        imagepng($im,$vdir_upload.$fupload_name);
      break;
  }

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
  imagedestroy($im2);
}

function UploadLogo($fupload_name){
  //direktori Logo
  $vdir_upload = "asset/";
  $vfile_upload = $vdir_upload . $fupload_name;
  $tipe_file   = $_FILES['fupload']['type'];

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload) or die(mysql_error());
}

function get_poin($poin) {
    if ($poin == '1') {
        return '1';
    } elseif ($poin == 'B') { //kalau mau poin lebih untuk masing2 orang
        return '2';
    } elseif ($poin == 'C') { //point 3 suara
        return '3';
    } elseif ($poin == 'D') {
        return '4';
    }
}

function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
    $s = str_replace($d, '', $s);
    $s = strtolower(str_replace($c, '-', $s));
    return $s;
}
?>
