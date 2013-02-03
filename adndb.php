<?php

$frec = array();

class AdnDB
{
  public $version = "Beta 0.1";
	
	private $profiles;
	
	public $relStr = array (
		0=>"",
		1=>"Padre/Madre",
		2=>"Hermano/a",
		3=>"Adoptado/a");
		
	public $provStr = array(
		0 =>'',
		2 =>'&Aacute;lava',
		3 =>'Albacete',
		4 =>'Alicante/Alacant',
		5 =>'Almer&iacute;a',
		6 =>'Asturias',
		7 =>'&Aacute;vila',
		8 =>'Badajoz',
		9 =>'Barcelona',
		10 =>'Burgos',
		11 =>'C&aacute;ceres',
		12 =>'C&aacute;diz',
		13 =>'Cantabria',
		14 =>'Castell&oacute;n/Castell&oacute;',
		15 =>'Ceuta',
		16 =>'Ciudad Real',
		17 =>'C&oacute;rdoba',
		18 =>'Cuenca',
		19 =>'Girona',
		20 =>'Las Palmas',
		21 =>'Granada',
		22 =>'Guadalajara',
		23 =>'Guip&uacute;zcoa',
		24 =>'Huelva',
		25 =>'Huesca',
		26 =>'Islas Baleares',
		27 =>'Ja&eacute;n',
		28 =>'A Coru&ntilde;a',
		29 =>'La Rioja',
		30 =>'Le&oacute;n',
		31 =>'Lleida',
		32 =>'Lugo',
		33 =>'Madrid',
		34 =>'M&aacute;laga',
		35 =>'Melilla',
		36 =>'Murcia',
		37 =>'Navarra',
		38 =>'Ourense',
		39 =>'Palencia',
		40 =>'Pontevedra',
		41 =>'Salamanca',
		42 =>'Segovia',
		43 =>'Sevilla',
		44 =>'Soria',
		45 =>'Tarragona',
		46 =>'Santa Cruz de Tenerife',
		47 =>'Teruel',
		48 =>'Toledo',
		49 =>'Valencia/Val&eacute;ncia',
		50 =>'Valladolid',
		51 =>'Vizcaya',
		52 =>'Zamora',
		53 =>'Zaragoza'
		);
		
	public $ccaaStr = array(
		0 => "",
		1 => "Andalucía",
 		2 => "Aragón",
		3 => "Canarias",
		4 => "Cantabria",
		5 => "Castilla y León",
		6 => "Castilla-La Mancha", 
		7 => "Cataluña",
		8 => "Ceuta",
		9 => "Comunidad Valenciana",
		10 => "Comunidad de Madrid",
		11 => "Extremadura",
		12 => "Galicia", 
		13 => "Islas Baleares", 
		14 => "La Rioja",
		15 => "Melilla", 
		16 => "Navarra",
		17 => "País Vasco", 
		18 => "Principado de Asturias",
		19 => "Región de Murcia");

	public function __construct()
	{
		try 
		{
			$mongoConn = new Mongo(); // connect
			$db = $mongoConn->selectDB("adn");
			$db->createCollection('profiles');
			$this->profiles = $db->selectCollection('profiles');
		}
		catch ( MongoConnectionException $e ) 
		{
			echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
			exit();
		}
	}
	
	public function add($profile)
	{
		$this->profiles->insert($profile);
	}
	
	public function delete($profile)
	{
		$this->profiles->remove( array('_id' => new MongoId($profile['_id'])));
	}

	public function getProfile($id)
	{
		return $this->profiles->findone(array('_id' => new MongoId($id)));
	}
	
	public function updateProfile($profile)
	{
		$this->profiles->update(array('_id' => new MongoId($profile['_id'])),$profile);
	}

	private function makeLike($v)
	{
	  $expresion = "/^" . $v ."/i";
	  //echo "(" . $v . " => " . $expresion . ")";
		return new MongoRegex($expresion);
	}
	
	public function allProfiles()
	{
		return $this->profiles->find();
	}
	
