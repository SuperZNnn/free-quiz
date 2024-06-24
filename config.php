<?php

    $dbHost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = 'Rafa@080';
    $dbName = 'freequiz';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    // if($conexao->connect_errno)
    // {
    //     echo "Erro";
    // }
    // else
    // {
    //     echo "Conexão efetuada com sucesso";
    // }
?>