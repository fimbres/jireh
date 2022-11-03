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
        if(!empty($datos['apellido_m']))
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
class Paciente extends Usuario
{
    private $id = false;
    protected $sexo;
    private $id_sexo;
    private $direccion;
    private DateTime $fecha;
    private $medio_envia = false;
    private $persona_responsable = false;
    private $rfc = false;
    private $codigo_postal = false;

    protected $id_poliza = false;
    protected $path_documento_poliza = false;
    protected $path_documento_antecedentes = false;
    protected $path_documento_presupuesto = false;
    //Para poder crear la clase de paciente necesitaremos mandar un
    // array con los datos para guardarlos.
    // el array debería de tener las siguientes keys
    // "sexo" / "direccion" / "fecha" 
    // También podra llevar mas datos, pero estos seran opcionales, los opcionales seran:
    // "medico_envia" / "persona_responsable" / "rfc" / "codigo_postal"
    public function __construct($datos){
        $this->id_sexo = $datos['sexo'];
        $this->direccion = $datos['direccion'];
        $this->fecha = new DateTime($datos['fecha']);
        if(!empty($datos['medico_envia']))
            $this->medio_envia = $datos['medico_envia'];
        if(!empty($datos['persona_responsable']))
            $this->persona_responsable = $datos['persona_responsable'];
        if(!empty($datos['rfc']))
            $this->rfc = $datos['rfc'];
        if(!empty($datos['codigo_postal']))
            $this->codigo_postal = $datos['codigo_postal'];
        
        parent::__construct($datos);
    }
    //Esta funcion solo sera llamada cuando ocurra un error a la hora de 
    // agregar al paciente dentro de la base de datos
    private function eliminar_fisico_BD(BaseDeDatos $BD){
        $BD->next_result();
        //Borramos primero la poliza que esta conectada al paciente
        if($this->id_poliza){
            $sql = "DELETE FROM Tb_Polizas WHERE IdPoliza = {$this->id_poliza}";
            $BD->query($sql);
        }
        $BD->next_result();
        //Borramos al paciente
        $sql = "DELETE FROM Tb_Paciente WHERE IdPaciente = {$this->id}";
        if($this->id)
            $BD->query($sql);
        $BD->next_result();
        //En teoria deberiamos de borrar los archivos dentro de cloudinary pero dejaremos
        // eso pendiente
        // if(!empty($this->path_documento_poliza))
        //     $BD->cloud_borrar_archivo($this->path_documento_poliza);
        // if(!empty($this->path_documento_antecedentes))
        //     $BD->cloud_borrar_archivo($this->path_documento_antecedentes);
        // if(!empty($this->path_documento_presupuesto))
        //     $BD->cloud_borrar_archivo($this->path_documento_presupuesto);
        //Una vez hecho eso se ha borrado todo de manera correcta.
        return true;  
    }
    public function agregar_BD(BaseDeDatos $BD,$archivos){
        $res = [false,''];
        $BD->next_result();
        //Conseguimos el numero de pacientes que tenemos en la Base de datos
        // esto nos servirá para crear una carpeta que no sea repetible:
        $sql = "SELECT count(IdPaciente) as num FROM Tb_Paciente ";
        $num_pacientes = $BD->query($sql);
        if(gettype($num_pacientes) != 'boolean')
            $num_pacientes = $num_pacientes->fetch_assoc();
        else
            return [false,"Hubo un error al hacer una conexión, inténtalo de nuevo"];
        foreach ($archivos as $documento => $arch) {
            //Verificamos que el archivo a guardar exista y no tenga error
            if ($arch['error'] == 0) {
                //Primero deberemos de guardar el archivo en una carpeta temporal
                // para eso haremos los siguientes pasos
                //Conseguimos la extension del archivo
                $nombre_array = explode('.', $arch['name']);
                $extension = strtolower(end($nombre_array));
                //GUARDAMOS EL ARCHIVO 
                $path = "files/temp/" . $documento . $extension;
                move_uploaded_file($arch['tmp_name'], $path);
                //Guardamos el archivo dentro de cloudinary
                $random = md5($num_pacientes['num']);
                $destino = "Pacientes/{$this->nombre}_{$random}/";
                $arch_cloud = $BD->cloud_subir_archivo($path, $extension, $documento, $destino);
                //Hacemos un switch para agregar al update
                switch ($documento) {
                    case 'documento_poliza':
                        $this->path_documento_poliza = $arch_cloud['secure_url'];
                        //Cuando tratamos con un documento de poliza este, debemos
                        // de agregarlo a una tabla
                        $query = "INSERT INTO Tb_Polizas(Archivo) values('{$arch_cloud['secure_url']}')";
                        if (!$BD->query($query))
                            return [!($this->eliminar_fisico_BD($BD)), "Hubo un error al guardar los archivos, inténtalo de nuevo"];
                        $BD->next_result();
                        $this->id_poliza = $BD->insert_id;
                        break;

                    case 'documento_antecedentes':
                        $this->path_documento_antecedentes = $arch_cloud['secure_url'];
                        break;
                    case 'documento_presupuesto':
                        $this->path_documento_presupuesto = $arch_cloud['secure_url'];
                        break;
                }
                unlink($path);
            }
        }
        //Conseguimos los datos de idstatus
        $status = $BD->getTb_Status('Activo');
        if(gettype($status) != 'boolean'){
            $status = $status->fetch_assoc();
        } else {
            $this->eliminar_fisico_BD($BD);
            return [false,"Hubo un error al hacer la conexión, vuelva a intentarlo"];
        }
        //Conseguimos los datos de idestadocivil, donde
        // siempre se pondrá soltero, ya que no se especifica en el formulario
        $estado_civil = $BD->getTb_estadocivil('Soltero(a)');
        if(gettype($estado_civil) != 'boolean'){
            $estado_civil = $estado_civil->fetch_assoc();
        } else {
            $this->eliminar_fisico_BD($BD);
            return [false,"Hubo un error al hacer la conexión, vuelva a intentarlo"];
        }
        $sql = "INSERT INTO Tb_Paciente(Nombre,APaterno,AMaterno,IdSexo,Direccion,
        CodigoPostal,Email,NumTelefono,FechaNacimiento,IdEstadoCivil,IdPoliza,MedicoEnvia,Representante,ArchivoAntecedentes,ArchivoPresupuesto,RFC,IdStatus)";
        $sql .= " values('{$this->nombre}','{$this->apellido_p}','{$this->apellido_p}',{$this->id_sexo},";
        $this->direccion ? $sql .= "'{$this->direccion}'," : $sql .= "NULL,"; 
        $this->codigo_postal ? $sql .= "'{$this->codigo_postal}'," : "NULL,";

