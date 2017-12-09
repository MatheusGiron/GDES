<?php
// INCLUI ARQUIVO DE CONFIGURAÇÃO
require_once 'config.php';

// DEFINE VARIÁVEIS E INICIA COM VALORES VAZIOS
$username = $password = "";
$username_err = $password_err = "";

// PROCESSA OS DADOS DO FORMULÁRIO QUANDO SUBMETIDO
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // VALIDA USERNAME
    if(empty(trim($_POST["username"]))){
        $username_err = 'Informe o nome do usuário.';
    } else{
        $username = trim($_POST["username"]);
    }

    // VALIDA PASSWORD
    if(empty(trim($_POST['password']))){
        $password_err = 'Informe a sua senha.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM DadosPessoais WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            header("location: home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'Senha inválida.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'Usuário inexistente.';
                }
            } else{
                echo "Algo deu errado, tente novamente.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Informe o nome do usuário e a senha:</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Usuário:</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Senha:</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Ainda não possui cadastro? <a href="cadastro.php">Crie um agora</a>!</p>
        </form>
    </div>
</body>
</html>
