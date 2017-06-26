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
        <title>Discos Virtuales</title>
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
            <div class="subtitulo_aplicacion">Mís Discos Virtuales</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusidiscosColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Mís Discos</div>
                <div class="subtitulo_hoja">Listado de Discos Virtuales</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">

                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Discos", "Crear Disco") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Discos", "Crear Disco")) {
                        echo "<div class = 'opcionIcon'><img src = 'recursos/imagenes/addicon.png' alt = 'Smiley face' height = '25' width = '25'></div>";
                        echo "<div onclick = $('#contieneAgregarDisco2').show(); class = 'opcionText'>Agregar Nuevo</div>";
                    }
                    ?>

                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="nombredisco">
            <input type="hidden" name="enorden" id="enorden" value="asc">




            <div class="contieneAgregarDisco" id="contieneAgregarDisco2">
                <div class="tituloAgregar">Agregar Disco</div>
                <form id="crearDisco2" name="crearDisco2" method="post" action="recursos/php/acciones.php?accion=agregarDisco2">                    

                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Proveedor
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <select onchange='actualizaServidores()' class="selectConfiguracion" id="selproveedor2" name="selproveedor2">
                                <?php
                                $query_proveedor = "SELECT * FROM proveedor order by nombre asc;";
                                $result_proveedor = pg_query($query_proveedor) or die('La consulta fallo: ' . pg_last_error());
                                $band = 0;
                                $idtemProveedor = 0;
                                while ($proveedor = pg_fetch_array($result_proveedor)) {
                                    if (tienePermiso($_SESSION["idusuario"], $proveedor["nombre"], "Discos", "Crear Disco")) {
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
                        <div id="errorEntrada001" class="ConfiguracionElemento-validacion"></div>
                    </div>                     

                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Servidor al que se agregara el disco
                        </div>
                        <div class="ConfiguracionElemento-contenedor" id="contieneServidores02">
                            <?php
                            $query_servidor = "SELECT * FROM servidor where idproveedor='" . $idtemProveedor . "' and fecha_eliminacion is null order by nombrevm asc;";
                            $result_servidor = pg_query($query_servidor) or die('La consulta fallo: ' . pg_last_error());
                            ?>
                            <select class="selectConfiguracion" id="selservidor2" name="selservidor2">
                                <?php
                                while ($servidor = pg_fetch_array($result_servidor)) {
                                    echo "<option value='" . $servidor["idservidor"] . "'>" . $servidor["nombrevm"] . "</option>";
                                }
                                ?>
                            </select>                            
                        </div>
                        <div id="errorEntrada002" class="ConfiguracionElemento-validacion"></div>
                    </div>                


                    <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre del Disco
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="nombredisco2" name="nombredisco2" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>
                    </div>  

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Tamaño del Disco en GB
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="tamanodisco2" name="tamanodisco2" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada004" class="ConfiguracionElemento-validacion"></div>
                    </div>                 

                    <div onclick=agregarDisco2() class="agregarBoton" id="botonAgregar" >Agregar Disco</div>
                    <div onclick=cerrarAgregar2() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>            

            <div class="contieneAgregarDisco" id="contieneAgregarDisco">
                <input type="hidden" id="servidoraconectar" name="servidoraconectar" value=""/>
                <div class="tituloAgregar">Conectar Disco</div>
                <form id="crearDisco" name="crearDisco" method="post" action="recursos/php/acciones.php?accion=agregarDisco">
                    <input type="hidden" name="idservidoragrega" id="idservidoragrega" value="">
                    <div class="ConfiguracionElemento" style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Servidor al que se conectara el disco
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <div id="SeleccionServidor">

                            </div>
                        </div>
                        <div id="errorEntrada01" class="ConfiguracionElemento-validacion"></div>
                    </div>                                
                    <div onclick=ConectarDisco() class="agregarBoton" id="botonAgregar" >Conectar Disco</div>
                    <div onclick=cerrarAgregar() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>
            <div class="contenedorTABLA" id="contenedorTABLA">
                <?php
                $sqlDiscos = "select usuario.idusuario, disco.iddisco as iddisco, disco.idenproveedor as idenproveedor, proveedor.nombre as proveedor, location.nombre as localizacion, servidor.nombrevm as servidor, disco.nombre as nombredisco, disco.sizegb as tamano, disco.estatus as estatus from usuario, proveedor, location, servidor, disco where disco.idservidor=servidor.idservidor and servidor.idlocation = location.idlocation and servidor.idproveedor= proveedor.idproveedor and servidor.idusuario=usuario.idusuario and servidor.idusuario=" . $_SESSION["idusuario"] . " and disco.fecha_eliminacion is null order by nombredisco asc;";
                $resultDiscos = pg_query($sqlDiscos) or die('La consulta fallo: ' . pg_last_error());
                ?>
                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultDiscos); ?> Elementos</div>
                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("proveedor") class="eleCabTabla" style="width: 11%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("localizacion") class="eleCabTabla" style="width: 10%">Localización<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("servidor") class="eleCabTabla" style="width: 12%">Maquina Virtual<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("nombredisco") class="eleCabTabla" style="width: 11%">Disco<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("idenproveedor") class="eleCabTabla" style="width: 18%">Id en Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("tamano") class="eleCabTabla" style="width: 10%">Tamaño<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("estatus") class="eleCabTabla" style="width: 8%">Estatus<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>                    
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($Disco = pg_fetch_array($resultDiscos)) {
                        if (tienePermiso($_SESSION["idusuario"], $Disco["proveedor"], "Discos", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Disco["iddisco"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Disco["iddisco"] . ") onmouseout=ocultarAcciones(" . $Disco["iddisco"] . ") class='panelOpciones' id='panelOpciones-" . $Disco["iddisco"] . "'>";
                            if (tienePermiso($_SESSION["idusuario"], $Disco["proveedor"], "Discos", "Desconectar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('desconectar'," . $Disco["iddisco"] . ")>Desconectar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Disco["proveedor"], "Discos", "Conectar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('conectar'," . $Disco["iddisco"] . ")>Conectar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Disco["proveedor"], "Discos", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Disco["iddisco"] . ")>Eliminar</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 11%' title='" . $Disco["proveedor"] . "'>" . $Disco["proveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Disco["localizacion"] . "'>" . $Disco["localizacion"] . "</div>";
                            if ($Disco["estatus"] == "off-line" || $Disco["estatus"] == "Eliminando") {
                                echo "<div class='eleLinTabla' style='width: 12%' title=''></div>";
                            } else {
                                echo "<div class='eleLinTabla' style='width: 12%' title='" . $Disco["servidor"] . "'>" . $Disco["servidor"] . "</div>";
                            }
                            echo "<div id='nombre-" . $Disco["iddisco"] . "' class='eleLinTabla' style='width: 11%' title='" . $Disco["nombredisco"] . "'>" . $Disco["nombredisco"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 18%' title='" . $Disco["idenproveedor"] . "'>" . $Disco["idenproveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Disco["tamano"] . "'>" . $Disco["tamano"] . " GB" . "</div>";
                            echo "<div id='estatus-" . $Disco["iddisco"] . "' class='eleLinTabla' style='width: 8%' title='" . $Disco["estatus"] . "'>" . $Disco["estatus"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            function validarEliminacion(iddisco) {
                $("#seactualiza").html("<div style='font-size: 12px;' id='dialog-confirm' title='¿Esta seguro que desea eliminar el disco " + $("#nombre-" + iddisco).html() + "?'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Recuerde que esta es una acción permanente, si borra el disco no podra recuperarlo luego.</p></div>");
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Eliminar Disco": function () {
                            location.href = "recursos/php/acciones.php?accion=eliminarDisco&id=" + iddisco;
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
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Recuerde que esta es una acción permanente, si borra el disco no podra recuperarlo luego.</p>
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
                    data: {accion: "6", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: "6", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

            function accionMaquina(nombreaccion, iddisco) {
                var estatus = $("#estatus-" + iddisco).html();
                //alert(estatus);
                if (nombreaccion == "desconectar") {
                    if (estatus == "on-line") {
                        location.href = "recursos/php/acciones.php?accion=desconectarDisco&id=" + iddisco;
                    } else {
                        alert("Es necesario que el disco virtual este 'on-line' para poder desconectarlo.");
                    }
                }

                if (nombreaccion == "conectar") {
                    if (estatus == "off-line") {
                        $("#servidoraconectar").val(iddisco);
                        $.ajax({
                            data: {accion: "15", iddisco: iddisco},
                            url: './recursos/php/ajax.php',
                            type: 'post',
                            success: function (response) {
                                $("#SeleccionServidor").html(response);
                                $("#contieneAgregarDisco").show();
                            }
                        });
                    } else {
                        alert("Es necesario que el disco virtual este 'off-line' para poder conectarlo.");
                    }
                }

                if (nombreaccion == "eliminar") {
                    if (estatus == "off-line" || estatus == "error") {
                        validarEliminacion(iddisco);
                        //location.href = "recursos/php/acciones.php?accion=eliminarDisco&id=" + iddisco;
                    } else {
                        alert("Es necesario que el disco virtual este 'off-line' para poder eliminarlo.");
                    }
                }
            }

            function ConectarDisco() {
                //alert($("#servidoraconectar").val()+" "+$("#servidorConectar").val());                
                location.href = "recursos/php/acciones.php?accion=conectarDisco&id=" + $("#servidoraconectar").val() + "&idservidor=" + $("#servidorConectar").val();
            }

            function cerrarAgregar() {
                $("#contieneAgregarDisco").hide();
            }

            function cerrarAgregar2() {
                $("#contieneAgregarDisco2").hide();
            }

            function agregarDisco2() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
                var bandCreacion = 0;

                if (typeof $("#selservidor2").val() === "undefined" || typeof $("#selservidor2").val() === "object") {
                    $("#errorEntrada002").html("Es necesario seleccionar un servidor para agregar el disco, si no existe debe crearlo.");
                    bandCreacion = 1;
                } else {
                    $("#errorEntrada002").html("");
                }

                if ($("#nombredisco2").val() != "") {
                    if (!expr.test($("#nombredisco2").val())) {
                        $("#errorEntrada003").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada003").html(" ");
                    }
                } else {
                    $("#errorEntrada003").html("El nombre del disco es obligatorio.");
                    bandCreacion = 1;
                }


                if ($("#tamanodisco2").val() != "") {
                    if (!expr2.test($("#tamanodisco2").val())) {
                        $("#errorEntrada004").html("Solo números, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada004").html(" ");
                    }
                } else {
                    $("#errorEntrada004").html("El tamaño del disco es obligatorio.");
                    bandCreacion = 1;
                }

                if (bandCreacion == 0) {
                    document.getElementById("crearDisco2").submit();
                }
            }

            function agregarDisco() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
                var bandCreacion = 0;

                if ($("#nombredisco").val() != "") {
                    if (!expr.test($("#nombredisco").val())) {
                        $("#errorEntrada02").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada02").html(" ");
                    }
                } else {
                    $("#errorEntrada02").html("El nombre del disco es obligatorio.");
                    bandCreacion = 1;
                }


                if ($("#tamanodisco").val() != "") {
                    if (!expr2.test($("#tamanodisco").val())) {
                        $("#errorEntrada03").html("Solo números, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada03").html(" ");
                    }
                } else {
                    $("#errorEntrada03").html("El tamaño del disco es obligatorio.");
                    bandCreacion = 1;
                }

                if (bandCreacion == 0) {
                    document.getElementById("crearDisco").submit();
                }

            }
        </script>        
    </body>
</html>
