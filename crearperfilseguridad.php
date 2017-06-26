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
        <title>Crear Perfil de Seguridad</title>
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
            <div class="subtitulo_aplicacion">Crear Perfil de Seguridad</div>
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
        
        <input type="hidden" id="accMarcadas" name="accMarcadas" value="">

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
                <div class="titulo_hoja">Crear Perfil de Seguridad</div>
                <div class="subtitulo_hoja">Formulario para la creación de perfiles de seguridad basados en acciones</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div style="float: left; border: 1px solid #CCC; height: auto; width: 820px; margin: 15px; padding: 15px; padding-top: 0px;">
                <form id="crearPerfSeg" name="crearPerfSeg" method="post" action="recursos/php/acciones.php?accion=crearPerfilSeguridad">
                    
                    <input type="hidden" name="concatenado" id="concatenado" value="" />
                    <div class="tituloAgregaGRUPO">
                        Nuevo Perfil de Seguridad para Usuarios
                    </div>
                    
                    <div class="formularioAgregaGRUPO">
                        
                         <div class="ConfiguracionElemento"  style="margin-bottom: 5px;">
                            <div class="ConfiguracionElemento-titulo">
                                Nombre del nuevo perfil
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <input id="nombrePerfil" name="nombrePerfil" type="text" class="entradaConfiguracion"/>
                            </div>
                            <!--<div id="errorEntrada003" class="ConfiguracionElemento-validacion"></div>-->
                        </div>  
                        
                        <div class="ConfiguracionElemento">
                            <div class="ConfiguracionElemento-titulo">
                                Seleccione un proveedor
                            </div>
                            <div class="ConfiguracionElemento-contenedor">
                                <select onchange='actualizaOpciones()' class="selectConfiguracion" id="selproveedor" name="selproveedor">
                                    <?php
                                    $query_proveedor = "SELECT * FROM proveedor order by nombre asc;";
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
                            <div id="error01" class="ConfiguracionElemento-validacion"></div>
                        </div> 

                        <div id="listaEntidades" class="ConfiguracionElemento"  style="margin-bottom: 10px; margin-top: 10px">
                            <fieldset>
                                <legend class=elementoCheckUsuario style='font-size:13px'>Entidades asociadas al proveedor</legend>

                                <?php
                                    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = ".$idtemProveedor." order by identidad asc;";
                                    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());
                                    //$idtemPerfil = 0;
                                    while ($entidad = pg_fetch_array($result_entidades)) {

                                        echo "<div>";
                                        echo "<input class='elementoCheckEntidad' type='checkbox' id='check".$entidad["nombre"]."' name='checkEntidades[]' value='".$entidad["identidad"]."'>";
                                        echo "<label class='elementoCheckUsuario' for='check".$entidad["nombre"]."'>".$entidad["nombre"]."</label>";
                                        echo "</div>";  

                                    }
                                ?>

                            </fieldset>
                        </div>
                        
                        <!--Generacion dinamica de acciones por entidad-->
                        
                        <div class="tituloAgregaGRUPO">
                        Acciones disponibles
                        </div>
                        
                        <!--Seccion de acciones-->
                        
                        <div class="ConfiguracionElemento" id="conjuntoAcciones">
                        
                        </div>
                        
                        <div style="width: 700px; float: left">
                            <div onclick="crearPerfil()" class="botoncrear">Crear Perfil</div>
                        </div>
                
                    </div>
                </form>
            </div>
        </div>




        <script type="text/javascript">
            $(document).ready(function () {
                actualizaAcciones();
                checkResponsivos();
                var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#seccion02").height(($(document).height() - 41));
                $("#seccion03").height((h - 130));
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
            
            function seleccionaAccion(idaccion){
                
                    var actuales = $("#accMarcadas").val();
                    //alert(actuales);
                    if(actuales == ""){
                        $("#accMarcadas").val(idaccion);
                    }else{
                        var seleccionados = actuales.split("_");
                        var bandera = 0;
                        for(var i = 0; i<seleccionados.length; i++){
                            if(seleccionados[i]==idaccion){
                                bandera=1;
                            }
                        }
                        
                        if(bandera==0){
                            actuales = actuales + "_" + idaccion;
                            $("#accMarcadas").val(actuales);
                        }else{
                            var newConcatena = "";
                            for(var i = 0; i<seleccionados.length; i++){
                                if(seleccionados[i]!=idaccion){
                                    if(newConcatena==""){
                                        newConcatena += seleccionados[i];
                                    } else {
                                        newConcatena += "_"+seleccionados[i];
                                    }
                                }
                            }
                            $("#accMarcadas").val(newConcatena);
                        }
                        
                    }
                    
            }
            
            function actualizaAcciones(){
                var actualprov = $("#selproveedor").val();
                //var marcadas = $("#accMarcadas").val();
                //alert("2 ");
                $.ajax({
                    data: {accion: "53", idproveedor: actualprov},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#conjuntoAcciones").html(response);
                        //checkResponsivos();
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
            
            function actualizaOpciones() {
                var actualprov = $("#selproveedor").val();
                
                $("#accMarcadas").val("");
                
                $.each($("input[name='checkEntidades[]']:checked"), function() {
                    $(this).removeAttr('checked');;
                  });
                
                $.ajax({
                    data: {accion: "52", idproveedor: actualprov},
                    url: './recursos/php/ajax.php',
                    type: 'post',
                    success: function (response) {
                        $("#listaEntidades").html(response);
                    }
                });
                  
                actualizaAcciones();
                
            }
            
            function checkResponsivos(){
                 //AJAX CHECKBOX ENTIDADES
                                                 
                        $('.elementoCheckEntidad').click(function() {
                            var marcadas = $("#accMarcadas").val();
                            //alert("1 marcadas:"+marcadas);
                            var actualprov = $("#selproveedor").val();

                                var entidades = [];
                                $.each($("input[name='checkEntidades[]']:checked"), function() {
                                  entidades.push($(this).val());

                                });

                                $.ajax({

                                    data: {accion: 53, selectedEntidades: entidades, idproveedor:actualprov, acciones:marcadas}, //--> send id of checked checkbox on other page
                                    url: './recursos/php/ajax.php',
                                    type: 'post',
                                    success: function(response) {
                                        $("#conjuntoAcciones").html(response);
                                    }
                                });

                          });
            }
            
            //Validaciones previas al enviar informacion

            function crearPerfil() {
                expr = /^[a-z0-9]+$/;
                expr2 = /^[0-9]+$/;
//                var bandCreacion = 0;
//
//                if (typeof $("#sellocalizacion").val() == "undefined" || $("#sellocalizacion").val() == null) {
//                    $("#error02").html("Es necesario seleccionar una localizacíon.");
//                    bandCreacion = 1;
//                }
//
//                if ($("#nombregrupo").val() != "") {
//                    if (!expr.test($("#nombregrupo").val())) {
//                        $("#error04").html("Solo números y letras en minuscula.");
//                        bandCreacion = 1;
//                    } else {
//                        $("#error04").html(" ");
//                    }
//                } else {
//                    $("#error04").html("El nombre del grupo es obligatorio.");
//                    bandCreacion = 1;
//                }

                    document.getElementById("crearPerfSeg").submit();

            }

        </script>        

    </body>
</html>

<?php
    pg_close($conexion);
?>
