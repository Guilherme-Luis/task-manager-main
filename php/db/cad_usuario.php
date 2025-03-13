<?php

session_start();

include('./conexao.php'); 

$nome = mysqli_real_escape_string($conexao, $_POST['nome']);
$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

$queryCheck = "SELECT id_user FROM user WHERE email_user = '{$email}'";
$resultCheck = mysqli_query($conexao, $queryCheck);

if (mysqli_num_rows($resultCheck) > 0) {
    $_SESSION['nao_autenticado_cadastro'] = true;
    header('Location: ../../index.php?erro=cadastro');
    exit();
}

$queryInsert = "INSERT INTO user(nome_user, email_user, senha_user) VALUES ('{$nome}', '{$email}', md5('{$senha}'))";
$result = mysqli_query($conexao, $queryInsert);

if ($result) {
    header('Location: ../../index.php');
} else {
    $_SESSION['nao_autenticado_cadastro'] = true;
    header('Location: ../../index.php?erro=cadastro');
}

exit();