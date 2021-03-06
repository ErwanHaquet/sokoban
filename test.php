<?php
include 'ex.php';
class Test extends PHPUnit_Framework_TestCase
{
	public function test()
	{

		$this->assertEquals(true, true);
	}

	public function testplateau()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], null, null, null);
		$this->assertEquals(5,$plateau->largeur);
		$this->assertEquals(3,$plateau->hauteur);
	}

	public function testmurtrue()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], null, null);
		$this->assertEquals('mur',$plateau->getType(0,0));
	}

	public function testmurfalse()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], null, null);
		$this->assertEquals('sol',$plateau->getType(1,1));
	}

	public function testmurtrue2()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], null, null);
		$this->assertEquals('mur',$plateau->getType(1,0));
	}

	public function testperso()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], null, null);
		$this->assertEquals(['droite'], $plateau->ouAller());
	}

	public function testperso2()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [2,1], null, null);
		$this->assertEquals(['droite', 'gauche'], $plateau->ouAller());
	}

	public function testjeufinifalse()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], [[3,1]]);
		$this->assertEquals(false, $plateau->isFini());
	}

	public function testjeufinitrue()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], [[2,1]]);
		$this->assertEquals(true, $plateau->isFini());
	}

	public function testCaisseCaisse()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1], [3,1]], null);
		$this->assertEquals([], $plateau->ouAller());
	}

	public function testCaisseMur()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[3,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], null);
		$this->assertEquals([], $plateau->ouAller());
	}

	public function testCaisseVide()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], null);
		$this->assertEquals(['droite'], $plateau->ouAller());
	}

	public function testSimpleMoove()
	{
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], null, null);
		$plateau->moove('droite');
		$this->assertEquals([2,1], $plateau->perso);
	}

	public function testSimpleMoove2(){
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], [[3,1]]);
		$this->assertEquals(false, $plateau->isFini());
		$plateau->moove('droite');
		$this->assertEquals(true, $plateau->isFini());
	}

	public function testSimpleResolve(){
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],
								[0,1],[4,1],
								[0,2],[1,2],[2,2],[3,2],[4,2]], [1,1], [[2,1]], [[3,1]]);
		$this->assertEquals(['droite'], $plateau->parcourir());
	}

	public function testResolve(){
		$plateau = new Plateau([[0,0],[1,0],[2,0],[3,0],[4,0],[5,0],
								[0,1],[5,1],
								[0,2],[1,2],[2,2],[3,2],[4,2],[5,2]], 
								[1,1], [[2,1]], [[4,1]]);
		$this->assertEquals(['droite', 'droite'], $plateau->parcourir());
	}

}