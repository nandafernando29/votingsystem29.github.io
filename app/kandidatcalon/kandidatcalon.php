<?php
if (!$user->get_sesi()) {
  header("location:index.php");
}else{
$modpath 	= "app/kandidatcalon/";
$action		= $modpath."proses.php";

$kandidat = new DataKandidat();
$hsl = new Hasil();
$validasi = new VotingValidasi;
switch (@$_GET['act']) {
default:
?>

<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Candidate</a>
			<div class="nav-collapse">
				<ul class="nav">
				<li><a href="?mod=<?php echo @$_GET['mod']?>&amp;act=add" class="medium-box"><i class="icon-plus-sign icon-white"></i> Add</a></li>
				</ul>
			</div>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
<div class="row">
	<div class="span1"></div>
	<div class="span10">
	<?php
	$arrayKandidat = $kandidat->tampilKandidatSemua();
	if(count($arrayKandidat)){
		foreach ($arrayKandidat as $data) {
	?>
		<div class="span3" align="center">
			<button class="btn btn-large btn-block btn-warning" type="button" disabled="disabled">Number <b><?php echo $c=$c+1;?></b></button>
			<img src="asset/img/kandidat/<?php echo $data['foto']; ?>" class="img-responsive">
			<b><?php echo strtoupper($data['ketua']);?></b>
			<div class="btn-group">
				<a href="?mod=<?php echo @$_GET['mod']?>&amp;act=edit&amp;kode=<?php echo $data['id_kandidat']; ?>" class="btn btn-info">Edit</a>
				<a href="<?php echo $action.'?mod='.$_GET['mod'].'&act=delete&kode='.$data['id_kandidat']; ?>" onClick="return confirm('Anda Yakin??');" class="btn btn-danger">Delete</a>
				<a href="?mod=<?php echo @$_GET['mod']?>&amp;act=hasil&amp;kode=<?php echo $data['id_kandidat']; ?>" class="btn btn-success">Result</a>
			</div>
		</div>
	<?php
		}
	}else{
		echo "No Candidate";
	}
	?>
	</div>
	<div class="span1"></div>
</div>
</div>
<?php
break;
case 'add':
?>

<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Add Candidate</a>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
	<?php
	if(isset($_POST['submit'])){
		$ketua = $validasi->xss($_POST['ketua']);
		$visi = $validasi->xss($_POST['visi']);
		$misi = $validasi->xss($_POST['misi']);
		$seotitle = seo_title($ketua);

		$extensionList = array("jpg", "jpeg", "png");
		$lokasi_file = $_FILES['fupload']['tmp_name'];
    	$nama_file   = $_FILES['fupload']['name'];
    	$pecah = explode(".", $nama_file);
		$ekstensi = @$pecah[1];
		$rand = rand(1111,9999);
		$nama_file_unik = $rand."-".$seotitle.'.'.$ekstensi;
		$image = 'calon-'.$nama_file_unik;

		if (in_array($ekstensi, $extensionList)){
			UploadImages($image);
			if($kandidat->tambahDataKandidat($ketua,$visi,$misi,$image)){?>
			<meta http-equiv='refresh' content='0; url=?mod=kandidatcalon'>
			<div class="alert-success alert-block">
		        <button type="button" class="close" data-dismiss="alert">×</button>
		        <h4>Success!</h4>
		    	Success adding candidate
	      	</div>
			<?php }else{
				echo "
				<div class=\"alert alert-block\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
					<h4>Failed!</h4>
					Failed to add
				</div>
				";
			}
		}else{
			echo "
			<div class=\"alert alert-block\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
				<h4>Wrong format!</h4>
				No supported format
			</div>
			";
		}
	}
	?>
	<form class="form-horizontal" method="post" id="form" enctype="multipart/form-data">
		<div class="control-group">
			<label class="control-label" for="ketua">Candidate Name</label>
			<div class="controls">
		  		<input type="text" class="span3" required maxlength="25" name="ketua" id="ketua" value="">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="visi">Vision</label>
			<div class="controls">
		  		<textarea class="span3" name="visi" required="true" id="visi"></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="misi">Mision</label>
			<div class="controls">
		  		<textarea class="span3" name="misi" required="true" id="misi"></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">Photo</label>
			<div class="controls">
		  		<input type="file" class="span4" required name="fupload" id="fupload">
			</div>
	  		<small><b>Use .jpg or .png format</b></small>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="submit" class="btn btn-primary">Save</button>
				<a href="?mod=<?php echo $_GET['mod']; ?>" class="btn">Close</a>
			</div>
		</div>
	</form>
</div>

<?php
break;
case 'edit':
$id = $validasi->sql(@$_GET['kode']);
$id = $validasi->xss($id);
?>
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Edit Candidate</a>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
	<?php
	if(isset($_POST['update'])){
		$ketua = $validasi->xss($_POST['ketua']);
		$visi = $validasi->xss($_POST['visi']);
		$misi = $validasi->xss($_POST['misi']);
		$id = $validasi->xss($_POST['txtkode']);
		$seotitle = seo_title($ketua);

		$extensionList = array("jpg", "jpeg", "png");
		$lokasi_file = $_FILES['fupload']['tmp_name'];
    	$nama_file   = $_FILES['fupload']['name'];
    	$pecah = explode(".", $nama_file);
		$ekstensi = @$pecah[1];
		$rand = rand(1111,9999);
		$nama_file_unik = $rand."-".$seotitle.'.'.$ekstensi;
		$image = 'calon-'.$nama_file_unik;

		if(!empty($_FILES['fupload']['tmp_name'])){
			if (in_array($ekstensi, $extensionList)){
				UploadImages($image);
				if($kandidat->updateDataKandidatFoto($ketua,$visi,$misi,$image,$id)){ ?>
					<meta http-equiv='refresh' content='0; url=?mod=kandidatcalon'>
					<div class="alert-success alert-block">
				        <button type="button" class="close" data-dismiss="alert">×</button>
				        <h4>Success!</h4>
				    	Success edit candidate info
			      	</div>
				<?php
				}else{
					echo "
					<div class=\"alert alert-block\">
						<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
						<h4>Failed!</h4>
						Failed to save
					</div>
					";
				}
			}else{
				echo "
				<div class=\"alert alert-block\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
					<h4>Wrong format</h4>
					Not supported format
				</div>
				";
			}
		}else{
			if($kandidat->updateDataKandidat($ketua,$visi,$misi,$id)){ ?>
				<meta http-equiv='refresh' content='0; url=?mod=kandidatcalon'>
				<div class="alert-success alert-block">
			        <button type="button" class="close" data-dismiss="alert">×</button>
			        <h4>Success!</h4>
			    	Success editing candidate info
		      	</div>
			<?php
			}else{
				echo "
				<div class=\"alert alert-block\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
					<h4>Failed!</h4>
					Failed to save
				</div>
				";
			}
		}
	}
	?>
	<form class="form-horizontal" method="post" id="form" enctype="multipart/form-data">
		<div class="control-group">
			<label class="control-label" for="ketua">Candidate Name</label>
			<div class="controls">
		  		<input type="text" class="span3" required maxlength="25" name="ketua" id="ketua" value="<?php echo $kandidat->bacaDataKandidat('ketua', $id); ?>">
			</div><input type="text" name="txtkode" style="display:none;" value="<?php echo $kandidat->bacaDataKandidat('id_kandidat', $id); ?>">
		</div>
		<div class="control-group">
			<label class="control-label" for="visi">Vision</label>
			<div class="controls">
		  		<textarea class="span3" name="visi" required="true" id="visi"><?php echo $kandidat->bacaDataKandidat('visi', $id); ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="misi">Mision</label>
			<div class="controls">
		  		<textarea class="span3" name="misi" required="true" id="misi"><?php echo $kandidat->bacaDataKandidat('misi', $id); ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">Candidate Photo</label>
			<div class="controls">
				<img src="asset/img/kandidat/<?php echo $kandidat->bacaDataKandidat('foto', $id); ?>" class="img-responsive"><br>
		  		<input type="file" class="span4" name="fupload" id="fupload">
			</div>
	  		<small><b>Use.jpg or .png format</b></small>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="update" class="btn btn-primary">Save</button>
				<a href="?mod=<?php echo $_GET['mod']; ?>" class="btn">Close</a>
			</div>
		</div>
	</form>
</div>

<?php
break;
case 'hasil':
$id = $validasi->sql(@$_GET['kode']);
$id = $validasi->xss($id);
?>
<div class="well">
    <h3 class="center">National Chi Nan University</h3>
	<h3 class="center">國立暨南國際大學</h3>
	<h4 class="center">2019/2020</h4>
	<div style="text-align:center;"><img src="asset/img/kandidat/<?php echo $kandidat->bacaDataKandidat('foto', $id); ?>" class="img-responsive"></div>
	<h6 class="center"><?php echo $kandidat->bacaDataKandidat('ketua', $id); ?></h6>
</div>
<h2>Voter</h2>
<section>
	<table id="datatable" class="table table-hover table-condensed">
		<thead>
			<tr>
				<th>No.</th>
				<th>Student ID</th>
				<th>Position</th>
				<th>Department</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$array = $hsl->tampilHasilSemua($id);
		if(count($array)){
			foreach ($array as $data) {
				$tgl = substr($data['waktu'], 0, 10);
		?>
			<tr>
				<td><?php echo $c=$c+1;?></td>
				<td><?php echo strtoupper($data['username']);?></td>
				<td><?php echo strtoupper($data['jurusan']);?></td>
				<td><?php echo strtoupper($data['prodi']);?></td>
				<td><?php echo DateToIndo($tgl);?></td>
			</tr>
		<?php
			}
		}else{
			echo "No voter";
		}
		?>
		</tbody>
	</table>
</section>
<?php
break;
}
}
?>
