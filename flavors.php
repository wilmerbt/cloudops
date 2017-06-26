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
            <div class="subtitulo_aplicacion">Mís Flavors</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiflavorColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Mís Flavors</div>
                <div class="subtitulo_hoja">Listado de Flavors</div>
            </div>
        </div>


        <div id="seccion03" class="seccion03">

            <!--Tabla Usuarios-->                    

            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">
                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Flavors", "Crear") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Flavors", "Crear")) {
                        echo "<div class = 'opcionIcon'><img src = 'recursos/imagenes/addicon.png' alt = 'Smiley face' height = '25' width = '25'></div>";
                        echo "<div onclick = $('#agregarFlavorDisp').show(); class = 'opcionText'>Agregar Nuevo</div>";
                    }
                    ?>

                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="idflavor">
            <input type="hidden" name="enorden" id="enorden" value="asc">

            <!--Display de agregar SO-->
            <div class="contieneAgregarFlavor checkboxScroll-flavor" style="margin-bottom: 10px" id="agregarFlavorDisp">
                <div class="tituloAgregar">Agregar Flavor</div>
                <form id="agregarFlavor" name="agregarFlavor" method="post" action="recursos/php/acciones.php?accion=agregarFlavor">

                    <div class="ConfiguracionElemento" style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="nombreFlavor" name="nombreFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento" style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Proveedor
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <select onchange="actualizaHddsExtra()" class="selectConfiguracion" id="selectedProv" name="selectedProv">
                                <?php
                                $query_proveedor = "SELECT * FROM proveedor ORDER BY nombre ASC;";
                                $result_proveedor = pg_query($query_proveedor) or die('La consulta fallo: ' . pg_last_error());
                                while ($proveedor = pg_fetch_array($result_proveedor)) {
                                    echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div id="errorEntrada001" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Cantidad de CPUs
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="cpusFlavor" name="cpusFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Memoria RAM (MB)
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="ramFlavor" name="ramFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Espacio HDDs
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="hddFlavor" name="hddFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div id="contenedorHDDsExtra">
                        <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                            <div class="ConfiguracionElemento-titulo">
                                Espacio extra HDDs
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input id="extraHddFlavor" name="extraHddFlavor" type="text" class="entradaConfiguracion"/>
                            </div>
                            <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                        </div>

                        <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                            <div class="ConfiguracionElemento-titulo">
                                Cantidad de extra HDDs
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input id="numExtraHddFlavor" name="numExtraHddFlavor" type="text" class="entradaConfiguracion"/>
                            </div>
                            <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                        </div>
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 10px;">
                        <div class="ConfiguracionElemento-titulo">
                            Precio
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="precioFlavor" name="precioFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 20px;">
                        <div class="ConfiguracionElemento-titulo">
                            Identificador
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="idFlavor" name="idFlavor" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div onclick=agregar() class="agregarBoton" id="botonAgregar" >Agregar Flavor</div>
                    <!--<input type="submit" class="agregarBoton" id="botonAgregar" >-->
                    <div onclick=cerrarAgregar2() class="cerrarAgregar" id="cerrarAgregar" style="margin-bottom: 15px">[CERRAR]</div>
                </form>
            </div>            

            <!--Fin display agregar-->



            <div class="contenedorTABLA" id="contenedorTABLA">

                <!--Solicitud Flavor-->
                <?php
                $sqlFlavors = "SELECT flavor.idflavor AS idflavor, flavor.nombre AS nombre, proveedor.idproveedor AS idproveedor,
                                            proveedor.nombre AS proveedor, flavor.numcpu AS numcpu, flavor.memoriaram AS memoriaram,
                                            flavor.tamanoosdisk AS tamanoosdisk, flavor.tamanoextradisk AS tamanoextradisk, 
                                            flavor.disextra AS disextra, flavor.precio AS precio, flavor.identificador AS identificador
                                            FROM flavor INNER JOIN proveedor ON flavor.idproveedor = proveedor.idproveedor
                                            WHERE flavor.fecha_eliminacion IS NULL
                                            ORDER BY idflavor ASC;";
                $resultFlavors = pg_query($sqlFlavors) or die('La consulta fallo: ' . pg_last_error());
                ?>

                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultFlavors); ?> Elementos</div>
                <div class="cabeceraTabla" >

                    <div class="eleCabTabla" style="width: 6%">Acciones</div>
                    <div onclick=ordena("nombre") class="eleCabTabla" style="width: 11%">Nombre<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("proveedor") class="eleCabTabla" style="width: 12%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("numcpu") class="eleCabTabla" style="width: 6%"># CPUs<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("memoriaram") class="eleCabTabla" style="width: 6%">RAM<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("tamanoosdisk") class="eleCabTabla" style="width: 8%">HDDs<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("tamanoextradisk") class="eleCabTabla" style="width: 8%">ext. HDDs<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("disextra") class="eleCabTabla" style="width: 10%"># ext. HDDs<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("precio") class="eleCabTabla" style="width: 8%">Precio<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("identificador") class="eleCabTabla" style="width: 14%">Identificador<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($Flavor = pg_fetch_array($resultFlavors)) {
                        if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 6%'><img onclick=mostrarAcciones(" . $Flavor["idflavor"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Flavor["idflavor"] . ") onmouseout=ocultarAcciones(" . $Flavor["idflavor"] . ") class='panelOpciones' id='panelOpciones-" . $Flavor["idflavor"] . "'>";

                            if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Editar")) {
                                echo "<div class='panelOPC' onclick=editar(" . $Flavor["idflavor"] . ")>Editar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=eliminar(" . $Flavor["idflavor"] . ")>Eliminar</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 11%' title='" . $Flavor["nombre"] . "'>" . $Flavor["nombre"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 12%' title='" . $Flavor["proveedor"] . "'>" . $Flavor["proveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 6%' title='" . $Flavor["numcpu"] . "'>" . $Flavor["numcpu"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 6%' title='" . $Flavor["memoriaram"] . "'>" . $Flavor["memoriaram"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["tamanoosdisk"] . "'>" . $Flavor["tamanoosdisk"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["tamanoextradisk"] . "'>" . $Flavor["tamanoextradisk"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Flavor["disextra"] . "'>" . $Flavor["disextra"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["precio"] . "'>" . $Flavor["precio"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 14%' title='" . $Flavor["identificador"] . "'>" . $Flavor["identificador"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>


            <!--Display de editar SO-->
            <div class="contieneAgregarFlavor checkboxScroll-flavor" id="editarFlavorDisp" name="editarFlavorDisp">


            </div>                        

            <!--Fin display editar-->

        </div>  

        <script type="text/javascript">
            $(document).ready(function () {

                if ($("#selectedProv").val() == 1) {
                    $("#contenedorHDDsExtra").hide();
                }

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
                    data: {accion: 60, ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: 60, ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

                $('#editarFlavorDisp').show();

                $.ajax({
                    data: {accion: 61, idflav: id},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#editarFlavorDisp").html(response);
                    }
                });

            }

            function eliminar(id) {

                location.href = "recursos/php/acciones.php?accion=eliminarFlavor&id=" + id;

            }

            function cerrarEditar() {
                $("#editarFlavorDisp").hide();
            }

            function cerrarAgregar() {
                $("#agregarFlavorDisp").hide();
            }

            function cerrarAgregar2() {
                $("#agregarFlavorDisp").hide();
            }

            function agregar() {

                document.getElementById("agregarFlavor").submit();

            }

            function update() {
                document.getElementById("editarFlavor").submit();
            }

            function actualizaHddsExtra() {

                var proveedor = $("#selectedProv").val();

                if (proveedor == 1) {
                    $("#extraHddFlavor").val("");
                    $("#numExtraHddFlavor").val("");
                    $("#contenedorHDDsExtra").hide();
                } else {
                    $("#contenedorHDDsExtra").show();
                }

                $("#agregarFlavorDisp").html(response);

            }

            function actualizaHddsExtraEditar() {

                var proveedor = $("#selectedProvEditar").val();

                if (proveedor == 1) {
                    $("#extraHddFlavorEditar").val("");
                    $("#numExtraHddFlavorEditar").val("");
                    $("#contenedorHDDsExtraEditar").hide();
                } else {
                    $("#contenedorHDDsExtraEditar").show();
                }

                $("#editarFlavorDisp").html(response);

            }

        </script>        
    </body>
</html>
<?php
pg_close($conexion);
?>