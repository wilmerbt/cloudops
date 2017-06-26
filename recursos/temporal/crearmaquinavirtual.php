<html>
    <head>
        <title>Prototipo</title>
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
                <div class="logotipoMediano"><img src="recursos/imagenes/icon-machine.png" alt="Smiley face" height="40" width="40"></div>
                <div class="titulo_hoja">Crear Maquina Virtual</div>
                <div class="subtitulo_hoja">Resumen de la infraestructura y el consumo de recursos</div>
            </div>
        </div>
        <div id="seccion03" class="seccion03">
            <div style="float: left; width: 300px; height: auto;">
                <div class="contenedorProveedor">
                    <div class="conPro-Cabecera">
                        <div class="conPro-Cabecera-Paso">01</div>
                        <div class="conPro-Cabecera-titulo">Proveedor</div>
                        <div class="conPro-Cabecera-subtitulo">Seleccione el Proveedor que mejor se adapte a sus necesidades</div>
                    </div>
                    <div onclick=seleccionaProveedor() class="conPro-proveedor2" title="Amazon Web Services"><img src="recursos/imagenes/amazon-noseleccionado.png" alt="Smiley face" height="87" width="174"></div>
                    <div onclick=seleccionaProveedor() class="conPro-proveedor" title="Windows Azure"><img src="recursos/imagenes/azure-noseleccionado.png" alt="Smiley face" height="87" width="87"></div>
                    <div onclick=seleccionaProveedor() class="conPro-proveedor2" title="vmWARE"><img src="recursos/imagenes/vmwarenoseleccionado.png" alt="Smiley face" height="87" width="174"></div>
                </div>                        
                <div class="contenedorOS" id="contenedorOS" style="visibility: hidden">
                    <div class="conPro-Cabecera">
                        <div class="conPro-Cabecera-Paso">02</div>
                        <div class="conPro-Cabecera-titulo">Sistema Operativo</div>
                        <div class="conPro-Cabecera-subtitulo">Seleccione el Sistema Operativo se adapte a sus necesidades</div>
                    </div>
                    
                    <div onclick=seleccionaOS() class="conPro-os"><img src="recursos/imagenes/ubuntu.png" alt="Ubuntu Server" height="87" width="87"></div>                                                               
                    <div onclick=seleccionaOS() class="conPro-os"><img src="recursos/imagenes/suse.png" alt="Red Hat Server" height="87" width="87"></div>
                    <div onclick=seleccionaOS() class="conPro-os"><img src="recursos/imagenes/redhat.png" alt="Red Hat Server" height="87" width="87"></div>
                    <div onclick=seleccionaOS() class="conPro-os"><img src="recursos/imagenes/azure-noseleccionado.png" alt="Windows Server" height="87" width="87"></div>
                </div>
            </div>
            
            
            <div id="contenedorVersionOS" style="float: left; width: 300px; height: auto; margin-left: 15px; visibility: hidden">
                <div class="contenedorProveedor" style="padding-bottom: 10px;">
                    <div class="conPro-Cabecera">
                        <div class="conPro-Cabecera-Paso">03</div>
                        <div class="conPro-Cabecera-titulo">Versión del Sistema Operativo</div>
                        <div class="conPro-Cabecera-subtitulo">Seleccione la Version del Sistema Operativo que mejor se adapte a sus necesidades</div>
                    </div>
                    <div onclick=seleccionaVersionOS() class="conOS-versionOS">
                        <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                        <div class="conOS-Titulo">Red Hat Enterprise Linux 6.7</div>
                        <div class="conOS-SubTitulo">Red Hat</div>
                    </div>
                    <div onclick=seleccionaVersionOS() class="conOS-versionOS">
                        <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                        <div class="conOS-Titulo">Red Hat Enterprise Linux 6.8</div>
                        <div class="conOS-SubTitulo">Red Hat</div>
                    </div> 
                    <div onclick=seleccionaVersionOS() class="conOS-versionOS">
                        <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                        <div class="conOS-Titulo">Red Hat Enterprise Linux 7.2</div>
                        <div class="conOS-SubTitulo">Red Hat</div>
                    </div>
                    <div onclick=seleccionaVersionOS() class="conOS-versionOS">
                        <div class="conOS-icon"><img src="recursos/imagenes/redhat.png" alt="Windows Server" height="50" width="50"></div>
                        <div class="conOS-Titulo">Red Hat Enterprise Linux 7.3</div>
                        <div class="conOS-SubTitulo">Red Hat</div>
                    </div>                     
                </div>                        
            </div>   
            
            
            <div id="contenedorConfiguracion" style="float: left; width: 400px; height: auto; margin-left: 15px; visibility: hidden">
                <div class="contenedorConfiguracion" style="padding-bottom: 10px;">
                    <div class="conCon-Cabecera">
                        <div class="conPro-Cabecera-Paso">04</div>
                        <div class="conCan-Cabecera-titulo">Configuración del Servidor</div>
                        <div class="conCan-Cabecera-subtitulo">Establezca las configuraciones basicas para el aprovisionamiento de la maquina virtual según sus necesidades.</div>
                    </div>
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Nombre Maquina Virtual
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input type="text" class="entradaConfiguracion"/>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div> 
                    
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Usuario
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input type="text" class="entradaConfiguracion"/>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div>     
                    
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Contraseña
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input type="password" class="entradaConfiguracion"/>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div> 
                    
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Confirmación de la Contraseña
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                            <input type="password" class="entradaConfiguracion"/>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div>    
                    
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Flavor
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                        <select class="selectConfiguracion">
                            <option value="1">Pequeño 1 Core  3 GB</option>
                            <option value="2">Mediano 2 Core  7 GB</option>
                            <option value="3">Grande  4 Core 14 GB</option>
                        </select>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div> 
                    
                    <div class="ConfiguracionElemento">
                        <div class="ConfiguracionElemento-titulo">
                            Localizacion
                        </div>
                        <div class="ConfiguracionElemento-contenedor">
                        <select class="selectConfiguracion">
                            <option value="1">East US</option>
                            <option value="2">East US 2</option>
                            <option value="3">Japan East</option>
                        </select>
                        </div>
                        <div class="ConfiguracionElemento-validacion">Deben ser solo numeros</div>
                    </div>                    
                </div>
                <div class="solicitarCreacion" style="padding-bottom: 10px;">
                    Crear Maquina Virtual
                </div>
                              
            </div>            
                        
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
            
            function seleccionaProveedor(){
                $(".contenedorOS").css("visibility", "visible");
            }
            
            function seleccionaOS(){
                $("#contenedorVersionOS").css("visibility", "visible");
            }  
            
            function seleccionaVersionOS(){
                $("#contenedorConfiguracion").css("visibility", "visible");
            }              
        </script>        
        
    </body>
</html>
