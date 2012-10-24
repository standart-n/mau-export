<?php class export {

function engine(&$q) {
	$q->html=$this->showExport($q);
}

function showExport(&$q) { $s=""; $i=0;
	if (query($q,$this->getSql($q),$m)) {
		//echo $this->getSql($q);
		foreach ($m as $key) { $i++;
			//$s.=$q->validate->toWin($key->VAL);
			$s.=mb_convert_encoding($key->VAL,"UTF-8","cp1251")."<br>\r\n";
		}
	}
	return $s;	
}

function getSql($q) { $s="";
	$s.="select ";
	$s.="trim(coalesce(a.caption,''))||';'|| ";
	$s.="trim(coalesce(d.vid,''))||';'|| ";
	$s.="trim(coalesce(b.street,''))||';'|| ";
	$s.="trim(coalesce(b.nomer,''))||';'|| ";
	$s.="trim(coalesce(a.apartment,''))||';'|| ";
	$s.="trim(coalesce(d.caption,''))||';'|| ";
	$s.="trim(coalesce(cast(ad.insertdt as dm_date),''))||';'|| ";
	$s.="trim(coalesce(cast(ad.val as numeric(15,0)),'')) as VAL ";
	$s.="from account_data ad ";
	$s.="left join device d on ad.device_d\$uuid=d.d\$uuid ";
	$s.="left join accounts a on d.account_d\$uuid=a.d\$uuid ";
	$s.="left join buildings b on a.building_d\$uuid=b.d\$uuid ";
	$s.="where cast(ad.insertdt as dm_date) between '".$q->url->from."' and '".$q->url->to."' ";
	$s.="order by d.caption;";
	return $s;
}

} ?>
