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
        <title>Redes Virtuales</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="recursos/css/estilobase.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="recursos/js/jquery-3.1.0.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>          
    </head>
    <body>
        <div id="seccion01" class="seccion01">
            <div class="titulo_aplicacion" ><img src="recursos/imagenes/logohawk.png" alt="Smiley face" height="63" width="127"></div>
            <div class="subtitulo_aplicacion">Mís Redes Virtuales</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiredColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Mís Redes Virtuales</div>
                <div class="subtitulo_hoja">Listado de Redes Virtuales</div>
            </div>
        </div>
        <?php
        $query_redes = "SELECT * FROM red where fecha_eliminacion is null and idorganizacion='" . $_SESSION["idorganizacion"] . "' order by ipv4cidr";
        $result_redes = pg_query($query_redes) or die('La consulta fallo: ' . pg_last_error());
        $seconcatenan = "";
        $band = 0;
        while ($redC = pg_fetch_array($result_redes)) {
            if ($band == 0) {
                $seconcatenan = $seconcatenan . $redC["idproveedor"] . "-" . $redC["ipv4cidr"];
                $band = 1;
            } else {
                $seconcatenan = $seconcatenan . "_" . $redC["idproveedor"] . "-" . $redC["ipv4cidr"];
            }
        }
        ?>     
        <input type="hidden" name="lista-redes" id="lista-redes" value="<?php echo $seconcatenan; ?>" />
        <div id="seccion03" class="seccion03">
            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">
                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Redes Virtuales", "Crear") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Redes Virtuales", "Crear")) {
                        echo "<div class = 'opcionIcon'><img src = 'recursos/imagenes/addicon.png' alt = 'Smiley face' height = '25' width = '25'></div>";
                        echo "<div onclick = $('#contieneAgregarRED').show() class = 'opcionText'>Agregar Nuevo</div>";
                    }
                    ?>
                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="nombrered">
            <input type="hidden" name="enorden" id="enorden" value="asc">


            <div class="contieneAgregarDisco" id="contieneAgregarRED">
                <div class="tituloAgregar">Agregar Red Virtual</div>
                <form id="crearRed" name="crearRed" method="post" action="recursos/php/acciones.php?accion=agregarRed">
                    <input type="hidden" name="idservidoragrega" id="idservidoragrega" value="">
                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Proveedor
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <select onchange='actualizaLoc()' class="selectConfiguracion" id="selproveedor" name="selproveedor">
                                <?php
                                $query_proveedor = "SELECT * FROM proveedor order by nombre asc;";
                                $result_proveedor = pg_query($query_proveedor) or die('La consulta fallo: ' . pg_last_error());
                                $band = 0;
                                $idtemProveedor = 0;
                                while ($proveedor = pg_fetch_array($result_proveedor)) {
                                    if (tienePermiso($_SESSION["idusuario"], $proveedor["nombre"], "Redes Virtuales", "Crear")) {
                                        if ($band == 0) {
                                            $idtemProveedor = $proveedor["idproveedor"];
                                            $band = 1;
                                        }
                                        echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="errorEntrada01" class="ConfiguracionElemento-validacion"></div>
                    </div>                

                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Localizacion
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <div id="actualizaLocalizacion">
                                <select class="selectConfiguracion" id="sellocalizacion" name="sellocalizacion">
                                    <?php
                                    $query_location = "SELECT * FROM location where idproveedor='" . $idtemProveedor . "';";
                                    $result_location = pg_query($query_location) or die('La consulta fallo: ' . pg_last_error());
                                    $band = 0;
                                    $idtemProveedor = 0;
                                    while ($localizacion = pg_fetch_array($result_location)) {
                                        echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="errorEntrada02" class="ConfiguracionElemento-validacion"></div>
                    </div>                

                    <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre de la Red
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input onblur="validaNombreRed()" id="nombrered" name="nombrered" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada03" class="ConfiguracionElemento-validacion"></div>
                    </div>  

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            IPv4 CIDR
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input onblur="validaCIDRred()" id="cidr" name="cidr" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada04" class="ConfiguracionElemento-validacion"></div>
                    </div>                 

                    <div onclick=agregarRed() class="agregarBoton" id="botonAgregar" >Agregar Red Virtual</div>
                    <div onclick=cerrarAgregar() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>
            <div class="contenedorTABLA" id="contenedorTABLA">
                <?php
                $sqlRedes = "Select red.idred as idred, proveedor.nombre as proveedor, location.nombre as localizacion, red.nombre as nombrered, red.idreal as idenproveedor, red.ipv4cidr as cidr from proveedor, location, red where red.idproveedor = proveedor.idproveedor and red.idlocation = location.idlocation and red.fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "' order by nombrered asc;";
                $resultRedes = pg_query($sqlRedes) or die('La consulta fallo: ' . pg_last_error());
                ?>
                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultRedes); ?> Elementos</div>
                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("proveedor") class="eleCabTabla" style="width: 12%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("localizacion") class="eleCabTabla" style="width: 15%">Localización<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("nombrered") class="eleCabTabla" style="width: 20%">Nombre de la Red<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("idenproveedor") class="eleCabTabla" style="width: 20%">Id en Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("cidr") class="eleCabTabla" style="width: 13%">IPv4 CIDR<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($Red = pg_fetch_array($resultRedes)) {

                        if (tienePermiso($_SESSION["idusuario"], $Red["proveedor"], "Redes Virtuales", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Red["idred"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Red["idred"] . ") onmouseout=ocultarAcciones(" . $Red["idred"] . ") class='panelOpciones' id='panelOpciones-" . $Red["idred"] . "'>";
                            if (tienePermiso($_SESSION["idusuario"], $Red["proveedor"], "Redes Virtuales", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Red["idred"] . ")>Eliminar</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 12%' title='" . $Red["proveedor"] . "'>" . $Red["proveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 15%' title='" . $Red["localizacion"] . "'>" . $Red["localizacion"] . "</div>";
                            echo "<div id='nombre-" . $Red["idred"] . "' class='eleLinTabla' style='width: 20%' title='" . $Red["nombrered"] . "'>" . $Red["nombrered"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 20%' title='" . $Red["idenproveedor"] . "'>" . $Red["idenproveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 13%' title='" . $Red["cidr"] . "'>" . $Red["cidr"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

            </div>
        </div>

        <script>
            function validarEliminacion(idred) {
                $("#seactualiza").html("<div style='font-size: 12px;' id='dialog-confirm' title='¿Esta seguro que desea eliminar la red " + $("#nombre-" + idred).html() + "?'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Recuerde que esta es una acción permanente, si borra la red no podra recuperarla luego.</p></div>");
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Eliminar Red": function () {
                            location.href = "recursos/php/acciones.php?accion=eliminarRed&id=" + idred;
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>

        <div id="seactualiza">
            <div style="font-size: 12px;" id="dialog-confirm" title="¿Esta seguro que desea eliminar esta red?">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Recuerde que esta es una acción permanente, si borra el grupo de seguridad no podra recuperarlo luego.</p>
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

                setInterval(function () {
                    actualizaTabla()
                }, 20000);
            });

            function actualizaLoc() {
                var actualprov = $("#selproveedor").val();
                $.ajax({
                    data: {accion: "7", idproveedor: actualprov},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#actualizaLocalizacion").html(response);
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
                    data: {accion: "8", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: "8", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

            function accionMaquina(nombreaccion, idred) {

                if (nombreaccion == "eliminar") {
                    validarEliminacion(idred);
                    //location.href = "recursos/php/acciones.php?accion=eliminarRed&id=" + idred;
                }
            }

            function cerrarAgregar() {
                $("#contieneAgregarRED").hide();
            }

            function validaNombreRed() {
                expr = /^[a-z0-9]+$/;

                if ($("#nombrered").val() != "") {
                    if (!expr.test($("#nombrered").val())) {
                        $("#errorEntrada03").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                    } else {
                        $("#errorEntrada03").html(" ");
                    }
                } else {
                    $("#errorEntrada03").html("El nombre de la red es obligatorio.");
                }
            }

            function validaCIDRred() {
                if ($("#cidr").val() != "") {
                    var aux01 = $("#cidr").val().split("/");

                    var aux02 = aux01[0].split(".");
                    var bandera = 0;
                    if (aux02.length == 4) {
                        for (var i = 0; i < 4; i++) {
                            if (!/^([0-9])*$/.test(aux02[i])) {
                                bandera = 1;
                            } else {
                                if (parseInt(aux02[i]) < 0 || parseInt(aux02[i]) >= 255) {
                                    bandera = 1;
                                }
                            }
                        }
                    } else {
                        bandera = 1;
                    }

                    if (aux01[1] === "" || aux01[1] === null) {
                        bandera = 1;
                    }

                    if (parseInt(aux01[1]) < 0 || parseInt(aux01[1]) >= 32) {
                        bandera = 1;
                    }

                    if (aux01.length < 2) {
                        bandera = 1;
                    } else {
                        if (!/^([0-9])*$/.test(aux01[1])) {
                            bandera = 1;
                        }
                    }

                    if (bandera == 1) {
                        $("#errorEntrada04").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                    } else {
                        $("#errorEntrada04").html(" ");
                        var redesactuales = $("#lista-redes").val();
                        var temp01 = redesactuales.split("_");
                        for (var i = 0; i < temp01.length; i++) {
                            var temp02 = temp01[i].split("-");
                            if (temp02[0] === $("#selproveedor").val() && temp02[1] === $("#cidr").val()) {
                                $("#errorEntrada04").html("Ya existe una red con el mismo CIDR proporcionado.");
                            }
                        }
                    }
                } else {
                    $("#errorEntrada04").html("El Bloque CIDR es obligatorio.");
                }
            }

            function agregarRed() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
                var bandCreacion = 0;

                if ($("#nombrered").val() != "") {
                    if (!expr.test($("#nombrered").val())) {
                        $("#errorEntrada03").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada03").html(" ");

                    }
                } else {
                    $("#errorEntrada03").html("El nombre de la red es obligatorio.");
                    bandCreacion = 1;
                }


                if ($("#cidr").val() != "") {
                    var aux01 = $("#cidr").val().split("/");

                    var aux02 = aux01[0].split(".");
                    var bandera = 0;
                    if (aux02.length == 4) {
                        for (var i = 0; i < 4; i++) {
                            if (!/^([0-9])*$/.test(aux02[i])) {
                                bandera = 1;
                            } else {
                                if (parseInt(aux02[i]) < 0 || parseInt(aux02[i]) >= 255) {
                                    bandera = 1;
                                }
                            }
                        }
                    } else {
                        bandera = 1;
                    }

                    if (aux01[1] === "" || aux01[1] === null) {
                        bandera = 1;
                    }

                    if (parseInt(aux01[1]) < 0 || parseInt(aux01[1]) >= 32) {
                        bandera = 1;
                    }

                    if (aux01.length < 2) {
                        bandera = 1;
                    } else {
                        if (!/^([0-9])*$/.test(aux01[1])) {
                            bandera = 1;
                        }
                    }

                    if (bandera == 1) {
                        $("#errorEntrada04").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                    } else {
                        $("#errorEntrada04").html(" ");
                        var redesactuales = $("#lista-redes").val();
                        var temp01 = redesactuales.split("_");
                        for (var i = 0; i < temp01.length; i++) {
                            var temp02 = temp01[i].split("-");
                            if (temp02[0] === $("#selproveedor").val() && temp02[1] === $("#cidr").val()) {
                                $("#errorEntrada04").html("Ya existe una red con el mismo CIDR proporcionado.");
                            }
                        }
                    }
                } else {
                    $("#errorEntrada04").html("El Bloque CIDR es obligatorio.");
                }


                if (bandCreacion == 0) {
                    document.getElementById("crearRed").submit();
                }

            }
        </script>        
    </body>
</html>
