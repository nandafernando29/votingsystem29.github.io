<?php
if (!$user->get_sesi()) {
  header("location:index.php");
}else{
$modpath 	= "app/pemilu/";
$action		= $modpath."proses.php";

$kandidat = new DataKandidat();
$validasi = new VotingValidasi;

$session = $_SESSION['id'];
$valpil = $kandidat->validasiPilihKandidat($session);

switch (@$_GET['act']) {
default:
?>
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<?php
			if($valpil>0){
			?>
			<a class="brand" href="#"><small>You have voted!</small></a>
			<?php }else{?>
			<script type="text/javascript">
			   $(document).ready(function() {
			       var detik = 0;
			       var menit = 3;
			       var jam = 0;
			       function hitung() {
				   setTimeout(hitung,1000);
				   $('#timer').html( 'Time Left: ' + menit + 'Minute - ' + detik + ' Seconds ');
				   detik --;
				   if(detik < 0) {
						detik = 59;
						menit --;
						if(menit < 0) {
							menit = 59;
							jam --;
							if(jam < 0) {
						  		clearInterval();
									alert('Your session was over, please login once more');
									document.location='?mod=logout'
							}
						}
				   }
			        }
			        hitung();
			   });
			</script>
			<a class="brand" href="#"><small>Your Time <i class="icon-time icon-white"></i> <b id="timer"></b></small></a>
			<?php } ?>
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->
<div class="well">
<?php
if($valpil>0){
	echo "<div class=\"alert alert-success alert-block\">
		Press Logout to exit!
	</div>";
}
?>
<div class="row">
	<div class="span1"></div>
	<div class="span10">
	<?php
	$arrayKandidat = $kandidat->tampilKandidatSemua();
	if(count($arrayKandidat)){
		foreach ($arrayKandidat as $data) {
	?>
		<div class="span3" align="center" style="margin-bottom:10px;">
			<button class="btn btn-large btn-block btn-warning" type="button" disabled="disabled">Candidate No <b><?php echo $c=$c+1;?></b></button>
			<img src="asset/img/kandidat/<?php echo $data['foto']; ?>" class="img-responsive">
			<b><?php echo strtoupper($data['ketua']);?></b>
			<?php
			if($valpil>0){
			}else{
			?>
			<a href="#myModal-<?php echo $data['id_kandidat']; ?>" role="button" data-toggle="modal" class="btn btn-large btn-block btn-success" type="button"><b>Choose</b></a>
			<?php } ?>
		</div>

		<div id="myModal-<?php echo $data['id_kandidat']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Confirm</h3>
			</div>
			<div class="modal-body">
				<img class="img-responsive offset2" width="150" src="asset/img/kandidat/<?php echo $data['foto']; ?>">
				<h4>Are you sure?</h4>
				<p>You choose <b><?php echo strtoupper($data['ketua']);?></b></p>
				<table width="100%" border="0">
					<tr>
						<td width="50%"><strong>Vision</strong><br/>
						<p><?php echo $data['visi']; ?></p>
						</td>
						<td width="50%"><strong>Mision</strong><br/>
						<p><?php echo $data['misi']; ?></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<a href="<?php echo $action.'?mod='.$_GET['mod'].'&act=pilih&kode='.$data['id_kandidat']; ?>" class="btn btn-primary">Yes</a>
				<button class="btn" data-dismiss="modal">No</button>
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
}
}
?>
