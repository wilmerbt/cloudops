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
        <title>Sistemas Operativos</title>
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
            <div class="subtitulo_aplicacion">Mís Sistemas Operativos</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiosColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Sistemas Operativos</div>
                <div class="subtitulo_hoja">Listado de Sistemas Operativos</div>
            </div>
        </div>


        <div id="seccion03" class="seccion03">

            <!--Tabla Usuarios-->                    

            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">
                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Sistemas Operativos", "Crear") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Sistemas Operativos", "Crear")) {
                        echo "<div class='opcionIcon'><img src='recursos/imagenes/addicon.png' alt='Smiley face' height='25' width='25'></div>";
                        echo "<div onclick=$('#agregarSODisp').show(); class='opcionText'>Agregar Nuevo</div>";
                    }
                    ?>
                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="idsistemaoperativo">
            <input type="hidden" name="enorden" id="enorden" value="asc">

            <!--Display de agregar SO-->
            <div class="contieneAgregarDisco" id="agregarSODisp">
                <div class="tituloAgregar">Agregar Sistema Operativo</div>
                <form id="agregarSO" name="agregarSO" method="post" action="recursos/php/acciones.php?accion=agregarSO">

                    <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="nombreSO" name="nombreSO" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Proveedor
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <select onchange="actualizaTipos()" class="selectConfiguracion" id="selectedProv" name="selectedProv">
                                <?php
                                $query_proveedor = "SELECT * FROM proveedor ORDER BY nombre ASC;";
                                $result_proveedor = pg_query($query_proveedor) or die('La consulta fallo: ' . pg_last_error());
                                $band = 0;
                                $idtemProveedor = 0;
                                while ($proveedor = pg_fetch_array($result_proveedor)) {
                                    if ($band == 0) {
                                        $idtemProveedor = $proveedor["idproveedor"];
                                        $band = 1;
                                    }
                                    echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div id="errorEntrada001" class="ConfiguracionElemento-validacion"></div>-->
                    </div>


                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Tipo
                        </div>
                        <div class="ConfiguracionElemento-contenedor" id="contenedorTipos">
                            <select class="selectConfiguracion" id="selectedTipo" name="selectedTipo">
                                <?php
                                $query_tipo = "SELECT tipoos.idtipoos AS idtipoos, tipoos.nombre AS nombre, 
                                            proveedor_tipoos.idproveedor AS idproveedor
                                            FROM tipoos INNER JOIN proveedor_tipoos ON tipoos.idtipoos = proveedor_tipoos.idtipoos
                                            WHERE idproveedor = " . $idtemProveedor . ";";
                                $result_tipo = pg_query($query_tipo) or die('La consulta fallo: ' . pg_last_error());
                                while ($tipo = pg_fetch_array($result_tipo)) {
                                    //$idtemTipo = $tipo["idtipoos"];
                                    echo "<option value='" . $tipo["idtipoos"] . "'>" . $tipo["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div id="errorEntrada001" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Identificador
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="identificadorSO" name="identificadorSO" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div onclick=agregar() class="agregarBoton" id="botonAgregar" >Agregar Sistema Operativo</div>
                    <!--<input type="submit" class="agregarBoton" id="botonAgregar" >-->
                    <div onclick=cerrarAgregar2() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>            

            <!--Fin display agregar-->



            <div class="contenedorTABLA" id="contenedorTABLA">

                <!--Solicitud Sistemas Operativos-->
                <?php
                $sqlSOs = "SELECT sistemaoperativo.idsistemaoperativo AS idsistemaoperativo, proveedor.nombre AS proveedor,
                            tipoos.nombre AS tipo, sistemaoperativo.nombre AS nombre, sistemaoperativo.identificador AS identificador,
                            sistemaoperativo.clasifica AS clasificacion FROM sistemaoperativo
                            INNER JOIN proveedor ON sistemaoperativo.idproveedor = proveedor.idproveedor
                            INNER JOIN tipoos ON sistemaoperativo.idtipoos = tipoos.idtipoos
                            WHERE sistemaoperativo.fecha_eliminacion IS NULL
                            ORDER BY idsistemaoperativo ASC;";
                $resultSOs = pg_query($sqlSOs) or die('La consulta fallo: ' . pg_last_error());
                ?>

                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultSOs); ?> Elementos</div>
                <div class="cabeceraTabla" >

                    <div class="eleCabTabla" style="width: 6%">Acciones</div>
                    <div onclick=ordena("nombre") class="eleCabTabla" style="width: 20%">Nombre<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("tipo") class="eleCabTabla" style="width: 10%">Tipo<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("proveedor") class="eleCabTabla" style="width: 14%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("clasificacion") class="eleCabTabla" style="width: 10%">Clasificación<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("identificador") class="eleCabTabla" style="width: 20%">Identificador<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($SO = pg_fetch_array($resultSOs)) {
                        if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 6%'><img onclick=mostrarAcciones(" . $SO["idsistemaoperativo"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $SO["idsistemaoperativo"] . ") onmouseout=ocultarAcciones(" . $SO["idsistemaoperativo"] . ") class='panelOpciones' id='panelOpciones-" . $SO["idsistemaoperativo"] . "'>";
                            
                            if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Editar")) {
                                echo "<div class='panelOPC' onclick=editar(" . $SO["idsistemaoperativo"] . ")>Editar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=eliminar(" . $SO["idsistemaoperativo"] . ")>Eliminar</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 20%' title='" . $SO["nombre"] . "'>" . $SO["nombre"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $SO["tipo"] . "'>" . $SO["tipo"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 14%' title='" . $SO["proveedor"] . "'>" . $SO["proveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $SO["clasificacion"] . "'>" . $SO["clasificacion"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 20%' title='" . $SO["identificador"] . "'>" . $SO["identificador"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>


            <!--Display de editar SO-->
            <div class="contieneAgregarDisco" id="editarSODisp" name="editarSODisp">


            </div>                        

            <!--Fin display editar-->

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
                    data: {accion: 58, ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: 58, ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

            function mostrarPerfiles(id) {
                $("#panelPerfiles-" + id).show();
            }

            function ocultarAcciones(id) {
                $("#panelOpciones-" + id).hide();
            }

            function ocultarPerfiles(id) {
                $("#panelPerfiles-" + id).hide();
            }


            function editar(id) {

                $('#editarSODisp').show();

                $.ajax({
                    data: {accion: 59, idso: id},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#editarSODisp").html(response);
                    }
                });

            }

            function eliminar(id) {

                location.href = "recursos/php/acciones.php?accion=eliminarSO&id=" + id;

            }

            function cerrarEditar() {
                $("#editarSODisp").hide();
            }

            function cerrarAgregar() {
                $("#agregarSODisp").hide();
            }

            function cerrarAgregar2() {
                $("#agregarSODisp").hide();
            }

            function agregar() {

                document.getElementById("agregarSO").submit();

            }

            function update() {
                document.getElementById("editarSO").submit();
            }

            function actualizaTipos() {

                $.ajax({
                    data: {accion: 62, proveedor: $("#selectedProv").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#selectedTipo").html(response);
                    }
                });

            }

            function actualizaTiposEditar() {

                $.ajax({
                    data: {accion: 62, proveedor: $("#selectedProvEditar").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#selectedTipoEditar").html(response);
                    }
                });

            }

        </script>        
    </body>
</html>
<?php
pg_close($conexion);
?>