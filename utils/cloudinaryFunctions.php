<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/jireh-php/vendor/autoload.php");
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;
    use Cloudinary\Api\Search\SearchApi;

    $config = Configuration::instance();
    $config->cloud->cloudName = 'dh23vdshc';
    $config->cloud->apiKey = '512523944414583';
    $config->cloud->apiSecret = '-T7ev2kaYOJ_CFFsxLxy7jIOniA';
    $config->url->secure = true;

    function uploadFile($rutaArchivo,$tipoArchivo,$path_destino,$nombre){
        $upload = new UploadApi();
        return $upload->upload("$rutaArchivo",[
            'public_id' => $nombre,
            'folder' => $path_destino,
            'use_filename' => true,
            'overwrite' => true,
            'tags' => ["$tipoArchivo"]]);
    }

    function callFile($tag){
        $search = new searchApi();
        $search->expression("$tag");
        $resultado = $search->execute();

        if($resultado){
            //CREAMOS LA PILA DONDE SE GUARDARAN LAS RUTAS
            $pilaRutas = new SplStack();

            //ITERAMOS SOBRE LOS RESULTADOS GUARDANDO LAS RUTAS EN LA PILA
            foreach($resultado['resources'] as $nombre){
                $pilaRutas->push($nombre['url']);
            }

            //SITUAR PUNTERO AL FINAL DE LA PILA
            $pilaRutas->rewind();

            return $pilaRutas;
        }else{
            return false;
        }
    }

?>
