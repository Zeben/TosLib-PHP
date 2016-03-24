<?php



function protect($input) {
	return trim(stripslashes(htmlspecialchars($input)));
}

function getSortedComments($uid) {
	date_default_timezone_set('UTC');
	include('../plugins/db.php');
	$comments = $db->query("SELECT * FROM comments WHERE uid = '".$db->real_escape_string(protect($uid))."'");
	
	$ret = array();
	while($comments_res = $comments->fetch_assoc())
	{
		$ret[] = $comments_res;
	}
	return $ret;
}

echo json_encode(getSortedComments($_GET['uid']));



//var_dump(getSortedComments($_GET['uid']));
//var_dump(build(getSortedComments($_GET['uid'])));

//$done = getSortedComments($_GET['uid']);
//echo json_encode(build(getSortedComments($_GET['uid'])));

?> 
