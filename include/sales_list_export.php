<?

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=sales_'.$start.'_'.$end.'.csv');
    header('Pragma: no-cache');
    echo "Product,Gender,Amount,Price,Date\n";


	$ajax = checkajax();

	$start = strftime('%Y-%m-%d',strtotime($_SESSION['salesstart']));
	$end = strftime('%Y-%m-%d',strtotime($_SESSION['salesend']));

	$query='SELECT pro.name AS product, gen.label AS gender, tran.count AS amount, -tran.drops AS price, tran.transaction_date AS transaction_date 
	FROM (transactions AS tran, people AS pp)
	LEFT OUTER JOIN products AS pro ON tran.product_id = pro.id
	LEFT OUTER JOIN genders AS gen ON pro.gender_id = gen.id
	WHERE tran.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND tran.product_id > 0 AND tran.transaction_date >= "'.$start.' 00:00" AND tran.transaction_date <= "'.$end.' 23:59"
	ORDER BY tran.id';

	echo $tran['product'].','.$tran['gender'].','.$tran['amount'].','.$tran['price'].','.$tran['transaction_date']."\n";
	
	while($f = db_fetch($result)) {
		echo '"'.$f['container'].'";"'.$f['firstname'].'";"'.$f['lastname'].'";"'.$f['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		
		$result2 = db_query('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age, GROUP_CONCAT(l.name SEPARATOR ", ") AS languages 
		FROM people AS p
		LEFT OUTER JOIN x_people_languages AS x ON x.people_id = p.id LEFT OUTER JOIN languages AS l ON l.id = x.language_id 
		WHERE camp_id = :campid AND NOT deleted AND parent_id = :parent_id 
		GROUP BY p.id
		ORDER BY date_of_birth DESC',array('campid'=>$_SESSION['camp']['id'],'parent_id'=>$f['id']));
		while($s = db_fetch($result2)) {
			echo '"";"'.$s['firstname'].'";"'.$s['lastname'].'";"'.$s['gender'].'";"'.$f['age'].'";"'.$f['languages'].'"'."\n";
		}
	}
	die();