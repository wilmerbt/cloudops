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
        <title>Key Pairs</title>
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
            <div class="subtitulo_aplicacion">Mís Key Pairs</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusikeyColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Mís Key Pair</div>
                <div class="subtitulo_hoja">Listado de Key Pairs</div>                
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">

                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Key Pairs", "Crear")) {
                        echo "<div class = 'opcionIcon'><img src = 'recursos/imagenes/addicon.png' alt = 'Smiley face' height = '25' width = '25'></div>";
                        echo "<div onclick = $('#contieneAgregarKEY').show() class = 'opcionText'>Agregar Nuevo</div>";
                    }
                    ?>                    
                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="nombre">
            <input type="hidden" name="enorden" id="enorden" value="asc">


            <div class="contieneAgregarDisco" id="contieneAgregarKEY">
                <div class="tituloAgregar">Agregar Key Pair</div>
                <form id="crearKey" name="crearKey" method="post" action="recursos/php/acciones.php?accion=agregarKey">
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
                                while ($proveedor = pg_fetch_array($result_proveedor)) {
                                    if ($proveedor["idproveedor"] == 1) {
                                        if (tienePermiso($_SESSION["idusuario"], $proveedor["nombre"], "Key Pairs", "Crear")) {
                                            echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="errorEntrada01" class="ConfiguracionElemento-validacion"></div>
                    </div>                                

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre del Key Pair
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input onblur="validaNombre()" id="nombrekey" name="nombrekey" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada03" class="ConfiguracionElemento-validacion"></div>
                    </div>                   

                    <div onclick=agregarKey() class="agregarBoton" id="botonAgregar" >Agregar Key Pair</div>
                    <div onclick=cerrarAgregar() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>


            <div class="contenedorTABLA" id="contenedorTABLA">
                <?php
                $sqlKeys = "select keypair.idkeypair as idkeypair, proveedor.nombre as proveedor, keypair.nombre as nombre, keypair.fingerprint as fingerprint, keypair.privatekey as privatekey from keypair, proveedor where keypair.idproveedor = proveedor.idproveedor and keypair.fecha_eliminacion is null and keypair.idorganizacion='" . $_SESSION["idorganizacion"] . "';";
                $resultKeys = pg_query($sqlKeys) or die('La consulta fallo: ' . pg_last_error());
                ?>
                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultKeys); ?> Elementos</div>
                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("proveedor") class="eleCabTabla" style="width: 20%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("nombre") class="eleCabTabla" style="width: 20%">Nombre<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("fingerprint") class="eleCabTabla" style="width: 40%">Finger Print<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>

                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($Key = pg_fetch_array($resultKeys)) {
                        if (tienePermiso($_SESSION["idusuario"], $Key["proveedor"], "Key Pairs", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Key["idkeypair"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Key["idkeypair"] . ") onmouseout=ocultarAcciones(" . $Key["idkeypair"] . ") class='panelOpciones' id='panelOpciones-" . $Key["idkeypair"] . "'>";
                            if (tienePermiso($_SESSION["idusuario"], $Key["proveedor"], "Key Pairs", "Descargar")) {
                                echo "<a href='recursos/keypairs/" . $Key["nombre"] . ".pem' download><div class='panelOPC' >Descargar Private Key</div></a>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Key["proveedor"], "Key Pairs", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Key["idkeypair"] . ")>Eliminar</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 20%' title='" . $Key["proveedor"] . "'>" . $Key["proveedor"] . "</div>";
                            echo "<div id='nombre-" . $Key["idkeypair"] . "' class='eleLinTabla' style='width: 20%' title='" . $Key["nombre"] . "'>" . $Key["nombre"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 40%' title='" . $Key["fingerprint"] . "'>" . $Key["fingerprint"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            function validarEliminacion(idkeypair) {
                $("#seactualiza").html("<div style='font-size: 12px;' id='dialog-confirm' title='¿Esta seguro que desea eliminar el Key Pair " + $("#nombre-" + idkeypair).html() + "?'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Recuerde que esta es una acción permanente, si borra el keypair no podra recuperarlo luego.</p></div>");
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Eliminar Key Pair": function () {
                            location.href = "recursos/php/acciones.php?accion=eliminarKeyPair&id=" + idkeypair;
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>
        <div id="seactualiza">
            <div style="font-size: 12px;" id="dialog-confirm" title="¿Esta seguro que desea eliminar este Disco?">
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
                    data: {accion: "18", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
            
            function cerrarAgregar() {
                $("#contieneAgregarKEY").hide();
            }

            function actualizaTabla() {
                $.ajax({
                    data: {accion: "18", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#contenedorTABLA").html(response);
                        var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                        $("#cuerpotabla").height((h - 310));
                    }
                });
            }

            function mostrarAcciones(id) {
                $("#panelOpciones-" + id).show();
            }

            function ocultarAcciones(id) {
                $("#panelOpciones-" + id).hide();
            }

            function accionMaquina(nombreaccion, idkeypair) {

                if (nombreaccion == "eliminar") {
                    validarEliminacion(idkeypair);
                }
            }

            function validaNombre() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;

                if ($("#nombrekey").val() != "") {
                    if (!expr.test($("#nombrekey").val())) {
                        $("#errorEntrada03").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                    } else {
                        $("#errorEntrada03").html(" ");

                    }
                } else {
                    $("#errorEntrada03").html("El nombre para el keypair es obligatorio.");
                }
            }

            function agregarKey() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
                var bandCreacion = 0;

                if ($("#nombrekey").val() != "") {
                    if (!expr.test($("#nombrekey").val())) {
                        $("#errorEntrada03").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada03").html(" ");

                    }
                } else {
                    $("#errorEntrada03").html("El nombre para el keypair es obligatorio.");
                    bandCreacion = 1;
                }

                if (bandCreacion == 0) {
                    document.getElementById("crearKey").submit();
                }
            }

        </script>        
    </body>
</html>
