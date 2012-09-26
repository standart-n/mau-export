<?php class pl_project {

function engine(&$q) {
	if (isset($q->controlsQueue)) {
		if (!$q->ajax) {
			if (isset($q->controlsQueue->site->queue)) {
				foreach($q->controlsQueue->site->queue as $key) {
					if (isset($q->$key)) {
						if (method_exists($q->$key,"engine")) {
							$q->$key->engine($q);
						}
					}
				}
			}
		}
		if ($q->ajax) {
			if (!$q->fn_bots->isBot()) {
				if (isset($q->controlsQueue->ajax->queue)) {
					foreach($q->controlsQueue->ajax->queue as $key) {
						if (isset($q->$key)) {
							if (method_exists($q->$key,"engine")) {
								$q->$key->engine($q);
							}
						}
					}			
				}
				$q->html=json_encode($q->json);
			}
		}
	}
}

} ?>
