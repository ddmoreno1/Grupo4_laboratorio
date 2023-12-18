<?php
require_once "../constantes.php";
session_start();
$username = $_POST['usuario'];
$password = $_POST['clave'];
$autenticar = isset($_POST['autenticacion']) ? $_POST['autenticacion'] : 'off';

// Verificar si se está simulando 2FA
if ($autenticar == 'on') {
    // Conectar a la base de datos
    $conexion = mysqli_connect(SERVER, USER, PASS, BD);
    $consulta = "SELECT * FROM usuarios WHERE Nombre = '$username' and Password = '$password'";
    $resultado = mysqli_query($conexion, $consulta);

    $filas = mysqli_num_rows($resultado);

    if ($filas > 0) {
        $_SESSION['login'] = $username;
        $row = $resultado->fetch_assoc();
        $_SESSION["Roles"] = $row['Rol'];

        // Redireccionar según el tipo de usuario
        if($_SESSION["Roles"]==1){
            header("location:../Clases/Principal/principal.php");
        }elseif($_SESSION["Roles"]==2){
            header("location:../ClasesMed/Principal/principalMedico.php");
        }elseif($_SESSION["Roles"]==3){
            header("location:../ClasesPac/Principal/principalPacientes.php"); 
        }
        exit;
    } else {
        header("Refresh: 4; URL= login.php");
        echo '<h1 style="color: red; font-size: 24px; text-align: center; margin-top: 20px;">NO SE PUDO INGRESAR, INTENTE DE NUEVO</h1>';
    }

    mysqli_free_result($resultado);
    mysqli_close($conexion);
} else {
    echo '<h1 style="color: black; font-size: 24px; text-align: center; margin-top: 20px;">Por favor, active el Check de Doble Autenticacion</h1>';
    echo '<div class="btn btn-info text-white "><a href="loginADM.php" style="color: white;">Regresar</a></div>';
}
?>
