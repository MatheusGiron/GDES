<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alegra - Cadastro realizado com sucesso!  </title>
</head>
 <body>
<?php
//DEFINIÇOES DO BD
$host      = 	'localhost';
$userbd    = 	'root';
$senhabd   = 	'123';
$bd        = 	'alegra';
// RECEBENDO OS DADOS PREENCHIDOS DO FORMULáRIO !
// DADOS PARA A TABELA DadosPessoais
$Nome					= $_POST ["Nome"];
$Matricula		= $_POST ["Matricula"];
$CPF				  = $_POST ["CPF"];
$Email			  = $_POST ["Email"];
// DADOS PARA A TABELA Endereco
$Rua	        = $_POST ["Rua"];
$Numero	      = $_POST ["Numero"];
$Complemento  = $_POST ["Complemento"];
$Bairro       = $_POST ["Bairro"];
$Cidade       = $_POST ["Cidade"];
$CEstado      = $_POST ["Estado"];
$CEP	        = $_POST ["CEP"];
//RECEBE DADOS, TESTA OS CHECKBOXES E DEFINE VARIÁVEIS
if(isset($_POST['Carro'])){
    $Carro = $_POST['Carro'];
}
else{
    $Carro=0;
}
if(isset($_POST['Moto'])){
    $Moto = $_POST['Moto'];
}
else{
    $Moto=0;
}
if(isset($_POST['Onibus'])){
    $Onibus = $_POST['Onibus'];
}
else{
    $Onibus=0;
}
if(isset($_POST['Bicicleta'])){
    $Bicicleta = $_POST['Bicicleta'];
}
else{
    $Bicicleta=0;
}
if(isset($_POST['Pe'])){
    $Pe = $_POST['Pe'];
}
else{
    $Pe=0;
}
//CONECTA AO BD
$conexao = mysqli_connect("$host", "$userbd", "$senhabd", "$bd");
if (!$conexao)
	die ("Erro de conexão com localhost!");
$banco = mysqli_select_db($conexao , $bd);
if (!$banco)
	die ("Erro de conexão com banco de dados!");
//INSERE DADOS NAS TABELAS
//INSERE DADOS PESSOAIS
mysqli_query($conexao , "INSERT INTO DadosPessoais ( Nome, Matricula, CPF, Email ) VALUES ('$Nome' , '$Matricula' , '$CPF' , '$Email')");
//INSERE ENDEREÇO
mysqli_query($conexao , "INSERT INTO Endereco ( Rua, Numero, Complemento, Bairro, Cidade, Estado, CEP ) VALUES ('$Rua' , '$Numero' , '$Complemento' , '$Bairro' , '$Cidade' , '$Estado' , '$CEP')");
//INSERE DADOS MEIO DE TRANSPORTE
mysqli_query($conexao , "INSERT INTO MeioTransporte ( Carro, Moto, Onibus, Bicicleta, Pe ) VALUES ('$Carro' , '$Moto' , '$Onibus' , '$Bicicleta', '$Pe')");
//ENCERRA A CONEXÃO COM BD
mysqli_close($conexao);
//RESPONDE AO USUÁRIO
echo "Seu cadastro foi realizado com sucesso!";
?>
</body>
</html>
