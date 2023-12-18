<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<style>
        
        .jumbotron{
        	color: aliceblue;
        	background: #000 url("../../assets/imagenes/header5.jpg");
        	background-size: cover;
        	overflow: hidden;      
        }
</style>
    <header>
        <!-- Navbar -->
        <?php
        session_start();

        if(isset($_SESSION['login'])) {
          $usuario = $_SESSION['login'];
          echo '<div class="jumbotron">
              <div class="page-header" style="display: flex; justify-content: space-between;">
                <h2>CRUD - ADM</h2>
                <h1 class="navbar-brand">HOLA: ' . $usuario . '</h1>
                <button class="btn btn-outline-secondary"><a class="text-white navbar-brand" href="../../Sesion/cerrar.php">CERRAR SESION</a></button>
              </div>
            </div>';
        } else {
          echo "No se ha iniciado sesión";
        }
        ?>
    </header>

    <body>
        <?php
        $html = '
    <div class="card">
                        <div class="card-header">
                        <table>
                        <tr>
                          <td>
                            <ul class="nav nav-tabs justify-content-center" role="tablist">
                            
                              <li class="nav-item">
                              <a class="nav-link " data-toggle="tab" href="#consultas" role="tab">
                                  <i></i> CONSULTAS
                                </a>
                              </li>
                              <li class="nav-item">
                              <a class="nav-link " data-toggle="tab" href="#recetas" role="tab">
                                  <i></i> RECETAS
                                </a>
                              </li>
                            </ul>
                          </td>
                        </tr>
                      </table>
                        </div>
                        <div class="card-body">
                            <!-- Tab panes -->
                            <div class="tab-content text-center">
                                
                                <div class="tab-pane active" id="consultas" role="tabpanel">
                                    <iframe src="../Consultas/consultas.php" style="width: 95%; height: 890px; border: none;"></iframe>
                                </div>
                                
                                <div class="tab-pane" id="recetas" role="tabpanel">
                                    <iframe src="../Recetas1/recetas.php" style="width: 95%; height: 890px; border: none;"></iframe>
                                </div>
                               
                </div>
            </div>
        </div>
';

    echo $html;

    ?>

    <!-- Script para manejar los tabs -->
    <script>
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab("show");
            });
        });
    </script>
    </body>

    <footer>
        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3">
                &copy; 2022 My Website. All rights reserved.
            </div>
        </footer>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