        $sql .= "'{$this->correo}','{$this->telefono}','{$this->fecha->format('Y-m-d')}',{$estado_civil['IdEstadoCivil']},";
        $this->id_poliza ? $sql .= "{$this->id_poliza}," : $sql .= " NULL,";
        $this->medio_envia ? $sql .= "'{$this->medio_envia}'," : $sql .= "NULL,";
        $this->persona_responsable ? $sql .= "'{$this->persona_responsable}'," : $sql .= "NULL,";
        $this->path_documento_antecedentes ? $sql .= "'{$this->path_documento_antecedentes}'," : $sql .=" NULL,";
        $this->path_documento_presupuesto ? $sql .= "'{$this->path_documento_presupuesto}'," : $sql .=" NULL,";
        $this->rfc ? $sql .= "'{$this->rfc}'," : $sql .= "NULL,";
        $sql .= "{$status['IdStatus']})";
        if($BD->query($sql)){
            $this->id = $BD->insert_id;
            $res = [true,"Se han guardado los datos correctamente"];
        } else {
            $this->eliminar_fisico_BD($BD);
            $res = [false,'Hubo un error al hacer la conexión, inténtalo de nuevo'];
        }
        return $res;
        
    }
    // *********************
    // Funciones SET
    // *********************

    // *********************
    // Funciones GET
    // *********************

    // *********************
    // Funciones static
    // *********************
    static public function verificar_datos_formulario($datos){
        $res = [];
        //Primero verificamos que todos los datos tengan algo dentro de ellos
        !isset($datos['nombre']) ? array_push($res, 'Nombre') : false;
        !isset($datos['apellido_p']) ? array_push($res, 'Apellido Paterno') : false;
        // !isset($datos['apellido_m']) ? array_push($res, 'Apellido Materno') : false;
        !isset($datos['sexo']) ? array_push($res, 'Sexo') : false; // en teoria este nunca debería de seleccionarse porque proviene de un select
        !isset($datos['direccion']) ? array_push($res, 'Dirección') : false;
        !isset($datos['correo']) ? array_push($res, 'Correo Electrónico') : false;
        !isset($datos['telefono']) ? array_push($res, 'Teléfono') : false;
        !isset($datos['fecha']) ? array_push($res, 'Fecha de nacimiento') : false;
        return $res;
    }
}
class Recepcionista extends Usuario
{
    protected $usuario_nombre;
    private $contra;
    private $id;

