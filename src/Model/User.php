<?php 

namespace MoslyApp\Model;
use MoslyApp\Model\Connection;
use PDO;

Class User {

	private static $conexao;

	public function __construct()
    {
		$conet = new Connection;
		self::$conexao = $conet->openConnection();
	}
	

	public static function getAllUser(){
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario");
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
	}

	public static function saveUser($email, $name, $password){

		$token = md5(rand(5, 1500)."mosly");

		$db = self::$conexao;
		$query = $db->prepare("INSERT INTO usuario(id, token, email, name, password) VALUES (DEFAULT, :token,:email,:name,:password)");
        $query->bindValue(":token", $token);
        $query->bindValue(":email", $email);
		$query->bindValue(":name", $name);
		$query->bindValue(":password", $password);
        $query->execute();
        return $db->lastInsertId();

	}

	public static function deleteUser($user_id, $token)
    {
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario WHERE id = :userId AND token = :token");
		$query->bindValue(":userId", $user_id);
		$query->bindValue(":token", $token);
		$query->execute();
		$retorno = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($retorno)) {
			$queryDel = $db->prepare("DELETE FROM usuario WHERE id = :id");
			$queryDel->bindValue(":id", $user_id);
			$ret = $queryDel->execute();
			if($ret){
				return 1;
			}
		}else{
			return "userIdOrTokenError";
		}
	}
	
	public static function validaToken($token){
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario WHERE token = :token");
		$query->bindValue(":token", $token);
		$query->execute();
		$retorno = $query->fetch(PDO::FETCH_ASSOC);
		return $retorno;
	}

	public static function updateUser($userId, $token, $name, $email, $password){
		$retorno = self::validaToken($token);
		if($retorno){
			$queryUpdate = $db->prepare("UPDATE usuario SET name = :name, email = :email, password = :password  WHERE id = :id");
			$queryUpdate->bindValue(":id", $userId);
			$queryUpdate->bindValue(":name", $name);
			$queryUpdate->bindValue(":email", $email);
			$queryUpdate->bindValue(":password", $password);
			$updateResult = $queryUpdate->execute();
			if($updateResult){
				return 1;
			}else{
				return "userIdInvalid";
			}
		}else{
			return "notPossibleTokenInvalid";
		}

	}

	public static function getOneUser($userId, $token){
		$retorno = self::validaToken($token);
		if($retorno){
			$db = self::$conexao;
			$query = $db->prepare("SELECT * FROM usuario WHERE id = :id");
			$query->bindParam(":id", $userId);
			$query->execute();
			$usuario = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($usuario)){
				return $usuario;
			}else{
				return "userNotFound";
			}
		}else{
			return "notPossibleTokenInvalid";
		}
	}

	public static function getUserDrink(){

	}

	public static function validateUserFromLogin($email, $password){
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario WHERE email = :email AND password = :password");
		$query->bindValue(":email", $email);
		$query->bindValue(":password", $password);
		$query->execute();
		$dados = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($dados)) {
			return $dados;
		}else{
			return "notLogged";
		}
	}

	
}