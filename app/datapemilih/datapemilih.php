<?php
if (!$user->get_sesi()) {
  header("location:index.php");
}else{
$modpath 	= "app/datapemilih/";
$action		= $modpath."proses.php";

$pmlh = new DataPemilih();
$validasi = new VotingValidasi;
switch (@$_GET['act']) {
default:
?>

<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Voter</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li><a href="?mod=<?php echo @$_GET['mod']?>&amp;act=add" class="medium-box"><i class="icon-plus-sign icon-white"></i> Add New Voter</a></li>
				</ul>
			</div>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<section>
	<table id="datatable" class="table table-hover table-condensed">
		<thead>
			<tr>
				<th>No.</th>
				<th>Student ID</th>
				<th>Edit or Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$arrayPemilih = $pmlh->tampilPemilihSemua();
		if(count($arrayPemilih)){
			foreach ($arrayPemilih as $data) {
		?>
			<tr>
				<td><?php echo $c=$c+1;?></td>
				<td><?php echo strtoupper($data['username']);?></td>
				<td><a href="?mod=<?php echo @$_GET['mod']?>&amp;act=edit&amp;kode=<?php echo $data['id_login']; ?>" class="btn btn-success" title="Edit"><i class="icon-pencil icon-white"></i></a>
				<a href="<?php echo $action.'?mod=datapemilih&act=delete&kode='.$data['id_login']; ?>" onClick="return confirm('Are you sure?');" class="btn btn-danger" title="Hapus"><i class="icon-trash icon-white"></i></a></td>
			</tr>
		<?php
			}
		}else{
			echo "No Voter";
		}
		?>
		</tbody>
	</table>
</section>
<?php
break;
case 'add':
?>

<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Add New Voter</a>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
	<?php
	if(isset($_POST['submit'])){
		$username = $validasi->xss($_POST['username']);
		$nama = $validasi->xss($_POST['nama']);
		$password = md5($validasi->xss($_POST['password']));
		$jurusan = $validasi->xss($_POST['jurusan']);
		$prodi = $validasi->xss($_POST['prodi']);
		$level = 2;

		if($pmlh->tambahDataPemilih($username,$nama,$password,$jurusan,$prodi,$level)){?>
			<meta http-equiv='refresh' content='0; url=?mod=datapemilih'>
			<div class="alert-success alert-block">
		        <button type="button" class="close" data-dismiss="alert">×</button>
		        <h4>Success!</h4>
		    	Success Adding New Voter!
	      	</div>
		<?php }else{
			echo "
			<div class=\"alert alert-block\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
				<h4>Failed!</h4>
				Failed To Add!
			</div>
			";
		}
	}
	?>
	<form class="form-horizontal" method="post" id="form">
		<div class="control-group">
			<label class="control-label" for="nim">Student ID</label>
			<div class="controls">
		  		<input type="text" class="span2" required name="username" id="username" value="">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">Password</label>
			<div class="controls">
		  		<input type="password" class="span3" required name="password" id="password" value="">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="submit" class="btn btn-primary">Save</button>
				<button type="reset" class="btn">Clear</button>
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
			<a class="brand" href="#">Edit Voter</a>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
	<?php
	if(isset($_POST['update'])){
		$username = $validasi->xss($_POST['username']);
		$nama = $validasi->xss($_POST['nama']);
		$password = md5($validasi->xss($_POST['password']));
		$jurusan = $validasi->xss($_POST['jurusan']);
		$prodi = $validasi->xss($_POST['prodi']);
		$id = $validasi->xss($_POST['kode']);

		if (!empty($_POST['password'])){
			$update = $pmlh->updateDataPemilih($username,$nama,$password,$jurusan,$prodi,$id);
		}
		else
		{
			$update = $pmlh->updateDataPemilih2($username,$nama,$jurusan,$prodi,$id);
		}

		if($update){?>
			<meta http-equiv='refresh' content='0; url=?mod=datapemilih'>
			<div class="alert-success alert-block">
		        <button type="button" class="close" data-dismiss="alert">×</button>
		        <h4>Success!</h4>
		    	Success Editing!
	      	</div>
		<?php }else{
			echo "
			<div class=\"alert alert-block\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
				<h4>Failed!</h4>
				Failed To Edit!
			</div>
			";
		}
	}
	?>
	<form class="form-horizontal" method="post" id="form">
		<div class="control-group">
			<label class="control-label" for="username">Student ID</label>
			<div class="controls">
		  		<input type="text" class="span2 uppercase" required name="username" id="username" value="<?php echo $pmlh->bacaDataPemilih('username', $id); ?>">
			</div><input type="text" name="kode" style="display:none;" value="<?php echo $pmlh->bacaDataPemilih('id_login', $id); ?>">
		</div>
		<div class="control-group">
			<label class="control-label" for="password">Password</label>
			<div class="controls">
		  		<input type="password" class="span3" name="password" id="password" value="">
		  		<small>empty the column if don't want to change the password</small>
			</div>
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
}
}
?>
