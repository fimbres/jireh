<?php

class BaseDeDatos extends mysqli{
    private $host = 'blhfarhgxzvfusb9hkvg-mysql.services.clever-cloud.com';
    private $usuario = 'ulsqbptq4rtdnjup';
    private $contra = 'VERq8gpiuRHtBGSFFcaO';
    private $nom_bd = 'blhfarhgxzvfusb9hkvg';

    public function __construct(){
        parent::__construct($this->host,$this->usuario,$this->contra,$this->nom_bd);
    }
    // *********************
    // Funciones GET
    // *********************
    public function getTb_Sexo($nombre = ''){
        $sql ="SELECT * FROM Tb_Sexo";
        if(!empty($nombre))
            $sql .= " where Sexo = '$nombre'";
        $res = $this->query($sql);
        $this->next_result();
        return ($this->error ? false :  $res);
    }
    public function getTb_Status($nombre = ''){
        $sql = "SELECT * FROM Tb_Status";
        if(!empty($nombre))
            $sql .= " where Descripcion = '$nombre'";
        $res = $this->query($sql);
        $this->next_result();
        return ($this->error ? false :  $res);
    }
}
?>