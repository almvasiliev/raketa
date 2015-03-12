<?php
	
	require_once ('back2url.html');
	$error_404 = '404.html';

	try
		{
			$db = new PDO('mysql:host=localhost;dbname=db_purchases','root','root');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $db->prepare("SELECT * FROM purchases ORDER BY purchases.ts_day_start");
			$stmt->execute();
		}
	catch (PDOException $e)
		{
			echo 'PDO Error!';
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
		}
	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$array_ts_day_start = array();
	$array_pm_id = array();
    $array_price = array();
    $array_a_count = array();

	foreach ($result as $array_values)
		{
    		$array_ts_day_start[] = $array_values['ts_day_start'];
    		$array_pm_id[] = $array_values['pm_id'];
			$array_price[] = $array_values['price'];
	    	$array_a_count[] = $array_values['a_count'];
    	}
	
    $zero_date = '27.05.2011';//Соответствует значению ts_day_start в БД равному '1'.
    $array_zero_date = explode(".", $zero_date);
    $zero_date_day = intval($array_zero_date[0]);
    $zero_date_month = intval($array_zero_date[1]);
    $zero_date_year = intval($array_zero_date[2]);
    $zero_date_one_day_before  = mktime(0, 0, 0, $zero_date_month  , $zero_date_day-1, $zero_date_year);
	$zero_date_one_day_before = date("d-m-Y", $zero_date_one_day_before);
	$start_date = $zero_date;//'27.05.2011';
    $finish_date = date('d.m.Y');

    $date_notice = 'You wrote the wrong date.';

    if (isset($_POST['ds']) and isset($_POST['df']))
		{
			if (!empty($_POST['ds']) and !empty($_POST['df']))
				{
					if (preg_match('/^(((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02])\.((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\.(0[13456789]|1[012])\.((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\.02\.((19|[2-9]\d)\d{2}))|(29\.02\.((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/', $_POST['ds']) and preg_match('/^(((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02])\.((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\.(0[13456789]|1[012])\.((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\.02\.((19|[2-9]\d)\d{2}))|(29\.02\.((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/', $_POST['df']))
						{
							$start_date = $_POST['ds'];
							$finish_date = $_POST['df'];
							$date_notice = '';
						}
				}
		}
   
    $num_days_start_to_zero = floor((strtotime($start_date)-strtotime($zero_date))/86400);//Лучше пользоваться классом DateInterval, однако, этот класс применим только в PHP 5 >= 5.3.0.

	
	if (!empty($date_notice))
		{
			echo "<table><tr><td>You wrote the wrong date range! That's why we were shown all purchases.</td></tr></table>";
		}

	if ($num_days_start_to_zero < 0)
		{
			echo "<table><tr><td>There are not purchases from $start_date to $zero_date_one_day_before</td></tr></table>";
			$start_date = $zero_date;
			$num_days_start_to_zero = 0;
		}

	$array_start_date = explode(".", $start_date);
    $start_date_day = intval($array_start_date[0]);
    $start_date_month = intval($array_start_date[1]);
    $start_date_year = intval($array_start_date[2]);

    $num_days = floor((strtotime($finish_date)-strtotime($start_date))/86400);//Лучше пользоваться классом DateInterval, однако, этот класс применим только в PHP 5 >= 5.3.0.

	//Принимаем во внимание, что массив ID товаров $array_pm_id может содержать различные числовые значения.
    //Например, некторые ID могут вовсе отсутсвовать, скажем по причине того, что товар уже давно снят с продажи, еще до запуска интернет магазина.
    //Поэтому формируем массив из неповторяющихся всех имеющихся ID после выборки из БД.
    //Воспользуемся связкой array_keys(array_flip($array_pm_id) вместо array_unique($array_pm_id), т.к. по скорости эта связка предпочтительнее.

    $array_unique_pm_id = array_values(array_keys(array_flip($array_pm_id)));

    if (isset($_POST['id']))
		{
			if ($_POST['id'] == 'sort_by_ID_desc'){rsort($array_unique_pm_id);}
			else {sort($array_unique_pm_id);}
		}
	else
		{
			back2url("$error_404",0);
		}

	$array_unique_ts_day_start = array_values(array_keys(array_flip($array_ts_day_start)));

	echo('<table><tr><td>Date/Goods(ID)</td>');
    		foreach ($array_unique_pm_id as $key => $value)
	    		{
	    			echo "<td>ID#$value</td>";
	    		}

	echo('</tr>');

	if (isset($_POST['date']))
		{
			if ($_POST['date'] == 'sort_by_date_desc')
				{
					rsort($array_unique_ts_day_start);
						for ($i=$num_days_start_to_zero+$num_days+1; $i > $num_days_start_to_zero; $i--){require ('handler.php');}
				}
			else
				{
					sort($array_unique_ts_day_start);
						for ($i=$num_days_start_to_zero+1; $i < $num_days_start_to_zero+$num_days+2; $i++){require ('handler.php');}
				}
		}
	else
		{
			back2url("$error_404",0);
		}
    				
	echo('
		</table>
		');
?>