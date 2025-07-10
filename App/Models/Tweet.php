<?php
namespace App\Models;
use MF\Model\Model;

class Tweet extends Model{
	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $value){
		$this->$atributo = $value;		
	}

	//salvar
	public function salvar(){
		$query = 'insert into tweets(id_usuario, tweet)values(:id_usuario, :tweet)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':tweet', $this->__get('tweet'));
		$stmt ->execute();
		return $this;		
	}

	//Excluir tweet
	public function excluirTweet(){
		$query = 'delete from tweets where id = :id';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();
	}

	//recuperar
	public function getAll(){
		$query = 'select
				t.id, t.id_usuario, u.nome, t.tweet, t.data
		from 
				tweets as t left join usuarios as u on(t.id_usuario = u.id)
		where
				t.id_usuario = :id_usuario or t.id_usuario in (select us.id_usuario_seguindo from usuarios_seguidores as us where us.id_usuario = :id_usuario)
		order by
				t.data desc';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}


