<?php

    include_once('../config.php');

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    //Logar com sessões (Quiz Singleplayer)
    if (isset($_GET['userid']) && $_GET['quizid']) {
        $user_id = $_GET['userid'];
        $quiz_id = $_GET['quizid'];

        $sql = "SELECT * FROM pontuation WHERE quizID = '$quiz_id' AND userID = '$user_id' AND quizType = 2";
        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) > 0){
            // Já jogou
            echo 'played';
        }
        else{
            echo 'allow';
        }

        exit();
    }

    //Logar Normalmente
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conexao->query($sql);
    
        if (mysqli_num_rows($result) < 1) {
            echo 'noEmail';
        } else {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['userpassword'];
    
            if (password_verify($senha, $hashedPassword)) {
                session_start();
                $user_id = $row['userid'];

                //Logar rapidamente (Quiz Singleplayer)
                if (isset($_GET['fastLogin'])) {
                    $quiz_id = $_GET['quizid'];
                    
                    $sql = "SELECT * FROM pontuation WHERE quizID = '$quiz_id' AND userID = '$user_id' AND quizType = 2";
                    $result = $conexao->query($sql);
                    if(mysqli_num_rows($result) > 0){
                        // Já jogou
                        echo 'played';
                        exit();
                    }
                }

                $_SESSION['user_id'] = $user_id;
                echo 'logged';
            } else {
                echo 'noPassword';
            }
        }
    }
    
?>