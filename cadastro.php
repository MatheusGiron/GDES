<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alegra - Cadastro realizado com sucesso!  </title>
</head>
 <body>
<?php

$host      = 	'localhost';
$userbd    = 	'root';
$senhabd   = 	'123';
$bd        = 	'alegra';

// RECEBENDO OS DADOS PREENCHIDOS DO FORMULáRIO !

$Nome		= $_POST ["Nome"];			//atribuição do campo "nome" vindo do formulário para variavel
$Matricula	= $_POST ["Matricula"];
$Cpf		= $_POST ["Cpf"];
$Endereco	= $_POST ["Endereco"];
$Meio		= $_POST ["Meio"];

$conexao = mysqli_connect("$host", "$userbd", "$senhabd", "$bd");

if (!$conexao)
	die ("Erro de conexão com localhost!");

$banco = mysqli_select_db($conexao , $bd);
if (!$banco)
	die ("Erro de conexão com banco de dados!");

mysqli_query($conexao , "INSERT INTO AllData ( Nome, Matricula, Cpf, Endereco, MeioTransporte ) VALUES ('$Nome' , '$Matricula' , '$Cpf' , '$Endereco' , '$Meio')");

mysqli_close($conexao);

echo "Seu cadastro foi realizado com sucesso!";
?>
</body>
</html>
