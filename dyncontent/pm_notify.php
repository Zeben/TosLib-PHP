<?php

function protect($data) { 
	return trim(stripslashes(htmlspecialchars($data)));
}


if(isset($_POST['check'])) {
	$inbox_count = 0;
	include '../plugins/db.php';
	$q = "SELECT pm_unreaded FROM pm WHERE pm_uid_to = '".protect($_COOKIE['id'])."'";
	$r = $db->query($q);
	while ($rr = $r->fetch_assoc()) {
		if($rr['pm_unreaded'] == 1) $inbox_count++;
	}
	echo $inbox_count;
}

?>