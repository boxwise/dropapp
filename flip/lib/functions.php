<?php

function makeproductimage($id) {
	$p = db_row('SELECT url, angle1, angle2, c1.cmyk AS color1, c2.cmyk AS color2, customimage FROM products AS p, colors AS c1, colors AS c2 WHERE p.color1_id = c1.id AND p.color2_id = c2.id AND p.id = :id',array('id'=>$id));

	if($p['customimage']) {
		copy($_SERVER['DOCUMENT_ROOT'].'/content/product-svg-template/'.$p['customimage'].'-small.svg',
			$_SERVER['DOCUMENT_ROOT'].'/content/product-svg/'.$p['url'].'-small.svg');
		copy($_SERVER['DOCUMENT_ROOT'].'/content/product-svg-template/'.$p['customimage'].'-big.svg',
			$_SERVER['DOCUMENT_ROOT'].'/content/product-svg/'.$p['url'].'-big.svg');
		return;
	}

	$d['c1'] = tan(deg2rad($p['angle1']));
	$d['c2'] = tan(deg2rad($p['angle2']));

	$d['color1'] = cmyk2rgb($p['color1']);
	$d['color2'] = cmyk2rgb($p['color2']);
	$d['color3'] = multiply($p['color1'],$p['color2']);

	$d['x'] = 1232;
	$d['y'] = 250;
	$d['x'] = intval(833 + ($d['c1']*($d['y']+173)));
	$d['bx'] = 2500;
	$d['by'] = 450;

	$img = new Zmarty;
	$img->assign('d',$d);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/content/product-svg/'.$p['url'].'-big.svg',$img->fetch($_SERVER['DOCUMENT_ROOT'].'/flip/templates/productimage.tpl'));

	$d['x'] = 300;
	$d['y'] = 150;
	$d['bx'] = 600;
	$d['by'] = 300;

	$img = new Zmarty;
	$img->assign('d',$d);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/content/product-svg/'.$p['url'].'-small.svg',$img->fetch($_SERVER['DOCUMENT_ROOT'].'/flip/templates/productimage.tpl'));
}

function multiply($cmyk1,$cmyk2) {

	list($c1,$m1,$y1,$k1)=explode(',',$cmyk1);
	list($c2,$m2,$y2,$k2)=explode(',',$cmyk2);

	$c3 = 100-intval((100-$c1)*((100-$c2)/100));
	$m3 = 100-intval((100-$m1)*((100-$m2)/100));
	$y3 = 100-intval((100-$y1)*((100-$y2)/100));
	$k3 = 100-intval((100-$k1)*((100-$k2)/100));

	return cmyk2rgb("$c3,$m3,$y3,$k3");
}
function cmyk2rgb($cmyk) {

	list($c,$m,$y,$k)=explode(',',$cmyk);

    $x = round($y/5) * 21 + round($c/5);
    $y = round($k/5) * 21 + round($m/5);

    if(!$cmykMap) $cmykMap = $_SERVER["DOCUMENT_ROOT"].'/site/img/cmyk_map_sRGB-IEC61966-21.png';
    if(!file_exists($cmykMap)) return false;

    $im = ImageCreateFromPng($cmykMap);
    $rgb = ImageColorAt($im, $x, $y);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

    return sprintf("#%02X%02X%02X", $r, $g, $b);
}
