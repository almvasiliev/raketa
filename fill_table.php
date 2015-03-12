<?php

	$values = '';

	for ($i = 0; $i < 10000; $i++)
		{
			$ts_day_start = mt_rand('1','1365');
			$pm_id = mt_rand('1','99');
			$price = mt_rand('10','25');
			$a_count = mt_rand('0','50');

			$values .= "('$ts_day_start', '$pm_id', '$price', '$a_count'), ";
		}

	unset($ts_day_start, $pm_id, $price, $a_count);
	
	$values = substr($values, 0, -2).';';

	try
		{
			$db = new PDO('mysql:host=localhost;dbname=db_purchases','root','root');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $db->prepare("INSERT INTO purchases (purchases.ts_day_start, purchases.pm_id, purchases.price, purchases.a_count) VALUES $values");
			$stmt->execute();
		}
	catch (PDOException $e)
		{
			echo 'PDO Error!';
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
		}
	unset($values, $i);
?>