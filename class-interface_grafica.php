<?php

class Interface_grafica{

public $arquivo;
public $diretorio;
public $tamanho;

public function __construct(){
}

//SETS METHODS
public function setArquivo($arquivo){
	$this->arquivo = $arquivo;
}
public function setDiretorio($diretorio){
	$this->diretorio = $diretorio;
}
public function setTamanho($tamanho){
	$this->tamanho = $tamanho;
}

//GETS METHODS
public function getArquivo(){
	return $arquivo;
}
public function getDiretorio(){
	return $diretorio;
}
public function getTamanho(){
	return $tamanho;
}

}