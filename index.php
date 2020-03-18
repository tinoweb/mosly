<?php 
session_start();

require __DIR__ . '/vendor/autoload.php';

use MoslyApp\Model\User;
use MoslyApp\Model\Connection;

$usu = new User;

///################Listar usuario######################### GET
$todosUsuarios = $usu->getallUser();
//echo "<pre>";
//print_r($todosUsuarios);
///#######################################################


///################listar 1 usuario##################  trazer tambem quantos drinks ele ja bebeu
$userId = 2;
$token = "445c65ae9df38c069f51163ed20a0f";
$umUsuario = $usu->getOneUser($userId, $token);
if($umUsuario == "userNotFound"){
    echo "Usuario não encontrado";
}elseif($umUsuario == "notPossibleTokenInvalid"){
    echo "Id do usuario Invalido ou token inválido.";
}else{
    echo "<pre>";
    print_r($umUsuario);
}

///##################################################


///################user drink######################### adicionar os drinks do usuario


///##################################################


///##############################autenticar usuario######################### POST
/*
$email = "carlos@gmail.com";
$password = "123123";

$retorno = $usu->validateUserFromLogin($email, $password);

if($retorno != "notLogged"){
    echo "<pre>";
    
    $_SESSION["username"]=$retorno["name"];
    $_SESSION["userid"]=$retorno["id"];
    $_SESSION["email"]=$retorno["email"];
    $_SESSION["token"]=$retorno["token"];
    print($_SESSION["username"] ." com o email =>". $_SESSION["email"] ." e token ". $_SESSION["token"]);
}else{
    if(isset($_SESSION)){
        session_destroy();
    }
    echo $_SESSION["username"];
    echo " usario não existe ou a senha é invalida";
}
///#####################################################################
*/


///################atualizar#################################################### PUT
/*
$userId = 2;
$token = "445c65ae9df38c069f51163ed20a0f";
$name = "Kristina Melody";
$email = "kris@gmail.com";
$password = "123456";

if( isset($_SESSION) && $_SESSION["userid"] == $userId ) {
    $updateUser = $usu->updateUser($userId, $token, $name, $email, $password);
    if($updateUser==1){
        echo "Usuario atualizado com sucesso";
    }elseif($updateUser == "notPossibleTokenInvalid"){
        echo "Id do usuario Invalido ou token inválido."; 
    }elseif($updateUser == "userIdInvalid"){
        echo "Usuario Invalido";
    }
}else{
    echo "Necessario autenticar para prosseguir com a operação.";
}
///##############################################################################
*/

///############################deletar usuario########################## DELETE
/*
$user_id = 1;
$token = "1eba822c3ebbf94a3e9d6e8e9f83d0";

if( isset($_SESSION) && $_SESSION["userid"] == $user_id ) {
    $retorno = $usu->deleteUser($user_id, $token);
    if($retorno == 1){
        echo "Usuario deletado com sucesso";        
    }elseif($retorno == "userIdOrTokenError"){
        echo "Id do usuario Invalido ou token errado."; 
    }
}else{
    echo "Necessario autenticar para prosseguir com a operação.";
}
///#####################################################################
*/


///############################cadastro usuario######################### POST
/*
$id = $usu->saveUser("carlos@gmail.com", "carlos", "123123");
echo "gravado com sucesso ".$id;
var_dump($_GET);
*/
///#####################################################################
//json_encode(