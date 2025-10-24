<?php



if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Email inválido, tente novamente";
    }
    else{
        $sql = "insert into "
    }

}



?>