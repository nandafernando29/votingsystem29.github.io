<?php
if (!$user->get_sesi()) {
  header("location:index.php");
}else{

$pmlh = new DataPemilih();
$validasi = new VotingValidasi;
switch (@$_GET['act']) {
default:
?>
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">Voter Profile</a>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
	<?php
	$id = $validasi->sql($_SESSION['id']);
	$id = $validasi->xss($id);
	?>
	<form class="form-horizontal" method="post" id="form">
		<div class="control-group">
			<label class="control-label" for="username">Student ID</label>
			<div class="controls">
		  		<input type="text" class="span2 uppercase" required name="username" id="username" disabled value="<?php echo $pmlh->bacaDataPemilih('username', $id); ?>">
			</div>
		</div>
	</form>
</div>

<?php
break;
}
}
?>
