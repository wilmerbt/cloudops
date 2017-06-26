<html>
    <head>
        <title>Prototipo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="recursos/css/estilobase.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="recursos/js/jquery-3.1.0.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    </head>
    <body>
        <div id="seccion01" class="seccion01">
            <div class="titulo_aplicacion" >IkusiCloud</div>
            <div class="subtitulo_aplicacion">Tablero Principal</div>
            <div class="contenedor_usuario">
                <div class="avatar"><img src="recursos/imagenes/avatar.png" alt="Smiley face" height="38" width="38"></div>
                <div class="contenedor_linea01">Lakhsmi Angarita</div>
                <div class="contenedor_linea02">lakhsmi.angarita@jcglobalresources.net</div>
            </div>
        </div>        
        
        <div id="seccion02" class="seccion02">
            <div class="opcion_menu">
                <div class="opcion_menu_icono"></div>
            </div>            
            <div class="opcion_menu">
                <div class="opcion_menu_icono"><img src="recursos/imagenes/icon-dashboard.png" alt="Smiley face" height="28" width="28"></div>
                <div class="opcion_menu_texto">Tablero Principal</div>
            </div>
            <div class="opcion_menu">
                <div class="opcion_menu_icono"><img src="recursos/imagenes/icon-machine.png" alt="Smiley face" height="28" width="28"></div>
                <div class="opcion_menu_texto">Maquinas Virtuales</div>
            </div>            
        </div> 
        <div id="seccion04" class="seccion04">
            
            <div style="width: 400px; height: 40px; float: left;">
                <div class="logotipoMediano"><img src="recursos/imagenes/icon-dashboard.png" alt="Smiley face" height="40" width="40"></div>
                <div class="titulo_hoja">Tablero Principal</div>
                <div class="subtitulo_hoja">Resumen de la infraestructura y el consumo de recursos</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            
        </div>
        
        
        
        <script type="text/javascript">
            $(document).ready(function() {                
                var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                $("#seccion02").height(($(document).height()-41));
                $("#seccion03").height((h-41));
                $("#seccion03").width((w-226));
                $("#seccion04").width((w-226));
                $(window).resize(function(e) {  
                    var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                    var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                    $("#seccion02").height((h-41));
                    $("#seccion03").height((h-41));
                    $("#seccion03").width((w-226));
                    $("#seccion04").width((w-226));
                });                 
            });                         
        </script>        
        
    </body>
</html>
