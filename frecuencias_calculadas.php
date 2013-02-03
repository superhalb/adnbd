<?php

$adndb = new AdnDB();
$profs = $adndb->allProfiles();

foreach($profs as $prof)
{
	$strs = $prof['strs'];
	foreach(array_keys($strs) as $str)
	{
		if(!isset($frec[$str]))
		{
			$frec[$str] = array();
			$frec[$str]['total'] = 0;
		}
		
		$al1 = $strs[$str]['al1'];
		$al2 = $strs[$str]['al2'];
		
		if(!isset($frec[$str][$al1])) $frec[$str][$al1] = 0;
		if(!isset($frec[$str][$al2])) $frec[$str][$al2] = 0;
		
		$frec[$str][$al1]++;
		$frec[$str][$al2]++;
		$frec[$str]['total']+=2;
	}
}

foreach(array_keys($frec) as $str)
{
	$totalStr = $frec[$str]['total'];
	foreach(array_keys($frec[$str]) as $alelo)
	{
		$frec[$str][$alelo] = $frec[$str][$alelo] * 100 / $totalStr; 
	}
}

?>