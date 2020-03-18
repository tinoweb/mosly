<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();


$method = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input');

$parametros = $_GET;
$headers = getallheaders();
$token = $headers["Token"];

require __DIR__ . '/vendor/autoload.php';

use MoslyApp\Model\User;
use MoslyApp\Model\Connection;

$usu = new User;

//var_dump($parametros['route']);

function pegaId($param){
    $arrRet = [];
    $expl = explode( "/", $param);
    $id = $expl[2];
    $user = $expl[1];
    $id = (int) $id;
    if($id && $user){
        $arrRet[0] = $id;
        $arrRet[1] = $user;
        return $arrRet;
    }
}

function pegaIdUserDrinkParam($param){
    $arrRet = [];
    $expl = explode( "/", $param);
    $user = $expl[1];
    $id = $expl[2];
    $drink = $expl[3];
    $id = (int) $id;
    if($id && $user){
        $arrRet[0] = $id;
        $arrRet[1] = $user;
        $arrRet[2] = $drink;
        return $arrRet;
    }
}

//$valor = pegaId($parametros['route'])[0];
//$userparam = pegaId($parametros['route'])[1];

///################Listar todos usuarios######################### GET
if($parametros['route'] == "/users" && $method == "GET" && !empty($token)){
    $retToken = $usu->validateAllTokens($token);
    if($retToken){
        $todosUsuarios = $usu->getallUser();
        $newArr = [];
        foreach($todosUsuarios as $key => $tdosUsuario){
            unset($tdosUsuario['token']);
            $newArr[$key] = $tdosUsuario;
        }
        echo json_encode($newArr);
    }else{
        $resposta = [
            "status" => "error",
            "message" => "token inválido"
        ];
        echo json_encode($resposta);
    }
}elseif($parametros['route'] == "/users" && $method == "POST" && !empty($body)){
    //############################cadastrar usuario######################### POST
    $body = json_decode($body, true);
    $name = $body["name"];
    $email = $body["email"];
    $password = $body["password"];

    if(!empty($name) && !empty($email) && !empty($password) ){
        $retorno = $usu->saveUser($email, $name, $password);
        if(is_numeric($retorno)){
            $resposta = [
                "status" => "success",
                "message" => "Usuario cadastrado com sucesso"
            ];
            echo json_encode($resposta);
        }elseif($retorno == "userWasInserted"){
            $resposta = [
                "status" => "error",
                "message" => "Erro, esse usuario ja se encontra cadastrado"
            ];
            echo json_encode($resposta);
        }
    }else{
        $resposta = [
            "status" => "error",
            "message" => "Erro, informação em falta."
        ];
        echo json_encode($resposta);
    }
}elseif($parametros['route'] == "/login" && $method == "POST" && !empty($body)){
    ///#######################logar com usuario################################
    $body = json_decode($body, true);
    $email = $body["email"];
    $password = $body["password"];

    $retorno = $usu->validateUserFromLogin($email, $password);

    if($retorno != "notLogged"){
        $_SESSION["username"]=$retorno["name"];
        $_SESSION["userid"]=$retorno["id"];
        $_SESSION["email"]=$retorno["email"];
        $_SESSION["token"]=$retorno["token"];
        $_SESSION["drink_counter"]=$retorno["drink_counter"]; 

        echo json_encode($retorno);
        
    }else{
        if(isset($_SESSION)){
            session_destroy();
        }
        $resposta = [
            "status" => "error",
            "message" => "Erro, Usuário não existe ou senha invalida!"
        ];
        echo json_encode($resposta);
    }
}elseif(
    is_numeric(pegaId($parametros['route'])[0]) 
    && pegaId($parametros['route'])[1] == "users" 
    && $method == "GET" && !empty($token)
    ){
        $userId = pegaId($parametros['route'])[0];
        $retToken = $usu->validateAllTokens($token);
        if($retToken){
            $umUsuario = $usu->getOneUser($userId, $token);
            if($umUsuario == "userNotFound"){
                $resposta = [
                    "status" => "error",
                    "message" => "Usuario não encontrado",
                ];
                
                echo json_encode($resposta);
            }elseif($umUsuario == "notPossibleTokenInvalid"){
                $resposta = [
                    "status" => "error",
                    "message" => "Id do usuario Invalido ou token inválido."
                ];
                echo json_encode($resposta);
            }else{
                unset($umUsuario["token"]);
                echo json_encode($umUsuario);
            }
        }else{
            $resposta = [
                "status" => "error",
                "message" => "token inválido"
            ];
            echo json_encode($resposta);
        }
}elseif(
    is_numeric(pegaId($parametros['route'])[0]) 
    && pegaId($parametros['route'])[1] == "users" 
    && $method == "PUT" && !empty($token)
    && !empty($body)
    ){
    
    $userId = pegaId($parametros['route'])[0];
    $body = json_decode($body, true);
    $name = $body["name"];
    $email = $body["email"];
    $password = $body["password"];
    
    if( isset($_SESSION) && $_SESSION["userid"] == $userId ) {
        $updateUser = $usu->updateUser($userId, $token, $name, $email, $password);
        if($updateUser==1){
            $resposta = [
                "status" => "sucesso",
                "message" => "Usuario atualizado com sucesso."
            ];
            echo json_encode($resposta);

        }elseif($updateUser == "notPossibleTokenInvalid"){
            $resposta = [
                "status" => "error",
                "message" => "Id do usuario Invalido ou token inválido."
            ];
            echo json_encode($resposta);

        }elseif($updateUser == "userIdInvalid"){
            $resposta = [
                "status" => "error",
                "message" => "Usuário Inválido"
            ];
            echo json_encode($resposta);
        }
    }else{
        $resposta = [
            "status" => "error",
            "message" => "Necessario autenticar para prosseguir com a operação."
        ];
        echo json_encode($resposta);
    }
}elseif(
    is_numeric(pegaId($parametros['route'])[0]) 
    && pegaId($parametros['route'])[1] == "users" 
    && $method == "DELETE" && !empty($token)
    ){
        $userId = pegaId($parametros['route'])[0];

        if( isset($_SESSION) && $_SESSION["userid"] == $userId ) {
            $retorno = $usu->deleteUser($userId, $token);
            if($retorno == 1){
                $resposta = [
                    "status" => "sucesso",
                    "message" => "Usuário deletado com sucesso"
                ];
                echo json_encode($resposta);
            }elseif($retorno == "userIdOrTokenError"){
                $resposta = [
                    "status" => "error",
                    "message" => "Id do usuário Inválido ou token errado."
                ];
                echo json_encode($resposta);
            }
        }else{
            $resposta = [
                "status" => "error",
                "message" => "Necessario autenticar para prosseguir com a operação."
            ];
            echo json_encode($resposta);
        }
}elseif(
        is_numeric(pegaIdUserDrinkParam($parametros['route'])[0]) 
        && pegaIdUserDrinkParam($parametros['route'])[1] == "users"
        && pegaIdUserDrinkParam($parametros['route'])[2] == "drink" 
        && !empty($body)
        && $method == "POST" 
        && !empty($token)
    ){
        $userId = pegaIdUserDrinkParam($parametros['route'])[0];
        $body = json_decode($body, true);
        $drink_ml = $body["drink_ml"];

        $retorno = $usu->incrimentUserDrink($userId, $token, $drink_ml);
        if($retorno == "userNotFound"){
            $resposta = [
                "status" => "error",
                "message" => "Usuario não encontrado."
            ];
            echo json_encode($resposta);
        }elseif($retorno == "notPossibleTokenInvalid"){
            $resposta = [
                "status" => "error",
                "message" => "Id do usuário token inválido."
            ];
            echo json_encode($resposta);
        }else{
            echo json_encode($retorno);
        }
}
