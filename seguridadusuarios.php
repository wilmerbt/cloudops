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
            <div class="subtitulo_aplicacion">Usuarios</div>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/ikconusuariosColor.png" alt="Smiley face" height="70" width="70"></div>
                <div class="titulo_hoja">Usuarios</div>
                <div class="subtitulo_hoja">Listado de Usuarios</div>
            </div>
        </div>


        <div id="seccion03" class="seccion03">



            <!--Tabla Usuarios-->



            <div class="opcioneslista" id="opcioneslista">
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/addicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick='$("#agregarUsuarioDisp").show();' class="opcionText">Agregar Nuevo</div>
                </div>  
                <div class="opcionlista">
                    <div class="opcionIcon"><img src="recursos/imagenes/refreshicon.png" alt="Smiley face" height="25" width="25"></div>
                    <div onclick=actualizaTabla() class="opcionText">Actualizar</div>
                </div>                
            </div>
            <input type="hidden" name="ordenado_por" id="ordenado_por" value="apellido">
            <input type="hidden" name="enorden" id="enorden" value="asc">

            <!--Solicitud Usuarios-->
            <?php
            $sqlUsuarios = "select usuario.idorganizacion, usuario.idusuario, usuario.apellido as apellido, usuario.nombre"
                    . " as nombre, usuario.correo as correo, organizacion.nombre as organizacion, usuario.superusuario AS superusuario"
                    . " from usuario, organizacion where usuario.idorganizacion = organizacion.idorganizacion"
                    . " and usuario.fecha_eliminacion IS NULL"
                    . " order by apellido asc;";
            $resultUsuarios = pg_query($sqlUsuarios) or die('La consulta fallo: ' . pg_last_error());
            ?>

            <div class="NumElementosPerfiles"><?php echo pg_num_rows($resultUsuarios); ?> Elementos</div>

            <!--Display de agregar usuario-->
            <div class="contieneAgregarUsuario" id="agregarUsuarioDisp">
                <div class="tituloAgregar">Agregar Usuario</div>
                <form id="agregarUsuario" name="agregarUsuario" method="post" action="recursos/php/acciones.php?accion=agregarUsuario">

                    <div class="ConfiguracionElemento" style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Organización
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <select class="selectConfiguracion" id="selectedOrg" name="selectedOrg">
                                <?php
                                $query_organizacion = "SELECT * FROM organizacion order by nombre asc;";
                                $result_organizacion = pg_query($query_organizacion) or die('La consulta fallo: ' . pg_last_error());
                                $band = 0;
                                $idtemOrganizacion = 0;
                                while ($organizacion = pg_fetch_array($result_organizacion)) {
                                    if ($band == 0) {
                                        $idtemOrganizacion = $organizacion["idorganizacion"];
                                        $band = 1;
                                    }
                                    echo "<option value='" . $organizacion["idorganizacion"] . "'>" . $organizacion["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!--<div id="errorEntrada001" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="nombreUsuario" name="nombreUsuario" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                    </div>  

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Apellido
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="apellidoUsuario" name="apellidoUsuario" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada004" class="ConfiguracionElemento-validacion"></div>-->
                    </div>   

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Correo
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="correoUsuario" name="correoUsuario" type="text" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada004" class="ConfiguracionElemento-validacion"></div>-->
                    </div> 

                    <div class="ConfiguracionElemento"  style="margin-bottom: 15px;">
                        <div class="ConfiguracionElemento-titulo">
                            Contraseña
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input id="passUsuario" name="passUsuario" type="password" class="entradaConfiguracion"/>
                        </div>
                        <!--<div id="errorEntrada004" class="ConfiguracionElemento-validacion"></div>-->
                    </div>

                    <div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>

                        <div class='ConfiguracionElemento-titulo'>
                            Perfiles
                        </div>
                        <div class='ConfiguracionElemento checkboxScroll'  style='margin-bottom: 5px;margin-top: 5px'>

                            <?php
                            $query_perfiles = "SELECT * FROM perfil WHERE fecha_eliminacion IS NULL order by nombre asc;";
                            $result_perfiles = pg_query($query_perfiles) or die('La consulta fallo: ' . pg_last_error());
                            $idtemPerfil = 0;
                            while ($perfil = pg_fetch_array($result_perfiles)) {

                                echo "<div>";
                                echo "<input type='checkbox' id='check" . $perfil["nombre"] . "' name='checkPerfiles[]' value='" . $perfil["idperfil"] . "'>";
                                echo "<label class=elementoCheckUsuario for='check" . $perfil["nombre"] . "'>" . $perfil["nombre"] . "</label>";
                                echo "</div>";
                            }
                            ?>

                        </div>

                    </div>  

                    <div class="ConfiguracionElemento"  style="margin-bottom: 20px;">
                        <fieldset>
                            <legend style="font-family: 'Titillium Web', sans-serif; font-size: 12px">Super Usuario</legend>
                            <div>
                                <input type='checkbox' id='checkSuperUsuario' name='checkSuperUsuario' value='1'>
                                <label class=elementoCheckUsuario for='checkSuperUsuario'>Permite la administración de usuarios y perfiles</label>
                            </div>
                        </fieldset>
                    </div>                    

                    <div onclick=agregar() class="agregarBoton" id="botonAgregar" >Agregar Usuario</div>
                    <!--<input type="submit" class="agregarBoton" id="botonAgregar" >-->
                    <div onclick=cerrarAgregar2() class="cerrarAgregar" id="cerrarAgregar">[CERRAR]</div>
                </form>
            </div>            

            <!--Fin display agregar-->



            <div class="contenedorTABLA" id="contenedorTABLA">

                <div class="cabeceraTabla" >
                    <div class="eleCabTabla" style="width: 8%">Acciones</div>
                    <div onclick=ordena("apellido") class="eleCabTabla" style="width: 12%">Apellido<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("nombre") class="eleCabTabla" style="width: 10%">Nombre<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("correo") class="eleCabTabla" style="width: 18%">Correo<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div onclick=ordena("organizacion") class="eleCabTabla" style="width: 18%">Organización<img style="margin-left: 3px; margin-top: 5px;" src="recursos/imagenes/downicon.png" alt="Smiley face" height="17" width="17"></div>
                    <div class="eleCabTabla" style="width: 13%">Perfiles</div>
                </div>
                <div id="cuerpotabla" class="cuerpotabla">
                    <?php
                    $sqlSubRedes = "select subred.idsubred as idsubred, proveedor.nombre as proveedor, location.nombre as localizacion, red.nombre as nombrered, subred.nombre as nombresubred, subred.idreal as idreal, subred.ipv4cidr as cidr from proveedor, location, red, subred where proveedor.idproveedor = red.idproveedor and subred.idred = red.idred and red.idlocation = location.idlocation and subred.fecha_eliminacion is null order by nombresubred asc;";
                    $resultSubRedes = pg_query($sqlSubRedes) or die('La consulta fallo: ' . pg_last_error());

                    while ($Usuario = pg_fetch_array($resultUsuarios)) {
                        echo "<div class='lineaTabla'>";
                        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Usuario["idusuario"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Usuario["idusuario"] . ") onmouseout=ocultarAcciones(" . $Usuario["idusuario"] . ") class='panelOpciones' id='panelOpciones-" . $Usuario["idusuario"] . "'>";
                        echo "<div class='panelOPC' onclick=editar(" . $Usuario["idusuario"] . ")>Editar</div>";
                        echo "<div class='panelOPC' onclick=eliminar(" . $Usuario["idusuario"] . ")>Eliminar</div>";
                        echo "</div></div>";
                        echo "<div class='eleLinTabla' style='width: 12%' title='" . $Usuario["apellido"] . "'>" . $Usuario["apellido"] . "</div>";
                        echo "<div class='eleLinTabla' style='width: 10%' title='" . $Usuario["nombre"] . "'>" . $Usuario["nombre"] . "</div>";
                        echo "<div class='eleLinTabla' style='width: 18%' title='" . $Usuario["correo"] . "'>" . $Usuario["correo"] . "</div>";

                        //Organizacion
                        $sqlOrganizacion = "select nombre from organizacion where organizacion.idorganizacion = " . $Usuario["idorganizacion"];
                        $resultOrganizacion = pg_query($sqlOrganizacion) or die('La consulta fallo: ' . pg_last_error());
                        echo "<div class='eleLinTabla' style='width: 15%' title='" . $Usuario["organizacion"] . "'>" . $Usuario["organizacion"] . "</div>";

                        echo "<div class='eleLinTabla' style='width: 2.5%' ></div>";

                        //Perfiles
                        $sqlPerfiles = "select usuarios_perfiles.idperfil, perfil.nombre as nombre from usuarios_perfiles, perfil "
                                . "where usuarios_perfiles.idusuario = " . $Usuario["idusuario"] . " and perfil.idperfil = usuarios_perfiles.idperfil AND perfil.fecha_eliminacion IS NULL order by perfil.nombre asc;";
                        $resultPerfiles = pg_query($sqlPerfiles) or die('La consulta fallo: ' . pg_last_error());
                        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarPerfiles(" . $Usuario["idusuario"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/icon_trespuntos.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarPerfiles(" . $Usuario["idusuario"] . ") onmouseout=ocultarPerfiles(" . $Usuario["idusuario"] . ") class='panelOpciones' id='panelPerfiles-" . $Usuario["idusuario"] . "'>";
                        while ($Perfil = pg_fetch_array($resultPerfiles)) {
                            echo "<div class='panelOPCPerfiles')>" . $Perfil["nombre"] . "</div>";
                        }
                        echo "</div></div>";

                        echo "</div>";
                    }
                    ?>
                </div>

            </div>


            <!--Display de editar usuario-->
            <div class="contieneAgregarUsuario" id="editarUsuarioDisp" name="editarUsuarioDisp">



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
                    data: {accion: "51", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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
                    data: {accion: "51", ordenado_por: $("#ordenado_por").val(), orden: $("#enorden").val()},
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

                $('#editarUsuarioDisp').show();

                $.ajax({
                    data: {accion: "50", idusuario: id},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#editarUsuarioDisp").html(response);
                    }
                });

            }

            function eliminar(id) {

                location.href = "recursos/php/acciones.php?accion=eliminarUsuario&id=" + id;

            }

            function cerrarAgregar() {
                $("#agregarUsuarioDisp").hide();
            }

            function cerrarAgregar2() {
                $("#agregarUsuarioDisp").hide();
            }

            function agregar() {

                document.getElementById("agregarUsuario").submit();

            }

        </script>        
    </body>
</html>
<?php
pg_close($conexion);
?>