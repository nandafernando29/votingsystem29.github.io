<ul class="nav">
<?php
include_once 'include/voting-class.php';
$user = new User();
if($user->sesi()==1){ ?>
	<li><a href="?mod=home"><i class="icon-book icon-white"></i> Home</a></li>
	<li><a href="?mod=datapemilih"><i class="icon-book icon-white"></i> Voter</a></li>
	<li><a href="?mod=kandidatcalon"><i class="icon-book icon-white"></i> Candidate</a></li>
	<li><a href="?mod=perolehansuara"><i class="icon-book icon-white"></i> Stats</a></li>
	<li><a href="?mod=logout"><i class="icon-off icon-white"></i> Logout</a></li>
<?php }elseif($user->sesi()==2){
	echo "<li><a href=\"?mod=home\"><i class=\"icon-book icon-white\"></i> Home</a></li>";
	echo "<li><a href=\"?mod=pemilu\"><i class=\"icon-th-large icon-white\"></i> Vote</a></li>";
	echo "<li><a href=\"?mod=logout\"><i class=\"icon-off icon-white\"></i> Logout</a></li>";
}
?>
</ul>
<div class="btn-group pull-right">
	<button class="btn btn-primary"><i class="icon-user icon-white"></i> <?php echo $_SESSION['nama']; ?></button>
</div>