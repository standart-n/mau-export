<?php class pl_ajax {

function engine(&$q) { $q->ajax=false;
	if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
		if ($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") { $q->ajax=true; $q->json=array(); }
	}
	if (isset($_SERVER["HTTP_REFERER"])) {
		$q->referer=$_SERVER["HTTP_REFERER"];
	}
}

} ?>
