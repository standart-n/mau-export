<?php class pl_getComponents {

function engine(&$q) { $er=false;
	if (isset($q->components)) {
		foreach ($q->components as $key) {
			if (isset($key->ajax)) {
				foreach ($key->ajax as $m) {
					if (!$q->base->ajax($q,$m)) { 
						$er=true;
					}
				}
			}
			if (isset($key->controls)) {
				foreach ($key->controls as $m) {
					if (!$q->base->controls($q,$m)) { 
						$er=true;
					}
				}
			}
			if (isset($key->templates)) {
				foreach ($key->templates as $m) {
					if (!$q->base->tpl($q,$m)) { 
						$er=true;
					}
				}
			}
			if (isset($key->sql)) {
				foreach ($key->sql as $m) {
					if (!$q->base->sql($q,$m)) { 
						$er=true;
					}
				}
			}
		}
	}
}

}
