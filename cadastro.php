<?php
// INCLUI ARQUIVO DE CONFIGURAÇÃO
require_once 'config.php';

// DEFINE VARIÁVEIS E INICIA COM VALORES VAZIOS
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// PROCESSA OS DADOS DO FORMULÁRIO QUANDO SUBMETIDO
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // VALIDA USERNAME
    if(empty(trim($_POST["username"]))){
        $username_err = "Informe o nome do usuário.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM DadosPessoais WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Esse nome de usuário já existe.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Erro! Tente novamente mais tarde.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    // VALIDA A SENHA
    if(empty(trim($_POST['password']))){
        $password_err = "Informe a sua senha.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "A senha deve ter ao menos 6 caracteres.";
    } else{
        $password = trim($_POST['password']);
    }

    // VALIDA A CONFIRMAÇÃO DE SENHA
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Confirme a senha.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'As senhas não conferem.';
        }
    }

    // CHECA ERROS DE ENTRADA ANTES DE INSERIR DADOS NO BD
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        //INSERE DADOS PESSOAIS
        $sql = "INSERT INTO DadosPessoais ( username, password ) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Algo deu errado, tente novamente.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // FECHA A CONEXÃO
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alegra - Cadastro de usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
    <script type="text/javascript" >
    function limpa_formulário_cep() {
    	//Limpa valores do formulário de cep.
    	document.getElementById('Logradouro').value=("");
    	document.getElementById('Bairro').value=("");
    	document.getElementById('Cidade').value=("");
    	document.getElementById('Estado').value=("");
    }
    function meu_callback(conteudo) {
    	if (!("erro" in conteudo)) {
    		//Atualiza os campos com os valores.
    		document.getElementById('Logradouro').value=(conteudo.logradouro);
    		document.getElementById('Bairro').value=(conteudo.bairro);
    		document.getElementById('Cidade').value=(conteudo.localidade);
    		document.getElementById('Estado').value=(conteudo.uf);
    	} //end if.
    	else {
    		//CEP não Encontrado.
    		limpa_formulário_cep();
    		alert("CEP não encontrado.");
    	}
    }
    function pesquisacep(valor) {
    	//Nova variável "cep" somente com dígitos.
    	var cep = valor.replace(/\D/g, '');
    	//Verifica se campo cep possui valor informado.
    	if (cep != "") {
    		//Expressão regular para validar o CEP.
    		var validacep = /^[0-9]{8}$/;
    		//Valida o formato do CEP.
    		if(validacep.test(cep)) {
    			//Preenche os campos com "..." enquanto consulta webservice.
    			document.getElementById('Logradouro').value="...";
    			document.getElementById('Bairro').value="...";
    			document.getElementById('Cidade').value="...";
    			document.getElementById('Estado').value="...";
    			//Cria um elemento javascript.
    			var script = document.createElement('script');
    			//Sincroniza com o callback.
    			script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
    			//Insere script no documento e carrega o conteúdo.
    			document.body.appendChild(script);
    		} //end if.
    		else {
    			//cep é inválido.
    			limpa_formulário_cep();
    			alert("Formato de CEP inválido.");
    		}
    	} //end if.
    	else {
    		//cep sem valor, limpa formulário.
    		limpa_formulário_cep();
    	}
    };

    //VALIDADOR DE PASSWORD
    function checkPass()
    {
        //Store the password field objects into variables ...
        var pass1 = document.getElementById('password');
        var pass2 = document.getElementById('confirm_password');
        //Store the Confimation Message Object ...
        var message = document.getElementById('confirmMessage');
        //Set the colors we will be using ...
        var goodColor = "#66cc66";
        var badColor = "#ff6666";
        //Compare the values in the password field
        //and the confirmation field
        if(pass1.value == pass2.value){
            //The passwords match.
            //Set the color to the good color and inform
            //the user that they have entered the correct password
            pass2.style.backgroundColor = goodColor;
            message.style.color = goodColor;
            message.innerHTML = "Senhas OK!"
        }else{
            //The passwords do not match.
            //Set the color to the bad color and
            //notify the user.
            pass2.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Senhas diferentes!"
        }
    }
    </script>
</head>
<body>
    <div class="wrapper">
        <h2>Cadastro</h2>
        <p>Preencha os campos abaixo para criar uma conta Alegra</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Usuário:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Senha:</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirme a senha:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" onkeyup="checkPass(); return false;"><span id="confirmMessage" class="confirmMessage"></span>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Cadastrar">
                <input type="reset" class="btn btn-default" value="Apagar">
            </div>
            <p>Já é cadastrado? <a href="login.php">Entre aqui</a>.</p>
        </form>
    </div>
    </form>
</body>
</html>
