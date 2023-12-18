<?php
class usuario{
	private $IdUsuario;
    private $Nombre;
    private $Password;
    private $Rol;
    private $Foto;

    private $con;

    function __construct($cn)
    {
        $this->con = $cn;
    }

		
		
//******** 3.1 METODO update_consulta() *****************


public function update_usuario()
    {
        $this->IdUsuario = $_POST['IdUsuario'];
        $this->Nombre = $_POST['Nombre'];
        $this->Password = $_POST['Password'];
        $this->Rol = $_POST['Rol'];
		//$this->foto = $this->_get_name_file($_FILES['Foto']['name'],12);
        //$this->Foto = $_FILES['Foto']['name'];
    /*
		//exit; SIRVE PARA HACER MANTENIMIENTO 
		if(!move_uploaded_file($_FILES['Foto']['tmp_name'],PATH.$this->Foto)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
		*/
        $sql = "UPDATE usuarios SET 
                    Nombre='$this->Nombre',
                    Password='$this->Password',
                    Rol='$this->Rol'
                WHERE IdUsuario=$this->IdUsuario;";
    
        //echo $sql;
        //exit;
        if ($this->con->query($sql)) {
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }

    }

	

//******** 3.2 METODO save_consulta() *****************	

public function save_usuario()
{

    $this->IdUsuario = $_POST['IdUsuario'];
    $this->Nombre = $_POST['Nombre'];
    $this->Password = $_POST['Password'];
    $this->Rol = $_POST['Rol'];
    $this->Foto = $_FILES['Foto']['name'];

    $this->Foto = $this->_get_name_file($_FILES['Foto']['name'],12);


    //exit;
    if (!move_uploaded_file($_FILES['Foto']['tmp_name'],PATH.$this->Foto)){
        $mensaje = "Cargar la imagen";
        echo $this->_message_error($mensaje);
        exit;
    }

	$sql = "INSERT INTO usuarios VALUES(NULL,
											'$this->Nombre',
											'$this->Password',
											'$this->Rol',
											'$this->Foto');";
    //echo $sql;
        //exit;
        if ($this->con->query($sql)) {
            echo $this->_message_ok("guardó");
        } else {
            echo $this->_message_error("guardar");
        }

}



//******** 3.3 METODO _get_name_File() *****************	
	
	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}
	
	
