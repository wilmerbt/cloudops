<?php session_start(); ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bienvenidos a CloudMarket</title>        
        <link href="../recursos/css/bienvenida.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">          
        <script type="text/javascript" src="../recursos/js/jquery-3.1.0.min.js"></script>
        
        <?php
            header('Content-Type: text/html; charset=UTF-8');        
            require_once("../recursos/php/funciones.php");
            $conexion=conexion();
            $idOrganizacion=1;
            
            $query_organizacion = "SELECT * FROM organizacion where idorganizacion='".$idOrganizacion."'";
            $result_organizacion = pg_query($query_organizacion) or die('La consulta fallo: ' . pg_last_error());                                
            $organizacion=pg_fetch_array($result_organizacion);            
            
        ?>        
        
    </head>
    <body>
        <div class="espacioIzquierdo">a</div>
        <div class="espacioCentral">
            <form id="loginUsuario" name="loginUsuario" method="post" action="../recursos/php/acciones.php?accion=loginUsuario&idorganizacion=<?php echo $organizacion["idorganizacion"]; ?>">
                <div class="contenedor" id="contiene">
                    <img id="logotipo" class="logo" title="<?php echo $organizacion["nombre"]; ?>" src="../recursos/imagenes/<?php echo $organizacion["logo"]; ?>" alt="Ikusi" height="<?php echo $organizacion["altologo"]; ?>" width="<?php echo $organizacion["anchologo"]; ?>">
                </div>
            
                <div class="contenedor">
                    <div class="contenedor-nombre">Correo Electronico</div>
                    <div class="contenedor-input">
                        <input type="text" name="correo" id="correo" class="entrada">
                    </div>
                    <div id="error01" class="contenedor-error"></div>
                </div>
                
                <div class="contenedor">
                    <div class="contenedor-nombre">Contraseña</div>
                    <div class="contenedor-input">
                        <input type="password" name="passw" id="passw" class="entrada">
                    </div>
                    <div id="error02" class="contenedor-error"></div>
                </div>
                
                <div class="contenedor">
                    <div class="boton" onclick="iniciarSesion()">Iniciar Sesión</div>
                </div>
            </form>
        </div>
        <div class="espacioDerecha">b</div>
        
        <script type="text/javascript">
            $(document).ready(function() { 
                var newMargin = parseInt(($("#contiene").width()-$("#logotipo").width())/2)+"px";
                $('#logotipo').css('margin-left', newMargin); 
                $("#logotipo").show();
            });
            
            function iniciarSesion(){
                var band=0;
                if($("#correo").val()==""){
                    $("#error01").html("Debe indicar su correo electronico.");
                    band=1;
                }else{
                    $("#error01").html("");
                }
                if($("#passw").val()==""){
                    band=1;
                    $("#error02").html("Debe indicar su contraseña.");
                }else{
                    $("#error02").html("");
                }                
                if(band==0){
                    document.getElementById("loginUsuario").submit();
                }                
            }
        </script>        
    </body>
</html>
