<?php
class medico{
	private $IdMedico;
    private $Nombre;
    private $Especialidad;
    private $IdUsuario;
    private $con;

    function __construct($cn)
    {
        $this->con = $cn;
    }


		
		
//******** 3.1 METODO update_consulta() *****************


public function update_medico()
    {
        $this->IdMedico = $_POST['IdMedico'];
        $this->Nombre = $_POST['Nombre'];
        $this->Especialidad = $_POST['Especialidad'];
        $this->IdUsuario = $_POST['IdUsuario'];

        $sql = "UPDATE medicos SET 
        Nombre='$this->Nombre',
        Especialidad='$this->Especialidad',
        IdUsuario='$this->IdUsuario'
        WHERE IdMedico = $this->IdMedico;";

        echo $sql;
        //exit;
        if ($this->con->query($sql)) {
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }

    }

	

//******** 3.2 METODO save_consulta() *****************	

public function save_medico()
{

	$this->Nombre = $_POST['Nombre'];
        $this->Especialidad = $_POST['Especialidad'];
        $this->IdUsuario = $_POST['IdUsuario'];
        
        $sql = "INSERT INTO medicos (Nombre, Especialidad, IdUsuario) 
        VALUES ('$this->Nombre', '$this->Especialidad', '$this->IdUsuario');";

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

	public function get_form($IdMedico=NULL){
		
        if ($IdMedico == NULL) {

        
			// Aquí deberías inicializar todas las propiIdUsuarioes a NULL o valores predeterminados
			// según corresponda para evitar errores de variables no definidas más adelante
			
			$this->Nombre = NULL;
			$this->Especialidad = NULL;
			$this->IdUsuario = NULL;
	
			$flag = "enabled";
			$op = "new";
	
		} else {
	
			$sql = "SELECT * FROM medicos WHERE IdMedico=$IdMedico;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
	
			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "tratar de actualizar la consulta con IdMedico= " . $IdMedico;
				echo $this->_message_error($mensaje);
			} else {
	
				$this->IdUsuario = $row['IdUsuario'];
	
				$flag = "enabled";
				$op = "update";
			}
		}

		
		
		$html = '
		<form name="medicos" method="POST" action="medicos.php" enctype="multipart/form-data"  onsubmit="return validarFormulario();">
		
		<input type="hidden" name="IdMedico" value="' . $IdMedico  . '"> //
		<input type="hidden" name="op" value="' . $op  . '">
		<div class="container mt-3"> 
    	<div class="table-responsive">
		
		<div  class="table table-hover" align="center">
		<table border="2" align="center" class=" table-secondary" >
		<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="medicos.php">Regresar</a></button></th>
					</tr>	
				<tr>
					<th colspan="2" class="text-center align-middle  table-info">DATOS DE LOS MEDICOS</th>
				</tr>

				<tr>
                            <td>Nombre:</td>
                            <td><input type="text" size="12" name="Nombre" value="' . $this->Nombre . '" required></td>
                        </tr>
                        <tr>
                            <td>Especialidad:</td>
                            <td>' . $this->_get_combo_db("especialidades", "IdEsp", "Descripcion", "Especialidad", $this->Especialidad) . '</td>
                        </tr>
                        <tr>
                        <td>Usuario Id:</td>
                        <td>' . $this->_get_combo_db("usuarios", "IdUsuario", "Nombre", "IdUsuario", $this->IdUsuario) . '</td>
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
				<th colspan="8" class="text-center align-middle table-info">LISTA DE LOS MEDICOS</th>
			</tr>
			<tr>
			<th colspan="8" class="text-center align-middle" ><a class="btn btn-outline-success" href="medicos.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
					<th class="text-center">Medico Id</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Especialidad</th>
                    <th class="text-center">Usuario</th>
				<th colspan="3">Acciones</th>
			</tr>
			</div>
			</div>';
			$sql = "SELECT medicos.IdMedico, 
            medicos.Nombre,
            especialidades.Descripcion AS Especialidad,
            usuarios.Nombre AS NombreUsuario
            FROM medicos
            INNER JOIN usuarios ON medicos.IdUsuario = usuarios.IdUsuario
            INNER JOIN especialidades ON medicos.Especialidad = especialidades.IdEsp";
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdMedico'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdMedico'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdMedico'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td class="text-center">' . $row['IdMedico'] . '</td>
                	<td class="text-center">' . $row['Nombre'] . '</td>
                	<td class="text-center">' . $row['Especialidad'] . '</td>
                	<td class="text-center">' . $row['NombreUsuario'] . '</td>
					<td><button class="btn btn-outline-danger"><a href="medicos.php?d=' . $d_del_final . '">Borrar</a></button></td>
					<td><button class="btn btn-outline-primary"><a href="medicos.php?d=' . $d_act_final . '">Actualizar</a></button></td>
					<td><button class="btn btn-outline-dark"><a href="medicos.php?d=' . $d_det_final . '">Detalle</a></button></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_medico($IdMedico){
		$sql = "SELECT medicos.IdMedico, 
            medicos.Nombre,
            especialidades.Descripcion AS Especialidad,
            usuarios.Nombre AS NombreUsuario
            FROM medicos 
            INNER JOIN usuarios ON medicos.IdUsuario = usuarios.IdUsuario
            INNER JOIN especialidades ON medicos.Especialidad = especialidades.IdEsp
            WHERE medicos.IdMedico = $IdMedico";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;


        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el consulta con EspecialidadId= ".$IdMedico;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<div class="container mt-5"> 
    			<div class="table-responsive" align="center">
				<div class="table table-hover" >
				<table  border="2" align="center" class=" table-secondary" >
                <tr>
						<th colspan="2"  class="text-center table-info">DATOS DE LOS MEDICOS</th>
					</tr>
					<tr>
                        <td>Id Médico: </td>
                        <td>' . $row['IdMedico'] . '</td>
                    </tr>
                    <tr>
                        <td>Nombre: </td>
                        <td>' . $row['Nombre'] . '</td>
                    </tr>
                    <tr>
                        <td>Especialidad: </td>
                        <td>' . $row['Especialidad'] . '</td>
                    </tr>
                    <tr>
                        <td>Nombre de Usuario: </td>
                        <td>' . $row['NombreUsuario'] . '</td>
                    </tr>
					<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="medicos.php">Regresar</a></button></th>
					</tr>																						
				</table>
				</div>
				</div>
		</div>';
				
				return $html;
		}
	}
	
	
	public function delete_medico($IdMedico){
		$sql = "DELETE FROM medicos WHERE IdMedico=$IdMedico;";
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
				<th><a href="medicos.php">Regresar</a></th>
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
				<th><a href="medicos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//**************************	
	
} // FIN SCRPIT
?>