<?php

class Casee {
	public $pX;
	public $pY;
	public $type;
	public function __construct($pX, $pY, $type){
		$this->pX = $pX;
		$this->pY = $pY;
		$this->type = $type;

	}
}

class Plateau {
	public $largeur;
	public $hauteur;
	public $cases;
	public $perso;
	public $caisses;
	public function __construct($murs, $perso, $caisses, $cibles){
		$this->perso = $perso;
		$this->caisses = $caisses;
		$this->largeur = 0;
		$this->hauteur = 0;
		for($i = 0; $i < count($murs); $i++)
		{
			if ($murs[$i][0] > $this->largeur){
				$this->largeur = $murs[$i][0];
			}
			if ($murs[$i][1] > $this->hauteur) {
				$this->hauteur = $murs[$i][1];
			}
			$this->cases[] = new Casee($murs[$i][0], $murs[$i][1], 'mur');
		}

		for($i = 0; $i < count($cibles); $i++)
		{
			$this->cases[] = new Casee($cibles[$i][0], $cibles[$i][1], 'cible');
		}
		// Ajoute 1 à la largeur et la hauteur car les index commencent à 0 
		$this->largeur ++; 
		$this->hauteur ++; 
	}
	public function ouAller(){
		$result = [];
		if($this->getType($this->perso[0], $this->perso[1]-1)!= 'mur'){
			$result[] = 'haut';
		}
		if($this->getType($this->perso[0]+1, $this->perso[1])!= 'mur'){
			$result[] = 'droite';
		}
		if($this->getType($this->perso[0], $this->perso[1]+1)!= 'mur'){
			$result[] = 'bas';
		}
		if($this->getType($this->perso[0]-1, $this->perso[1])!= 'mur'){
			$result[] = 'gauche';
		}
		return $result;
	}
	public function getType($pX, $pY){
		for($i = 0; $i < count($this->cases); $i++)
		{
			$case = $this->cases[$i];
			if ($case->pX == $pX && $case->pY == $pY)
			{
				if ($case->type == 'mur') {
					return 'mur';
				}
				elseif ($case->type == 'cible') {
					return 'cible';
				}
			}
		}
		return 'sol';
	}
	public function isFini(){
		
		for($i = 0; $i < count($this->caisses); $i++)
		{
			if ($this->getType($this->caisses[$i][0], $this->caisses[$i][1]) != 'cible') {
				return false;
			}
		}
		return true;
		
	}
	
}
?>