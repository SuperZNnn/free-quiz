<?php

    require "../../vendor/autoload.php";
    include_once("../../config.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    if (!isset($_SERVER['HTTP_MY_CUSTOM_HEADER']) || $_SERVER['HTTP_MY_CUSTOM_HEADER'] !== '40028922') {
        header("HTTP/1.0 403 Forbidden");
        echo "Acesso proibido!";
        exit();
    }

    if (isset($_GET['sendEmail'])){
        // Detectar se o email e valido
        $emailInput = $_GET['email'];

        $sql = "SELECT * FROM users WHERE email = '$emailInput'";
        $result = $conexao->query($sql);
    
        if (mysqli_num_rows($result) > 0){
            $row = $result->fetch_assoc();

            $currentDateTime = new DateTime();
            $tokenExpiryDateTime = new DateTime($row['reset_toeken_expires_at']);

            if ($row['reset_token_used'] == false && $currentDateTime < $tokenExpiryDateTime) {
                $returnData = array (
                    "status" => "noSend"
                );
            }
            else{
                $returnData = array (
                    "status" => "send"
                );
                $nick = $row['username'];
                sendEmail($emailInput,$nick,$conexao);
            }
            
        }
        else {
            $returnData = array (
                "status" => false
            );
        }

        echo json_encode($returnData);
    }

    if (isset($_GET['compareCodes'])){
        $email = $_GET['email'];
        $code = $_GET['code'];

        $code_hash = hash("sha256", $code);

        $sql = "SELECT * FROM users WHERE reset_token_hash = '$code_hash' AND email = '$email' AND reset_toeken_expires_at > NOW()";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode(array("isValid" => true));
        } else {
            echo json_encode(array("isValid" => false));
        }
    }

    function sendEmail ($emailTo,$name,$connection) {
        // Generate Token
        $token = generateRandomNumberString(8);
        $token_hash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 15);
        $sql = "UPDATE users SET reset_token_hash = '$token_hash', reset_toeken_expires_at = '$expiry' WHERE email = '$emailTo'";
        $result = $connection->query($sql);

        // Set Mail Message
        $mail = new PHPMailer(true);

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->Host = 'smtp.gmail.com';
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = "rafaelgcsoares08@gmail.com";
        $mail->Password = "grfk hrzd qxtc utzi";

        $mail->setFrom("rafaelgcsoares08@gmail.com","FreeQuiz");
        $mail->addAddress($emailTo);

        $mail->isHTML(true);
        $mail->Subject = "FreeQuiz";
        $mail->Body = "<html>
        <head>
            <style>
                .emailBorder{
                    padding: 1vh 1vh;
                    width: 39vh;
                    border: .4vh solid #73503C;
                    border-radius: 1vh;
                }
                h1{
                    font-family: sans-serif;
                    text-align: center;
                    color: rgba(131, 131, 131, 1);
                    font-size: 5vh;
                }
                h2{
                    font-family: sans-serif;
                    font-size: 2.5vh;
                    text-align: center;
                }
                p{
                    font-family: sans-serif;
                    font-size: 2vh;
                    text-align: justify;
                }
                h3{
                    font-family: sans-serif;
                    font-size: 5vh;
                    letter-spacing: 1vh;
                    text-align: center;
                }            
            </style>
        </head>
        <body>
            <div class=\"emailBorder\" >
                <h1>Freequiz</h1>
                <h2>Recuperação de Senha</h2>
                <p>Olá <b>$name</b>, percebemos que alguém deseja mudar a senha da sua conta, segue abaixo o código para redefinir sua senha, caso não seja você basta ignorar essa mensagem.<br>Atenciosamente, Free Quiz!</p>
                <h3>$token</h3>
                <p style=\"text-align: center;\">Código expira em 15 minutos</p>
            </div>
        </body>
        </html>";

        $sql = "UPDATE users SET reset_token_used = 0 WHERE email = '$emailTo'";
        $result = $connection->query($sql);

        $mail->send();
    }

    function generateRandomNumberString($length) {
        $numbers = '';
        for ($i = 0; $i < $length; $i++) {
            $numbers .= random_int(0, 9);
        }
        return $numbers;
    }

    if (isset($_GET['getPassword'])){
        $email = $_GET['email'];
        $suppPassword = $_GET['password'];

        $sql = "SELECT userpassword FROM users WHERE email = '$email'";
        $result = $conexao->query($sql);

        $row = $result->fetch_assoc();
        $hashedPassword = $row['userpassword'];

        if (password_verify($suppPassword, $hashedPassword)){
            echo json_encode(array("password" => "equals"));
        }
        else{
            echo json_encode(array("password" => "notequals"));
        }
    }

    if (isset($_GET['resetPassword'])){
        $newPassword = $_POST['password'];
        $email = $_POST['email'];

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $sql = "UPDATE users SET userpassword = '$hashedPassword', reset_token_used = 1 WHERE email = '$email'";
        $result = $conexao->query($sql);

        echo json_encode(array("done"=>true));
    }
?>