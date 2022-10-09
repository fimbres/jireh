<div id="sideBar" class="d-flex flex-column flex-shrink-0 p-3">
    <div id="top-sideBar" class=""><a href="#"><img class="rounded" src="files/logo_jireh.jpg" width="180px" alt=""></a><ul class=""><li><a id="btnMenu" href="#" class="link-light pt-2 pb-2 ps-4 pe-4">Menu</a></li></ul></div>
    <hr class="separator">
    <ul id="list-menu" class="nav nav-pills flex-column mb-auto">
        <!--Incluimos la lista de opciones del menu-->
        <?php
            require_once('utils/menuItems.php');
        ?>

        <!--Recorremos la lista para generar el menu-->
        <?php foreach($list as $key=>$value):?>
            <li class="nav-item">
                <a href="#" class="nav-link link-light mt-2 text-center" aria-current="page">
                    <?php print($list[$key]->getName());?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>