	public function search($profile)
	{
		$re_name = $this->makeLike($profile['name']);
		$re_surname = $this->makeLike($profile['surname']);
		$re_date = $this->makeLike($profile['date']);
		$re_contact = $this->makeLike($profile['contact']);
		
		$query = array(
			'name'=>$re_name,
			'surname'=>$re_surname,
			'date'=>$re_date,
			'contact'=>$re_contact,
		);

		if($profile['prov']!=0)
		{
			$query['prov'] = $this->makeLike($profile['prov']);
		}
		if($profile['ccaa']!=0)
		{
			$query['ccaa'] = $this->makeLike($profile['ccaa']);
		}
		if($profile['rel']!=0)
		{
			$query['rel'] = $this->makeLike($profile['rel']);
		}
		
		return $this->profiles->find($query);
	}
	
	protected function strIP($strName, $strC, $strP)
	{
		global $frec;
		
		$predeffrec = 0.01 * 20;
		
		if($strC['al1']==$strC['al2'])
			$child = "Q";
		else
			$child = "PQ";

		if($strP['al1']==$strP['al2'])
			$parent = "Q";
		else
			$parent = "QR";

		$ipformula = $child . $parent;
		
		if($ipformula == "QQ")
		{
			if($strC['al1']==$strP['al1'])
				$q = 0.01 * $frec[$strName][$strC['al1']];
			else return 0;
			if($q == null) $q=$predeffrec;
			return 1/$q;
		}
		
		if($ipformula == "PQQ")
		{
			if($strC['al1']==$strP['al1'])
				$q = 0.01 * $frec[$strName][$strC['al1']];
			else if($strC['al2']==$strP['al1'])
				$q = 0.01 * $frec[$strName][$strC['al2']];
			else return 0;
			if($q == null) $q=$predeffrec;
			return 1/(2*$q);
		}
		
		if($ipformula == "QQR")
		{
			if($strC['al1']==$strP['al1'])
				$q = 0.01 * $frec[$strName][$strP['al1']];
			else if($strC['al1']==$strP['al2'])
				$q = 0.01 * $frec[$strName][$strP['al2']];
			else return 0;
			if($q == null) $q=$predeffrec;
			return 1/(2*$q);
		}

		if($ipformula == "PQQR")
		{
			if(($strC['al1']==$strP['al1'])&&($strC['al2']==$strP['al2'])) // PQPQ
			{
				$p = 0.01 * $frec[$strName][$strC['al1']];
				$q = 0.01 * $frec[$strName][$strC['al2']];
				if($p == null) $p=$predeffrec;
				if($q == null) $q=$predeffrec;
				return ( $p + $q ) / (4 * $p * $q);
			}
			else if(($strC['al1']==$strP['al1'])||($strC['al1']==$strP['al2']))
				$q = 0.01 * $frec[$strName][$strC['al1']];
			else if(($strC['al2']==$strP['al1'])||($strC['al2']==$strP['al2']))
				$q = 0.01 * $frec[$strName][$strC['al2']];
			else return 0;
			if($q == null) $q=$predeffrec;
			return 1/(4*$q);
		}
	}
	
	public function comp($p0,$p1)
	{
		global $frec;

		$result = array();
		$strs0 = $p0['strs'];
		$strs1 = $p1['strs'];
		
		foreach(array_keys($frec) as $strName)
		{
			if(in_array($strName,array_keys($strs0)) && in_array($strName,array_keys($strs1)))
			{
				$result[$strName] = $this->strIP($strName, $strs0[$strName], $strs1[$strName]);
			}
		}
		
		$ipTotal = 1;
		$matchs = 0;
		$checks = 0;
		foreach(array_keys($result) as $strName)
		{
			$checks++;
			if($result[$strName]>0) $matchs++;
			$ipTotal = $ipTotal * $result[$strName];
		}
		
		$result['ipTotal'] = $ipTotal*100 / ($ipTotal+1);
		$result['matchs'] = $matchs;
		$result['checks'] = $checks;
		
		return $result;
	}
}
?>