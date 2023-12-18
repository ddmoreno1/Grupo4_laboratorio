<?php
class especialidad{
	private $IdEsp;
	private $Descripcion;
	private $Dias;
	private $Franja_HI;
	private $Franja_HF;
	private $con;

    function __construct($cn)
    {
        $this->con = $cn;
    }

		
		
//******** 3.1 METODO update_consulta() *****************


public function update_especialidad()
    {
        $this->IdEsp = $_POST['IdEsp'];
		$this->Descripcion = $_POST['Descripcion'];
		$this->Dias = $_POST['Dias'];
		$this->Franja_HI = $_POST['Franja_HI'];
		$this->Franja_HF = $_POST['Franja_HF'];

		$sql = "UPDATE especialidades SET  
									Descripcion='$this->Descripcion',
									Dias='$this->Dias',
									Franja_HI='$this->Franja_HI',
									Franja_HF='$this->Franja_HF'
									
				WHERE IdEsp=$this->IdEsp;";

        echo $sql;
        //exit;
        if ($this->con->query($sql)) {
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }

    }

	

//******** 3.2 METODO save_consulta() *****************	

public function save_especialidad()
{

    $this->Descripcion = $_POST['Descripcion'];
		$this->Dias = $_POST['Dias'];
		$this->Franja_HI = $_POST['Franja_HI'];
		$this->Franja_HF = $_POST['Franja_HF'];


		
		$sql = "INSERT INTO especialidades VALUES(NULL,
											'$this->Descripcion',
											'$this->Dias',
											'$this->Franja_HI',
											'$this->Franja_HF');";


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

	public function get_form($IdEsp=NULL){
		
        if ($IdEsp == NULL) {
			$this->Descripcion = NULL;
			$this->Dias = NULL;
			$this->Franja_HI = NULL;
			$this->Franja_HF = NULL;
			

			$flag = NULL;
			$op = "new";
		} else {

			$sql = "SELECT * FROM especialidades WHERE IdEsp=$IdEsp;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();

			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "tratar de actualizar el vehiculo con id= ". $IdEsp;
				echo $this->_message_error($mensaje);
			} else {

                // ***** TUPLA ENCONTRADA *****
                /*echo "<br>TUPLA <br>";                                                                                                   echo "</pre>";*/

                $this->Descripcion = $row['Descripcion'];
				$this->Dias = $row['Dias'];
				$this->Franja_HI = $row['Franja_HI'];
				$this->Franja_HF = $row['Franja_HF'];

                $flag = "disabled";
                $op = "update";
            }
        }

		
		
		$html = '
		<form name="especialidad" method="POST" action="especialidades.php" enctype="multipart/form-data"  onsubmit="return validarFormulario();">
		
		<input type="hidden" name="IdEsp" value="' . $IdEsp  . '"> //
		<input type="hidden" name="op" value="' . $op  . '">
		<div class="container mt-3"> 
    	<div class="table-responsive">
		
		<div  class="table table-hover" align="center">
		<table border="2" align="center" class=" table-secondary" >
		<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="especialidades.php">Regresar</a></button></th>
					</tr>	
				<tr>
					<th colspan="2" class="text-center align-middle  table-info">DATOS DE ESPECIALIDADES</th>
				</tr>

				<tr>
				<td>Descripcion:</td>
				<td><input type="text" size="15" name="Descripcion" value="' . $this->Descripcion. '" required></td>
			</tr>
			
			<tr>
				<td>Dias:</td>
				<td><input type="text" size="15" name="Dias" value="' . $this->Dias. '" required></td>
			</tr>	
			<tr>
				<td>Franja Horaria de Inicio:</td>
				<td><input type="time" name="Franja_HI" value="' . $this->Franja_HI . '" required></td>
			</tr>
			<tr>
				<td>Franja Horaria de Fin:</td>
				<td><input type="time" name="Franja_HF" value="' . $this->Franja_HF . '" required></td>
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
				<th colspan="8" class="text-center align-middle table-info">LISTA DE ESPECIALIDADES</th>
			</tr>
			<tr>
			<th colspan="8" class="text-center align-middle" ><a class="btn btn-outline-success" href="especialidades.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
				<th>Descripcion</th>
				<th>Dias</th>
				<th>Franja Horaria Inicio</th>
				<th>Franja Horaria Fin</th>
				<th colspan="3">Acciones</th>
			</tr>
			</div>
			</div>';
			$sql = "SELECT IdEsp, Descripcion, Dias, Franja_HI, Franja_HF
				FROM especialidades;";

		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdEsp'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdEsp'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdEsp'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
                <td>' . $row['Descripcion'] . '</td>
                <td>' . $row['Dias'] . '</td>
                <td>' . $row['Franja_HI'] . '</td>
				<td>' . $row['Franja_HF'] . '</td>
					<td><button class="btn btn-outline-danger"><a href="especialidades.php?d=' . $d_del_final . '">Borrar</a></button></td>
					<td><button class="btn btn-outline-primary"><a href="especialidades.php?d=' . $d_act_final . '">Actualizar</a></button></td>
					<td><button class="btn btn-outline-dark"><a href="especialidades.php?d=' . $d_det_final . '">Detalle</a></button></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_especialidad($IdEsp){
		$sql = "SELECT Descripcion, Dias, Franja_HI, Franja_HF 
			FROM especialidades 
				WHERE IdEsp = " . $IdEsp . ";";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;


        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el consulta con EspecialidadId= ".$IdEsp;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<div class="container mt-5"> 
    			<div class="table-responsive" align="center">
				<div class="table table-hover" >
				<table  border="2" align="center" class=" table-secondary" >
                <tr>
						<th colspan="2"  class="text-center table-info">DATOS DE LA ESPECIALIDAD</th>
					</tr>
                <tr>
                                <td>Descripcion: </td>
                                <td>' . $row['Descripcion'] . '</td>
                            </tr>
                            <tr>
                                <td>Fecha: </td>
                                <td>' . $row['Dias'] . '</td>
                            </tr>
                            <tr>
                                <td>Hora de inicio: </td>
                                <td>' . $row['Franja_HI'] . '</td>
                            </tr>
							<tr>
                                <td>Hora de Fin: </td>
                                <td>' . $row['Franja_HF'] . '</td>
                            </tr>
					<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="especialidades.php">Regresar</a></button></th>
					</tr>																						
				</table>
				</div>
				</div>
		</div>';
				
				return $html;
		}
	}
	
	
	public function delete_especialidad($IdEsp){
		$sql = "DELETE FROM especialidades WHERE IdEsp=$IdEsp;";
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
				<th><a href="especialidades.php">Regresar</a></th>
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
				<th><a href="especialidades.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//**************************	
	
} // FIN SCRPIT
?>