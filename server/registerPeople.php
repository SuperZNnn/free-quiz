<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_POST["nick"])) {
        $nick = $_POST["nick"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conexao->query($sql);

        if (isset($nick) && empty($nick) || isset($email) && empty($email) || isset($password) && empty($password)){
            echo 'algoInvalido';
        }
        else if ($result->num_rows > 0) {
            echo 'jaCadastrado';
        } else {
            // Registrar no banco de dados
            $result = mysqli_query($conexao, "INSERT INTO users (username, email, userpassword) VALUES ('$nick', '$email', '$hashedPassword')");

            // Iniciar sessão
            $sql = "SELECT userid FROM users WHERE email = '$email'";
            $result = $conexao->query($sql);
            $row = $result->fetch_assoc();

            session_start();
            $_SESSION['user_id'] = $row['userid'];

            echo 'cadastradoSucesso';
        }
    }

?>