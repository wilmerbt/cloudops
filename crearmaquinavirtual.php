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
        <title>Crear Maquina Virtual</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="recursos/css/estilobase.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="recursos/js/jquery-3.1.0.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">                
    </head>
    <body>
        <div id="seccion01" class="seccion01">
            <div class="titulo_aplicacion" ><img src="recursos/imagenes/logohawk.png" alt="Smiley face" height="63" width="127"></div>
            <div class="subtitulo_aplicacion">Crear Maquina Virtual</div>
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
        <div id="seccion04" class="seccion04" style="padding-bottom: -10px;">

            <div style="width: 400px; height: 40px; float: left;">
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusiservidoresColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Crear Maquina Virtual</div>
                <div class="subtitulo_hoja">Resumen de la infraestructura y el consumo de recursos</div>
            </div>
        </div>
        <?php
        $query_servidores = "SELECT * FROM servidor where fecha_eliminacion is null and idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $result_servidores = pg_query($query_servidores) or die('La consulta fallo: ' . pg_last_error());
        $seconcatenan = "";
        $band = 0;
        while ($servidorC = pg_fetch_array($result_servidores)) {
            if ($band == 0) {
                $seconcatenan = $seconcatenan . $servidorC["nombrevm"];
                $band = 1;
            } else {
                $seconcatenan = $seconcatenan . "-" . $servidorC["nombrevm"];
            }
        }
        ?>        
        <input type="hidden" name="lista-servidores" id="lista-servidores" value="<?php echo $seconcatenan; ?>" />
        <input type="hidden" id="idproveedorSEL" name="idproveedorSEL" />
        <input type="hidden" id="idosSEL" name="idosSEL" />
        <input type="hidden" id="idosVersionSEL" name="idosVersionSEL" />
        <input type="hidden" id="idFlavor" name="idFlavor" />
        <div id="seccion03" class="seccion03" style="margin-top:-10px; padding-top: -5px;">
            <div class="menuWizard" style="margin-top: 10px;margin-left: 15px;">
                <div class="menuWizard-opcion">01. Proveedor</div>
                <div class="menuWizard-opcion">02. Sistema Operativo</div>
                <div class="menuWizard-opcion">03. Versión del OS</div>
                <div class="menuWizard-opcion">04. Flavor</div>
                <div class="menuWizard-opcion" style="border-bottom: 0px">05. Configuración</div>
            </div>
            <div class="wizardMaquina" style="margin-top: 10px;">
                <div id="tab01" style="display: inline">
                    <div class="wizardMaquina-cabecera">
                        <div class="wizardMaquina-cabecera-paso">01</div>
                        <div class="wizardAux01">
                            <div class="wizardMaquina-cabecera-paso-titulo">Proveedor</div>
                            <div class="wizardMaquina-cabecera-paso-subtitulo">Seleccione el proveedor que mejor se adapte a sus necesidades</div>
                        </div>                        
                    </div>
                    <div class="wizardMaquina-cuerpo">
                        <div class="ConfiguracionWizard-titulo">Proveedor Seleccionado</div>
                        <input value="" class="entradaWizardSEL" type="text" name="proveedorSEL" id="proveedorSEL" readonly="true"/>                        
                        <div id="errorEntrada01" class="errorEnentrada"></div>
                        <?php
                        $query_proveedor = "SELECT * FROM proveedor order by idproveedor";
                        $result_proveedor = pg_query($query_proveedor) or die('La consulta fallo: ' . pg_last_error());
                        while ($proveedor = pg_fetch_array($result_proveedor)) {
                            if (tienePermiso($_SESSION["idusuario"],$proveedor["nombre"], "Servidores", "Crear")) {
                                $ancho = 0;
                                $alto = 0;
                                if ($proveedor["tipologo"] == "rectangular") {
                                    $ancho = 261;
                                    $alto = 130;
                                } else if ($proveedor["tipologo"] == "cuadrado") {
                                    $ancho = 130;
                                    $alto = 130;
                                }
                                echo "<div onclick=clickProveedor(" . $proveedor["idproveedor"] . ",'" . str_replace(" ", "_", $proveedor["nombre"]) . "') class='wizardElementoSeleccion' title='" . $proveedor["nombre"] . "'><img src='recursos/imagenes/" . $proveedor["logo"] . "' alt='" . $proveedor["nombre"] . "' height='" . $alto . "' width='" . $ancho . "'></div>";
                            }
                        }
                        ?>   
                        <div id="botonNext01" onclick=siguiente01() class="wizardSiguiente SiguienteDeshabilitado">Siguiente</div>
                    </div>                         
                </div>
                <div id="tab02" style="display: none">
                    <div class="wizardMaquina-cabecera">
                        <div class="wizardMaquina-cabecera-paso">02</div>
                        <div class="wizardAux01">
                            <div class="wizardMaquina-cabecera-paso-titulo">Sistema Operativo</div>
                            <div class="wizardMaquina-cabecera-paso-subtitulo">Seleccione el Sistema Operativo que Necesite</div>
                        </div>                        
                    </div>
                    <div class="wizardMaquina-cuerpo">
                        <input value="Windows Server" class="entradaWizardSEL" type="text" name="osSEL" id="osSEL"/> 
                        <div class="wizardElementoSeleccion" title="Ubuntu Server"><img src="recursos/imagenes/ubuntu.png" alt="Ubuntu Server" height="130" width="130"></div>
                        <div class="wizardElementoSeleccion" title="Suse"><img src="recursos/imagenes/suse.png" alt="Red Hat Server" height="130" width="130"></div>
                        <div class="wizardElementoSeleccion" title="Red Hat Server"><img src="recursos/imagenes/redhat.png" alt="Red Hat Server" height="130" width="130"></div>
                        <div class="wizardElementoSeleccion" title="Windows Azure"><img src="recursos/imagenes/azure-noseleccionado.png" alt="Windows Server" height="130" width="130"></div>
                        <div onclick=siguiente02() class="wizardSiguiente SiguienteHabilitado">Siguiente</div>
                        <div onclick=anterior02() class="wizardAnterior SiguienteHabilitado">Anterior</div>
                    </div>                         
                </div>
                <div id="tab03" style="display: none">
                    <div class="wizardMaquina-cabecera">
                        <div class="wizardMaquina-cabecera-paso">03</div>
                        <div class="wizardAux01">
                            <div class="wizardMaquina-cabecera-paso-titulo">Versión del Sistema Operativo</div>
                            <div class="wizardMaquina-cabecera-paso-subtitulo">Seleccione la Versión del Sistema Operativo que mejor se adapte a sus necesidades</div>
                        </div>                        
                    </div>
                    <div class="wizardMaquina-cuerpo">
                        <input value="Windows Server 2012 R2x64" class="entradaWizardSEL" type="text" name="osVersionSEL" id="osVersionSEL"/> 
                        <div class="conOS-versionOS2">
                            <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                            <div class="conOS-Titulo">Red Hat Enterprise Linux 6.7</div>
                            <div class="conOS-SubTitulo">Red Hat</div>
                        </div>
                        <div class="conOS-versionOS2">
                            <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                            <div class="conOS-Titulo">Red Hat Enterprise Linux 6.8</div>
                            <div class="conOS-SubTitulo">Red Hat</div>
                        </div> 
                        <div class="conOS-versionOS2">
                            <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                            <div class="conOS-Titulo">Red Hat Enterprise Linux 7.2</div>
                            <div class="conOS-SubTitulo">Red Hat</div>
                        </div>
                        <div class="conOS-versionOS2">
                            <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                            <div class="conOS-Titulo">Red Hat Enterprise Linux 7.3</div>
                            <div class="conOS-SubTitulo">Red Hat</div>
                        </div>                                                                        
                        <div onclick=siguiente03() class="wizardSiguiente SiguienteHabilitado">Siguiente</div>
                        <div onclick=anterior03() class="wizardAnterior SiguienteHabilitado">Anterior</div>
                    </div>                         
                </div>   

                <div id="tab04" style="display: none">
                    <div class="wizardMaquina-cabecera">
                        <div class="wizardMaquina-cabecera-paso">04</div>
                        <div class="wizardAux01">
                            <div class="wizardMaquina-cabecera-paso-titulo">Flavor</div>
                            <div class="wizardMaquina-cabecera-paso-subtitulo">Seleccione la configuración de vCPU, Memoria Ram y Disco que mejor se adapte a sus necesidades</div>
                        </div>                        
                    </div>
                    <div class="wizardMaquina-cuerpo">
                        <input value="DS1_V2 Standard" class="entradaWizardSEL" type="text" name="osSEL" id="osSEL"/> 
                        <div class="wizardFlavor">
                            <div class="flavor-cabecera">DS1_V2 Standard</div>
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">1</div>
                                <div class="flavor-linea-der">Core</div>
                            </div>
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">3.5</div>
                                <div class="flavor-linea-der">GB Memoria RAM</div>
                            </div>      
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">2</div>
                                <div class="flavor-linea-der">Data Disk</div>
                            </div>  
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">3200</div>
                                <div class="flavor-linea-der">Max IOPS</div>
                            </div> 
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">7</div>
                                <div class="flavor-linea-der">GB Local SSD</div>
                            </div>
                            <div class="flavor-linea">
                                <div class="flavor-linea-izq">54.31</div>
                                <div class="flavor-linea-der">USD/HOUR</div>
                            </div>                            
                        </div>                                                                                                                                                
                        <div onclick=siguiente04() class="wizardSiguiente SiguienteHabilitado">Siguiente</div>
                        <div onclick=anterior04() class="wizardAnterior SiguienteHabilitado">Anterior</div>
                    </div>                         
                </div>        

                <div id="tab05" style="display: none">
                    <div class="wizardMaquina-cabecera">
                        <div class="wizardMaquina-cabecera-paso">05</div>
                        <div class="wizardAux01">
                            <div class="wizardMaquina-cabecera-paso-titulo">Configuración del Servidor</div>
                            <div class="wizardMaquina-cabecera-paso-subtitulo">Establezca las configuraciones necesarias para desplegar el servidor</div>
                        </div>                        
                    </div>
                    <div class="wizardMaquina-cuerpo">

                        <div class="contenedorFormulario">  
                            <form id="crearMaquina" name="crearMaquina" method="post" action="recursos/php/acciones.php?accion=crearMaquina">
                                <input type="hidden" name="aux01" id="aux01" value="" />
                                <input type="hidden" name="aux02" id="aux02" value="" />
                                <input type="hidden" name="aux03" id="aux03" value="" />
                                <input type="hidden" name="aux04" id="aux04" value="" />
                                <div class="ConfiguracionElemento">
                                    <div class="ConfiguracionElemento-titulo">
                                        Nombre Maquina Virtual
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <input onblur="validaNombreMaquina()" id="namemaquina" name="namemaquina" type="text" class="entradaConfiguracion"/>
                                    </div>
                                    <div id="errorEntrada05" class="ConfiguracionElemento-validacion"></div>
                                </div> 

                                <div class="ConfiguracionElemento" id="contieneUsuario">
                                    <div class="ConfiguracionElemento-titulo">
                                        Usuario
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <input onblur="validaUsuario()" id="nameusuario" name="nameusuario" type="text" class="entradaConfiguracion"/>
                                    </div>
                                    <div id="errorEntrada06" class="ConfiguracionElemento-validacion"></div>
                                </div>     

                                <div class="ConfiguracionElemento" id="contienePassword">
                                    <div class="ConfiguracionElemento-titulo">
                                        Contraseña
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <input onblur="validaContrasena()" id="contrasena" name="contrasena" type="password" class="entradaConfiguracion"/>
                                    </div>
                                    <div id="errorEntrada07" class="ConfiguracionElemento-validacion"></div>
                                </div> 

                                <div class="ConfiguracionElemento" id="contieneConfirmacion">
                                    <div class="ConfiguracionElemento-titulo">
                                        Confirmación de la Contraseña
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <input onblur="validaConfirmacionContrasena()" id="confcontrasena" name="confcontrasena" type="password" class="entradaConfiguracion"/>
                                    </div>
                                    <div id="errorEntrada08" class="ConfiguracionElemento-validacion"></div>
                                </div>                        

                                <div class="ConfiguracionElemento">
                                    <div class="ConfiguracionElemento-titulo">
                                        Localizacion
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <div id="SeleccionLocalizacion">                                            
                                            <select class="selectConfiguracion" id="localizacion" name="localizacion">
                                            </select>
                                        </div>
                                    </div>
                                    <div id="errorEntrada09" class="ConfiguracionElemento-validacion"></div>
                                </div>

                                <div class="ConfiguracionElemento">
                                    <div class="ConfiguracionElemento-titulo">
                                        Red Virtual
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <div id="SeleccionRedVirtual">

                                        </div>
                                    </div>
                                    <div id="errorEntrada11" class="ConfiguracionElemento-validacion"></div>
                                </div>   

                                <div class="ConfiguracionElemento">
                                    <div class="ConfiguracionElemento-titulo">
                                        Sub Red Virtual
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <div id="SeleccionSubRedVirtual">

                                        </div>
                                    </div>
                                    <div id="errorEntrada12" class="ConfiguracionElemento-validacion"></div>
                                </div>

                                <div class="ConfiguracionElemento">
                                    <div class="ConfiguracionElemento-titulo">
                                        Grupo de Seguridad
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <div id="SeleccionGrupoSeguridad">

                                        </div>
                                    </div>
                                    <div id="errorEntrada10" class="ConfiguracionElemento-validacion"></div>
                                </div> 

                                <div class="ConfiguracionElemento" id="contieneKeyPair">
                                    <div class="ConfiguracionElemento-titulo">
                                        Key Pair
                                    </div>
                                    <div class="ConfiguracionElemento-contenedor">
                                        <div id="SeleccionKeyPair">

                                        </div>
                                    </div>
                                    <div id="errorEntrada13" class="ConfiguracionElemento-validacion"></div>
                                </div>                                 
                            </form>    
                        </div>

                        <div onclick=anterior05() class="wizardAnterior SiguienteHabilitado">Anterior</div>
                        <div onclick=siguiente05() class="wizardSiguiente SiguienteHabilitado">Deploy</div>                         
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
            $(window).resize(function (e) {
                var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#seccion02").height((h - 41));
                $("#seccion03").height((h - 41));
                $("#seccion03").width((w - 226));
                $("#seccion04").width((w - 226));
            });
        });
        
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
        
        function siguiente01() {
            var proveedorSEL = $("#proveedorSEL").val();
            if (proveedorSEL != "") {
                $("#tab01").hide();
                $("#tab02").show();
                $.ajax({
                    data: {accion: "1", idproveedor: $("#idproveedorSEL").val(), idtipoos: $("#idosSEL").val(), idosVersion: $("#idosVersionSEL").val(), idflavor: $("#idFlavor").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#tab02").html(response);
                        var osSEL = $("#osSEL").val();
                        if (osSEL != "") {
                            $("#botonNext02").removeClass("SiguienteDeshabilitado");
                            $("#botonNext02").addClass("SiguienteHabilitado");
                            $("#errorEntrada02").html("");
                        }
                    }
                });
            } else {
                $("#errorEntrada01").html("Debe seleccionar primero un proveedor para poder continuar.");
            }

        }

        function siguiente02() {
            var osSEL = $("#osSEL").val();
            if (osSEL != "") {
                $("#tab02").hide();
                $("#tab03").show();
                $.ajax({
                    data: {accion: "2", idproveedor: $("#idproveedorSEL").val(), idtipoos: $("#idosSEL").val(), idosVersion: $("#idosVersionSEL").val(), idflavor: $("#idFlavor").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#tab03").html(response);
                        var osVersionSEL = $("#osVersionSEL").val();
                        if (osVersionSEL != "") {
                            $("#botonNext03").removeClass("SiguienteDeshabilitado");
                            $("#botonNext03").addClass("SiguienteHabilitado");
                            $("#errorEntrada03").html("");
                        }
                    }
                });
            } else {
                $("#errorEntrada02").html("Debe seleccionar primero un Tipo de Sistema Operativo para poder continuar.");
            }

        }

        function siguiente03() {
            var osVersionSEL = $("#osVersionSEL").val();
            if (osVersionSEL != "") {
                $("#tab03").hide();
                $("#tab04").show();
                $.ajax({
                    data: {accion: "3", idproveedor: $("#idproveedorSEL").val(), idtipoos: $("#idosSEL").val(), idosVersion: $("#idosVersionSEL").val(), idflavor: $("#idFlavor").val()},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#tab04").html(response);
                        var flavorSEL = $("#flavorSEL").val();
                        if (flavorSEL != "") {
                            $("#botonNext04").removeClass("SiguienteDeshabilitado");
                            $("#botonNext04").addClass("SiguienteHabilitado");
                            $("#errorEntrada04").html("");
                        }
                    }
                });
            } else {
                $("#errorEntrada03").html("Debe seleccionar primero la versión especifica del sistema operativo para poder continuar.");
            }

        }

        function siguiente04() {
            var flavorSEL = $("#flavorSEL").val();
            if (flavorSEL != "") {
                $("#tab04").hide();
                $("#tab05").show();
            } else {
                $("#errorEntrada04").html("Debe seleccionar primero el flavor con el que desea desplegar su nuevo servidor.");
            }

        }

        function siguiente05() {
            expr = /^[a-z0-9]+$/;
            exprPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,12}$/;
            var bandCreacion = 0;
            if ($("#namemaquina").val() != "") {
                if (!expr.test($("#namemaquina").val())) {
                    $("#errorEntrada05").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                    bandCreacion = 1;
                } else {
                    $("#errorEntrada05").html(" ");
                    var servidoresactuales = $("#lista-servidores").val();
                    var temporal = servidoresactuales.split("-");
                    for (var i = 0; i < temporal.length; i++) {
                        if (temporal[i] === $("#namemaquina").val()) {
                            $("#errorEntrada05").html("Ya existe un servidor con el mismo nombre.");
                            bandCreacion = 1;
                        }
                    }

                    var longitud = $("#namemaquina").val();
                    if (longitud.length < 7 || longitud.length > 20) {
                        bandCreacion = 1;
                        $("#errorEntrada05").html("El nombre del servidor debe tener como minimo 7 caracteres y como maximo 20.");
                    }
                }
            } else {
                $("#errorEntrada05").html("El nombre de la maquina es obligatorio.");
                bandCreacion = 1;
            }

            if ($("#idproveedorSEL").val() == 2) {

                if ($("#nameusuario").val() != "") {
                    if (!expr.test($("#nameusuario").val())) {
                        $("#errorEntrada06").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada06").html(" ");
                        var longitud = $("#nameusuario").val();
                        if (longitud.length < 7 || longitud.length > 20) {
                            bandCreacion = 1;
                            $("#errorEntrada06").html("El nombre del usuario debe tener como minimo 7 caracteres y como maximo 20.");
                        }
                    }
                } else {
                    $("#errorEntrada06").html("El nombre de usuario es obligatorio.");
                    bandCreacion = 1;
                }
            }


            var band1 = 0;
            var band2 = 0;

            if ($("#idproveedorSEL").val() == 2) {
                if ($("#contrasena").val() != "") {
                    if (!exprPassword.test($("#contrasena").val())) {
                        $("#errorEntrada07").html("Debe Contener 01 letra mayuscula, 01 letra minuscula, 01 número y 01 caracter especial.");
                        bandCreacion = 1;
                    } else {
                        band1 = 1;
                        $("#errorEntrada07").html(" ");
                    }
                } else {
                    $("#errorEntrada07").html("La Contraseña no puede estar vacia.");
                    bandCreacion = 1;
                }

                if ($("#confcontrasena").val() != "") {
                    if (!exprPassword.test($("#confcontrasena").val())) {
                        $("#errorEntrada08").html("Debe Contener 01 letra mayuscula, 01 letra minuscula, 01 número y 01 caracter especial.");
                        bandCreacion = 1;
                    } else {
                        band2 = 1;
                        $("#errorEntrada08").html(" ");
                    }
                } else {
                    $("#errorEntrada08").html("La Contraseña no puede estar vacia.");
                    bandCreacion = 1;
                }

                if (band1 == 1 && band2 == 1 && $("#confcontrasena").val() != $("#contrasena").val()) {
                    $("#errorEntrada07").html("Las Contraseñas deben coincidir.");
                    $("#errorEntrada08").html("Las Contraseñas deben coincidir.");
                    bandCreacion = 1;
                }
            }

            if (typeof $("#localizacion").val() == "undefined" || $("#localizacion").val() == null) {
                $("#errorEntrada09").html("Es obligatorio seleccionar una localizacion.");
                bandCreacion = 1;
            } else {
                $("#errorEntrada09").html(" ");
            }

            if (typeof $("#redvirtual").val() == "undefined" || $("#redvirtual").val() == null) {
                $("#errorEntrada11").html("Es obligatorio seleccionar una red.");
                bandCreacion = 1;
            } else {
                $("#errorEntrada11").html(" ");
            }

            if (typeof $("#subredvirtual").val() == "undefined" || $("#subredvirtual").val() == null) {
                $("#errorEntrada12").html("Es obligatorio seleccionar una sub red.");
                bandCreacion = 1;
            } else {
                $("#errorEntrada12").html(" ");
            }

            if (typeof $("#gruposeguridad").val() == "undefined" || $("#gruposeguridad").val() == null) {
                $("#errorEntrada10").html("Es obligatorio seleccionar un grupo de seguridad.");
                bandCreacion = 1;
            } else {
                $("#errorEntrada10").html(" ");
            }

            if ($("#idproveedorSEL").val() == 1) {
                if (typeof $("#selkey").val() == "undefined" || $("#selkey").val() == null) {
                    $("#errorEntrada13").html("Es obligatorio seleccionar un keypair para el despliegue del servidor.");
                    bandCreacion = 1;
                } else {
                    $("#errorEntrada13").html(" ");
                }
            }

            if (bandCreacion == 0) {
                $("#aux01").val($("#idproveedorSEL").val());
                $("#aux02").val($("#idosSEL").val());
                $("#aux03").val($("#idosVersionSEL").val());
                $("#aux04").val($("#idFlavor").val());
                document.getElementById("crearMaquina").submit();
            } else {
                alert("No se cumple con lo requerimientos para crear la maquina");
            }
        }

        function anterior02() {
            $("#tab02").hide();
            $("#tab01").show();
        }

        function anterior03() {
            $("#tab03").hide();
            $("#tab02").show();
        }

        function anterior04() {
            $("#tab04").hide();
            $("#tab03").show();
        }

        function anterior05() {
            $("#tab05").hide();
            $("#tab04").show();
        }

        function validaNombreMaquina() {
            expr = /^[a-z0-9]+$/;
            if ($("#namemaquina").val() != "") {
                if (!expr.test($("#namemaquina").val())) {
                    $("#errorEntrada05").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                } else {
                    $("#errorEntrada05").html(" ");
                    var servidoresactuales = $("#lista-servidores").val();
                    var temporal = servidoresactuales.split("-");
                    for (var i = 0; i < temporal.length; i++) {
                        if (temporal[i] === $("#namemaquina").val()) {
                            $("#errorEntrada05").html("Ya existe un servidor con el mismo nombre.");
                        }
                    }

                    var longitud = $("#namemaquina").val();
                    if (longitud.length < 7 || longitud.length > 20) {
                        $("#errorEntrada05").html("El nombre del servidor debe tener como minimo 7 caracteres y como maximo 20.");
                    }
                }
            } else {
                $("#errorEntrada05").html("El nombre de la maquina es obligatorio.");
            }
        }

        function validaUsuario() {
            expr = /^[a-z0-9]+$/;
            if ($("#nameusuario").val() != "") {
                if (!expr.test($("#nameusuario").val())) {
                    $("#errorEntrada06").html("Solo caracteres alfanumericos en minuscula, sin espacios en blanco.");
                } else {
                    $("#errorEntrada06").html(" ");
                    var longitud = $("#nameusuario").val();
                    if (longitud.length < 7 || longitud.length > 20) {
                        $("#errorEntrada06").html("El nombre del usuario debe tener como minimo 7 caracteres y como maximo 20.");
                    }
                }
            } else {
                $("#errorEntrada06").html("El nombre de usuario es obligatorio.");
            }
        }

        function validaContrasena() {
            exprPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,12}$/;
            if ($("#contrasena").val() != "") {
                if (!exprPassword.test($("#contrasena").val())) {
                    $("#errorEntrada07").html("Debe Contener 01 letra mayuscula, 01 letra minuscula, 01 número y 01 caracter especial.");
                } else {
                    $("#errorEntrada07").html(" ");
                }
            } else {
                $("#errorEntrada07").html("La Contraseña no puede estar vacia.");
            }
        }

        function validaConfirmacionContrasena() {
            exprPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,12}$/;
            if ($("#confcontrasena").val() != "") {
                if (!exprPassword.test($("#confcontrasena").val())) {
                    $("#errorEntrada08").html("Debe Contener 01 letra mayuscula, 01 letra minuscula, 01 número y 01 caracter especial.");
                } else {
                    $("#errorEntrada08").html(" ");
                }
            } else {
                $("#errorEntrada08").html("La Contraseña no puede estar vacia.");
            }
        }

        function clickProveedor(id, name) {
            $("#proveedorSEL").val(name.replace(/\_+/g, " "));
            $("#botonNext01").removeClass("SiguienteDeshabilitado");
            $("#botonNext01").addClass("SiguienteHabilitado");
            $("#errorEntrada01").html("");
            $("#idproveedorSEL").val(id);

            $("#contieneUsuario").show();
            $("#contienePassword").show();
            $("#contieneConfirmacion").show();
            $("#contieneKeyPair").show();

            $.ajax({
                data: {accion: "4", idproveedor: $("#idproveedorSEL").val()},
                url: './recursos/php/ajax.php',
                type: 'post',
                success: function (response) {
                    $("#SeleccionLocalizacion").html(response);

                    $.ajax({
                        data: {accion: "13", idlocalizacion: $("#localizacion").val()},
                        url: './recursos/php/ajax.php',
                        type: 'post',
                        success: function (response) {
                            $("#SeleccionRedVirtual").html(response);

                            $.ajax({
                                data: {accion: "14", idred: $("#redvirtual").val()},
                                url: './recursos/php/ajax.php',
                                type: 'post',
                                success: function (response) {
                                    $("#SeleccionSubRedVirtual").html(response);


                                    $.ajax({
                                        data: {accion: "12", idred: $("#redvirtual").val(), idlocalizacion: $("#localizacion").val(), idproveedor: $("#idproveedorSEL").val()},
                                        url: './recursos/php/ajax.php',
                                        type: 'post',
                                        success: function (response) {
                                            $("#SeleccionGrupoSeguridad").html(response);
                                        }
                                    });

                                }
                            });

                        }
                    });

                    if ($("#idproveedorSEL").val() == 1) {
                        $.ajax({
                            data: {accion: "19", idproveedor: $("#idproveedorSEL").val()},
                            url: './recursos/php/ajax.php',
                            type: 'post',
                            success: function (response) {
                                $("#SeleccionKeyPair").html(response);
                            }
                        });
                    } else {
                        $("#contieneKeyPair").hide();
                    }

                }
            });

            if ($("#idproveedorSEL").val() == 1) {
                $("#contieneUsuario").hide();
                $("#contienePassword").hide();
                $("#contieneConfirmacion").hide();
            }
        }


        function cambiaLocalizacion() {
            $.ajax({
                data: {accion: "13", idlocalizacion: $("#localizacion").val()},
                url: './recursos/php/ajax.php',
                type: 'post',
                success: function (response) {
                    $("#SeleccionRedVirtual").html(response);

                    $.ajax({
                        data: {accion: "14", idred: $("#redvirtual").val()},
                        url: './recursos/php/ajax.php',
                        type: 'post',
                        success: function (response) {
                            $("#SeleccionSubRedVirtual").html(response);


                            $.ajax({
                                data: {accion: "12", idred: $("#redvirtual").val(), idlocalizacion: $("#localizacion").val(), idproveedor: $("#idproveedorSEL").val()},
                                url: './recursos/php/ajax.php',
                                type: 'post',
                                success: function (response) {
                                    $("#SeleccionGrupoSeguridad").html(response);
                                }
                            });

                        }
                    });

                }
            });
        }

        function cambiaGrupoSeguridad() {
        }

        function cambiaRedVirtual() {
            $.ajax({
                data: {accion: "14", idred: $("#redvirtual").val()},
                url: './recursos/php/ajax.php',
                type: 'post',
                success: function (response) {
                    $("#SeleccionSubRedVirtual").html(response);


                    $.ajax({
                        data: {accion: "12", idred: $("#redvirtual").val(), idlocalizacion: $("#localizacion").val(), idproveedor: $("#idproveedorSEL").val()},
                        url: './recursos/php/ajax.php',
                        type: 'post',
                        success: function (response) {
                            $("#SeleccionGrupoSeguridad").html(response);
                        }
                    });

                }
            });
        }

        function clickTipoOS(id, name) {
            $("#osSEL").val(name.replace(/\_+/g, " "));
            $("#idosSEL").val(id);
            $("#botonNext02").removeClass("SiguienteDeshabilitado");
            $("#botonNext02").addClass("SiguienteHabilitado");
            $("#errorEntrada02").html("");
        }

        function clickOS(id, name) {
            $("#osVersionSEL").val(name.replace(/\_+/g, " "));
            $("#idosVersionSEL").val(id);
            $("#botonNext03").removeClass("SiguienteDeshabilitado");
            $("#botonNext03").addClass("SiguienteHabilitado");
            $("#errorEntrada03").html("");
        }

        function clickFlavor(id, name) {
            $("#flavorSEL").val(name.replace(/\__+/g, " "));
            $("#idFlavor").val(id);
            $("#botonNext04").removeClass("SiguienteDeshabilitado");
            $("#botonNext04").addClass("SiguienteHabilitado");
            $("#errorEntrada04").html("");
        }
    </script>        

</body>
</html>
