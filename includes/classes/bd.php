<?php

if(isset($extra_diagonal_path)){
    require_once($extra_diagonal_path .'vendor/autoload.php');
} else{
    require_once('vendor/autoload.php');
}

 use Cloudinary\Configuration\Configuration;
 use Cloudinary\Api\Upload\UploadApi;

//Cloudinary de ejemplo (alexis)
//  $config = Configuration::instance();
//  $config->cloud->cloudName = 'dymuy4udb';
//  $config->cloud->apiKey = '524848689883964';
//  $config->cloud->apiSecret = '0fZ6QUc_G1HccUf9AcksfRTQOo0';
//  $config->url->secure = true;
$config = Configuration::instance();
$config->cloud->cloudName = 'dh23vdshc';
$config->cloud->apiKey = '512523944414583';
$config->cloud->apiSecret = '-T7ev2kaYOJ_CFFsxLxy7jIOniA';
$config->url->secure = true;
class BaseDeDatos extends mysqli{
    //Datos para conectarse a la BD de MySQL
    private $host = 'blhfarhgxzvfusb9hkvg-mysql.services.clever-cloud.com';
    private $usuario = 'ulsqbptq4rtdnjup';
    private $contra = 'VERq8gpiuRHtBGSFFcaO';
    private $nom_bd = 'blhfarhgxzvfusb9hkvg';
    private $upload_cloud;

    public function __construct(){
        $this->upload_cloud = new UploadApi();
        parent::__construct($this->host,$this->usuario,$this->contra,$this->nom_bd);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    //Funcion para guardar archivos dentro de cloudinary
    public function cloud_subir_archivo($path,$extension,$nombre,$path_destino = ''){
        return $this->upload_cloud->upload($path,[
            'public_id' => $nombre,
            'folder' => $path_destino,
            'use_filename' => true,
            'overwrite' => true,
            'tags' => [$extension]
            ]
        );
    }
    // dejamos esto pendiente
    // public function cloud_borrar_archivo($path){
    //     return $this->upload_cloud->destroy($path);
    // }
    // *********************
    // Funciones GET
    // *********************
    public function getTb_Sexo($nombre = ''){
        $this->next_result();
        $sql ="SELECT * FROM Tb_Sexo";
        if(!empty($nombre))
            $sql .= " where Sexo = '$nombre';";
        $res = $this->query($sql);
        $this->next_result();
        return ($this->error ? false :  $res);
    }
    public function getTb_Status($nombre = ''){
        $this->next_result();
        $sql = "SELECT * FROM Tb_Status";
        if(!empty($nombre))
            $sql .= " where Descripcion = '$nombre';";
        $res = $this->query($sql);
        $this->next_result();
        return ($this->error ? false :  $res);
    }
    public function getTb_estadocivil($estado = ''){
        $this->next_result();
        $sql = "SELECT * FROM Tb_estadocivil";
        if(!empty($estado))
            $sql .= " where EstadoCivil = '$estado';";
        $res = $this->query($sql);
        $this->next_result();
        return ($this->error ? false :  $res);
    }

    public function getTbCita_cita($idCita){
        $this->next_result();
        if(!empty($idCita))
            $sql = "SELECT * FROM Tb_Cita WHERE IdCita='$idCita'";
        
        $res = $this->query($sql);
        $row = $res->fetch_array(MYSQLI_ASSOC);

        $this->next_result();
        
        return($this->error ? false : $row);
    }

    public function getTbPaciente_nombrePaciente($idPaciente){
        $this->next_result();
        if(!empty($idPaciente))
            $sql = "SELECT Nombre,APaterno,AMaterno FROM Tb_Paciente WHERE IdPaciente='$idPaciente'";
        
        $res = $this->query($sql);
        $row = $res->fetch_array(MYSQLI_ASSOC);

        $this->next_result();
        
        return($this->error ? false : $row);
    }

    public function getTbDoctor_nombreDoctor($idDoctor){
        $this->next_result();
        if(!empty($idDoctor))
            $sql = "SELECT Nombre,APaterno,AMaterno FROM Tb_Doctor WHERE IdDoctor='$idDoctor'";
        
        $res = $this->query($sql);
        $row = $res->fetch_array(MYSQLI_ASSOC);

        $this->next_result();
        
        return($this->error ? false : $row);
    }

}
?>