<?php

require('../plugins/db.php');
$comments = $db->query("SELECT * FROM comments");

$done = array();
while($comments_res = $comments->fetch_assoc()) {
	$done[] = array(
			'id' => $comments_res['id'],
			'comment' => $comments_res['comment'],
			'pid' => $comments_res['parent_id']
		);
}

$done2 = array( 
				array(),
				array(), 
				array()
			  )

print_r(($done));

function printTree($tree, $pid = 0) {
	global $visited;
	$visited[] = $pid;

	foreach ($tree[$pid] as $key => $vertex) {
		if(!in_array($vertex, $visited))
			printTree()
	}
}

?>