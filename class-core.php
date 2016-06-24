<?php
define('BLOCK_SIZE', 4096);
include 'class-interface_grafica.php';
include 'class-disk.php';

class Core{

public Interface_grafica $interface_grafica;
public Disk $disco;
public $posicao;
public $mount;
public $super_block;//Diz aonde está o bit_map e aonde começa a raiz
public $bit_map = array();//guarda um vetor de 011010 informando quais blocos estão ocupados ou desocupados.
//Guarda nome, tipo e em que bloco se encontra o arquivo.
public $outra_coisa = array(
	'nome' => '',
	'tipo' => '',
	'comeco' => 0,
	'fim' => 0
);

public function __construct(){
	$interface_grafica = new Interface_grafica();
}
//SETS METHODS
public function setPosicao($posicao){
	$this->posicao = $posicao;
}
//GETS METHODS
public function getPosicao(){
	return $posicao;
}

public function create_disk(){
	$this->disco = new Disk('meu_disco', 4);
}

//mkfs – Cria um sistema de arquivos no disco. Esta função deverá escrever no disco o superblock, a estrutura utilizada para gerenciar espaço livre e o diretório raiz, inicialmente vazio.
public function mkfs($disco){}

//mount – Cria uma instância do sistema de arquios a partir do conteúdo de um disco (o sistema de arquivos terá sido criado anteriormente com a função mkfs).
public function mount($disco){
	$this->mount = $disco;
}

//mkdir – Cria um novo diretório no sistema de arquivos (mkdir("dir3", "/dir1/dir2"))
public function mkdir($dir, $raiz){}

//lsdir – Retorna um vetor ou lista com os arquivos existentes em um diretório (e.g. lsdir("/dir1/dir2/"))
public function lsdir($dir){}

//create_file – Cria um novo arquivo em um diretório específico (e.g. create_file("arq1", "/dir1/dir2")). A criação de um novo arquivo deverá causar alteração no disco – i.e. um novo inode foi ocupado, o diretório no disco deve ser atualizado, etc.
public function create_file($arquivo, $diretorio){}

//file_open – Abre um arquivo previamente criado, retorna um handle com o qual pode-se chamar funções de leitura e escrita – File f = file_open("arq1", "/dir1/dir2").
public function open_file($mount, $diretorio, $arquivo){
	$arquivo_aberto = $diretorio.$arquivo;
	return $arquivo_aberto;
}

//file_write – Escreve bytes em uma determinada posição do arquivo: file_write(f, pos, data, data_len)
public function write_file($arquivo_aberto, $posicao_para_escrever, $conteudo, $tamanho_conteudo){}

//file_read – Lê bytes de um arquivo: file_read(f, pos, data, data_len).
public function read_file($arquivo_aberto, $posicao, $conteudo, $tamanho_conteudo){}

//delete_file – Remove um arquivo dentro de algum diretório (delete_file("/dir1/dir2/arq1")).
public function delete_file($arquivo, $diretorio){}

//exemplode uso dos metodos
//disk_t * disk = disk_new("arquivo.dat");
//mkfs(disk);
//fs = mount(disk);
//mkdir(fs, "dir1", "/");
//mkdir(fs, "dir2", "/dir1");
//create_file(fs, "arq1", "/dir1/dir2");
//int fd = open_file(fs, "/dir1/dir2/arq1");
//file_write(fs, fd, 10, "teste", strlen("teste"));

}