    //Para poder crear la clase de recepcionista necesitaremos mandar un
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
    public function agregar_BD(BaseDeDatos $BD){
        $BD->next_result();
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
                values('" .$this->nombre ."','". $this->apellido_p ."',";
        empty($this->apellido_m) ? $sql .= "NULL," : $sql .= "'{$this->apellido_m}',";
        $sql = "'". $this->telefono ."','". $this->correo ."','" . $this->usuario_nombre ."','". $this->contra ."',". $status['IdStatus'] .")";
        if ($BD->query($sql)) {
            $this->id = $BD->insert_id;
            $BD->next_result();
            return [true,"Se han guardado los datos correctamente"];
        } else {
            $BD->next_result();
            return [false, "Hubo un error al intentar guardar los datos, vuelve a intentarlo"];
        }
    }

    public function modificar_BD(BaseDeDatos $BD){
        
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
    static public function verificar_datos_formulario($datos,BaseDeDatos $BD,$tipo = 'agregar'){
        $res = [];
        !isset($datos['nombre']) ? array_push($res, 'Nombre') : false;
        !isset($datos['apellido_p']) ? array_push($res, 'Apellido Paterno') : false;
        // !isset($datos['apellido_m']) ? array_push($res, 'Apellido Materno') : false;
        !isset($datos['telefono']) ? array_push($res, 'Teléfono') : false;
        !isset($datos['correo']) ? array_push($res, 'Correo Electrónico') : false;
        !isset($datos['usuario']) ? array_push($res, 'Usuario') : false;
        !isset($datos['contra']) ? array_push($res, 'Contraseña') : false;

        if($tipo == 'agregar'){
            !isset($datos['correo_conf']) ? array_push($res, 'Confirmación Correo Electrónico') : false;
            !isset($datos['contra_conf']) ? array_push($res, 'Confirmación Contraseña') : false;
            strcmp($datos['correo'], $datos['correo_conf']) != 0 ? array_push($res, 'No coinciden los Correos Electrónicos') : false;
            strcmp($datos['contra'], $datos['contra_conf']) != 0 ? array_push($res, 'No coinciden las Contraseñas') : false;
        }
        
        $BD->next_result();
        $sql = "SELECT * FROM Tb_Recepcionista WHERE Usuario = '{$datos['usuario']}'";
        if(!$BD->query($sql))
            array_push($res,"El nombre de usuario ya esta ocupado");
        $BD->next_result();
        return $res;
    }
}
?>