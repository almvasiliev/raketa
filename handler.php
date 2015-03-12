<?php
$date  = mktime(0, 0, 0, $zero_date_month, $zero_date_day-1+$i, $zero_date_year);
$date = date("d.m.Y", $date);
echo ('<tr>');
echo ("<td>$date</td>");
if (!in_array($i,$array_unique_ts_day_start))
	{
		for ($j=0; $j < count($array_unique_pm_id); $j++)
			{
				echo "<td>-</td>";
			}
	}
else
	{
		$array_flip_keys_ts_day_start = array_flip(array_keys($array_ts_day_start,$i));
		$array_pm_id_this_str = array_intersect_key($array_pm_id,$array_flip_keys_ts_day_start);
		$array_price_this_str = array_intersect_key($array_price,$array_flip_keys_ts_day_start);
		$array_a_count_this_str = array_intersect_key($array_a_count,$array_flip_keys_ts_day_start);
		
			for ($j=0; $j < count($array_unique_pm_id); $j++)
				{
					//Возможна ситуация, когда за день, для одного и того же товара несколько раз меняется цена.
					//Поэтому итоговая сумма за день, в обшем случае, будет складываться из нескольких позиций цен на конкретный товар и количества единиц проданного товара соответственно.
					if (!in_array($array_unique_pm_id[$j],$array_pm_id_this_str))
						{
							echo "<td>0</td>";
						}
					else
						{
							$price_this_str = '0';
							$note = '';
							$array_keys_pm_id_this_str = array_keys($array_pm_id_this_str,$array_unique_pm_id[$j]);

							foreach ($array_keys_pm_id_this_str as $val)
								{
									$price_this_str += $array_price_this_str[$val]*$array_a_count_this_str[$val];
									$note .= "$array_price_this_str[$val]х$array_a_count_this_str[$val]+";
								}
							$note = substr($note, 0, -1);
							echo("<td>$price_this_str<font size='-1'><sup>$note</sup></font></td>");
						}
				}
	}
echo('</tr>');
?>