<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "TEConnect";
    
    // Include config file
//require_once "config.php";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /views/home.php"); //Locacion del index en la computadora
    ///TEConnect-master/index.html
    exit;
}

 
// Define variables and initialize with empty values
$correo = $password_u = "";
$correo_err = $password_u_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["correo"]))){
        $correo_err = "Por favor ingrese su correo";
    } else{
        $correo = trim($_POST["correo"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["contrasena"]))){
        $password_u_err = "Por favor ingrese su contraseña";
    } else{
        $password_u = trim($_POST["contrasena"]);
    }
       
    // Validate credentials
    if(empty($correo_err) && empty($password_u_err)){
        // Prepare a select statement
        $sql = "SELECT ID_User, Correo, Contrasena FROM Usuario WHERE Correo = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_correo);
            
            // Set parameters
            $param_correo = $correo;
            
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $correo, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                    	//$c="<p>".$password_u.$hashed_password."</p>";
            			//echo $c;    
                        if($password_u == $hashed_password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["correo"] = $correo;
                            $_SESSION["id"] = $id;   
                            
                            // Redirect user to welcome page
                            header("location: /views/home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_u_err = "La contraseña es invalida";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $correo_err = "No existe una cuenta para este usuario";
                }
            } else{
                echo "Oops! Algo salio mal, intente despues";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TEConnect</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>TEConnect</h2>
        <p>Inicia sesion con tus datos</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Correo</label>
                <input type="text" name="correo" class="form-control" value="<?php echo $correo; ?>">
                <span class="help-block"><?php echo $correo_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control">
                <span class="help-block"><?php echo $password_u_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p><a href="/views/RegistrarUsuario.php">Registrate</a></p>
        </form>
    </div>   
</body>
</html>