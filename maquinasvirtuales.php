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
        <title>Maquinas Virtuales</title>
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
            <div class="subtitulo_aplicacion">Mís Maquinas Virtuales</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiservidoresColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Mís Maquinas Virtuales</div>
                <div class="subtitulo_hoja">Listado de Maquinas Virtuales</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">

                    <?php
                    if (tienePermiso($_SESSION["idusuario"], "Amazon Web Services", "Servidores", "Crear") || tienePermiso($_SESSION["idusuario"], "Windows Azure", "Servidores", "Crear")) {
                        echo "<div class='opcionIcon'><img src='recursos/imagenes/addicon.png' alt='Smiley face' height='25' width='25'></div>";
                        echo "<a href='crearmaquinavirtual.php'><div class='opcionText'>Agregar Nuevo</div></a>";
                    }
                    ?>

                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="nombreproveedor">
            <input type="hidden" name="enorden" id="enorden" value="asc">


            <div class="contieneAgregarDisco" id="contieneAgregarDisco">
                <div class="tituloAgregar">Agregar Disco Al Servidor</div>
                <form id="crearDisco" name="crearDisco" method="post" action="recursos/php/acciones.php?accion=agregarDisco">
                    <input type="hidden" name="idservidoragrega" id="idservidoragrega" value="">
                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Servidor al que se agregara el disco
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input readonly="true" id="servidoragrega" name="servidoragrega" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada01" class="ConfiguracionElemento-validacion"></div>
                    </div>                


                    <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre del Disco
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="nombredisco" name="nombredisco" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada02" class="ConfiguracionElemento-validacion"></div>
                    </div>  

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Tamaño del Disco en GB
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="tamanodisco" name="tamanodisco" type="text" class="entradaConfiguracion"/>
                        </div>
                        <div id="errorEntrada03" class="ConfiguracionElemento-validacion"></div>
                    </div>                 

                    <div onclick=agregarDisco() class="agregarBoton" id="botonAgregar" >Agregar Disco</div>
                    <div onclick=cerrarAgregar() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>
            <div class="contenedorTABLA" id="contenedorTABLA">
                <?php
                $sqlMaquinas = "select servidor.idservidor as idservidor, proveedor.nombre as nombreproveedor, location.nombre as localizacion, servidor.nombrevm as nombremaquina, servidor.ippublica as ippublica, sistemaoperativo.nombre as sistemaoperativo, flavor.nombre as flavor, servidor.status as estatus from proveedor, location, servidor, sistemaoperativo, flavor where servidor.idlocation=location.idlocation and servidor.idproveedor=proveedor.idproveedor and servidor.idsistemaoperativo = sistemaoperativo.idsistemaoperativo and flavor.idflavor = servidor.idflavor and servidor.fecha_eliminacion is null and idusuario=" . $_SESSION["idusuario"] . " order by nombreproveedor asc;";
                $resultMaquinas = pg_query($sqlMaquinas) or die('La consulta fallo: ' . pg_last_error());
                ?>
                <div class="contenedorNumElementos"><?php echo pg_num_rows($resultMaquinas); ?> Elementos</div>
                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("nombreproveedor") class="eleCabTabla" style="width: 15%">Proveedor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("localizacion") class="eleCabTabla" style="width: 10%">Localización<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("nombremaquina") class="eleCabTabla" style="width: 15%">Maquina Virtual<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("estatus") class="eleCabTabla" style="width: 7%">Estatus<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("ippublica") class="eleCabTabla" style="width: 10%">IP Publica<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("sistemaoperativo") class="eleCabTabla" style="width: 17%">Sistema Operativo<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("flavor") class="eleCabTabla" style="width: 10%">Flavor<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>                    
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    while ($Maquina = pg_fetch_array($resultMaquinas)) {
                        if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Visualizar")) {
                            echo "<div class='lineaTabla'>";
                            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Maquina["idservidor"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Maquina["idservidor"] . ") onmouseout=ocultarAcciones(" . $Maquina["idservidor"] . ") class='panelOpciones' id='panelOpciones-" . $Maquina["idservidor"] . "'>";
                            if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Apagar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('apagar'," . $Maquina["idservidor"] . ")>Apagar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Encender")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('encender'," . $Maquina["idservidor"] . ")>Encender</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Reiniciar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('reiniciar'," . $Maquina["idservidor"] . ")>Reiniciar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Eliminar")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Maquina["idservidor"] . ")>Eliminar</div>";
                            }
                            if (tienePermiso($_SESSION["idusuario"], $Maquina["nombreproveedor"], "Servidores", "Agregar Disco")) {
                                echo "<div class='panelOPC' onclick=accionMaquina('agregardisco'," . $Maquina["idservidor"] . ")>Agregar Disco</div>";
                            }
                            echo "</div></div>";
                            echo "<div class='eleLinTabla' style='width: 15%' title='" . $Maquina["nombreproveedor"] . "'>" . $Maquina["nombreproveedor"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Maquina["localizacion"] . "'>" . $Maquina["localizacion"] . "</div>";
                            echo "<div id='nombre-" . $Maquina["idservidor"] . "' class='eleLinTabla' style='width: 15%' title='" . $Maquina["nombremaquina"] . "'>" . $Maquina["nombremaquina"] . "</div>";
                            echo "<div id='estatus-" . $Maquina["idservidor"] . "' class='eleLinTabla' style='width: 7%' title='" . $Maquina["estatus"] . "'>" . $Maquina["estatus"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Maquina["ippublica"] . "'>" . $Maquina["ippublica"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 17%' title='" . $Maquina["sistemaoperativo"] . "'>" . $Maquina["sistemaoperativo"] . "</div>";
                            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Maquina["flavor"] . "'>" . $Maquina["flavor"] . "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            function validarEliminacion(idmaquina) {
                $("#seactualiza").html("<div style='font-size: 12px;' id='dialog-confirm' title='¿Esta seguro que desea eliminar el servidor " + $("#nombre-" + idmaquina).html() + "?'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Recuerde que esta es una acción permanente, si borra el servidor no podra recuperarlo luego.</p></div>");
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Eliminar Servidor": function () {
                            location.href = "recursos/php/acciones.php?accion=eliminarMaquina&id=" + idmaquina;
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>
        <div id="seactualiza">
            <div style="font-size: 12px;" id="dialog-confirm" title="¿Esta seguro que desea eliminar este servidor?">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Recuerde que esta es una acción permanente, si borra el servidor no podra recuperarlo luego.</p>
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
                    data: {accion: "5", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: "5", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

            function accionMaquina(nombreaccion, idmaquina) {
                var estatus = $("#estatus-" + idmaquina).html();
                if (nombreaccion == "apagar") {
                    if (estatus == "on-line") {
                        location.href = "recursos/php/acciones.php?accion=apagarMaquina&id=" + idmaquina;
                    } else {
                        alert("Es necesario que la maquina virtual este 'on-line' para poder apagarla.");
                    }
                }

                if (nombreaccion == "encender") {
                    if (estatus == "Apagada") {
                        location.href = "recursos/php/acciones.php?accion=encenderMaquina&id=" + idmaquina;
                    } else {
                        alert("Es necesario que la maquina virtual este 'Apagada' para poder encenderla.");
                    }
                }

                if (nombreaccion == "reiniciar") {
                    if (estatus == "on-line") {
                        location.href = "recursos/php/acciones.php?accion=reiniciarMaquina&id=" + idmaquina;
                    } else {
                        alert("Es necesario que la maquina virtual este 'on-line' para poder reiniciarla.");
                    }
                }

                if (nombreaccion == "eliminar") {
                    if (estatus == "on-line" || estatus == "Apagada") {
                        validarEliminacion(idmaquina);
                        //location.href = "recursos/php/acciones.php?accion=eliminarMaquina&id=" + idmaquina;
                    } else {
                        alert("Es necesario que la maquina virtual este 'on-line' o 'Apagada' para poder eliminarla.");
                    }

                }

                if (nombreaccion == "agregardisco") {
                    if (estatus == "on-line") {
                        $("#servidoragrega").val($("#nombre-" + idmaquina).html());
                        $("#idservidoragrega").val(idmaquina);
                        $("#contieneAgregarDisco").show();
                    } else {
                        alert("Es necesario que la maquina virtual este 'on-line' para poder agregarle un nuevo disco.");
                    }
                }
            }

            function cerrarAgregar() {
                $("#contieneAgregarDisco").hide();
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
