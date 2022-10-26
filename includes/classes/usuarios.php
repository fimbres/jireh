<?php 

class Usuario
{
    protected $nombre;
    protected $apellido_p;
    protected $apellido_m;
    protected $telefono;
    protected $correo;

    //Para poder crear la clase de recepcionista necesitaremos obtener el mandar un
    // array con los datos para guardarlos.
    // el array deberia de tener las siguientes keys
    // "nombre" / "apellido_p" / "apellido_m" / "telefono" / "correo" 
    public function __construct($datos)
    {
        $this->nombre = $datos['nombre'];
        $this->apellido_p = $datos['apellido_p'];
        $this->apellido_m = $datos['apellido_m'];
        $this->telefono = $datos['telefono'];
        $this->correo = $datos['correo'];
    }

    // *********************
    // Funciones SET
    // *********************
    private function setNombre($valor){
        $this->nombre = $valor;
    }
    private function setApellido_p($valor){
        $this->apellido_p = $valor;
    }
    private function setApellido_m($valor){
        $this->apellido_m = $valor;
    }
    private function setTelefono($valor){
        $this->telefono = $valor;
    }
    private function setCorreo($valor){
        $this->correo = $valor;
    }
    // *********************
    // Funciones GET
    // *********************
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido_p(){
        return $this->apellido_p;
    }
    public function getApellido_m(){
        return $this->apellido_m;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function getCorreo(){
        return $this->telefono;
    }
}

class Recepcionista extends Usuario
{
    protected $usuario_nombre;
    private $contra;
    private $id;

    //Para poder crear la clase de recepcionista necesitaremos obtener el mandar un
    // array con los datos para guardarlos.
    // el array deberia de tener las siguientes keys
    // "nombre" / "apellido_p" / "apellido_m" / "telefono" / "correo" / "usuario" / "contra"
    public function __construct($datos)
    {
        $this->usuario_nombre = $datos['usuario'];
        $this->contra = $datos['contra'];
        parent::__construct($datos);
    }

    //Con esta funcion agregamos a la recepcionista a la base de datos
    public function crear_recepcionista_BD(BaseDeDatos $BD){
        //Primero buscamos el valor del id de estado activo dentro de la base de datos
        $status = $BD->getTb_Status('Activo');
        // al verificar que no sea boleando evitamos el error de 
        // pensar directamente que estamos tratando con una clase y podemos
        // dar por bueno que todo salio bien si no es boleano
        if(gettype($status) != 'boolean'){
            $status = $status->fetch_assoc();
        } else {
            return [false,"Hubo un error al hacer la conexion, vuelva a intentarlo"];
        }
        $sql = "INSERT INTO Tb_Recepcionista(Nombre,APaterno,AMaterno,NumTelefono,Email,Usuario,Contrasena,IdStatus) 
                values('" .$this->nombre ."','". $this->apellido_p ."','". $this->apellido_m ."',
                '". $this->telefono ."','". $this->correo ."','" . $this->usuario_nombre ."','". $this->contra ."',". $status['IdStatus'] .")";
        if ($BD->query($sql)) {
            return [true,"Se han guardado los datos correctamente"];
        } else {
            return [false, "Hubo un error al intentar guardar los datos, vuelve a intentarlo"];
        }
    }

    // *********************
    // Funciones SET
    // *********************
    private function setUsuarioNombre($valor){
        $this->usuario_nombre = $valor;
    }
    private function setContra($valor){
        $this->contra = $valor;
    }
    // *********************
    // Funciones GET
    // *********************
    public function getUsuarioNombre(){
        return $this->usuario_nombre;
    }
    private function getContra(){
        return $this->contra;
    }

    // *********************
    // Funciones ESTATICAS
    // *********************

    
    static public function verificar_datos_formulario_recepcionista($datos){
        $res = [];
        !isset($datos['nombre']) ? array_push($res, 'Nombre') : false;
        !isset($datos['apellido_p']) ? array_push($res, 'Apellido Paterno') : false;
        !isset($datos['apellido_m']) ? array_push($res, 'Apellido Materno') : false;
        !isset($datos['telefono']) ? array_push($res, 'Teléfono') : false;
        !isset($datos['correo']) ? array_push($res, 'Correo Electrónico') : false;
        !isset($datos['correo_conf']) ? array_push($res, 'Confirmación Correo Electrónico') : false;
        !isset($datos['usuario']) ? array_push($res, 'Usuario') : false;
        !isset($datos['contra']) ? array_push($res, 'Contraseña') : false;
        !isset($datos['contra_conf']) ? array_push($res, 'Confirmación Contraseña') : false;
        strcmp($datos['correo'], $datos['correo_conf']) != 0 ? array_push($res, 'No coinciden los Correos Electrónicos') : false;
        strcmp($datos['contra'], $datos['contra_conf']) != 0 ? array_push($res, 'No coinciden las Contraseñas') : false;
        return $res;
    }
}
?>