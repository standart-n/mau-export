<?php class pl_getFunctions {

function engine(&$q) { $er=false;
	if (isset($q->functions)) {
		foreach ($q->functions as $key) {
			foreach ($key as $m) {
				if (!$q->base->fn($q,$m)) { 
					$er=true;
				}
			}
		}
	}
}

} ?>
