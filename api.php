<?php
//Import config.php
require_once("config.php");

//Set JSON MIME Type as Header
header("Content-Type: application/json");

//Check if action parameter is set
if(!isset($_GET["action"])){
    exit(json_encode(["error"=>true, "error_type"=>"ACTION_UNDEFINED"]));
}

$action = $_GET["action"];

switch($action){
    case "create":
        create();
        break;
    default:
        //Return Error if no action matched
        exit(json_encode(["error"=>true, "error_type"=>"ACTION_INVALID"]));
        break;
}

function create(){
    // Attempt to connect to MySQL database
    $mysqli = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    //Check if content parameter is set
    if(!isset($_GET["content"])){
        exit(json_encode(["error"=>true, "error_type"=>"CONTENT_UNDEFINED"]));
    }

    //Check if content parameter is empty
    if(empty($_GET["content"])){
        exit(json_encode(["error"=>true, "error_type"=>"CONTENT_EMPTY"]));
    }

    // Get ID for paste
    $id = uniqid();

    $sql = "INSERT INTO `pastes`(`id`, `content`) VALUES (?,?)";
            
    if($stmt = mysqli_prepare($mysqli, $sql)){
        mysqli_stmt_bind_param($stmt, "ss", $param_id, $param_content);
        
        $param_id = $id;
        $param_content = $_GET["content"];
        
        if(mysqli_stmt_execute($stmt)){
            exit(json_encode(["error"=>false, "id"=>$id]));
        } else{
            exit(json_encode(["error"=>true, "error_type"=>"CREATE_FAILED"]));
        }

        mysqli_stmt_close($stmt);
    }
}

?>