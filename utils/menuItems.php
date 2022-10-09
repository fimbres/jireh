<?php 
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
    array_push($list,$item1 = new menuItem("Agregar Cliente","agregarCliente.php"));
    array_push($list,$item2 = new menuItem("Administrar Clientes","administrarCliente.php"));
    array_push($list,$item3 = new menuItem("Administrar Citas","administrarCitas.php"));
    array_push($list,$item4 = new menuItem("Registrar Pago","registrarPago.php"));
    array_push($list,$item5 = new menuItem("Ver Calendario","verCalendario.php"));
?>