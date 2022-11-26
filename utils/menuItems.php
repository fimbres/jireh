<?php 
    require_once('sessionCheck.php');

    class menuItem{
        protected $name;
        protected $url;

        public  function __construct($name,$url){
            $this->name = $name;
            $this->url = $url;
        }

        public function getName(){
            return $this->name;
        }

        public function getUrl(){
            return $this->url;
        }
    }

    //CREANDO LISTA DE OBJETOS
    $list = array();

    //OPCIONES DEL MENU
    
    if(comprobar_sesion()){
        switch($_SESSION['rol']){
            case 'Tb_Doctor': array_push($list,$item5 = new menuItem("Agenda","agenda.php?IdDoctor=" . $_SESSION['user_id'])); break;
            case 'Tb_Recepcionista':  array_push($list,$item5 = new menuItem("Agenda","agenda.php")); 
                array_push($list,$item2 = new menuItem("Gestión de Pacientes","gestionPacientes.php"));
                array_push($list,$item2 = new menuItem("Gestión de Pagos","gestionPagos.php?idCita=11"));
                break;
            case 'Tb_Admin': array_push($list,$item1 = new menuItem("Gestion de Doctores","gestionDoctores.php"));
                array_push($list,$item2 = new menuItem("Gestion de Recepcionistas","gestionRecepcionistas.php"));
                break;
            default: break;
        }
        array_push($list,$item5 = new menuItem("Cerrar Sesión","utils/logout.php"));
    }
?>