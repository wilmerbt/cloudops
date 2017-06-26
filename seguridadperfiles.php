<?php
session_start();
if ($_SESSION["idorganizacion"] != NULL && $_SESSION["idusuario"] != NULL) {
    header('Content-Type: text/html; charset=UTF-8');
    require_once("./recursos/php/funciones.php");
    $conexion = conexion();

    $sqlOrganizacion = "select * from organizacion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultOrganizacion = pg_query($sqlOrganizacion) or die('La consulta fallo: ' . pg_last_error());
    $organizacion = pg_fetch_array($resultOrganizacion);

    $query_login = "SELECT * FROM usuario where idusuario='" . $_SESSION["idusuario"] . "'";
    $result_login = pg_query($query_login) or die('La consulta fallo: ' . pg_last_error());
    $login = pg_fetch_array($result_login);
} else {
    ?>  
    <script type="text/javascript">
        alert("Debe iniciar sesión para poder accesar al contenido de esta pagina.");
        location.href = "index.php";
    </script>
    <?php
}
?>
<html>
    <head>
        <title>Usuarios y Perfiles de seguridad</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="recursos/css/estilobase.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="recursos/js/jquery-3.1.0.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    </head>
    <body>
        <div id="seccion01" class="seccion01">
            <div class="titulo_aplicacion" ><img src="recursos/imagenes/logohawk.png" alt="Smiley face" height="63" width="127"></div>
            <div class="subtitulo_aplicacion">Perfiles de Seguridad</div>
            <div onclick="menuUsuario()" class="contenedor_usuario">
                <div class="avatar"><img src="recursos/imagenes/avatar.png" alt="Smiley face" height="60" width="60"></div>
                <div class="contenedor_linea01"><?php echo $login["nombre"] . " " . $login["apellido"]; ?></div>
                <div class="contenedor_linea02"><?php echo $login["correo"] ?></div>
            </div>
            
            <!--Display de <Menu Usuario>-->
            <div class="contieneMenuUsuario" onmouseover="menuUsuario()" onmouseout="escondeMenuUsuario()" id="menuUsuarioDisp">
                <div class="opcionMenuUsuario" onclick="cerrarSesion('<?php echo $organizacion["identificador"]?>')" onmouseover="menuUsuario()">
                    Cerrar Sesion
                </div>
            </div>
        </div>        

        <div id="seccion02" class="seccion02">
            <div class="opcion_menu">
                <div class="opcion_menu_icono"></div>
            </div>            
            <?php
            menu();
            ?>
        </div> 
        <div id="seccion04" class="seccion04">

            <div style="width: 400px; height: 40px; float: left;">
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiperfilColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Perfiles de Seguridad</div>
                <div class="subtitulo_hoja">Listado de Perfiles</div>
            </div>
        </div>


        <div id="seccion03" class="seccion03">

            <!--Solicitud Perfiles-->
            <?php
            $sqlPerfiles = "SELECT idperfil, nombre"
                    . " FROM perfil"
                    . " WHERE fecha_eliminacion IS NULL ORDER BY nombre asc;";
            $resultPerfiles = pg_query($sqlPerfiles) or die('La consulta fallo: ' . pg_last_error());
            ?>

            <!--Tabla Perfiles-->


            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/addicon.png" alt="Smiley face" height="25" width="25"></div>
                    <a href="crearperfilseguridad.php"><div class="opcionText">Agregar Nuevo</div></a>
                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="perfil.nombre">
            <input type="hidden" name="enorden" id="enorden" value="asc">

            <div class="NumElementosPerfiles"><?php echo pg_num_rows($resultPerfiles); ?> Elementos</div>


            <!--Tabla -->

            <div class="contenedorTABLA" id="contenedorTABLA">

                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("perfil.nombre") class="eleCabTabla" style="width: 33%">Nombre<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("proveedor.nombre") class="eleCabTabla" style="width: 33%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>                            
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    $sqlListaPerfiles = "SELECT perfil.idperfil AS idperfil, perfil.nombre AS nombre, proveedor.nombre AS proveedor
                        FROM perfil INNER JOIN perfiles_acciones ON perfil.idperfil = perfiles_acciones.idperfil
                        INNER JOIN acciones ON perfiles_acciones.idacciones = acciones.idacciones
                        INNER JOIN entidad ON acciones.identidad = entidad.identidad
                        INNER JOIN proveedor ON entidad.idproveedor = proveedor.idproveedor 
                        WHERE perfil.fecha_eliminacion IS NULL GROUP BY perfil.idperfil, proveedor.nombre 
                        ORDER BY perfil.nombre ASC;";

                    $sqlResultListaPerfiles = pg_query($sqlListaPerfiles) or die('La consulta fallo: ' . pg_last_error());

                    while ($DatosPerfil = pg_fetch_array($sqlResultListaPerfiles)) {

                        echo "<div class='lineaTabla'>";
                        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $DatosPerfil["idperfil"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $DatosPerfil["idperfil"] . ") onmouseout=ocultarAcciones(" . $DatosPerfil["idperfil"] . ") class='panelOpciones' id='panelOpciones-" . $DatosPerfil["idperfil"] . "'>";
                        echo "<div class='panelOPC' onclick=editar(" . $DatosPerfil["idperfil"] . ")>Editar</div>";
                        echo "<div class='panelOPC' onclick=eliminar(" . $DatosPerfil["idperfil"] . ")>Eliminar</div>";
                        echo "</div></div>";

                        echo "<div class='eleLinTabla' style='width: 33%' title='" . $DatosPerfil["nombre"] . "'>" . $DatosPerfil["nombre"] . "</div>";

                        echo "<div class='eleLinTabla' style='width: 33%' title='" . $DatosPerfil["proveedor"] . "'>" . $DatosPerfil["proveedor"] . "</div>";

                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        $("#seccion02").height(($(document).height() - 41));
        $("#seccion03").height((h - 41));
        $("#seccion03").width((w - 226));
        $("#seccion04").width((w - 226));
        $("#opcioneslista").width((w - 226));
        $("#cuerpotabla").height((h - 310));
        $(window).resize(function (e) {
            var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            $("#seccion02").height((h - 41));
            $("#seccion03").height((h - 41));
            $("#seccion03").width((w - 226));
            $("#seccion04").width((w - 226));
        });

    });

    function editar(id) {
        location.href = "editarperfilseguridad.php?id=" + id;
    }

    function eliminar(id) {

        location.href = "recursos/php/acciones.php?accion=eliminarPerfil&id=" + id;

    }

    function actualizaServidores() {
        $.ajax({
            data: {accion: "16", idproveedor: $("#selproveedor2").val()},
            url: './recursos/php/ajax.php',
            type: 'post',
            success: function (response) {
                $("#contieneServidores02").html(response);
            }
        });
    }

    function ordena(columna) {
        var actual = $("#ordenado_por").val();
        if (actual == columna) {
            var orden = $("#enorden").val();
            if (orden == "asc") {
                $("#enorden").val("desc");
            } else {
                $("#enorden").val("asc");
            }
        } else {
            $("#ordenado_por").val(columna);
            $("#enorden").val("desc");
        }

        $.ajax({
            data: {accion: "54", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
            url: './recursos/php/ajax.php',
            type: 'post',
            success: function (response) {
                $("#contenedorTABLA").html(response);
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#cuerpotabla").height((h - 310));
            }
        });
    }

    function actualizaTabla() {
        $.ajax({
            data: {accion: "54", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
            url: './recursos/php/ajax.php',
            type: 'post',
            success: function (response) {
                $("#contenedorTABLA").html(response);
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#cuerpotabla").height((h - 310));
            }
        });
    }
    
    //Menu usuario
            function menuUsuario(){
                $("#menuUsuarioDisp").show();
            }
            function escondeMenuUsuario(){
                $("#menuUsuarioDisp").hide();
            }
            
            //Acciones Menu usuario
            function cerrarSesion(org){
                location.href = "recursos/php/acciones.php?accion=cerrarSesion&org="+org;
            }

    function mostrarAcciones(id) {
        $("#panelOpciones-" + id).show();
    }

    function ocultarAcciones(id) {
        $("#panelOpciones-" + id).hide();
    }


</script>        
</body>
</html>
<?php
pg_close($conexion);
?>