//************* PARTE I ********************
	
	    
	 //Aquí se agregó el parámetro:  $defecto/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	/*Aquí se agregó el parámetro:  $defecto
	private function _get_combo_anio($nombre,$anio_inicial,$defecto){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($i == $defecto)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}*/
	
	//Aquí se agregó el parámetro:  $defecto
    
	private function _get_radio($arreglo,$nombre,$defecto){
		
		$html = '
		<table border=0 align="left">';
		
		//CODIGO NECESARIO EN CASO QUE EL USUARIO NO SE ESCOJA UNA OPCION
		
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				
				if($defecto == NULL){
					// OPCION PARA GRABAR UN NUEVO consulta (id=0)
					$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN consulta EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
	
//************* PARTE II ******************	

	public function get_form($IdUsuario=NULL){
		
        if ($IdUsuario == NULL) {
            $this->Nombre = NULL;
            $this->Password = NULL;
            $this->Rol = NULL;
            $this->Foto = NULL;

            $flag = NULL;
            $op = "new";

        } else {

            $sql = "SELECT * FROM usuarios WHERE IdUsuario=$IdUsuario;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();

            $num = $res->num_rows;
            if ($num == 0) {
                $mensaje = "tratar de actualizar la consulta con IdUsuario= " . $IdUsuario;
                echo $this->_message_error($mensaje);
            } else {

                // ** TUPLA ENCONTRADA **
                echo "<br>TUPLA <br>";
                echo "<pre>";
                print_r($row);
                echo "</pre>";

                $this->Nombre = $row['Nombre'];
                $this->Password = $row['Password'];
                $this->Rol = $row['Rol'];
                $this->Foto = $row['Foto'];

                $flag = "disabled";
                $op = "update";
            }
        }

		
		
		$html = '
		<form name="usuarios" method="POST" action="usuarios.php" enctype="multipart/form-data">
		
		<input type="hidden" name="IdUsuario" value="' . $IdUsuario  . '"> 
		<input type="hidden" name="op" value="' . $op  . '">
		<div class="container mt-3"> 
    	<div class="table-responsive">
		
		<div  class="table table-hover" align="center">
		<table border="2" align="center" class=" table-secondary" >
		<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="usuarios.php">Regresar</a></button></th>
					</tr>	
				<tr>
					<th colspan="2" class="text-center align-middle  table-info">DATOS DE LOS USUARIOS</th>
				</tr>

				<tr>
                                        <td>Nombre:</td>
                                        <td><input type="text" size="12" name="Nombre" value="' . $this->Nombre . '" required></td>
                                    </tr>
                                    <tr>
                                    <td>Password:</td>
                                    <td><input type="text" size="12" name="Password" value="' . $this->Password . '" required></td>
                                </tr>
									<tr>
										<td>Rol:</td>
										<td>' . $this->_get_combo_db("usuarios", "Rol", "Rol", "Rol", $this->Rol) . '</td>
									</tr>
										 <tr>
											 <td>Foto:</td>
											 <td><input type="file" name="Foto" '.$flag.' required></td>
										 </tr>
					 <tr>
						 <th colspan="2" class="text-center">
							 <input type="submit" class="btn btn-primary" name="Guardar" value="GUARDAR">
						 </th>
					 </tr>												
			</table>
			</div>
			</div>
			</div>
			</form>';
		return $html;
	}
	
	

	public function get_list(){
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<div class="container mt-10"> 
    	<div class=""> 
	
		    
		<table class="table table-hover table-bordered text-center align-middle table-secondary" >
			<tr>
				<th colspan="8" class="text-center align-middle table-info">LISTA DE USUARIOS</th>
			</tr>
			<tr>
			<th colspan="8" class="text-center align-middle" ><a class="btn btn-outline-success" href="usuarios.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
                <th class="text-center">IdUsuario</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Password</th>
                <th class="text-center">Rol</th>
				<th colspan="3">Acciones</th>
			</tr>
			</div>
			</div>';
			$sql = "SELECT usuarios.IdUsuario, usuarios.Nombre, usuarios.Password, roles.Nombre AS NombreRol 
                FROM usuarios 
                INNER JOIN roles ON usuarios.Rol = roles.IdRol";

		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdUsuario'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdUsuario'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdUsuario'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
                    <td class="text-center">' . $row['IdUsuario'] . '</td>
                    <td class="text-center">' . $row['Nombre'] . '</td>
                    <td class="text-center">' . $row['Password'] . '</td>
                    <td class="text-center">' . $row['NombreRol'] . '</td>
					<td><button class="btn btn-outline-danger"><a href="usuarios.php?d=' . $d_del_final . '">Borrar</a></button></td>
					<td><button class="btn btn-outline-primary"><a href="usuarios.php?d=' . $d_act_final . '">Actualizar</a></button></td>
					<td><button class="btn btn-outline-dark"><a href="usuarios.php?d=' . $d_det_final . '">Detalle</a></button></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_usuario($IdUsuario){
		$sql = "SELECT u.IdUsuario, u.Nombre, u.Password, r.Nombre AS RolNombre, u.Foto
                FROM usuarios u
                INNER JOIN roles r ON u.Rol = r.IdRol
                WHERE u.IdUsuario = $IdUsuario";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;


        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el consulta con Idusuario= ".$IdUsuario;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<div class="container mt-5"> 
    			<div class="table-responsive" align="center">
				<div class="table table-hover" >
				<table  border="2" align="center" class=" table-secondary" >
                <tr>
						<th colspan="2"  class="text-center table-info">DATOS DE LOS USUARIOS</th>
					</tr>
					<tr>
                            <td>Id usuarios: </td>
                            <td>' . $row['IdUsuario'] . '</td>
                        </tr>
                        <tr>
                            <td>Nombre: </td>
                            <td>' . $row['Nombre'] . '</td>
                        </tr>
                        <tr>
                            <td>Apellido: </td>
                            <td>' . $row['Password'] . '</td>
                        </tr>
                        <tr>
                            <td>Rol: </td>
                            <td>' . $row['RolNombre'] . '</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center"><img src='.PATH.$row['Foto'].' width="300px" /></th>
                        </tr>
					<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="usuarios.php">Regresar</a></button></th>
					</tr>																						
				</table>
				</div>
				</div>
		</div>';
				
				return $html;
		}
	}
	
	
	public function delete_usuario($IdUsuario){
		$sql = "DELETE FROM usuarios WHERE IdUsuario=$IdUsuario;";
			if($this->con->query($sql)){
			echo $this->_message_ok("ELIMINÓ");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}
	

	
//*************************	
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="usuarios.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="usuarios.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//**************************	
	
} // FIN SCRPIT
?>