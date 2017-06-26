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
        <title>Crear Grupo de Seguridad</title>
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
            <div class="subtitulo_aplicacion">Crear Grupo de Seguridad</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikusigruposeguridadColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Crear Grupo de Seguridad</div>
                <div class="subtitulo_hoja">Formulario para la creación de grupo de seguridad</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div style="float: left; border: 1px solid #CCC; height: auto; width: 820px; margin: 15px; padding: 15px; padding-top: 0px;">
                <form id="crearGrupoSeguridad" name="crearGrupoSeguridad" method="post" action="recursos/php/acciones.php?accion=crearGrupoSeguridad">
                    <input type="hidden" name="concatenado" id="concatenado" value="" />
                    <div class="tituloAgregaGRUPO">
                        01. Configuración del Grupo de Seguridad
                    </div>
                    <div class="formularioAgregaGRUPO">
                        <div class="ConfiguracionElemento">
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
                                        if (tienePermiso($_SESSION["idusuario"], $proveedor["nombre"], "Grupos de Seguridad", "Crear")) {
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
                            <div id="error01" class="ConfiguracionElemento-validacion"></div>
                        </div> 

                        <div class="ConfiguracionElemento">
                            <div class="ConfiguracionElemento-titulo">
                                Localizacion
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <div id="actualizaLocalizacion">
                                    <select onchange=cambiaLocalizacion() class="selectConfiguracion" id="sellocalizacion" name="sellocalizacion">
                                        <?php
                                        if ($idtemProveedor == 1) {
                                            $query_location = "SELECT * FROM location where idproveedor='" . $idtemProveedor . "';";
                                            $result_location = pg_query($query_location) or die('La consulta fallo: ' . pg_last_error());
                                            $band2 = 0;
                                            $idtemLocalizacion = 0;
                                            while ($localizacion = pg_fetch_array($result_location)) {
                                                if ($band2 == 0) {
                                                    $band2 = 1;
                                                    $idtemLocalizacion = $localizacion["idlocation"];
                                                }
                                                echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
                                            }
                                        }

                                        if ($idtemProveedor == 2) {
                                            $query_location = "SELECT * FROM location where idproveedor='" . $idtemProveedor . "';";
                                            $result_location = pg_query($query_location) or die('La consulta fallo: ' . pg_last_error());
                                            $band2 = 0;
                                            $idtemLocalizacion = 0;
                                            while ($localizacion = pg_fetch_array($result_location)) {
                                                $sqlValidaSubscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $localizacion["idlocation"] . "';";
                                                $resultValidaSubscripcion = pg_query($sqlValidaSubscripcion) or die('La consulta fallo: ' . pg_last_error());
                                                if (pg_num_rows($resultValidaSubscripcion) > 0) {
                                                    if ($band2 == 0) {
                                                        $band2 = 1;
                                                        $idtemLocalizacion = $localizacion["idlocation"];
                                                    }
                                                    echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="error02" class="ConfiguracionElemento-validacion"></div>
                        </div>  


                        <div class="ConfiguracionElemento" id="contenedordelared">
                            <div class="ConfiguracionElemento-titulo">
                                Red Virtual
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <div id="actualizaRed">
                                    <select class="selectConfiguracion" id="selred" name="selred">
                                        <?php
                                        $query_red = "SELECT * FROM red where idproveedor='" . $idtemProveedor . "' and idlocation='" . $idtemLocalizacion . "' and fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "';";
                                        $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
                                        while ($red = pg_fetch_array($result_red)) {
                                            echo "<option value='" . $red["idred"] . "'>" . $red["nombre"] . " " . $red["ipv4cidr"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="error03" class="ConfiguracionElemento-validacion"></div>
                        </div>                         

                        <div class="ConfiguracionElemento">
                            <div class="ConfiguracionElemento-titulo">
                                Nombre Grupo
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input onblur="validaNombreGrupo()"  id="nombregrupo" name="nombregrupo" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="error04" class="ConfiguracionElemento-validacion"></div>
                        </div> 
                    </div>
                    <div onclick='abrirAgregar("Inbound")' class="tituloAgregaGRUPO">
                        <div style="float: left; width: 150px;">02. Reglas de Entrada</div> <div class="botonAgregar">Agregar Regla</div>
                    </div> 
                    <div class="tablagrupo" id="tablainbound">
                        <div class="lineatablagrupo">
                            <div class="columagrupo0" style="width: 70px;">Prioridad</div>
                            <div class="columagrupo0" style="width: 250px;">Nombre</div>
                            <div class="columagrupo0" style="width: 150px;">Origen</div>
                            <div class="columagrupo0" style="width: 150px;">Destiono</div>
                            <div class="columagrupo0" style="width: 70px;">Puerto</div>
                            <div class="columagrupo0" style="width: 70px;">Accion</div>                    
                        </div>                
                    </div>

                    <div onclick='abrirAgregar("Outbound")' class="tituloAgregaGRUPO">
                        <div style="float: left; width: 150px;">03. Reglas de Salida</div> <div class="botonAgregar">Agregar Regla</div>
                    </div>             

                    <div class="tablagrupo" id="tablaoutbound">
                        <div class="lineatablagrupo">
                            <div class="columagrupo0" style="width: 70px;">Prioridad</div>
                            <div class="columagrupo0" style="width: 250px;">Nombre</div>
                            <div class="columagrupo0" style="width: 150px;">Origen</div>
                            <div class="columagrupo0" style="width: 150px;">Destiono</div>
                            <div class="columagrupo0" style="width: 70px;">Puerto</div>
                            <div class="columagrupo0" style="width: 70px;">Accion</div>                    
                        </div>                
                    </div>




                    <div class="contieneAgregarRegla" id="contieneAgregarREGLA">
                        <div class="tituloAgregar">Agregar Regla al Grupo de Seguridad</div>                
                        <input type="hidden" name="idservidoragrega" id="idservidoragrega" value="">

                        <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Tipo de Regla
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input readonly="readonly" id="tiporegla" name="tiporegla" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="errorEntrada01" class="ConfiguracionElemento-validacion"></div>
                        </div>  

                        <div id="contenedorPrioridad" class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Prioridad [ Número entero que no puede estar repetido ]
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input onblur="validaPrioridad()" id="prioridadregla" name="prioridadregla" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="errorEntrada02" class="ConfiguracionElemento-validacion"></div>
                        </div>  

                        <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Nombre
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input onblur="validaNombre()" id="nombreregla" name="nombreregla" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="errorEntrada03" class="ConfiguracionElemento-validacion"></div>
                        </div>

                        <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Protocolo
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <div id="actualizaLocalizacion">
                                    <select class="selectConfiguracion" id="protocoloregla" name="protocoloregla">
                                        <option id="TCP">TCP</option>
                                        <option id="UDP">UDP</option>
                                    </select>
                                </div>
                            </div>
                            <div id="errorEntrada04" class="ConfiguracionElemento-validacion"></div>
                        </div>

                        <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Puerto
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input onblur="validaPuerto()" id="puertoregla" name="puertoregla" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="errorEntrada05" class="ConfiguracionElemento-validacion"></div>
                        </div> 

                        <div id="contenedorSeguridad" class="ConfiguracionElemento" style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Tipo de Seguridad
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <div id="actualizaLocalizacion">
                                    <select class="selectConfiguracion" id="seguridadregla" name="seguridadregla">
                                        <option id="Allow">Allow</option>
                                        <option id="Deny">Deny</option>
                                    </select>
                                </div>
                            </div>
                            <div id="errorEntrada06" class="ConfiguracionElemento-validacion"></div>
                        </div>

                        <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                            <div class="ConfiguracionElemento-titulo">
                                CIDR
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input onblur="validaCIDR()" id="cidrregla" name="cidrregla" value="*" type="text" class="entradaConfiguracion"/>
                            </div>
                            <div id="errorEntrada07" class="ConfiguracionElemento-validacion"></div>
                        </div> 

                        <div onclick=agregarRegla() class="agregarBoton" id="botonAgregar" >Agregar Regla a la definición del Grupo</div>
                        <div onclick=cerrarAgregar() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>

                    </div> 
                    <div style="width: 700px; float: left">
                        <div onclick="creargrupo()" class="botoncrear">Crear Grupo</div>
                    </div>
                </form>
            </div>
        </div>



        <script type="text/javascript">
            $(document).ready(function () {
                var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#seccion02").height(($(document).height() - 41));
                $("#seccion03").height((h - 170));
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

            function actualizaLoc() {
                var actualprov = $("#selproveedor").val();
                $.ajax({
                    data: {accion: "7", idproveedor: actualprov},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#actualizaLocalizacion").html(response);
                        cambiaLocalizacion();
                    }
                });

                if (actualprov == 2 || actualprov == 3) {
                    $("#contenedordelared").hide();
                } else if (actualprov == 1) {
                    $("#contenedordelared").show();
                }

                $("#concatenado").val("");
                $("#tablainbound").html("<div class='lineatablagrupo'><div class='columagrupo0' style='width: 70px;'>Prioridad</div><div class='columagrupo0' style='width: 250px;'>Nombre</div><div class='columagrupo0' style='width: 150px;'>Origen</div><div class='columagrupo0' style='width: 150px;'>Destiono</div><div class='columagrupo0' style='width: 70px;'>Puerto</div><div class='columagrupo0' style='width: 70px;'>Accion</div></div>");
                $("#tablaoutbound").html("<div class='lineatablagrupo'><div class='columagrupo0' style='width: 70px;'>Prioridad</div><div class='columagrupo0' style='width: 250px;'>Nombre</div><div class='columagrupo0' style='width: 150px;'>Origen</div><div class='columagrupo0' style='width: 150px;'>Destiono</div><div class='columagrupo0' style='width: 70px;'>Puerto</div><div class='columagrupo0' style='width: 70px;'>Accion</div></div>");
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
            
            function cambiaProveedor() {
                alert("cambio el proveedor.");
            }

            function cambiaLocalizacion() {
                //alert("cambio la localizacion");
                var actualloca = $("#sellocalizacion").val();
                $.ajax({
                    data: {accion: "17", idlocalizacion: actualloca},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#actualizaRed").html(response);
                    }
                });
            }

            function validaPrioridad() {
                expr = /^[0-9]+$/;
                if ($("#prioridadregla").val() != "") {
                    if (!expr.test($("#prioridadregla").val())) {
                        $("#errorEntrada02").html("Solo números.");
                    } else {
                        $("#errorEntrada02").html(" ");
                    }
                } else {
                    $("#errorEntrada02").html("La prioridad es obligatorio.");
                }
            }

            function validaNombreGrupo() {
                expr = /^[a-z0-9]+$/;
                if ($("#nombregrupo").val() != "") {
                    if (!expr.test($("#nombregrupo").val())) {
                        $("#error04").html("Solo números y letras en minuscula.");
                    } else {
                        $("#error04").html(" ");
                        var longitud = $("#nombregrupo").val();
                        if (longitud.length < 7 || longitud.length > 20) {
                            $("#error04").html("El nombre del grupo debe tener como minimo 7 caracteres y como maximo 20.");
                        }
                    }
                } else {
                    $("#error04").html("El nombre del grupo es obligatorio.");
                }
            }

            function validaNombre() {
                if ($("#nombreregla").val() != "") {
                    expr2 = /^[a-z0-9]+$/;
                    if (!expr2.test($("#nombreregla").val())) {
                        $("#errorEntrada03").html("Solo números y letras en minuscula.");
                    } else {
                        $("#errorEntrada03").html(" ");
                    }
                } else {
                    $("#errorEntrada03").html("El nombre de la regla es obligatorio.");
                }
            }

            function validaPuerto() {
                expr = /^[0-9]+$/;
                if ($("#puertoregla").val() != "") {
                    if (!expr.test($("#puertoregla").val())) {
                        $("#errorEntrada05").html("Solo números.");
                    } else {
                        $("#errorEntrada05").html(" ");
                    }
                } else {
                    $("#errorEntrada05").html("El número de puerto es obligatorio.");
                }
            }

            function validaCIDR() {
                if ($("#selproveedor").val() == 2) {
                    if ($("#cidrregla").val() != "") {
                        if ($("#cidrregla").val() == "*") {
                            $("#errorEntrada07").html(" ");
                        } else {
                            var aux01 = $("#cidrregla").val().split("/");

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
                                $("#errorEntrada07").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                            } else {
                                $("#errorEntrada07").html(" ");
                            }
                        }
                    } else {
                        $("#errorEntrada07").html("El Bloque CIDR es obligatorio.");
                    }
                } else if ($("#selproveedor").val() == 1) {
                    if ($("#cidrregla").val() != "") {
                        var aux01 = $("#cidrregla").val().split("/");

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
                            $("#errorEntrada07").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                        } else {
                            $("#errorEntrada07").html(" ");
                        }
                    } else {
                        $("#errorEntrada07").html("El Bloque CIDR es obligatorio.");
                    }
                }
            }


            function agregarRegla() {
                expr = /^[0-9]+$/;
                expr2 = /^[a-z0-9]+$/;
                exprPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,12}$/;
                var bandCreacion = 0;



                if ($("#selproveedor").val() == 2) {
                    if ($("#prioridadregla").val() != "") {
                        if (!expr.test($("#prioridadregla").val())) {
                            $("#errorEntrada02").html("Solo números, y debe ser unica dentro del grupo de seguridad.");
                            bandCreacion = 1;
                        } else {
                            $("#errorEntrada02").html(" ");
                        }
                    } else {
                        $("#errorEntrada02").html("La prioridad es obligatoria.");
                        bandCreacion = 1;
                    }
                }

                if ($("#nombreregla").val() != "") {
                    if (!expr2.test($("#nombreregla").val())) {
                        $("#errorEntrada03").html("Solo números y letras en minuscula.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada03").html(" ");
                    }
                } else {
                    $("#errorEntrada03").html("El nombre de la regla es obligatorio.");
                    bandCreacion = 1;
                }

                if ($("#selproveedor").val() == 2) {
                    if ($("#prioridadregla").val() != "") {
                        if (!expr.test($("#prioridadregla").val())) {
                            $("#errorEntrada02").html("Solo números.");
                            bandCreacion = 1;
                        } else {
                            $("#errorEntrada02").html(" ");
                        }
                    } else {
                        $("#errorEntrada02").html("La prioridad es obligatorio.");
                        bandCreacion = 1;
                    }
                }

                if ($("#puertoregla").val() != "") {
                    if (!expr.test($("#puertoregla").val())) {
                        $("#errorEntrada05").html("Solo números.");
                        bandCreacion = 1;
                    } else {
                        $("#errorEntrada05").html(" ");
                    }
                } else {
                    $("#errorEntrada05").html("El número de puerto es obligatorio.");
                    bandCreacion = 1;
                }

                if ($("#selproveedor").val() == 2) {
                    if ($("#cidrregla").val() != "") {
                        if ($("#cidrregla").val() == "*") {
                            $("#errorEntrada07").html(" ");
                        } else {
                            var aux01 = $("#cidrregla").val().split("/");
                            var aux02 = aux01[0].split(".");
                            var bandera = 0;
                            if (aux02.length == 4) {
                                for (var i = 0; i < 4; i++) {
                                    if (parseInt(aux02[i]) > 254 || parseInt(aux02[i]) < 0) {
                                        bandera = 1;
                                    }
                                }
                            } else {
                                bandera = 1;
                            }

                            if (parseInt(aux01[1]) <= 16 || parseInt(aux01[1]) >= 28) {
                                bandera = 1;
                            }
                            if (bandera == 1) {
                                $("#errorEntrada07").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                            } else {
                                $("#errorEntrada07").html(" ");
                            }
                        }
                    } else {
                        $("#errorEntrada07").html("El Bloque CIDR es obligatorio.");
                        bandCreacion = 1;
                    }
                } else if ($("#selproveedor").val() == 1) {
                    if ($("#cidrregla").val() != "") {
                        var aux01 = $("#cidrregla").val().split("/");

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
                            $("#errorEntrada07").html("El Bloque CIDR no cumple con el formato, ejemplo 10.0.0.0/24 la mascara debe ser >16");
                            bandCreacion = 1;
                        } else {
                            $("#errorEntrada07").html(" ");
                        }
                    } else {
                        $("#errorEntrada07").html("El Bloque CIDR es obligatorio.");
                        bandCreacion = 1;
                    }
                }

                if (bandCreacion == 0) {
                    var Token = $("#concatenado").val();

                    if ($("#tiporegla").val() == "Inbound") {
                        if (Token == "") {
                            Token = $("#nombreregla").val() + "_" + $("#protocoloregla").val() + "_" + $("#puertoregla").val() + "_" + $("#cidrregla").val() + "_*_" + $("#seguridadregla").val() + "_" + $("#prioridadregla").val() + "_Inbound";
                        } else {
                            Token = Token + "__" + $("#nombreregla").val() + "_" + $("#protocoloregla").val() + "_" + $("#puertoregla").val() + "_" + $("#cidrregla").val() + "_*_" + $("#seguridadregla").val() + "_" + $("#prioridadregla").val() + "_Inbound";
                        }
                        $("#tablainbound").append("<div class='lineatablagrupo'><div class='columagrupo' style='width: 70px;'>" + $("#prioridadregla").val() + "</div><div class='columagrupo' style='width: 250px;'>" + $("#nombreregla").val() + "</div><div class='columagrupo' style='width: 150px'>" + $("#cidrregla").val() + "</div><div class='columagrupo' style='width: 150px;'>Any</div><div class='columagrupo' style='width: 70px;'>" + $("#puertoregla").val() + " " + $("#protocoloregla").val() + "</div><div class='columagrupo' style='width: 70px;'>" + $("#seguridadregla").val() + "</div></div>");
                    } else {
                        if (Token == "") {
                            Token = $("#nombreregla").val() + "_" + $("#protocoloregla").val() + "_" + $("#puertoregla").val() + "_*_" + $("#cidrregla").val() + "_" + $("#seguridadregla").val() + "_" + $("#prioridadregla").val() + "_Outbound";
                        } else {
                            Token = Token + "__" + $("#nombreregla").val() + "_" + $("#protocoloregla").val() + "_" + $("#puertoregla").val() + "_" + $("#cidrregla").val() + "_*_" + $("#seguridadregla").val() + "_" + $("#prioridadregla").val() + "_Outbound";
                        }
                        $("#tablaoutbound").append("<div class='lineatablagrupo'><div class='columagrupo' style='width: 70px;'>" + $("#prioridadregla").val() + "</div><div class='columagrupo' style='width: 250px;'>" + $("#nombreregla").val() + "</div><div class='columagrupo' style='width: 150px'>" + $("#cidrregla").val() + "</div><div class='columagrupo' style='width: 150px;'>Any</div><div class='columagrupo' style='width: 70px;'>" + $("#puertoregla").val() + " " + $("#protocoloregla").val() + "</div><div class='columagrupo' style='width: 70px;'>" + $("#seguridadregla").val() + "</div></div>");
                    }

                    $("#concatenado").val(Token);

                    $("#prioridadregla").val("");
                    $("#nombreregla").val("");
                    $("#puertoregla").val("");
                    $("#cidrregla").val("*");
                    $("#protocoloregla").val("TCP");
                    $("#seguridadregla").val("Allow");
                    cerrarAgregar();



                }
            }

            function creargrupo() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
                var bandCreacion = 0;

                if (typeof $("#sellocalizacion").val() == "undefined" || $("#sellocalizacion").val() == null) {
                    $("#error02").html("Es necesario seleccionar una localizacíon.");
                    bandCreacion = 1;
                } else {
                    $("#error02").html(" ");
                }

                if ($("#selproveedor").val() == "1") {
                    if (typeof $("#selred").val() == "undefined" || $("#selred").val() == null) {
                        $("#error03").html("Es necesario seleccionar una red virtual.");
                        bandCreacion = 1;
                    } else {
                        $("#error03").html(" ");
                    }
                }

                if ($("#nombregrupo").val() != "") {
                    if (!expr.test($("#nombregrupo").val())) {
                        $("#error04").html("Solo números y letras en minuscula.");
                        bandCreacion = 1;
                    } else {
                        $("#error04").html(" ");
                        var longitud = $("#nombregrupo").val();
                        if (longitud.length < 7 || longitud.length > 20) {
                            bandCreacion = 1;
                            $("#error04").html("El nombre del grupo debe tener como minimo 7 caracteres y como maximo 20.");
                        }
                    }
                } else {
                    $("#error04").html("El nombre del grupo es obligatorio.");
                    bandCreacion = 1;
                }

                if ($("#concatenado").val() == "") {
                    bandCreacion = 1;
                    alert("El grupo debe contar como minimo con una regla de entrada o salida.");
                }
                if (bandCreacion == 0) {
                    document.getElementById("crearGrupoSeguridad").submit();
                }

            }

            function abrirAgregar(tipo) {
                if ($("#selproveedor").val() == 1 && tipo == "Outbound") {
                    alert("Por el momento no esta disponible la creación de reglas de salida para el proveedor Amazon.");
                } else {
                    if ($("#selproveedor").val() == 1) {
                        $("#contenedorPrioridad").hide();
                        $("#contenedorSeguridad").hide();
                        $("#prioridadregla").val("-");
                        $("#cidrregla").val("0.0.0.0/0");

                    } else {
                        $("#contenedorPrioridad").show();
                        $("#contenedorSeguridad").show();
                        $("#prioridadregla").val("");
                        $("#cidrregla").val("*");
                    }
                    $("#tiporegla").val(tipo);
                    $("#contieneAgregarREGLA").show();
                }
            }
            function cerrarAgregar() {
                $("#contieneAgregarREGLA").hide();
            }

        </script>        

    </body>
</html>
