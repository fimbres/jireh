<?php 

class Usuario
{
    protected $nombre;
    protected $apellido_p;
    protected $apellido_m = false;
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
        return $this->correo;
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
    private $id = false;
    protected $usuario_nombre;
    private $contra;
    protected $id_status = false;

    //Para poder crear la clase de recepcionista necesitaremos mandar un
    // array con los datos para guardarlos.
    // el array deberia de tener las siguientes keys
    //  "usuario" / "contra"
    // Las keys opcionales seran
    // "id", "id_status"
    //Y tambien debera tener las key necesarias para los campos de la clase usuario
    public function __construct($datos)
    {
        $this->usuario_nombre = $datos['usuario'];
        $this->contra = $datos['contra'];
        if(isset($datos['id']))
            $this->id = $datos['id'];
        if(isset($datos['id_status']))
            $this->id_status = $datos['id_status'];
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
        $sql .= "'". $this->telefono ."','". $this->correo ."','" . $this->usuario_nombre ."','". $this->contra ."',". $status['IdStatus'] .")";
        try {
            $BD->query($sql);
            $this->id = $BD->insert_id;
            $BD->next_result();
            return [true,"Se han guardado los datos correctamente"];
        } catch (mysqli_sql_exception $e) {
            $BD->next_result();
            if (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                return [false, "Ya hay un usuario con el correo o nombre de usuario ingresados."];
            }
            else{
                return [false, "Datos incorrectos: " . $e->getMessage()];
            }
        }
    }
    public function modificar_BD($datos,BaseDeDatos $BD){
        $this->nombre = $datos['nombre'];
        $this->apellido_p = $datos['apellido_p'];
        $this->apellido_m = (isset($datos['apellido_m']) ? $datos['apellido_m'] : false);
        $this->telefono = $datos['telefono'];
        $this->correo = $datos['correo'];
        $this->usuario_nombre = $datos['usuario'];
        // $this->contra = $datos['contra'];
        if(!$this->id)
            return [false,"Hubo un error al cargar los datos, inténtalo de nuevo"];
        $BD->next_result();
        $sql = "UPDATE Tb_Recepcionista SET Nombre = '{$this->nombre}', APaterno = '{$this->apellido_p}',";
        $sql .= ($this->apellido_m ? " AMaterno = '{$this->apellido_m}', " : " AMaterno = NULL, ");
        $sql .=" Email = '{$this->correo}', NumTelefono = '{$this->telefono}', Usuario = '{$this->usuario_nombre}' 
        WHERE IdRecepcionista = {$this->id}";
        try {
            $BD->query($sql);
            return [true, "Se han hecho los cambios"];
        } catch (mysqli_sql_exception $e) {
            $BD->next_result();
            if (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                return [false, "Ya hay un usuario con el correo o nombre de usuario ingresados."];
            }
            else{
                return [false, "Datos incorrectos: " . $e->getMessage()];
            }
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
    public function getIdStatus(){
        return $this->id_status;
    }
    public function getId(){
        return $this->id;
    }

    // *********************
    // Funciones ESTATICAS
    // *********************
    static public function crear_recepcionista($id, BaseDeDatos $BD){
        $BD->next_result();
        $sql = "SELECT * FROM Tb_Recepcionista WHERE IdRecepcionista = $id";
        $res = $BD->query($sql);
        $BD->next_result();
        if($res){
            if($res->num_rows > 0){
                $res = $res->fetch_assoc();
                //Creamos a la recepcionista,
                $datos = [
                    "nombre" => $res['Nombre'],
                    "apellido_p" => $res['APaterno'],
                    "apellido_m" => $res['AMaterno'],
                    "telefono" => $res['NumTelefono'],
                    "correo" => $res['Email'],
                    "usuario" => $res['Usuario'],
                    "contra" => $res['Contrasena'],
                    "id" => $res['IdRecepcionista'],
                    "id_status" => $res['IdStatus']
                ];
                $valor = new Recepcionista($datos);
                return $valor;
            }
        }
        return false;
    }

    static public function verificar_datos_formulario($datos,BaseDeDatos $BD,$tipo = 'agregar'){
        $res = [];
        !isset($datos['nombre']) ? array_push($res, 'Nombre') : false;
        !isset($datos['apellido_p']) ? array_push($res, 'Apellido Paterno') : false;
        // !isset($datos['apellido_m']) ? array_push($res, 'Apellido Materno') : false;
        !isset($datos['telefono']) ? array_push($res, 'Teléfono') : false;
        !isset($datos['correo']) ? array_push($res, 'Correo Electrónico') : false;
        !isset($datos['usuario']) ? array_push($res, 'Usuario') : false;

        if($tipo == 'agregar'){
            !isset($datos['contra']) ? array_push($res, 'Contraseña') : false;
            !isset($datos['correo_conf']) ? array_push($res, 'Confirmación Correo Electrónico') : false;
            !isset($datos['contra_conf']) ? array_push($res, 'Confirmación Contraseña') : false;
            strcmp($datos['correo'], $datos['correo_conf']) != 0 ? array_push($res, 'No coinciden los Correos Electrónicos') : false;
            strcmp($datos['contra'], $datos['contra_conf']) != 0 ? array_push($res, 'No coinciden las Contraseñas') : false;
        }
        
        if($tipo != "modificar_usuario_igual"){
            $BD->next_result();
            $sql = "SELECT * FROM Tb_Recepcionista WHERE Usuario = '{$datos['usuario']}'";
            $res_query = $BD->query($sql);
            if(gettype($res_query) != 'boolean'){
                if($res_query->num_rows > 0)
                    array_push($res,"El nombre de usuario ya esta ocupado");
            } 
            $BD->next_result();
        }
        return $res;
    }
}
class Doctor extends Usuario
{
    private $id = false;
    protected $usuario_nombre;
    private $contra;
    protected $id_status;

    public function __construct($datos)
    {
        $this->usuario_nombre = $datos['usuario'];
        $this->contra = $datos['contra'];

        if(isset($datos['id']))
            $this->id = $datos['id'];
        if(isset($datos['id_status']))
            $this->id_status = $datos['id_status'];
        parent::__construct($datos);
    }
    //apaterno amaterno Telefono correo usuario contraseña
    public function agregar_BD(BaseDeDatos $BD){
        $BD->next_result();
        $status = $BD->getTb_status('Activo');
        if(gettype($status) != 'boolean'){
            $status = $status->fetch_assoc();
        }else{
            return [false, "Hubo un error al hacer la conexion, vuelva a intentarlo"];
        }
        $sql = "INSERT INTO Tb_Doctor(Nombre, APaterno, AMaterno, Email, NumTelefono, Usuario, Contrasena, IdStatus) 
        values('" .$this->nombre ."','". $this->apellido_p ."',";
        empty($this->apellido_m) ? $sql .= "NULL," : $sql .= "'{$this->apellido_m}',";
        $sql .= "'". $this->correo ."','". $this->telefono ."','" . $this->usuario_nombre ."','". $this->contra ."',". $status['IdStatus'] .")";

        //$sql = "INSERT INTO Tb_Doctor(Nombre, APaterno, AMaterno, Email, NumTelefono, Usuario, Contrasena, IdStatus)
        //VALUES('".$this->nombre ."','". $this->apaterno . "', '";
        //empty($this->amaterno) ? $sql .= "NULL," : $sql .= "'{$this->amaterno}',";
        //$sql .= "'". $this->correo ."','". $this->Telefono ."','" . $this->usuario ."','". $this->contraseña ."',". $status['IdStatus'] .")";
        try {
            $BD->query($sql);
            $this->id = $BD->insert_id;
            $BD->next_result();
            return [true, "Se han guardado los datos correctamente"];
        } catch (mysqli_sql_exception $e) {
            $BD->next_result();
            if (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                return [false, "Ya hay un usuario con el correo o nombre de usuario ingresados."];
            }
            else{
                return [false, "Datos incorrectos: " . $e->getMessage()];
            }
        }
        // if($BD->query($sql)){
        //     $this->id = $BD->insert_id;
        //     $BD->next_result();
        //     return [true,"Se han guardado los datos correctamente"];
        // }else{
        //     $BD->next_result();
        //     return [false,"Hubo un error al intentar guardar los datos, vuelva a intentarlo"];
        // }
    }

    public function modifica_BD($datos,BaseDeDatos $BD){
        $this->nombre = $datos['nombre'];
        $this->apellido_p = $datos['apellido_p'];
        $this->apellido_m = (isset($datos['apellido_m']) ? $datos['apellido_m'] : false);
        $this->telefono = $datos['telefono'];
        $this->correo = $datos['correo'];
        $this->usuario = $datos['usuario'];
        // $this->contra = $datos['contra'];
        if(!$this->id)
            return [false,"Hubo un error al cargar los datos, intentarlo de nuevo"];
        $BD->next_result();
        $sql = "UPDATE Tb_Doctor
        SET Nombre='{$this->nombre}', APaterno='{$this->apellido_p}',"; 
        $sql .= ($this->apellido_m ?  "AMaterno='{$this->apellido_m}', ": "AMaterno = NULL ,");
        $sql .=" Email='{$this->correo}', NumTelefono='{$this->telefono}', Usuario='{$this->usuario}' 
        WHERE IdDoctor={$this->id}";
        try {
            $BD->query($sql);
            return [true, "Se han hecho los cambios"];
        } catch (mysqli_sql_exception $e) {
            $BD->next_result();
            if (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                return [false, "Ya hay un usuario con el correo o nombre de usuario ingresados."];
            }
            else{
                return [false, "Datos incorrectos: " . $e->getMessage()];
            }
        }
    }

    private function setUsuarioNombre($valor){
        $this->usuario_nombre = $valor;
    }
    private function setContra($valor){
        $this->contra = $valor;
    }

    public function getUsuarioNombre(){
        return $this->usuario_nombre;
    }

    public function getContra(){
        return $this->contra;
    }

    public function getIdStatus(){
        return $this->id_status;
    }

    public function getId(){
        return $this->id;
    }

    static public function crear_Doctor($id, BaseDeDatos $BD){
        $BD->next_result();
        $sql = "SELECT * FROM Tb_Doctor WHERE IdDoctor = $id";
        $res = $BD->query($sql);
        $BD->next_result();
        if($res){
            if($res->num_rows > 0){
                $res = $res->fetch_assoc();
                $datos = [
                    "nombre" => $res['Nombre'],
                    "apellido_p" => $res['APaterno'],
                    "apellido_m" => $res['AMaterno'],
                    "correo" => $res['Email'],
                    "telefono" => $res['NumTelefono'],
                    "usuario" => $res['Usuario'],
                    "contra" => $res['Contrasena'],
                    "id" => $res['IdDoctor'],
                    "Id_status" => $res['IdStatus'],
                ];
                $valor = new Doctor($datos);
                return $valor;
            }
        }
        return false;

    }

    static public function verificar_datos_formulario($datos,BaseDeDatos $BD,$tipo = 'agregar'){
        $res =[];
        !isset($datos['nombre']) ? array_push($res, 'Nombre') : false;
        !isset($datos['apellido_p']) ? array_push($res, 'Apellido Paterno') : false;
        !isset($datos['telefono']) ? array_push($res, 'Telfono') : false;
        !isset($datos['correo']) ? array_push($res, 'Correo') : false;
        !isset($datos['usuario']) ? array_push($res, 'Usuario') : false;

        if($tipo == 'agregar'){
            !isset($datos['contra']) ? array_push($res, 'Contraseña') : false;
            !isset($datos['correo_conf']) ? array_push($res, 'Confirmacion Correo Electronico') : false;
            !isset($datos['contra_conf']) ? array_push($res, 'Confirmacion Contraseña') : false;
            strcmp($datos['correo'], $datos['correo_conf']) !=0 ? array_push($res , 'No coinciden los Correos Electronicos'): false;
            strcmp($datos['contra'], $datos['contra_conf']) !=0 ? array_push($res , 'No coinciden las Contraseñas'): false;
        }

        if($tipo !="modificar_usuario_igual"){
            $BD->next_result();
            $sql = "SELECT * FROM Tb_Doctor WHERE Usuario = '{$datos['usuario']}'";
            $res_query = $BD->query($sql);
            if(gettype($res_query) != 'boolean'){
                if($res_query->num_rows > 0)
                    array_push($res, "El nombre de usuario ya esta ocupado");
            }
            $BD->next_result();
        }
        return $res;
    }

}

class Citas{
    private $id = false;
    protected $idPaciente;
    private $idDoctor;
    private $tratamiento;
    private $fechaInicio;
    private $fechaFinal;
    private $idTratamiento;
    private $MotivoCan;
    private $costo;
    protected $idStatus;

    public function __construct($datos){

        if(isset($datos['paciente'])){
            $this->idPaciente = $datos['paciente'];
        }
        if(isset($datos['id'])){
            $this->id = $datos['id'];
        }
        if(isset($datos['doctor'])){
            $this->idDoctor = $datos['doctor'];
        }
        if(isset($datos['fecha'] )){
            $this->fechaInicio = $datos['fecha']." ".$datos['horainicio'];
        }
        if(isset($datos['fecha'])){
            $this->fechaFinal = $datos['fecha']." ".$datos['horafin'];
        }
        if(isset($datos['tratamiento'])){
            $this->tratamiento = $datos['tratamiento'];
        }
        if(isset($datos['costo'])){
            $this->costo = $datos['costo'];
        }

    }

     // *********************
    // Funciones SET
    // *********************

    // *********************
    // Funciones GET
    // *********************
    public function getIdPaciente(){
        return $this->idPaciente;
    }
    public function getIdDoctor(){
        return $this->idDoctor;
    }
    public function getIdStatus(){
        return $this->id_status;
    }
    
    public function getIdCita(){
        return $this->id;
    }
    public function getFechaInicio(){
        return $this->fechaInicio;
    }
    public function getFechaFinal(){
        return $this->fechaFinal;
    }
    public function getTratamiento(){
        return $this->tratamiento;
    }
    public function getCosto(){
        return $this->costo;
    }

    public function agregar_BD(BaseDeDatos $BD){
        $BD->next_result();

        $status = $BD->getTb_Status('Vigente');

        if(gettype($status) !='boolean'){
            $status = $status->fetch_assoc();
        }else{
            return [false,"Hubo un error al hacer la conexion, vuelva a intentarlo"];
        }
        $sql = "INSERT INTO Tb_Cita(IdPaciente, FechaInicio, FechaFinal, IdDoctor, Descripcion, IdTratamiento, MotivoCancelacion, Costo, IdStatus)
                values(".$this->idPaciente .",'". $this->fechaInicio ."','".$this->fechaFinal ."',".$this->idDoctor.",'".$this->tratamiento."',";
                empty($this->idTratamiento) ? $sql .= "NULL," : $sql .= "{$this->idTratamiento},";
                $sql .= "'prueba1234',";
                $sql .="{$this->costo},";
                $sql .= $status['IdStatus'] .");";

        if($BD->query($sql)){
            $this->id = $BD->insert_id;
            $BD->next_result();
            return [true,"Se ha registrado la cita correctamente"];
        }else{
            $BD->next_result();
            return[false,"Hubo un error al intentar guardar los datos, volver a intentarlo"];
        }
    }
    
    static public function crear_Cita($id, BaseDeDatos $BD){
        $BD->next_result();
        $sql = "SELECT * FROM Tb_Cita WHERE IdCita = $id";
        $res = $BD->query($sql);
        $BD->next_result();
        if($res){
            if($res->num_rows > 0){
                $res = $res->fetch_assoc();
                $datos = [
                    "id" => $res['IdCita'],
                    "idPaciente" => $res['IdPaciente'],
                    "idDoctor" => $res['IdDoctor'],
                    "fechaInicio" => $res['FechaInicio'],
                    "fechaFinal" => $res['FechaFinal'],
                    "tratamiento" => $res['Descripcion'],
                    "costo" => $res['Costo'],
                    "idStatus" => $res['IdStatus'],
                ];
                $valor = new Citas($datos);
                return $valor;
            }
        }
        return false;

    }
    
    static public function verificar_datos_formulario($datos,BaseDeDatos $BD, $tipo = 'agregar'){
        $res = [];
        !isset($datos['paciente']) ? array_push($res, 'Paciente') : false;
        !isset($datos['doctor']) ? array_push($res,'Doctor') : false;
        !isset($datos['tratamiento']) ? array_push($res, 'Tratamiento') :false;
        //!isset($datos['costo']) ? array_push($res, 'Costo') :false;
        if(isset($datos['fecha'] )){
            $fechaInicio = $datos['fecha']." ".$datos['horainicio'];
        }
        if(isset($datos['fecha'])){
            $fechaFinal = $datos['fecha']." ".$datos['horafin'];
        }
        $status = $BD->getTb_Status('Vigente');

        if(gettype($status) !='boolean'){
            $status = $status->fetch_assoc();
        }else{
            return [false,"Hubo un error al hacer la conexion, vuelva a intentarlo"];
        }

        if($tipo == 'agregar'){
            $BD->next_result();
            $sql = "SELECT * FROM Tb_Cita WHERE '{$fechaInicio}' BETWEEN FechaInicio AND FechaFinal AND IdPaciente = {$datos['paciente']} AND IdStatus = {$status['IdStatus']}";
            $res_query = $BD->query($sql);
            if(gettype($res_query) != 'boolean'){
                if($res_query->num_rows > 0)
                    array_push($res, "La Hora y fecha para este paciente ya esta ocupado");
            }
            $BD->next_result();
            $sql = " SELECT * FROM Tb_Cita WHERE '{$fechaInicio}' BETWEEN FechaInicio AND FechaFinal AND IdDoctor  = {$datos['doctor']} AND IdStatus = 1;";
            $res_query = $BD->query($sql);
            if(gettype($res_query) != 'boolean'){
                if($res_query->num_rows > 0)
                    array_push($res, "La Hora y fecha para este doctor ya esta ocupado");
            }
            $BD->next_result();
           
            $sql = "SELECT CAST('{$fechaInicio}' as TIME) < CAST('{$fechaFinal}' as TIME);";
            $res_query = $BD->query($sql);
            if(mysqli_fetch_array($res_query)[0] == '0'){
                    array_push($res, "Favor de verficar la hora de la cita");
            }
            $BD->next_result();
        }
        return $res;
        
    }
    static public function verificar_datos_formulario2($datos,BaseDeDatos $BD, $tipo = 'agregar'){
        $res = [];
        !isset($datos['paciente']) ? array_push($res, 'Paciente') : false;
        !isset($datos['doctor']) ? array_push($res,'Doctor') : false;
        !isset($datos['tratamiento']) ? array_push($res, 'Tratamiento') :false;
        //!isset($datos['costo']) ? array_push($res, 'Costo') :false;
        if(isset($datos['fecha'] )){
            $fechaInicio = $datos['fecha']." ".$datos['horainicio'];
        }
        if(isset($datos['fecha'])){
            $fechaFinal = $datos['fecha']." ".$datos['horafin'];
        }
        $status = $BD->getTb_Status('Vigente');

        if(gettype($status) !='boolean'){
            $status = $status->fetch_assoc();
        }else{
            return [false,"Hubo un error al hacer la conexion, vuelva a intentarlo"];
        }
        if($tipo == "agregar"){
            $sql = " SELECT CAST('{$fechaInicio}' as TIME) < CAST('{$fechaFinal}' as TIME);";
            $res_query = $BD->query($sql);
            $result = mysqli_fetch_array($res_query);
            if($result[0] == '0') {
                array_push($res, "Favor de verficar la hora de la cita");
            }
            // $BD->next_result();
            // $sql = "SELECT * FROM Tb_Cita WHERE '{$fechaInicio}' BETWEEN FechaInicio AND FechaFinal AND IdPaciente = {$datos['paciente']} AND IdStatus = {$status['IdStatus']}";
            // $res_query = $BD->query($sql);
            // if(gettype($res_query) != 'boolean'){
            //     if($res_query->num_rows > 0)
            //         array_push($res, "La Hora y fecha para este paciente ya esta ocupado");
            // }
            // $BD->next_result();
            // $sql = " SELECT * FROM Tb_Cita WHERE '{$fechaInicio}' BETWEEN FechaInicio AND FechaFinal AND IdDoctor  = {$datos['doctor']} AND IdStatus = 1;";
            // $res_query = $BD->query($sql);
            // if(gettype($res_query) != 'boolean'){
            //     if($res_query->num_rows > 0)
            //         array_push($res, "La Hora y fecha para este doctor ya esta ocupado");
            // }
            $BD->next_result();
        }
        return $res;
        
    }


    public function modificar_BD($datos, BaseDeDatos $BD){
        $this->idPaciente = $datos['paciente'];
        $this-> idDoctor = $datos['doctor'];
        $this-> tratamiento = $datos['tratamiento'];
        if(isset($datos['fecha'] )){
            $fechaInicio = $datos['fecha']." ".$datos['horainicio'];
        }
        if(isset($datos['fecha'])){
            $fechaFinal = $datos['fecha']." ".$datos['horafin'];
        }
        $this-> idTratamiento;
        $this-> MotivoCan;
        $this-> costo = $datos['costo'];
        if(!$this->id)
            return [false, "Hubo un error al cargar los datos, inténtalo de nuevo"];
        $BD->next_result();
        $sql = "UPDATE Tb_Cita SET IdPaciente= {$this->idPaciente}, FechaInicio='{$fechaInicio}', FechaFinal='{$fechaFinal}', IdDoctor={$this->idDoctor},
        Descripcion='{$this->tratamiento}',";
        $sql .= ($this->idTratamiento ?"IdTratamiento={$this->idTratamiento},":" IdTratamiento= NULL,");
        $sql .= ($this->MotivoCan ? "MotivoCancelacion='{$this->MotivoCan}',":"MotivoCancelacion=NULL,");
        $sql .= "Costo='{$this->costo}' WHERE IdCita={$this->id}" ;
        try{
            $BD->query($sql);
            return [true,"Se han hecho los cambios"];
        } catch(mysqli_sql_exception $e){
            $BD->next_result();
            if(stripos($e->getMessage(), 'Duplicate entry' !== false)){
                return [false,"Ya hay una fecha ocupada"];
            }
            else{
                return [false,"Datos Incorrectos". $e->getMessage()];
            } 
        }
    }

}

?>