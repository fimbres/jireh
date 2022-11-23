<?php 
    class Alerta{
        
        protected $mensaje_principal;
        protected $descripciones = [];
        //Los mensajes listados funcionaran como una matriz, donde
        // cada vector estara "ligado" con una descripcion
        protected $mensajes_listados = [];
        //Datos para una alerta de bootstrap
        
        //Datos para el sweet alert
        private $opciones_sweet = [];

        //Esta variable sirve para saber que elemento vamos
        // a esperar que le suceda algo
        protected $escuchar= false;
        //Esta variable tendra la informacion, que sera ejecutada
        // en un posible then
        protected $then = "";

        protected $redireccion = "";

        public function __construct(string $men_princ,$descrip= [],$men = [], $redireccion = ''){
            $this->mensaje_principal=  $men_princ;
            if(!empty($descrip))
                $this->descripciones = $descrip;
            if(!empty($men))
                $this->mensajes_listados = $men;
            $this->init_datos_sweet_alert();

            $this->redireccion = $redireccion;
        }
        private function init_datos_sweet_alert(){
            $this->opciones_sweet['icon'] = "'success'";
            $this->opciones_sweet['showCloseButton'] = "true";
            $this->opciones_sweet['showCancelButton'] = "false";
            $this->opciones_sweet['showConfirmButton'] = "true";
            $this->opciones_sweet['confirmButtonColor'] = "'#28a745'";
            $this->opciones_sweet['confirmButtonText'] = "'Aceptar'";
        }
        public function activar_sweet_alert($regresar = true, $tipo = 'click'){
            //Verifcamos que los datos de descripcion y de listado de mensajes
            // tengan valores
            $html = '';
            if(!empty($this->descripciones)){
                $i = 0;
                $html .= " html: `";
                foreach($this->descripciones as $valor){
                    $html .= $valor;
                    if(!empty($this->mensajes_listados)){
                        $html .= "<ul>";
                        foreach($this->mensajes_listados[$i] as $men){
                            $html .= "
                            <li>
                                {$men}
                            </li>
                            ";
                        }
                        $html .= "</ul>";
                    }
                    
                    $i++;
                }
                $html .="`,";
            } else if(!empty($this->mensajes_listados)){
                $html .= " html: `";
                $html .= "<ul>";
                $i =0;
                foreach($this->mensajes_listados[$i] as $men){
                    $html .= "
                    <li>
                        {$men}
                    </li>
                    ";
                    $i++;
                }
                $html .= "</ul>";
                $html .="`,";
            }
            $ops = $html;
            foreach ($this->opciones_sweet as $llave => $valor) {
                $ops .= " {$llave}: {$valor},";
            }
            if($this->escuchar){
                $res = "
                    $('{$this->escuchar}').on('{$tipo}', () =>{
                        Swal.fire({
                            title: '{$this->mensaje_principal}',
                            {$ops}
                        })
                        .then(res => {
                            if('{$this->redireccion}'){
                                window.location = '{$this->redireccion}';
                            }
                            else{
                                {$this->then}
                            }
                        })
                    })
                ";
            } else {
                $res = "
                    Swal.fire({
                        title: '{$this->mensaje_principal}',
                        {$ops}
                    })
                    .then(res =>{
                        if('{$this->redireccion}'){
                            window.location = '{$this->redireccion}';
                        }
                        else{
                            {$this->then}
                        }
                    })
                ";
            }
            if($regresar)
                return $res;
            else 
                echo $res;
        }
        // *********************
        // Funciones SET
        // *********************
        public function setOpcion($llave,$valor){
            $this->opciones_sweet[$llave] = $valor;
        }
        public function setThen($valor){
            $this->then = $valor;
        }
        public function setEscuchar($valor){
            $this->escuchar = $valor;
        }
        public function setRedireccion($valor){
            $this->redireccion = $valor;
        }
        // *********************
        // Funciones GET
        // *********************
        public function getMensajePrincipa(){
            return $this->mensaje_principal;
        }
        public function getDescripciones(){
            return $this->descripciones;
        }
        public function getMensajesListados(){
            return $this->mensajes_listados;
        }
        public function getThen($valor){

        }

    }

?>