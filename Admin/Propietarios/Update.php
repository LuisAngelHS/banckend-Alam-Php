<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With");

include_once '../../Database.php';
include_once 'Propietarios.php';

$database = new DataBase();
$db = $database->getConnection();

$precio = new Propietario($db);
try{
	$data = json_decode(file_get_contents("php://input"));
    
	if(!isset($data)){
	    return json_encode(array("Error"=>"Los  valores no son validos"));
	}
	
	$nombre=isset($data->nombre)?$data->nombre:null;
	$direccion=isset($data->direccion)?$data->direccion:'No definido';
	$telefono=isset($data->telefono)?$data->telefono:'No definido';
	$correo=isset($data->correo)?$data->correo:null;
    $usuario=isset($data->usuario)?$data->usuario:'No definido';
    
	
	$query = "UPDATE ".$precio->table_name." SET nombre=:nombre,direccion=:direccion,telefono=:telefono,correo=:correo,usuario=:usuario where id=:id";
	
	$stm = $precio->conn->prepare($query);
	$stm->bindParam(":nombre",$nombre);
	$stm->bindParam(":direccion",$direccion);
	$stm->bindParam(":telefono",$telefono);
	$stm->bindParam(":correo",$correo);
    $stm->bindParam(":usuario",$usuario);
	$stm->bindParam(":id",$data->id);
	
	$stmt = $precio->Update($stm);
	if($stmt !==false){
		echo json_encode(array("Mensaje"=>"Datos Actualizados", "nom"=>$nombre));
	}else{
		echo json_encode(array("Error"=>"No se ha creado el registro"));
	}
}catch(Exception $ex){
	echo json_encode(array("Error"=>"No se ha podido procesar la solicitud"));
}

?>