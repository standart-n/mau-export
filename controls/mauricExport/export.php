<?php class export {

function engine(&$q) {
	$q->html=$this->showExport($q);
}

function showExport(&$q) { $s=""; $i=0;

	header('Content-Type: application/xml');
	$dom = new DOMDocument('1.0', 'utf-8');

	$ymaps = $dom -> createElement('ymaps:ymaps');
	$ymaps -> setAttribute('xsi:schemaLocation', 'http://maps.yandex.ru/schemas/ymaps/1.x/ymaps.xsd');
	$ymaps -> setAttribute('xmlns:ymaps', 'http://maps.yandex.ru/ymaps/1.x');
	$ymaps -> setAttribute('xmlns:repr', 'http://maps.yandex.ru/representation/1.x');
	$ymaps -> setAttribute('xmlns:gml', 'http://www.opengis.net/gml');
	$ymaps -> setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');


	$ymaps -> appendChild($this->representation(&$q,$dom));
	$ymaps -> appendChild($this->geoObjectCollection(&$q,$dom));

	$dom -> appendChild($ymaps);
	$s = $dom -> saveXML();

	return $s;	
}

function members(&$q,$dom) {

	$members = $dom -> createElement('gml:featureMembers');

		if (query($q,$this -> getSql($q),$m)) {
			foreach ($m as $key) {


						$object = $dom -> createElement('ymaps:GeoObject');

							$propertys = $dom -> createElement('ymaps:metaDataProperty');

								$meta = $dom -> createElement('ymaps:AnyMetaData');					

									$url = $dom -> createElement('ymaps:url');

									$title = $dom -> createElement('ymaps:title');
									$title_inner = $dom -> createTextNode(iconv("cp1251","UTF-8",$key->SVID));
									$title -> appendChild($title_inner);

									$lifesit = $dom -> createElement('ymaps:lifesit');

									$ptype = $dom -> createElement('ymaps:ptype');
									$ptype_inner = $dom -> createTextNode('1');
									$ptype -> appendChild($ptype_inner);

									$features = $dom -> createElement('ymaps:features');

									$meta -> appendChild($url);
									$meta -> appendChild($title);
									$meta -> appendChild($lifesit);
									$meta -> appendChild($ptype);
									$meta -> appendChild($features);

								$propertys -> appendChild($meta);


							$name = $dom -> createElement('gml:name');

							$description = $dom -> createElement('gml:description');
							$description_inner = $dom -> createCDATASection($this->descr($key));
							$description -> appendChild($description_inner);

							$point = $dom -> createElement('gml:Point');
							$pos = $dom -> createElement('gml:pos');
							$pos_inner = $dom -> createTextNode($key->POINT);
							$pos -> appendChild($pos_inner);
							$point -> appendChild($pos);

						$object -> appendChild($propertys);
						$object -> appendChild($name);
						$object -> appendChild($description);
						$object -> appendChild($point);

						$members -> appendChild($object);

				
				//}
			}
		}

	return $members;

}

function geoObjectCollection(&$q,$dom) {
	$collection = $dom -> createElement('ymaps:GeoObjectCollection');

		$collection -> appendChild($this->members(&$q,$dom));

		$style = $dom -> createElement('ymaps:style');
		$style_inner = $dom -> createTextNode('#styleCafe');
		$style -> appendChild($style_inner);

		$collection -> appendChild($style);

	return $collection;
}

function getSql($q) { $s="";
	// $region=iconv("UTF-8","cp1251",$q->url->region);
	$s.="select * ";
	$s.="from VW_BAD_ROADS ";
	$s.="where status = 0 and PERIOD_END is null";

	//$s.="select rd.VAL as VAL from BAD_ROADS * ";
	//$s.="where 1=1 ";
	//$s.="order by d.caption;";
	//	echo $s;
	return $s;
}

function descr($m) {
	return "
		<p>
			<table class='table'>
				<tr>
					<td>Исполнитель:</td>
					<td>".iconv("cp1251","UTF-8",$m->SAGENT)."</td>
				</tr>
				<tr>
					<td>Дата начала:</td>
					<td>".iconv("cp1251","UTF-8",$m->PERIOD_BEG)."</td>
				</tr>
				<tr>
					<td>План. дата закр.:</td>
					<td>".iconv("cp1251","UTF-8",$m->PLAN_PERIOD_END)."</td>
				</tr>
			</table>
		</p>
		";
}

function representation(&$q,$dom) {

	$representation = $dom -> createElement('repr:Representation');
		$style = $dom -> createElement('repr:Style');
		$style -> setAttribute('gml:id', 'styleCafe');

			$iconStyle = $dom -> createElement('repr:iconStyle');

				$href = $dom -> createElement('repr:href');
				$href_inner = $dom -> createTextNode('http://test.izh.ru/images/map/map_icons/roadwork.png');
				$href -> appendChild($href_inner);
				$iconStyle -> appendChild($href);

				$size = $dom -> createElement('repr:size');
				$size -> setAttribute('x', '46');
				$size -> setAttribute('y', '47');
				$iconStyle -> appendChild($size);

				$offset = $dom -> createElement('repr:offset');
				$offset -> setAttribute('x', '-16');
				$offset -> setAttribute('y', '-14');
				$iconStyle -> appendChild($offset);

			$style -> appendChild($iconStyle);

			$balloonContentStyle = $dom -> createElement('repr:balloonContentStyle');

				$template = $dom -> createElement('repr:template');
				$template_inner = $dom -> createTextNode('#balloonTemplate');
				$template -> appendChild($template_inner);
				$balloonContentStyle -> appendChild($template);

			$style -> appendChild($balloonContentStyle);

		$representation -> appendChild($style);


		$template = $dom -> createElement('repr:Template');
		$template -> setAttribute('gml:id', 'balloonTemplate');

			$text = $dom -> createElement('repr:text');
			$text_innner = $dom -> createCDATASection('<div style="font-size:14px;"><div>$[description]</div><div><img src="http://test.izh.ru/res_ru/$[metaDataProperty.AnyMetaData.foto]"/></div></div>');
			$text -> appendChild($text_innner);

			$template -> appendChild($text);

		$representation -> appendChild($template);

		return $representation;	
}


} ?>
