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
	public $movements;
	public $impasses;

	public function __construct($murs, $perso, $caisses, $cibles){
		$this->impasses = [];
		$this->perso = $perso;
		$this->caisses = $caisses;
		$this->largeur = 0;
		$this->hauteur = 0;

		// On défini la hauteur et la largeur du plateau, puis on instancie chaque cases de type mur
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

		// On instancie les cases de type cible.
		for($i = 0; $i < count($cibles); $i++)
		{
			$this->cases[] = new Casee($cibles[$i][0], $cibles[$i][1], 'cible');
		}

		// Ajoute 1 à la largeur et la hauteur car les index commencent à 0 
		$this->largeur ++; 
		$this->hauteur ++; 
	}

	// renvoi un tableau de déplacement possible
	public function ouAller(){
		$result = []; $haut = []; $droite = []; $bas = []; $gauche = []; $dir = [];

		// Assigne les positions des cases utilisées pour determiner les deplacements
		// $i est la position du perso, $i[1] la position aprés deplacement, $i[2] la position 1 case aprés le deplacement
		for ($i = 1; $i < 3; $i++){
			$dir['haut'][$i] = [$this->perso[0], $this->perso[1]-$i];
			$dir['droite'][$i] = [$this->perso[0]+$i, $this->perso[1]];
			$dir['bas'][$i] = [$this->perso[0], $this->perso[1]+$i];
			$dir['gauche'][$i] = [$this->perso[0]-$i, $this->perso[1]];
		}

		foreach ($dir as $key => $value){
			// On vérifie que la case aprés deplacement ne soit pas un mur
			if ($this->getType($value[1][0], $value[1][1])!= 'mur'){
				$depIsCaisse = false; $afterIsCaisse = false;

				for($i = 0; $i < count($this->caisses); $i++){
					// On regarde si la position aprés deplacement est une caisse
					if ($this->caisses[$i][0] == $value[1][0] && $this->caisses[$i][1] == $value[1][1]){
						$depIsCaisse = true;
					}
					// Et si 1 case aprés deplacement est une caisse
					if ($this->caisses[$i][0] == $value[2][0] && $this->caisses[$i][1] == $value[2][1]){
						$afterIsCaisse = true;
					}
				}
				if ($depIsCaisse == true){
					if ($this->getType($value[2][0], $value[2][1]) != 'mur' && $afterIsCaisse == false){
						$result[] = $key;
					}
				}else {
					$result[] = $key;
				}
			}
		}
		return $result;
	}

	// Défini si une case est de type 'mur', 'cible' ou 'autre'.
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

	// Vérifie si le jeu est fini
	public function isFini(){
		
		for($i = 0; $i < count($this->caisses); $i++)
		{
			if ($this->getType($this->caisses[$i][0], $this->caisses[$i][1]) != 'cible') {
				return false;
			}
		}
		return true;
	}

	// Deplace le personnage et les caisses en fonction des deplacements
	public function moove($dir){

		if ($dir == 'haut') {
			$dest = [$this->perso[0], $this->perso[1]-1];
			$destPlus1 = [$this->perso[0], $this->perso[1]-2];
		}
		elseif ($dir == 'droite') {
			$dest = [$this->perso[0]+1, $this->perso[1]];
			$destPlus1 = [$this->perso[0]+2, $this->perso[1]];
		}
		elseif ($dir == 'bas') {
			$dest = [$this->perso[0], $this->perso[1]+1];
			$destPlus1 = [$this->perso[0], $this->perso[1]+2];
		}
		elseif ($dir == 'gauche') {
			$dest = [$this->perso[0]-1, $this->perso[1]];
			$destPlus1 = [$this->perso[0]-2, $this->perso[1]];
		}
		else { return 'Mauvaise direction'; }

		// Mise à jour de la position du perso
		$this->perso = $dest;

		// Mise à jour de la position des caisses
		for($i = 0; $i < count($this->caisses); $i++)
		{
			if ($this->caisses[$i][0] == $dest[0] && $this->caisses[$i][1] == $dest[1]) {
				$this->caisses[$i] = $destPlus1;
			}
		}

		$this->movements[] = $dir;
	}

	public function inverse($value){
		if ($value == 'haut'){ return 'bas';}
		elseif ($value == 'bas'){ return 'haut';}
		elseif ($value == 'droite'){ return 'gauche';}
		elseif ($value == 'gauche'){ return 'droite';}
	}

	public function parcourir($lastDir = null){
		$possibilites = $this->ouAller();

		foreach ($this->impasses as $impasse) {
			if ($this->perso == $impasse[0]){
				if(($key = array_search($impasse[1], $possibilites)) !== false) {
					    unset($possibilites[$key]);
				}
			}
		}


		if(($key = array_search($this->inverse($lastDir), $possibilites)) !== false) {
			unset($possibilites[$key]);
		}


		foreach ($possibilites as $key => $possibilite){
			$this->moove($possibilite);
			if ($this->isFini()){
				$result = $this->movements;
				//var_dump($result);
				return $result;
			};

			// NE FONCTIONNE PAS A REVOIR
			//if (!empty($this->ouAller)){
			//	$this->moove($this->inverse($possibilite));
			//	$this->impasses = [$this->perso, $possibilite];
			//
			//}
			$this->parcourir($possibilite);
		}

	}

}


?>