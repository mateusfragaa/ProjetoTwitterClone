<?php
// A conexão do banco de dados está disponível pelo herança de Model

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{
	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $value){
		$this->$atributo = $value;
	}


	// Salvar
	public function salvar(){
		$query = 'insert into usuarios(nome, email, senha) values(:nome, :email, :senha)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->nome);
		$stmt->bindValue(':email', $this->email);
		$stmt->bindValue(':senha', $this->senha);
		$stmt->execute();
		return $this;
	}

	// Verificar se um registro pode ser salvo
	public function validarCadastro(){
		$valido = true;

		if (strlen($this->__get('nome')) < 3 || strlen($this->__get('email')) < 9 || strlen($this->__get('senha')) < 4) {
			$valido = false;
		}

		return $valido;
	}

	// Recuperar usuário por email
	public function getUsuarioEmail(){
		$query = 'select nome, email from usuarios where email = :email';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->email);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function autenticar(){
		$query = 'select id, nome, email from usuarios where email = :email and senha = :senha';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();
		$usuario =  $stmt->fetch(\PDO::FETCH_ASSOC);

		if (!empty($usuario['nome']) && !empty($usuario['id'])) {
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
			$this->__set('email', $usuario['email']);
		}
		
		return $this;
	}

	public function getAll(){
		if ($this->__get('nome') != 'all') {
			$query = 
			'select
			 	u.id,
			 	u.nome,
			 	u.email,
			 		(select 
			 				count(*) 
			 		from 
			 				usuarios_seguidores as us 
			 		where 
			 				us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id ) as seguindo_sn

			from usuarios as u 
			where
				u.nome like :nome and u.id != :id_usuario';
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':nome','%'.$this->__get('nome').'%');
			$stmt->bindValue(':id_usuario', $this->__get('id'));
			$stmt->execute();
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}else{ // Para conseguir todos os usuários da rede
			$query = 'select u.id, u.nome, u.email,
					(select 
			 				count(*) 
			 		from 
			 				usuarios_seguidores as us 
			 		where 
			 				us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id ) as seguindo_sn 
						
					from usuarios as u
					where u.id != :id_usuario';
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':id_usuario', $this->__get('id'));
			$stmt->execute();
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
			
	}

	public function seguirUsuario($id_usuario_seguindo){
		$query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo)values(:id_usuario, :id_usuario_seguindo)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();
		return true;
	}

	public function deixarSeguirUsuario($id_usuario_seguindo){
		$query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();
		return true;
	}

	// Todos os métodos abaixo são para preencher o perfil dinâmicamente

	public function getNome(){
		$query = 'select nome from usuarios where id = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function totalTweets(){
		$query = 'select count(*) as totalTweets from tweets where id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function totalSeguindo(){
		$query = 'select count(*) as totalSeguindo from usuarios_seguidores  where id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function totalSeguidores(){
		$query = 'select count(*) as totalSeguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}



}