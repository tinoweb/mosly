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
	
	public static function validateUserOnSave($name, $email){
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario WHERE name = :name and email = :email");
		$query->bindValue(":name", $token);
		$query->bindValue(":email", $email);
		$query->execute();
		$retorno = $query->fetch(PDO::FETCH_ASSOC);
		return $retorno;
	}

	public static function saveUser($email, $name, $password){
		$retorno = self::validateUserOnSave($name, $email);
		if(!empty($retorno)){
			return "userWasInserted";
		}else{
			$token = md5(rand(5, 1500)."mosly");
	
			$db = self::$conexao;
			$query = $db->prepare("INSERT INTO usuario(token, email, name, password) VALUES (:token, :email, :name, :password)");
			$query->bindValue(":token", $token);
			$query->bindValue(":email", $email);
			$query->bindValue(":name", $name);
			$query->bindValue(":password", $password);
			$query->execute();
			return $db->lastInsertId();
		}
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

	public static function validaUserId($userId){
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
	}

	public static function validateAllTokens($token){
		$valToken = self::validaToken($token);
		if(!empty($valToken)){
			return true;
		}else{
			return false;
		}
	}

	public static function updateUser($userId, $token, $name, $email, $password){
		$retorno = self::validaToken($token);
		if($retorno){
			$db = self::$conexao;
			$queryUpdate = $db->prepare("UPDATE usuario SET name = :nome, email = :emaill, password = :senha  WHERE id = :id");
			$queryUpdate->bindValue(":id", $userId);
			$queryUpdate->bindValue(":nome", $name);
			$queryUpdate->bindValue(":emaill", $email);
			$queryUpdate->bindValue(":senha", $password);
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
			$usuario = self::validaUserId($userId);
			if($usuario != "userNotFound"){
				$drink = self::countDrink($usuario["id"]);
				$usuario["drink_counter"] = $drink;
				return $usuario;
			}else{
				return $usuario;
			}
		}else{
			return "notPossibleTokenInvalid";
		}
	}


	public static function validateUserFromLogin($email, $password){
		$db = self::$conexao;
		$query = $db->prepare("SELECT * FROM usuario WHERE email = :email AND password = :password");
		$query->bindValue(":email", $email);
		$query->bindValue(":password", $password);
		$query->execute();
		$dados = $query->fetch(PDO::FETCH_ASSOC);
		if(!empty($dados)) {
			$drinks = self::countDrink($dados["id"]);
			$dados["drink_counter"] = $drinks;
			return $dados;
		}else{
			return "notLogged";
		}
	}

	public static function incrimentUserDrink($userId, $token, $drink_ml){
		$retorno = self::validaToken($token);
		if($retorno){
			$returnUserValid = self::validaUserId($userId);
			if($returnUserValid == "userNotFound"){
				return $returnUserValid;
			}else{
				$drink_counter = self::insertUserDrink($drink_ml, $userId);
				$returnUserValid["drink_counter"] = $drink_counter;
				return $returnUserValid;
			}
		}else{
			return "notPossibleTokenInvalid";
		}
	}


	public static function insertUserDrink($drink, $userId){
		$db = self::$conexao;
		$query = $db->prepare("INSERT INTO drinks(id, drink, idusuario) VALUES (DEFAULT, :drink, :userId)");
		$query->bindValue(":drink", $drink);
		$query->bindValue(":userId", $userId);
		$query->execute();
		$idInserted = $db->lastInsertId();
		$drinks = self::countDrink($userId);
		return $drinks;
	}

	public static function countDrink($userId){
		$db = self::$conexao;
		$query2 = $db->prepare("SELECT count(drink) AS drink_counter FROM drinks WHERE idusuario = $userId");
		$query2->execute();
		$drinks = $query2->fetch(PDO::FETCH_ASSOC);
		return $drinks["drink_counter"];
	}
	
}