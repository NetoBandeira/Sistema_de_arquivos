<?php
/*******************************************************************************
 * Classe de Disco em PHP
 * Setores do disco gravados em uma base SQLite
 *
 * @author Rafael Abreu
 * @contact 51 8487-2343
 * @website www.rafaelabreu.eti.br
 *
 * Uso:
 *
 * // Converte um valor inteiro para "bytes"
 * // Um inteiro de 64 bits vai ser no máximo 18446744073709551616
 * // (20 dígitos)
 * function val_to_byte($val) {
 *      $bytes = str_split($val);
 *      $n_bytes = count($bytes);
 *      $r_bytes = 20;
 *
 *      return array_pad($bytes, -$r_bytes, 0);
 * }
 *
 * // Copia $valor (representado como "20 bytes") para dentro de $vector
 * function memcpy(&$vector, $valor, $cursor) {
 *     $bytes = $this->str_to_byte($valor);

 *     foreach($bytes as $byte) {
 *         $vector[$cursor] = $byte;
 *         $cursor++;
 *     }
 * }
 *
 * // Cria um novo bloco em meória com o tamanho correto
 * function create_block() {
 *    for ( $i = 0; $i < BLOCK_SIZE; $i++)
 *        $block[] = 0;
 *    return $block;
 * }
 *
 * define('BLOCK_SIZE', 4096);
 * include "class-disk.php";
 * $d = new Disk('disk', 4); // Novo disco chamado "disk" com 4Mbytes de "espaço"
 * $sb = create_block();
 * $nblocks = 1024;
 * $root_dir_block = 4;
 * memcpy($sb, $nblocks, 0);
 * memcpy($sb, $root_dir_block, 20); // $nblocks ocupou os bytes de 0 a 19 do bloco
 * $d->write_block_to_disk($sb);
 *
 * $sb_2 = $d->read_block_from_disk(0);
 * $nblocks_2 = (int)implode("", array_slice($sb_2, 0, 20)); // converte os 20 primeiros bytes em um int.
 * if ($nblocks_2 != $nblocks) {
 *    // ERRO!
 * }
 ******************************************************************************/

$blocos[
    0 => 'SB',
    1 => 'BM',
    2 => 'BM',
    3 => 'RAIZ',
    4 => '{nome:Imagens, tipo:d, bloco:4}',
    5 => '{nome:foto1, tipo:a, bloco:5, dir:4, conteudo:asdfasdfsaf asdfasdfsaf sadfsadfsa}'
];
class Disk{

	private $name;
	private $size;
	private $db;

	public function __construct($name, $size) {
		$this->name = $name;
		$this->size = $size;
		$this->conecta_sqlite();
	}

	private function conecta_sqlite() {
        //Cria um banco de dados sqlite como se fosse um disco
        try {
            $this->db = new PDO("sqlite:".$this->name);
        } catch( Exception $e ) {
            die($e->getMessage());
        }
    }

    public function get_name() {
    	return $this->name;
    }

    public function get_size() {
    	return $this->size;
    }

    public function new_disk() {
    	$n_blocks = $this->size*1024*1024/BLOCK_SIZE;
        $sql = "CREATE TABLE IF NOT EXISTS disk ( "
                    . "block INTEGER PRIMARY KEY, "
                    . "data varchar(".(BLOCK_SIZE * 4).")"
                . ")";

        $this->db->exec($sql);
        //Limpa tabela para um novo sistema de arquivos
        $sql = "DELETE FROM disk;DELETE FROM sqlite_sequence WHERE name='disk';";
        $this->db->exec($sql);

        //Cria o número de blocos como uma linha do banco de dados
        $st = $this->db->prepare( "INSERT INTO disk( block, data ) "
                                    ." VALUES( :block, :data ) "
                                ."");
        if( $n_blocks <= 3 ) die('Disco muito pequeno\n');
        for( $i = 0; $i < $n_blocks; $i++ ) {
            $st->execute( [':block'=>$i, ':data'=>''] );
            printf(".");
        }
    }

    public function write_block_to_disk($block_pos, $block) {
        $sql = "UPDATE disk SET data = :data WHERE block = :block";
        $st = $this->db->prepare($sql);

        return $st->execute(
                        [
                            ':data'     => json_encode($block),
                            ':block'    => $block_pos
                        ]
                    );
    }

    public function read_block_from_disk($block_pos) {
        $disk = $this->db->prepare("SELECT data FROM disk WHERE block = :block");

        $disk->execute([':block'=>$block_pos]);
        return json_decode($disk->fetch(PDO::FETCH_OBJ)->data);
    }

}