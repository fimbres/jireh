<?php
    require_once('vendor/autoload.php');
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;
    use Cloudinary\Api\Search\SearchApi;

    $config = Configuration::instance();
    $config->cloud->cloudName = 'dh23vdshc';
    $config->cloud->apiKey = '512523944414583';
    $config->cloud->apiSecret = '-T7ev2kaYOJ_CFFsxLxy7jIOniA';
    $config->url->secure = true;

    function uploadFile(){
        $upload = new uploadApi();
        $upload->upload('../archivo.pdf',[
            'use_filename' => true,
            'overwrite' => true,
            'tags' => ['animal','dog']]);
    }

    function callFile(){
        $search = new searchApi();
        $search->expression('resource_type:image');
        $resultado = $search->execute();

        if($resultado){
            /*MOSTRAR INFORMACION
            echo "correcto";
            echo ("<pre>");
            var_dump($resultado);
            echo ("</pre>");
            */

            //CREAMOS LA PILA DONDE SE GUARDARAN LAS RUTAS
            $pilaRutas = new SplStack();

            //ITERAMOS SOBRE LOS RESULTADOS GUARDANDO LAS RUTAS EN LA PILA
            foreach($resultado['resources'] as $nombre){
                $pilaRutas->push($nombre['url']);
                //print_r($nombre['filename']);
            }

            //echo $stack->count();
            //SITUAR PUNTERO AL FINAL DE LA PILA
            $pilaRutas->rewind();

            return $pilaRutas;
        }
    }

?>
