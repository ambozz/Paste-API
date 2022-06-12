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
    case "get":
        get();
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

function get(){
    // Attempt to connect to MySQL database
    $mysqli = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    //Check if id parameter is set
    if(!isset($_GET["id"])){
        exit(json_encode(["error"=>true, "error_type"=>"ID_UNDEFINED"]));
    }

    //Check if id parameter is empty
    if(empty($_GET["id"])){
        exit(json_encode(["error"=>true, "error_type"=>"ID_EMPTY"]));
    }

    if($stmt = mysqli_prepare($mysqli, "SELECT content, UNIX_TIMESTAMP(create_time) as create_time FROM pastes WHERE id = ?")){
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        $param_id = $_GET["id"];
    
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $content, $create_time);
    
        if ($stmt->num_rows != 1){
            exit(json_encode(["error"=>true, "error_type"=>"PASTE_NOT_FOUND"]));
        }

        while( $stmt->fetch() ) {
            exit(json_encode(["error"=>false, "content"=>$content, "create_time"=>$create_time]));
        }
    }
}

?>