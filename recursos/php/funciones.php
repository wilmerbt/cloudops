<?php

function conexion() {
    $dbconn = pg_connect("host=10.80.42.14 dbname=ikusicloud user=postgres password=Jcglobal2012")
            or die('No se ha podido conectar: ' . pg_last_error());
    return $dbconn;
}

function menu() {
    echo "<div class='opcion_menu'>";
    echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusitablero.png' alt='Smiley face' height='40' width='40'></div>";
    echo "<div class='opcion_menu_texto'>Tablero Principal</div>";
    echo "</div>";

    $sqlUser = "select * from usuario where idusuario='" . $_SESSION["idusuario"] . "'";
    $resultUser = pg_query($sqlUser) or die('La consulta fallo: ' . pg_last_error());
    $User = pg_fetch_array($resultUser);

    if ($User["superusuario"] == 1) {
        echo "<a href='seguridadusuarios.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikconusuarios.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Usuarios</div>";
        echo "</div> </a>";
        echo "<a href='seguridadperfiles.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusiperfil.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Perfiles de Seguridad</div>";
        echo "</div> </a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Sistemas Operativos", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Sistemas Operativos", "Visualizar")) {
        echo "<a href='sistemasoperativos.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusios.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Sistemas Operativos</div>";
        echo "</div></a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Flavors", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Flavors", "Visualizar")) {
        echo "<a href='flavors.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusiflavor.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Flavors</div>";
        echo "</div></a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Redes Virtuales", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Redes Virtuales", "Visualizar")) {
        echo "<a href='redesvirtuales.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusired.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Redes</div>";
        echo "</div> </a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Subredes", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Subredes", "Visualizar")) {
        echo "<a href='subredesvirtuales.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusisubred.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Sub Redes</div>";
        echo "</div> </a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Grupos de Seguridad", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Grupos de Seguridad", "Visualizar")) {
        echo "<a href='gruposdeseguridad.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusigruposeguridad.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Grupos de Seguridad</div>";
        echo "</div> </a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Key Pairs", "Visualizar")) {
        echo "<a href='keypairs.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusikey.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Key Pairs</div>";
        echo "</div> </a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Servidores", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Servidores", "Visualizar")) {
        echo "<a href='maquinasvirtuales.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusiservidores.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Maquinas</div>";
        echo "</div></a>";
    }

    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Discos", "Visualizar") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Discos", "Visualizar")) {
        echo "<a href='discos.php'><div class='opcion_menu'>";
        echo "<div class='opcion_menu_icono'><img src='recursos/imagenes/ikusidiscos.png' alt='Smiley face' height='40' width='40'></div>";
        echo "<div class='opcion_menu_texto'>Discos</div>";
        echo "</div> </a>";
    }
}

function tienePermiso($idUsuario, $proveedor, $entidad, $accion) {

    $sqlAcciones = "SELECT usuarios_perfiles.idusuario AS idusuario, usuarios_perfiles.idperfil AS idperfil,
    perfiles_acciones.idacciones, entidad.nombre AS entidad, proveedor.idproveedor,
    proveedor.nombre AS proveedor, acciones.nombre AS accion FROM usuarios_perfiles INNER JOIN perfiles_acciones ON
    usuarios_perfiles.idperfil = perfiles_acciones.idperfil
    INNER JOIN acciones ON perfiles_acciones.idacciones = acciones.idacciones
    INNER JOIN entidad ON acciones.identidad = entidad.identidad
    INNER JOIN proveedor ON entidad.idproveedor = proveedor.idproveedor
    WHERE idusuario = " . $idUsuario . " AND proveedor.nombre = '" . $proveedor . "' AND entidad.nombre = '" . $entidad . "' 
    AND acciones.nombre = '" . $accion . "';";

    $resultAcciones = pg_query($sqlAcciones) or die('La consulta fallo: ' . pg_last_error());

    $accionesUsuarioArray = array();

    while ($Accion = pg_fetch_array($resultAcciones)) {
        $accionesUsuarioArray[] = $Accion["accion"];
    }

    if (in_array($accion, $accionesUsuarioArray)) {
        return true;
    } else {
        return false;
    }
}
