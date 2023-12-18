<?php
require_once "../constantes.php";
session_start();
$username = $_POST['usuario'];
$password = $_POST['clave'];

//Conectar a la base de datos
$conexion = mysqli_connect(SERVER, USER, PASS, BD);
$consulta = "SELECT * FROM usuarios WHERE Nombre = '$username' and Password = '$password'";
$resultado = mysqli_query($conexion, $consulta);

$filas = mysqli_num_rows($resultado); //0 si no coincide, 1 o + si concidio

//var_dump($usuario);

echo '<br>';
if($filas>0){
    $_SESSION['login'] = $username; // Guardar el usuario en una sesión
    $row = $resultado->fetch_assoc();
    $_SESSION["Roles"] = $row['Rol'];
    if($_SESSION["Roles"]==1){
        header("location:../Clases/Principal/principal.php");
    }elseif($_SESSION["Roles"]==2){
        header("location:../ClasesMed/Principal/principalMedico.php");
    }elseif($_SESSION["Roles"]==3){
        header("location:../ClasesPac/Principal/principalPacientes.php"); 
    }
} else {
    header("Refresh: 4; URL= login.php");
    echo '<h1 style="color: red; font-size: 24px; text-align: center; margin-top: 20px;">NO SE PUDO INGRESAR, INTENTE DE NUEVO</h1>';
}
mysqli_free_result($resultado);
mysqli_close($conexion);
?>