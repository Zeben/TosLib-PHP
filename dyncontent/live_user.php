<?php

if(isset($_GET['query'])) {
	include "../plugins/db.php";

	$r = $db->query("SELECT u_login, u_id FROM admins WHERE u_login LIKE '%".$db->real_escape_string($_GET['query'])."%' ORDER BY u_login");
	while ($rr = $r->fetch_assoc()) {
		$json_arr[] = $rr;
	}
	if(!empty($json_arr)) echo json_encode($json_arr);
	$db->close();
}

if(isset($_GET['all'])) {
	if(isset($_GET['page'])) {
		include "../plugins/db.php";

		$lim = $_GET['page'] * 5;
		$rr = $db->query("SELECT * FROM admins");
		$numr = $rr->num_rows;
		$r = $db->query("SELECT u_login, u_id FROM admins ORDER BY u_login LIMIT ".$lim.", 5");
		while ($rr = $r->fetch_assoc()) {
			$json_arr[] = array($rr, 'ucount' => $numr);
		}
		if(!empty($json_arr)) echo json_encode($json_arr);
		$db->close();
	}
}

?> 
