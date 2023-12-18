<?php
class medicamento{
	private $IdMedicamento;
	private $Nombre;
	private $Tipo;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}

		
		
//******** 3.1 METODO update_consulta() *****************


public function update_medicamento()
    {
        $this->IdMedicamento = $_POST['IdMedicamento'];
		$this->Nombre = $_POST['Nombre'];
		$this->Tipo = $_POST['Tipo'];
		
		
		//exit;
		$sql = "UPDATE medicamentos SET 
		                            IdMedicamento='$this->IdMedicamento',
									Nombre='$this->Nombre',
									Tipo='$this->Tipo'
				WHERE IdMedicamento=$this->IdMedicamento;";
		//echo $sql;

        //echo $sql;
        //exit;
        if ($this->con->query($sql)) {
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }

    }

	

//******** 3.2 METODO save_consulta() *****************	

public function save_medicamento()
{

    $this->Nombre = $_POST['Nombre'];
		$this->Tipo = $_POST['Tipo'];	 
			/*	echo "<br> FILES <br>";    
				echo "<pre>";
					print_r($_FILES);
				echo "</pre>";	*/
		//exit; SIRVE PARA HACER MANTENIMIENTO 
		
		$sql = "INSERT INTO medicamentos VALUES(NULL,
		                                    
											'$this->Nombre',
											'$this->Tipo');";


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

	public function get_form($IdMedicamento=NULL){
		
        if($IdMedicamento == NULL){
			$this->Nombre = NULL;
			$this->Tipo = NULL;
			
			$flag = NULL;  //VARIABLES AUXILIARES
			$op = "new";
			
		}else{

			$sql = "SELECT * FROM medicamentos WHERE IdMedicamento=$IdMedicamento;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de actualizar la consulta con id= ".$IdMedicamento;
                echo $this->_message_error($mensaje);
            }else{   

                // ***** TUPLA ENCONTRADA *****
                /*echo "<br>TUPLA <br>";                                                                                                   echo "</pre>";*/

                $this->Nombre = $row['Nombre'];
				$this->Tipo = $row['Tipo'];
				
				
				$flag = "enable";
				$op = "update";
			}
		}

		
		
		$html = '
		<form name="medicamento" method="POST" action="medicamentos.php" enctype="multipart/form-data"  onsubmit="return validarFormulario();">
		
		<input type="hidden" name="IdMedicamento" value="' . $IdMedicamento  . '"> 
		<input type="hidden" name="op" value="' . $op  . '">
		<div class="container mt-3"> 
    	<div class="table-responsive">
		
		<div  class="table table-hover" align="center">
		<table border="2" align="center" class=" table-secondary" >
		<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="medicamentos.php">Regresar</a></button></th>
					</tr>	
				<tr>
					<th colspan="2" class="text-center align-middle  table-info">DATOS DE MEDICAMENTOS</th>
				</tr>

				<tr>
					<td>Nombre:</td>
					<td><input type="text" size="15" name="Nombre" value="' . $this->Nombre . '" required></td>
				</tr>
				<tr>
					<td>Tipo:</td>
					<td>' . $this->_get_combo_db("medicamentos","Tipo","Tipo","Tipo",$this->Tipo) . '</td>
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
				<th colspan="8" class="text-center align-middle table-info">LISTA DE MEDICAMENTOS</th>
			</tr>
			<tr>
			<th colspan="8" class="text-center align-middle" ><a class="btn btn-outline-success" href="medicamentos.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tr>
			    <th>IdMedicamento</th>
				<th>Nombre</th>
				<th>Tipo</th>
				<th colspan="3">Acciones</th>
			</tr>
			</div>
			</div>';
			$sql = "SELECT m.IdMedicamento, m.Nombre AS Nombre, m.Tipo
			FROM medicamentos m;";

		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['IdMedicamento'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['IdMedicamento'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['IdMedicamento'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
                <td>' . $row['IdMedicamento'] . '</td>
					<td>' . $row['Nombre'] . '</td>
					<td>' . $row['Tipo'] . '</td>
					<td><button class="btn btn-outline-danger"><a href="medicamentos.php?d=' . $d_del_final . '">Borrar</a></button></td>
					<td><button class="btn btn-outline-primary"><a href="medicamentos.php?d=' . $d_act_final . '">Actualizar</a></button></td>
					<td><button class="btn btn-outline-dark"><a href="medicamentos.php?d=' . $d_det_final . '">Detalle</a></button></td>
				</tr>';
		}
		$html .= '  
		</table>';
		
		return $html;
		
	}
	
	
	public function get_detail_medicamento($IdMedicamento){
		$sql = "SELECT IdMedicamento, Nombre, Tipo
					FROM medicamentos
					WHERE IdMedicamento = $IdMedicamento;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;


        //Si es que no existiese ningun registro debe desplegar un mensaje 
        //$mensaje = "tratar de eliminar el consulta con id= ".$id;
        //echo $this->_message_error($mensaje);
        //y no debe desplegarse la tablas
        
        if($num==0){
            $mensaje = "tratar de editar el consulta con IdMedicamento= ".$IdMedicamento;
            echo $this->_message_error($mensaje);
        }else{ 
				$html = '
				<div class="container mt-5"> 
    			<div class="table-responsive" align="center">
				<div class="table table-hover" >
				<table  border="2" align="center" class=" table-secondary" >
                <tr>
						<th colspan="2"  class="text-center table-info">DATOS DEL MEDICAMENTOS</th>
					</tr>
					<tr>
						<td>Nombre: </td>
						<td>'. $row['Nombre'] .'</td>
					</tr>
					<tr>
						<td>Tipo: </td>
						<td>'. $row['Tipo'] .'</td>
					</tr>
					<tr align="center">
						<th colspan="2"><button class="btn btn-outline-success"><a href="medicamentos.php">Regresar</a></button></th>
					</tr>																						
				</table>
				</div>
				</div>
		</div>';
				
				return $html;
		}
	}
	
	
	public function delete_medicamento($IdMedicamento){
		$sql = "DELETE FROM medicamentos WHERE IdMedicamento=$IdMedicamento;";
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
				<th><a href="medicamentos.php">Regresar</a></th>
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
				<th><a href="medicamentos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
//**************************	
	
} // FIN SCRPIT
?>