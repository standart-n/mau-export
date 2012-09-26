<?php class pl_baseFdb {

function engine(&$q) {
	if (isset($q->database)) {
		if (isset($q->database->connections)) {
			foreach ($q->database->connections as $alias=>$cn){
				if (isset($cn->type)) {
					if (($cn->type=="firebird") || ($cn->type=="fb")) { 
						$cn->type="firebird";
						$q->$alias->type=$cn->type;
						if (isset($cn->charset)) { $q->$alias->charset=$cn->charset; }
						if ((isset($cn->path)) && (isset($cn->login)) && (isset($cn->password))) {
							$q->$alias->db=ibase_connect($cn->path,$cn->login,$cn->password);
							if (isset($cn->transactions)) {
								foreach ($cn->transactions as $it_alias=>$it) {
									$q->$alias->$it_alias=ibase_trans(IBASE_WRITE+IBASE_COMMITTED+IBASE_REC_VERSION+IBASE_NOWAIT,$q->$alias->db);
								}						
							}					
						}				
					}			
				}		
			}
		}
	}
}

} ?>
