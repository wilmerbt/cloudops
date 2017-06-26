<?php
session_start();

header('Content-Type: text/html; charset=UTF-8');
require_once("funciones.php");
$conexion = conexion();

/* Selecciona el proveedor y recarga la pantalla de selección del tipo de sistema operativo */
if ($_POST["accion"] == 1) {
    echo "<div class='wizardMaquina-cabecera'>";
    echo "<div class='wizardMaquina-cabecera-paso'>02</div>";
    echo "<div class='wizardAux01'>";
    echo "<div class='wizardMaquina-cabecera-paso-titulo'>Sistema Operativo</div>";
    echo "<div class='wizardMaquina-cabecera-paso-subtitulo'>Seleccione el Sistema Operativo que Necesite</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='wizardMaquina-cuerpo'>";
    $auxiliar = "";
    if ($_POST["idtipoos"] != null) {
        $query_Tos = "select * from tipoos where idtipoos='" . $_POST["idtipoos"] . "'";
        $result_Tos = pg_query($query_Tos) or die('La consulta fallo: ' . pg_last_error());
        $Tos = pg_fetch_array($result_Tos);
        $auxiliar = $Tos["nombre"];
    }
    echo "<div class='ConfiguracionWizard-titulo'>Sistema Operativo Seleccionado</div>";
    echo "<input value='" . $auxiliar . "' class='entradaWizardSEL' type='text' name='osSEL' id='osSEL' readonly='true'/>";
    echo "<div id='errorEntrada02' class='errorEnentrada'></div>";
    $query_validos = "select * from proveedor_tipoos where idproveedor='" . $_POST["idproveedor"] . "'";
    $result_validos = pg_query($query_validos) or die('La consulta fallo: ' . pg_last_error());
    while ($valido = pg_fetch_array($result_validos)) {
        $query_tipoos = "select * from tipoos where idtipoos='" . $valido["idtipoos"] . "'";
        $result_tipoos = pg_query($query_tipoos) or die('La consulta fallo: ' . pg_last_error());
        $tipoos = pg_fetch_array($result_tipoos);
        echo "<div onclick=clickTipoOS(" . $tipoos["idtipoos"] . ",'" . str_replace(" ", "_", $tipoos["nombre"]) . "') class='wizardElementoSeleccion' title='" . $tipoos["nombre"] . "'><img src='recursos/imagenes/" . $tipoos["logo"] . "' alt='" . $tipoos["nombre"] . "' height='130' width='130'></div>";
    }
    echo "<div id='botonNext02' onclick=siguiente02() class='wizardSiguiente SiguienteDeshabilitado'>Siguiente</div>";
    echo "<div id='botonPrev02' onclick=anterior02() class='wizardAnterior SiguienteHabilitado'>Anterior</div>";
    echo "</div>";
}

/* Selecciona el tipo de sistema operativo y recarga la pantalla de selección de sistema operativo */
if ($_POST["accion"] == 2) {
    echo "<div class='wizardMaquina-cabecera'>";
    echo "<div class='wizardMaquina-cabecera-paso'>03</div>";
    echo "<div class='wizardAux01'>";
    echo "<div class='wizardMaquina-cabecera-paso-titulo'>Versión del Sistema Operativo</div>";
    echo "<div class='wizardMaquina-cabecera-paso-subtitulo'>Seleccione la Versión del Sistema Operativo que mejor se adapte a sus necesidades</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='wizardMaquina-cuerpo'>";
    $auxiliar = "";
    if ($_POST["idosVersion"] != null) {
        $query_Tos = "select * from sistemaoperativo where idsistemaoperativo='" . $_POST["idosVersion"] . "'";
        $result_Tos = pg_query($query_Tos) or die('La consulta fallo: ' . pg_last_error());
        $Tos = pg_fetch_array($result_Tos);
        $auxiliar = $Tos["nombre"];
    }
    echo "<div class='ConfiguracionWizard-titulo'>Versión de Sistema Operativo Seleccionado</div>";
    echo "<input value='" . $auxiliar . "' class='entradaWizardSEL' type='text' name='osVersionSEL' id='osVersionSEL' readonly='true'/>";
    echo "<div id='errorEntrada03' class='errorEnentrada'></div>";
    $query_os = "select * from sistemaoperativo where idproveedor='" . $_POST["idproveedor"] . "' and idtipoos='" . $_POST["idtipoos"] . "'";
    $result_os = pg_query($query_os) or die('La consulta fallo: ' . pg_last_error());
    while ($os = pg_fetch_array($result_os)) {
        $query_tipoos = "select * from tipoos where idtipoos='" . $_POST["idtipoos"] . "'";
        $result_tipoos = pg_query($query_tipoos) or die('La consulta fallo: ' . pg_last_error());
        $tipo = pg_fetch_array($result_tipoos);
        echo "<div onclick=clickOS(" . $os["idsistemaoperativo"] . ",'" . str_replace(" ", "_", $os["nombre"]) . "') class='conOS-versionOS2'>";
        echo "<div class='conOS-icon'><img src='recursos/imagenes/" . $tipo["logo"] . "' alt='Windows Server' height='50' width='50'></div>";
        echo "<div class='conOS-Titulo'>" . $os["nombre"] . "</div>";
        echo "<div class='conOS-SubTitulo'>" . $tipo["nombre"] . "</div>";
        echo "</div>";
    }
    echo "<div id='botonNext03' onclick=siguiente03() class='wizardSiguiente SiguienteDeshabilitado'>Siguiente</div>";
    echo "<div id='botonPrev03' onclick=anterior03() class='wizardAnterior SiguienteHabilitado'>Anterior</div>";
    echo "</div>";
}

/* Selecciona el sistema operativo y recarga la pantalla de selección de flavor */
if ($_POST["accion"] == 3) {
    echo "<div class='wizardMaquina-cabecera'>";
    echo "<div class='wizardMaquina-cabecera-paso'>04</div>";
    echo "<div class='wizardAux01'>";
    echo "<div class='wizardMaquina-cabecera-paso-titulo'>Flavor</div>";
    echo "<div class='wizardMaquina-cabecera-paso-subtitulo'>Seleccione la configuración de vCPU, Memoria Ram y Disco que mejor se adapte a sus necesidades</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='wizardMaquina-cuerpo'>";
    $auxiliar = "";
    if ($_POST["idflavor"] != null) {
        $query_Tos = "select * from flavor where idflavor='" . $_POST["idflavor"] . "'";
        $result_Tos = pg_query($query_Tos) or die('La consulta fallo: ' . pg_last_error());
        $Tos = pg_fetch_array($result_Tos);
        $auxiliar = $Tos["nombre"];
    }
    echo "<div class='ConfiguracionWizard-titulo'>Flavor Seleccionado</div>";
    echo "<input value='" . $auxiliar . "' class='entradaWizardSEL' type='text' name='flavorSEL' id='flavorSEL' readonly='true'/>";
    echo "<div id='errorEntrada04' class='errorEnentrada'></div>";
    $query_flavor = "select * from flavor where idproveedor='" . $_POST["idproveedor"] . "' order by idflavor";
    $result_flavor = pg_query($query_flavor) or die('La consulta fallo: ' . pg_last_error());
    while ($flavor = pg_fetch_array($result_flavor)) {
        if ($_POST["idproveedor"] == 2) {
            echo "<div class='wizardFlavor' onclick=clickFlavor(" . $flavor["idflavor"] . ",'" . str_replace(" ", "__", $flavor["nombre"]) . "')>";
            echo "<div class='flavor-cabecera'>" . $flavor["nombre"] . "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["numcpu"] . "</div>";
            echo "<div class='flavor-linea-der'>Core</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . ($flavor["memoriaram"] / 1024) . "</div>";
            echo "<div class='flavor-linea-der'>GB Memoria RAM</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . (($flavor["tamanoosdisk"] / 1000)) . "</div>";
            echo "<div class='flavor-linea-der'>GB Disco HHD</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . (($flavor["tamanoextradisk"] / 1024)) . "</div>";
            echo "<div class='flavor-linea-der'>GB Disco SSD</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["disextra"] . "</div>";
            echo "<div class='flavor-linea-der'>Discos Extra</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["precio"] . "</div>";
            echo "<div class='flavor-linea-der'>USD/HOUR</div>";
            echo "</div>";
            echo "</div>";
        } else if ($_POST["idproveedor"] == 1) {
            echo "<div class='wizardFlavor' onclick=clickFlavor(" . $flavor["idflavor"] . ",'" . str_replace(" ", "__", $flavor["nombre"]) . "')>";
            echo "<div class='flavor-cabecera'>" . $flavor["nombre"] . "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["numcpu"] . "</div>";
            echo "<div class='flavor-linea-der'>Core</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["memoriaram"] . "</div>";
            echo "<div class='flavor-linea-der'>GB Memoria RAM</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["tamanoosdisk"] . "</div>";
            echo "<div class='flavor-linea-der'>GB Disco HHD</div>";
            echo "</div>";
            echo "<div class='flavor-linea'>";
            echo "<div class='flavor-linea-izq'>" . $flavor["precio"] . "</div>";
            echo "<div class='flavor-linea-der'>USD/HOUR</div>";
            echo "</div>";
            echo "</div>";
        }
    }

    echo "<div id='botonNext04' onclick=siguiente04() class='wizardSiguiente SiguienteDeshabilitado'>Siguiente</div>";
    echo "<div id='botonPrev04' onclick=anterior04() class='wizardAnterior SiguienteHabilitado'>Anterior</div>";
    echo "</div>";
}

/* Refresca el select de localizaciones */
if ($_POST["accion"] == 4) {
    //echo $_SESSION["idorganizacion"];
    $query_localizacion = "select * from location where idproveedor='" . $_POST["idproveedor"] . "'";
    $result_localizacion = pg_query($query_localizacion) or die('La consulta fallo: ' . pg_last_error());

    if ($_POST["idproveedor"] == 1) {
        echo "<select onchange=cambiaLocalizacion() class='selectConfiguracion' id='localizacion' name='localizacion'>";
        while ($localizacion = pg_fetch_array($result_localizacion)) {
            echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
        }
        echo "</select>";
    }

    if ($_POST["idproveedor"] == 2) {
        echo "<select onchange=cambiaLocalizacion() class='selectConfiguracion' id='localizacion' name='localizacion'>";
        while ($localizacion = pg_fetch_array($result_localizacion)) {
            $sqlValidaSubscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $localizacion["idlocation"] . "';";
            $resultValidaSubscripcion = pg_query($sqlValidaSubscripcion) or die('La consulta fallo: ' . pg_last_error());
            if (pg_num_rows($resultValidaSubscripcion) == 1) {
                echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
            }
        }
        echo "</select>";
    }
}

if ($_POST["accion"] == 5) {

    $sqlMaquinas = "select servidor.idservidor as idservidor, proveedor.nombre as nombreproveedor, location.nombre as localizacion, servidor.nombrevm as nombremaquina, servidor.ippublica as ippublica, sistemaoperativo.nombre as sistemaoperativo, flavor.nombre as flavor, servidor.status as estatus from proveedor, location, servidor, sistemaoperativo, flavor where servidor.idlocation=location.idlocation and servidor.idproveedor=proveedor.idproveedor and servidor.idsistemaoperativo = sistemaoperativo.idsistemaoperativo and flavor.idflavor = servidor.idflavor and servidor.fecha_eliminacion is null and idusuario=" . $_SESSION["idusuario"] . " order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultMaquinas = pg_query($sqlMaquinas) or die('La consulta fallo: ' . pg_last_error());
    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultMaquinas) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "nombreproveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombreproveedor') class='eleCabTabla' style='width: 15%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombreproveedor') class='eleCabTabla' style='width: 15%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombreproveedor') class='eleCabTabla' style='width: 15%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "localizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombremaquina") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombremaquina') class='eleCabTabla' style='width: 15%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombremaquina') class='eleCabTabla' style='width: 15%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombremaquina') class='eleCabTabla' style='width: 15%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "estatus") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 7%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 7%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 7%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "ippublica") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('ippublica') class='eleCabTabla' style='width: 10%'>IP Publica<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('ippublica') class='eleCabTabla' style='width: 10%'>IP Publica<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='miley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('ippublica') class='eleCabTabla' style='width: 10%'>IP Publica<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "sistemaoperativo") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('sistemaoperativo') class='eleCabTabla' style='width: 17%'>Sistema Operativo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('sistemaoperativo') class='eleCabTabla' style='width: 17%'>Sistema Operativo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('sistemaoperativo') class='eleCabTabla' style='width: 17%'>Sistema Operativo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "flavor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('flavor') class='eleCabTabla' style='width: 10%'>Flavor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('flavor') class='eleCabTabla' style='width: 10%'>Flavor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('flavor') class='eleCabTabla' style='width: 10%'>Flavor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }
    echo "</div>";

    echo "<div id='cuerpotabla' class='cuerpotabla'>";
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
    echo "</div>";
}



if ($_POST["accion"] == 6) {

    $sqlDiscos = "select usuario.idusuario, disco.iddisco as iddisco, disco.idenproveedor as idenproveedor, proveedor.nombre as proveedor, location.nombre as localizacion, servidor.nombrevm as servidor, disco.nombre as nombredisco, disco.sizegb as tamano, disco.estatus as estatus from usuario, proveedor, location, servidor, disco where disco.idservidor=servidor.idservidor and servidor.idlocation = location.idlocation and servidor.idproveedor= proveedor.idproveedor and servidor.idusuario=usuario.idusuario and servidor.idusuario=" . $_SESSION["idusuario"] . " and disco.fecha_eliminacion is null order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultDiscos = pg_query($sqlDiscos) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultDiscos) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 11%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 11%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 11%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "localizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "servidor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('servidor') class='eleCabTabla' style='width: 12%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('servidor') class='eleCabTabla' style='width: 12%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('servidor') class='eleCabTabla' style='width: 12%'>Maquina Virtual<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombredisco") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombredisco') class='eleCabTabla' style='width: 11%'>Disco<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombredisco') class='eleCabTabla' style='width: 11%'>Disco<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombredisco') class='eleCabTabla' style='width: 11%'>Disco<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "idenproveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 18%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 18%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 18%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "tamano") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('tamano') class='eleCabTabla' style='width: 10%'>Tamaño<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('tamano') class='eleCabTabla' style='width: 10%'>Tamaño<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='miley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('tamano') class='eleCabTabla' style='width: 10%'>Tamaño<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "estatus") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 8%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 8%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('estatus') class='eleCabTabla' style='width: 8%'>Estatus<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    echo "</div>";

    echo "<div id='cuerpotabla' class='cuerpotabla'>";
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
    echo "</div>";
}

if ($_POST["accion"] == 7) {

    echo "<select onchange=cambiaLocalizacion() class='selectConfiguracion' id='sellocalizacion' name='sellocalizacion'>";

    if ($_POST["idproveedor"] == 1) {
        $query_location = "SELECT * FROM location where idproveedor='" . $_POST["idproveedor"] . "';";
        $result_location = pg_query($query_location) or die('La consulta fallo: ' . pg_last_error());
        while ($localizacion = pg_fetch_array($result_location)) {
            echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
        }
    }

    if ($_POST["idproveedor"] == 2) {
        $query_location = "SELECT * FROM location where idproveedor='" . $_POST["idproveedor"] . "';";
        $result_location = pg_query($query_location) or die('La consulta fallo: ' . pg_last_error());
        while ($localizacion = pg_fetch_array($result_location)) {
            $sqlValidaSubscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $localizacion["idlocation"] . "';";
            $resultValidaSubscripcion = pg_query($sqlValidaSubscripcion) or die('La consulta fallo: ' . pg_last_error());
            if (pg_num_rows($resultValidaSubscripcion) > 0) {
                echo "<option value='" . $localizacion["idlocation"] . "'>" . $localizacion["nombre"] . "</option>";
            }
        }
    }


    echo "</select>";
}



if ($_POST["accion"] == 8) {

    $sqlRedes = "Select red.idred as idred, proveedor.nombre as proveedor, location.nombre as localizacion, red.nombre as nombrered, red.idreal as idenproveedor, red.ipv4cidr as cidr from proveedor, location, red where red.idproveedor = proveedor.idproveedor and red.idlocation = location.idlocation and red.fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "' order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultRedes = pg_query($sqlRedes) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultRedes) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "localizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombrered") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 20%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 20%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 20%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "idenproveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('idenproveedor') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "cidr") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='miley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
    }

    echo "</div>";

    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($Red = pg_fetch_array($resultRedes)) {
        if (tienePermiso($_SESSION["idusuario"], $Red["proveedor"], "Redes Virtuales", "Visualizar")) {
            echo "<div class='lineaTabla'>";
            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Red["idred"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Red["idred"] . ") onmouseout=ocultarAcciones(" . $Red["idred"] . ") class='panelOpciones' id='panelOpciones-" . $Red["idred"] . "'>";
            if (tienePermiso($_SESSION["idusuario"], $Red["proveedor"], "Redes Virtuales", "Eliminar")) {
                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Red["idred"] . ")>Eliminar</div>";
            }
            echo "</div></div>";
            echo "<div class='eleLinTabla' style='width: 12%' title='" . $Red["proveedor"] . "'>" . $Red["proveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 15%' title='" . $Red["localizacion"] . "'>" . $Red["localizacion"] . "</div>";
            echo "<div id='nombre-" . $Red["idred"] . "' class='eleLinTabla' style='width: 20%' title='" . $Red["nombrered"] . "'>" . $Red["nombrered"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 20%' title='" . $Red["idenproveedor"] . "'>" . $Red["idenproveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 13%' title='" . $Red["cidr"] . "'>" . $Red["cidr"] . "</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}



if ($_POST["accion"] == 9) {

    $sqlSubRedes = "select subred.idsubred as idsubred, proveedor.nombre as proveedor, location.nombre as localizacion, red.nombre as nombrered, subred.nombre as nombresubred, subred.idreal as idreal, subred.ipv4cidr as cidr from proveedor, location, red, subred where proveedor.idproveedor = red.idproveedor and subred.idred = red.idred and red.idlocation = location.idlocation and subred.fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "' order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultSubRedes = pg_query($sqlSubRedes) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultSubRedes) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "localizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 10%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombrered") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 18%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 18%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombrered') class='eleCabTabla' style='width: 18%'>Nombre de la Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombresubred") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombresubred') class='eleCabTabla' style='width: 18%'>Nombre de la Sub Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombresubred') class='eleCabTabla' style='width: 18%'>Nombre de la Sub Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombresubred') class='eleCabTabla' style='width: 18%'>Nombre de la Sub Red<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "idreal") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 13%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 13%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 13%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "cidr") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='miley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('cidr') class='eleCabTabla' style='width: 13%'>IPv4 CIDR<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='miley face' height='17' width='17'></div>";
    }

    echo "</div>";

    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($SubRed = pg_fetch_array($resultSubRedes)) {
        if (tienePermiso($_SESSION["idusuario"], $SubRed["proveedor"], "Subredes", "Visualizar")) {
            echo "<div class='lineaTabla'>";
            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $SubRed["idsubred"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $SubRed["idsubred"] . ") onmouseout=ocultarAcciones(" . $SubRed["idsubred"] . ") class='panelOpciones' id='panelOpciones-" . $SubRed["idsubred"] . "'>";
            if (tienePermiso($_SESSION["idusuario"], $SubRed["proveedor"], "Subredes", "Eliminar")) {
                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $SubRed["idsubred"] . ")>Eliminar</div>";
            }
            echo "</div></div>";
            echo "<div class='eleLinTabla' style='width: 12%' title='" . $SubRed["proveedor"] . "'>" . $SubRed["proveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 10%' title='" . $SubRed["localizacion"] . "'>" . $SubRed["localizacion"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 18%' title='" . $SubRed["nombrered"] . "'>" . $SubRed["nombrered"] . "</div>";
            echo "<div id='nombre-" . $SubRed["idsubred"] . "' class='eleLinTabla' style='width: 18%' title='" . $SubRed["nombresubred"] . "'>" . $SubRed["nombresubred"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 13%' title='" . $SubRed["idreal"] . "'>" . $SubRed["idreal"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 13%' title='" . $SubRed["cidr"] . "'>" . $SubRed["cidr"] . "</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}

if ($_POST["accion"] == 10) {
    echo "<select class='selectConfiguracion' id='selred' name='selred'>";
    $query_red = "SELECT * FROM red where idproveedor='" . $_POST["idproveedor"] . "' and fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "';";
    $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
    while ($red = pg_fetch_array($result_red)) {
        echo "<option value='" . $red["idred"] . "'>" . $red["nombre"] . " - " . $red["ipv4cidr"] . "</option>";
    }
    echo "</select>";
}


if ($_POST["accion"] == 11) {


    $sqlGrupos = "select gruposeguridad.idgruposeguridad as idgrupo, proveedor.nombre as proveedor, location.nombre as localizacion, gruposeguridad.nombre as nombregrupo, gruposeguridad.idreal as idreal from gruposeguridad, proveedor, location where gruposeguridad.idproveedor = proveedor.idproveedor and gruposeguridad.idlocation = location.idlocation and gruposeguridad.fecha_eliminacion is null and gruposeguridad.idorganizacion='" . $_SESSION["idorganizacion"] . "' order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultGrupos = pg_query($sqlGrupos) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultGrupos) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 25%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 25%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 25%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "localizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('localizacion') class='eleCabTabla' style='width: 15%'>Localización<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombregrupo") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombregrupo') class='eleCabTabla' style='width: 20%'>Nombre del Grupo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombregrupo') class='eleCabTabla' style='width: 20%'>Nombre del Grupo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombregrupo') class='eleCabTabla' style='width: 20%'>Nombre del Grupo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "idreal") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('idreal') class='eleCabTabla' style='width: 20%'>Id en Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    echo "</div>";


    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($Grupo = pg_fetch_array($resultGrupos)) {
        if (tienePermiso($_SESSION["idusuario"], $Grupo["proveedor"], "Grupos de Seguridad", "Visualizar")) {
            echo "<div class='lineaTabla'>";
            echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Grupo["idgrupo"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Grupo["idgrupo"] . ") onmouseout=ocultarAcciones(" . $Grupo["idgrupo"] . ") class='panelOpciones' id='panelOpciones-" . $Grupo["idgrupo"] . "'>";
            if (tienePermiso($_SESSION["idusuario"], $Grupo["proveedor"], "Grupos de Seguridad", "Eliminar")) {
                echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Grupo["idgrupo"] . ")>Eliminar</div>";
            }
            echo "</div></div>";
            echo "<div class='eleLinTabla' style='width: 25%' title='" . $Grupo["proveedor"] . "'>" . $Grupo["proveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 15%' title='" . $Grupo["localizacion"] . "'>" . $Grupo["localizacion"] . "</div>";
            echo "<div id='nombre-" . $Grupo["idgrupo"] . "' class='eleLinTabla' style='width: 20%' title='" . $Grupo["nombregrupo"] . "'>" . $Grupo["nombregrupo"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 20%' title='" . $Grupo["idreal"] . "'>" . $Grupo["idreal"] . "</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}


if ($_POST["accion"] == 12) {


    if ($_POST["idproveedor"] == 2) {
        if ($_POST["idlocalizacion"] != null && $_POST["idlocalizacion"] != "") {
            $query_grupo = "select * from gruposeguridad where idlocation='" . $_POST["idlocalizacion"] . "' and fecha_eliminacion is null and idorganizacion ='" . $_SESSION["idorganizacion"] . "'";
            $result_grupo = pg_query($query_grupo) or die('La consulta fallo: ' . pg_last_error());
            echo "<select onchange=cambiaGrupoSeguridad() class='selectConfiguracion' id='gruposeguridad' name='gruposeguridad'>";
            while ($grupo = pg_fetch_array($result_grupo)) {
                echo "<option value='" . $grupo["idgruposeguridad"] . "'>" . $grupo["nombre"] . "</option>";
            }
            echo "</select>";
        } else {
            echo "<select onchange=cambiaGrupoSeguridad() class='selectConfiguracion' id='gruposeguridad' name='gruposeguridad'>";
            echo "</select>";
        }
    } else if ($_POST["idproveedor"] == 1) {
        if ($_POST["idlocalizacion"] != null && $_POST["idlocalizacion"] != "" && $_POST["idred"] != null) {
            $query_grupo = "select * from gruposeguridad where idlocation='" . $_POST["idlocalizacion"] . "' and idred='" . $_POST["idred"] . "' and fecha_eliminacion is null and idorganizacion ='" . $_SESSION["idorganizacion"] . "'";
            $result_grupo = pg_query($query_grupo) or die('La consulta fallo: ' . pg_last_error());
            echo "<select onchange=cambiaGrupoSeguridad() class='selectConfiguracion' id='gruposeguridad' name='gruposeguridad'>";
            while ($grupo = pg_fetch_array($result_grupo)) {
                echo "<option value='" . $grupo["idgruposeguridad"] . "'>" . $grupo["nombre"] . "</option>";
            }
            echo "</select>";
        } else {
            echo "<select onchange=cambiaGrupoSeguridad() class='selectConfiguracion' id='gruposeguridad' name='gruposeguridad'>";
            echo "</select>";
        }
    }
}


if ($_POST["accion"] == 13) {
    $query_red = "select * from red where idlocation='" . $_POST["idlocalizacion"] . "' and fecha_eliminacion is null and red.idorganizacion = '" . $_SESSION["idorganizacion"] . "'";
    $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
    echo "<select onchange=cambiaRedVirtual() class='selectConfiguracion' id='redvirtual' name='redvirtual'>";
    while ($red = pg_fetch_array($result_red)) {
        echo "<option value='" . $red["idred"] . "'>" . $red["nombre"] . " " . $red["ipv4cidr"] . "</option>";
    }
    echo "</select>";
}


if ($_POST["accion"] == 14) {
    //$query_subred = "select * from subred where idred='" . $_POST["idred"] . "' and fecha_eliminacion is null";

    if ($_POST["idred"] != null && $_POST["idred"] != "") {
        $query_subred = "select subred.idsubred as idsubred, subred.nombre as nombre, subred.idreal, subred.ipv4cidr as ipv4cidr, red.idorganizacion from subred, red where subred.idred = red.idred and subred.fecha_eliminacion is null and subred.idred='" . $_POST["idred"] . "' and red.idorganizacion = '" . $_SESSION["idorganizacion"] . "';";
        $result_subred = pg_query($query_subred) or die('La consulta fallo: ' . pg_last_error());
        echo "<select class='selectConfiguracion' id='subredvirtual' name='subredvirtual'>";
        while ($subred = pg_fetch_array($result_subred)) {
            echo "<option value='" . $subred["idsubred"] . "'>" . $subred["nombre"] . " " . $subred["ipv4cidr"] . "</option>";
        }
        echo "</select>";
    } else {
        echo "<select class='selectConfiguracion' id='subredvirtual' name='subredvirtual'>";
        echo "</select>";
    }
}

if ($_POST["accion"] == 15) {
    $query_disco = "select * from disco where iddisco='" . $_POST["iddisco"] . "'";
    $result_disco = pg_query($query_disco) or die('La consulta fallo: ' . pg_last_error());
    $disco = pg_fetch_array($result_disco);

    $query_servidor = "select * from servidor where idservidor='" . $disco["idservidor"] . "'";
    $result_servidor = pg_query($query_servidor) or die('La consulta fallo: ' . pg_last_error());
    $servidor = pg_fetch_array($result_servidor);

    $query_servidores = "select * from servidor where idproveedor='" . $servidor["idproveedor"] . "' and idlocation='" . $servidor["idlocation"] . "' and fecha_eliminacion is null and idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $result_servidores = pg_query($query_servidores) or die('La consulta fallo: ' . pg_last_error());

    echo "<select class='selectConfiguracion' id='servidorConectar' name='servidorConectar'>";
    while ($servidores = pg_fetch_array($result_servidores)) {
        echo "<option value='" . $servidores["idservidor"] . "'>" . $servidores["nombrevm"] . "</option>";
    }
    echo "</select>";
}


if ($_POST["accion"] == 16) {
    $query_servidor = "select * from servidor where idproveedor='" . $_POST["idproveedor"] . "' and fecha_eliminacion is null and idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $result_servidor = pg_query($query_servidor) or die('La consulta fallo: ' . pg_last_error());
    echo "<select class='selectConfiguracion' id='selservidor2' name='selservidor2'>";
    while ($servidor = pg_fetch_array($result_servidor)) {
        echo "<option value='" . $servidor["idservidor"] . "'>" . $servidor["nombrevm"] . "</option>";
    }
    echo "</select>";
}


if ($_POST["accion"] == 17) {
    $query_red = "select * from red where idlocation='" . $_POST["idlocalizacion"] . "' and fecha_eliminacion is null and red.idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
    echo "<select onchange=cambiaRedVirtual() class='selectConfiguracion' id='selred' name='selred'>";
    while ($red = pg_fetch_array($result_red)) {
        echo "<option value='" . $red["idred"] . "'>" . $red["nombre"] . " " . $red["ipv4cidr"] . "</option>";
    }
    echo "</select>";
}



if ($_POST["accion"] == 18) {

    $sqlKeys = "select keypair.idkeypair as idkeypair, proveedor.nombre as proveedor, keypair.nombre as nombre, keypair.fingerprint as fingerprint, keypair.privatekey as privatekey from keypair, proveedor where keypair.idproveedor = proveedor.idproveedor and keypair.fecha_eliminacion is null and keypair.idorganizacion='" . $_SESSION["idorganizacion"] . "' order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultKeys = pg_query($sqlKeys) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultKeys) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 20%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 20%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 20%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "fingerprint") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('fingerprint') class='eleCabTabla' style='width: 20%'>Finger Print<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('fingerprint') class='eleCabTabla' style='width: 20%'>Finger Print<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('fingerprint') class='eleCabTabla' style='width: 20%'>Finger Print<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    echo "</div>";


    echo "<div id='cuerpotabla' class='cuerpotabla'>";
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
    echo "</div>";
}

if ($_POST["accion"] == 19) {
    $query_key = "select * from keypair where fecha_eliminacion is null and idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $result_key = pg_query($query_key) or die('La consulta fallo: ' . pg_last_error());
    echo "<select class='selectConfiguracion' id='selkey' name='selkey'>";
    while ($key = pg_fetch_array($result_key)) {
        echo "<option value='" . $key["idkeypair"] . "'>" . $key["nombre"] . "</option>";
    }
    echo "</select>";
}












//Update usuarios
if ($_POST["accion"] == 50) {

    $query_usuario = "select usuario.idorganizacion as idorganizacion, usuario.idusuario, usuario.apellido as apellido, usuario.nombre"
            . " as nombre, usuario.correo as correo, organizacion.nombre as organizacion, usuario.superusuario AS superusuario, usuario.contrasena as contrasena"
            . " from usuario, organizacion where usuario.idorganizacion = organizacion.idorganizacion"
            . " and usuario.idusuario = '" . $_POST["idusuario"] . "';";
    $result_usuario = pg_query($query_usuario) or die('La consulta fallo: ' . pg_last_error());
    $usuario = pg_fetch_array($result_usuario);

    //echo "llegó al AJAX".$_POST["idusuario"];
    echo "<div class='tituloAgregar'>Editar Usuario</div>";
    echo "<form id='editarUsuario' name='editarUsuario' method='post' action='recursos/php/acciones.php?accion=editarUsuario&id=" . $_POST["idusuario"] . "'>";


    echo "<div class='ConfiguracionElemento' style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Organización";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<select class='selectConfiguracion' id='selectedOrg' name='selectedOrg'>";

    $query_organizacion = "SELECT * FROM organizacion order by nombre asc;";
    $result_organizacion = pg_query($query_organizacion) or die('La consulta fallo: ' . pg_last_error());

    while ($organizacion = pg_fetch_array($result_organizacion)) {
        echo "<option " . (($organizacion["idorganizacion"] == $usuario["idorganizacion"]) ? "selected='selected'" : "") . " value='" . $organizacion["idorganizacion"] . "'>" . $organizacion["nombre"] . "</option>";
    }

    echo "</select>";
    echo "</div>";
    echo "</div>";


    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Nombre";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='nombreUsuario' name='nombreUsuario' type='text' class='entradaConfiguracion' value='" . $usuario["nombre"] . "'/>";
    echo "</div>";
    echo "</div>";


    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Apellido";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='apellidoUsuario' name='apellidoUsuario' type='text' class='entradaConfiguracion' value='" . $usuario["apellido"] . "'/>";
    echo "</div>";
    echo "</div>";


    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Correo";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='correoUsuario' name='correoUsuario' type='text' class='entradaConfiguracion' value='" . $usuario["correo"] . "'/>";
    echo "</div>";
    echo "</div>";


    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Contraseña";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='passUsuario' name='passUsuario' type='password' class='entradaConfiguracion' value='" . $usuario["contrasena"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Perfiles";
    echo "</div>";
    echo "<div class='ConfiguracionElemento checkboxScroll'  style='margin-bottom: 5px;margin-top: 5px'>";
    //echo "<fieldset>";
    //echo "<legend class=elementoCheckUsuario style='font-size:13px'>Perfiles</legend>";

    $query_perfiles = "SELECT * FROM perfil WHERE fecha_eliminacion IS NULL order by nombre asc;";
    $result_perfiles = pg_query($query_perfiles) or die('La consulta fallo: ' . pg_last_error());

    $sqlPerfilUsuario = "SELECT * FROM usuarios_perfiles WHERE idusuario = '" . $_POST["idusuario"] . "';";
    $resultPerfilUsuario = pg_query($sqlPerfilUsuario) or die('La consulta fallo: ' . pg_last_error());

    function perfilIsChecked($campo) {

        global $resultPerfilUsuario;
        pg_result_seek($resultPerfilUsuario, 0);

        while ($perfilCheck = pg_fetch_array($resultPerfilUsuario)) {

            if ($perfilCheck["idperfil"] == $campo) {
                return "checked";
            }
        }
    }

    while ($perfil = pg_fetch_array($result_perfiles)) {

        echo "<div>";
        echo "<input type='checkbox' id='check" . $perfil["nombre"] . "' name='checkPerfiles[]' value=" . $perfil["idperfil"] . " "
        . perfilIsChecked($perfil["idperfil"]) . ">";
        echo "<label class=elementoCheckUsuario for='check" . $perfil["nombre"] . "'>" . $perfil["nombre"] . "</label>";
        echo "</div>";
    }

    //echo "</fieldset>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 20px;'>";
    echo "<fieldset>";
    echo "<legend class='elementoCheckUsuario' style='font-family: 'Titillium Web', sans-serif; font-size: 12px'>Super Usuario</legend>";
    echo "<div>";
    if ($usuario["superusuario"] == 1) {
        echo "<input checked type='checkbox' id='checkSuperUsuario' name='checkSuperUsuario' value='1'>";
        echo "<label class=elementoCheckUsuario for='checkSuperUsuario'>Permite la administración de usuarios y perfiles</label>";
    } else {
        echo "<input type='checkbox' id='checkSuperUsuario' name='checkSuperUsuario' value='1'>";
        echo "<label class=elementoCheckUsuario for='checkSuperUsuario'>Permite la administración de usuarios y perfiles</label>";
    }
    echo "</div>";
    echo "</fieldset>";
    echo "</div>";

    echo "<div onclick=enviarEditar(" . $_POST["idusuario"] . ") class='agregarBoton' id='botonAgregar' >Editar Usuario</div>";
    echo "<div onclick=$('#editarUsuarioDisp').hide(); class='cerrarAgregar' id='cerrarAgregar'>[CERRAR]</div>";


    echo "</form>";
    ?>

    <script type="text/javascript">

        function enviarEditar(userId) {
            //alert("enviarEditar= " + <?php //echo $_POST["idusuario"];                         ?>);
            document.getElementById("editarUsuario").submit();
        }

    </script>

    <?php
}

//Ordenar Usuarios
if ($_POST["accion"] == 51) {

    $sqlUsuarios = "select usuario.idorganizacion, usuario.idusuario, usuario.apellido as apellido, usuario.nombre"
            . " as nombre, usuario.correo as correo, organizacion.nombre as organizacion"
            . " from usuario, organizacion where usuario.idorganizacion = organizacion.idorganizacion"
            . " and usuario.fecha_eliminacion IS NULL"
            . " order by " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";
    $resultUsuarios = pg_query($sqlUsuarios) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "apellido") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('apellido') class='eleCabTabla' style='width: 12%'>Apellido<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('apellido') class='eleCabTabla' style='width: 12%'>Apellido<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('apellido') class='eleCabTabla' style='width: 12%'>Apellido<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 10%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 10%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 10%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "correo") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('correo') class='eleCabTabla' style='width: 18%'>Correo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('correo') class='eleCabTabla' style='width: 18%'>Correo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('correo') class='eleCabTabla' style='width: 18%'>Correo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "organizacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('organizacion') class='eleCabTabla' style='width: 18%'>Organizacion<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('organizacion') class='eleCabTabla' style='width: 18%'>Organizacion<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('organizacion') class='eleCabTabla' style='width: 18%'>Organizacion<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    echo "<div class='eleCabTabla' style='width: 13%'>Perfiles</div>";
    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($Usuario = pg_fetch_array($resultUsuarios)) {
        echo "<div class='lineaTabla'>";
        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $Usuario["idusuario"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Usuario["idusuario"] . ") onmouseout=ocultarAcciones(" . $Usuario["idusuario"] . ") class='panelOpciones' id='panelOpciones-" . $Usuario["idusuario"] . "'>";
        echo "<div class='panelOPC' onclick=editar(" . $Usuario["idusuario"] . ")>Editar</div>";
        //echo "<div class='panelOPC' onclick=$('#editarUsuarioDisp').show();>Editar</div>";
        echo "<div class='panelOPC' onclick=accionMaquina('eliminar'," . $Usuario["idusuario"] . ")>Eliminar</div>";
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
                . "where usuarios_perfiles.idusuario = " . $Usuario["idusuario"] . " and perfil.idperfil = usuarios_perfiles.idperfil order by perfil.nombre asc;";
        $resultPerfiles = pg_query($sqlPerfiles) or die('La consulta fallo: ' . pg_last_error());
        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarPerfiles(" . $Usuario["idusuario"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/icon_trespuntos.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarPerfiles(" . $Usuario["idusuario"] . ") onmouseout=ocultarPerfiles(" . $Usuario["idusuario"] . ") class='panelOpciones' id='panelPerfiles-" . $Usuario["idusuario"] . "'>";
        while ($Perfil = pg_fetch_array($resultPerfiles)) {
            echo "<div class='panelOPCPerfiles')>" . $Perfil["nombre"] . "</div>";
        }
        echo "</div></div>";

        echo "</div>";
        //echo "</div>";
    }
}

//======CREAR PERFILES========
//Cambio proveedor <Entidades>
if ($_POST["accion"] == 52) {

    echo "<fieldset>";
    echo "<legend class=elementoCheckUsuario style='font-size:13px'>Entidades asociadas al proveedor</legend>";


    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = " . $_POST["idproveedor"] . " order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());
    //$idtemPerfil = 0;
    while ($entidad = pg_fetch_array($result_entidades)) {

        echo "<div>";
        echo "<input class='elementoCheckEntidad' type='checkbox' id='check" . $entidad["nombre"] . "' name='checkEntidades[]' value='" . $entidad["identidad"] . "'>";
        echo "<label class='elementoCheckUsuario' for='check" . $entidad["nombre"] . "'>" . $entidad["nombre"] . "</label>";
        echo "</div>";
    }

    echo "</fieldset>";

    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = '" . $_POST["idproveedor"] . "' order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        $('.elementoCheckEntidad').click(function () {
            var marcadas = $("#accMarcadas").val();
            //alert("1.1 marcadas: "+marcadas);            
            var actualprov = $("#selproveedor").val();
            var entidades = [];
            $.each($("input[name='checkEntidades[]']:checked"), function () {
                entidades.push($(this).val());

            });

            $.ajax({

                data: {accion: 53, selectedEntidades: entidades, idproveedor: actualprov, acciones: marcadas}, //--> send id of checked checkbox on other page
                url: './recursos/php/ajax.php',
                type: 'post',
                success: function (response) {
                    $("#conjuntoAcciones").html(response);
                }
            });

        });
    </script>
    <?php
}

//Actualizacion de acciones <Entidades-acciones>
if ($_POST["accion"] == 53) {

    //echo $_POST["acciones"];
    $accionesViejas = explode("_", $_POST["acciones"]);

    //print_r($accionesViejas);
    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = '" . $_POST["idproveedor"] . "' order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());

    //print("proveedor: ".$_POST["idproveedor"]);
    //print_r($_POST["selectedEntidades"]);
    //print("Ajax");

    if (isset($_POST["selectedEntidades"])) {

        //print_r($_POST["selectedEntidades"]);

        while ($entidad = pg_fetch_array($result_entidades)) {

            if (in_array($entidad["identidad"], $_POST["selectedEntidades"])) {

                echo "<div id='listaAcciones" . $entidad["nombre"] . "' class='ConfiguracionElemento'  style='margin-bottom: 20px; margin-top: 10px'>";
                echo "<fieldset>";
                echo "<legend class=elementoCheckUsuario style='font-size:13px'>" . $entidad["nombre"] . "</legend>";

                $query_acciones = "SELECT * FROM acciones WHERE identidad = " . $entidad["identidad"] . " order by idacciones asc;";
                $result_acciones = pg_query($query_acciones) or die('La consulta fallo: ' . pg_last_error());
                //$idtemPerfil = 0;
                while ($accion = pg_fetch_array($result_acciones)) {

                    $bandera = 0;

                    foreach ($accionesViejas as $accionVieja) {
                        if ($accionVieja == $accion["idacciones"]) {
                            $bandera = 1;
                        }
                    }

                    if ($bandera == 0) {
                        echo "<div>";
                        echo "<input onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                        echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                        echo "</div>";
                    } else {
                        echo "<div>";
                        echo "<input checked onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                        echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                        echo "</div>";
                    }
                }

                echo "</fieldset>";
                echo "</div>";
            }
        }
    } else {

        echo "</br>";
        echo "<label class='elementoCheckUsuario' style='font-size:15px'>Seleccione una entidad...</label>";
        echo "</br>";
    }
}

//Ordenar Perfiles
if ($_POST["accion"] == 54) {

    $sqlListaPerfiles = "SELECT perfil.idperfil AS idperfil, perfil.nombre AS nombre, proveedor.nombre AS proveedor
                        FROM perfil INNER JOIN perfiles_acciones ON perfil.idperfil = perfiles_acciones.idperfil
                        INNER JOIN acciones ON perfiles_acciones.idacciones = acciones.idacciones
                        INNER JOIN entidad ON acciones.identidad = entidad.identidad
                        INNER JOIN proveedor ON entidad.idproveedor = proveedor.idproveedor 
                        WHERE perfil.fecha_eliminacion IS NULL GROUP BY perfil.idperfil, proveedor.nombre
                        ORDER BY " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";

    $sqlResultListaPerfiles = pg_query($sqlListaPerfiles) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 8%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "perfil.nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('perfil.nombre') class='eleCabTabla' style='width: 33%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('perfil.nombre') class='eleCabTabla' style='width: 33%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('perfil.nombre') class='eleCabTabla' style='width: 33%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "proveedor.nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor.nombre') class='eleCabTabla' style='width: 33%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor.nombre') class='eleCabTabla' style='width: 33%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor.nombre') class='eleCabTabla' style='width: 33%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }
    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($DatosPerfil = pg_fetch_array($sqlResultListaPerfiles)) {

        echo "<div class='lineaTabla'>";
        echo "<div class='eleLinTablaOPC' style='width: 8%'><img onclick=mostrarAcciones(" . $DatosPerfil["idperfil"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $DatosPerfil["idperfil"] . ") onmouseout=ocultarAcciones(" . $DatosPerfil["idperfil"] . ") class='panelOpciones' id='panelOpciones-" . $DatosPerfil["idperfil"] . "'>";
        echo "<div class='panelOPC' onclick=editar(" . $DatosPerfil["idperfil"] . ")>Editar</div>";
        echo "<div class='panelOPC' onclick=eliminar(" . $DatosPerfil["idperfil"] . ")>Eliminar</div>";
        echo "</div></div>";

        echo "<div class='eleLinTabla' style='width: 33%' title='" . $DatosPerfil["nombre"] . "'>" . $DatosPerfil["nombre"] . "</div>";

        echo "<div class='eleLinTabla' style='width: 33%' title='" . $DatosPerfil["proveedor"] . "'>" . $DatosPerfil["proveedor"] . "</div>";

        echo "</div>";
    }
    echo "</div>";
}

//======EDITAR PERFILES========
//Cambio proveedor <Entidades>
if ($_POST["accion"] == 55) {

    echo "<fieldset>";
    echo "<legend class=elementoCheckUsuario style='font-size:13px'>Entidades asociadas al proveedor</legend>";


    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = " . $_POST["idproveedor"] . " order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());

    while ($entidad = pg_fetch_array($result_entidades)) {

        echo "<div>";
        echo "<input class='elementoCheckEntidad' type='checkbox' id='check" . $entidad["nombre"] . "' name='checkEntidades[]' value='" . $entidad["identidad"] . "'>";
        echo "<label class='elementoCheckUsuario' for='check" . $entidad["nombre"] . "'>" . $entidad["nombre"] . "</label>";
        echo "</div>";
    }

    echo "</fieldset>";

    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = '" . $_POST["idproveedor"] . "' order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        $('.elementoCheckEntidad').click(function () {
            var marcadas = $("#accMarcadas").val();
            //alert("llego");
            var actualprov = $("#selproveedor").val();

            var entidades = [];
            $.each($("input[name='checkEntidades[]']:checked"), function () {
                entidades.push($(this).val());

            });

            $.ajax({

                data: {accion: 56, selectedEntidades: entidades, idproveedor: actualprov, acciones: marcadas}, //--> send id of checked checkbox on other page
                url: './recursos/php/ajax.php',
                type: 'post',
                success: function (response) {
                    $("#conjuntoAcciones").html(response);
                }
            });

        });
    </script>
    <?php
}

//Actualizacion de acciones <Entidades-acciones>
if ($_POST["accion"] == 56) {


    $accionesExplode = explode("_", $_POST["acciones"]);

    $query_entidades = "SELECT * FROM entidad WHERE idproveedor = '" . $_POST["idproveedor"] . "' order by identidad asc;";
    $result_entidades = pg_query($query_entidades) or die('La consulta fallo: ' . pg_last_error());

    if (isset($_POST["idperfil"])) {
        $queryAccionesViejas = "SELECT idacciones FROM perfiles_acciones WHERE idperfil = " . $_POST["idperfil"] . ";";
        $resultAccionesViejas = pg_query($queryAccionesViejas) or die('La consulta fallo: ' . pg_last_error());

        if (isset($_POST["selectedEntidades"])) {

            while ($entidad = pg_fetch_array($result_entidades)) {

                if (in_array($entidad["identidad"], $_POST["selectedEntidades"])) {

                    echo "<div id='listaAcciones" . $entidad["nombre"] . "' class='ConfiguracionElemento'  style='margin-bottom: 20px; margin-top: 10px'>";
                    echo "<fieldset>";
                    echo "<legend class=elementoCheckUsuario style='font-size:13px'>" . $entidad["nombre"] . "</legend>";

                    $query_acciones = "SELECT * FROM acciones WHERE identidad = " . $entidad["identidad"] . " order by idacciones asc;";
                    $result_acciones = pg_query($query_acciones) or die('La consulta fallo: ' . pg_last_error());
                    //$idtemPerfil = 0;
                    while ($accion = pg_fetch_array($result_acciones)) {

                        $bandera = 0;

                        foreach ($accionesExplode as $accionExplode) {
                            if ($accionExplode == $accion["idacciones"]) {
                                $bandera = 1;
                            }
                        }

                        if ($bandera == 0) {
                            echo "<div>";
                            echo "<input onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                            echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                            echo "</div>";
                        } else {
                            echo "<div>";
                            echo "<input checked onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                            echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                            echo "</div>";
                        }
                    }

                    echo "</fieldset>";
                    echo "</div>";
                }
            }
        } else {

            echo "</br>";
            echo "<label class='elementoCheckUsuario' style='font-size:15px'>Seleccione una entidad...</label>";
            echo "</br>";
        }
    } else {

        if (isset($_POST["selectedEntidades"])) {

            while ($entidad = pg_fetch_array($result_entidades)) {

                if (in_array($entidad["identidad"], $_POST["selectedEntidades"])) {

                    echo "<div id='listaAcciones" . $entidad["nombre"] . "' class='ConfiguracionElemento'  style='margin-bottom: 20px; margin-top: 10px'>";
                    echo "<fieldset>";
                    echo "<legend class=elementoCheckUsuario style='font-size:13px'>" . $entidad["nombre"] . "</legend>";

                    $query_acciones = "SELECT * FROM acciones WHERE identidad = " . $entidad["identidad"] . " order by idacciones asc;";
                    $result_acciones = pg_query($query_acciones) or die('La consulta fallo: ' . pg_last_error());
                    //$idtemPerfil = 0;
                    while ($accion = pg_fetch_array($result_acciones)) {

                        $bandera = 0;

                        foreach ($accionesExplode as $accionExplode) {
                            if ($accionExplode == $accion["idacciones"]) {
                                $bandera = 1;
                            }
                        }

                        if ($bandera == 0) {
                            echo "<div>";
                            echo "<input onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                            echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                            echo "</div>";
                        } else {
                            echo "<div>";
                            echo "<input checked onclick=seleccionaAccion(" . $accion["idacciones"] . ") type='checkbox' id='check" . $accion["nombre"] . $entidad["nombre"] . "' name='checkAcciones[]' value='" . $accion["idacciones"] . "'>";
                            echo "<label class=elementoCheckUsuario for='check" . $accion["nombre"] . $entidad["nombre"] . "'>" . $accion["nombre"] . "</label>";
                            echo "</div>";
                        }
                    }

                    echo "</fieldset>";
                    echo "</div>";
                }
            }
        } else {

            echo "</br>";
            echo "<label class='elementoCheckUsuario' style='font-size:15px'>Seleccione una entidad...</label>";
            echo "</br>";
        }
    }
}


//Ordena Sistemas Operativos
if ($_POST["accion"] == 58) {

    $sqlSOs = "SELECT sistemaoperativo.idsistemaoperativo AS idsistemaoperativo, proveedor.nombre AS proveedor,
                tipoos.nombre AS tipo, sistemaoperativo.nombre AS nombre, sistemaoperativo.identificador AS identificador,
                sistemaoperativo.clasifica AS clasificacion FROM sistemaoperativo
                INNER JOIN proveedor ON sistemaoperativo.idproveedor = proveedor.idproveedor
                INNER JOIN tipoos ON sistemaoperativo.idtipoos = tipoos.idtipoos
                WHERE sistemaoperativo.fecha_eliminacion IS NULL
                ORDER BY " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";

    $resultSOs = pg_query($sqlSOs) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultSOs) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 6%'>Acciones</div>";
    if ($_POST["ordenado_por"] == "nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 20%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "tipo") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('tipo') class='eleCabTabla' style='width: 10%'>Tipo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('tipo') class='eleCabTabla' style='width: 10%'>Tipo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('tipo') class='eleCabTabla' style='width: 10%'>Tipo<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 14%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 14%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 14%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "clasificacion") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('clasificacion') class='eleCabTabla' style='width: 10%'>Clasificación<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('clasificacion') class='eleCabTabla' style='width: 10%'>Clasificación<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('clasificacion') class='eleCabTabla' style='width: 10%'>Clasificación<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "identificador") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 20%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 20%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 20%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }
    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($SO = pg_fetch_array($resultSOs)) {
        if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Visualizar")) {
            echo "<div class='lineaTabla'>";
            echo "<div class='eleLinTablaOPC' style='width: 6%'><img onclick=mostrarAcciones(" . $SO["idsistemaoperativo"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $SO["idsistemaoperativo"] . ") onmouseout=ocultarAcciones(" . $SO["idsistemaoperativo"] . ") class='panelOpciones' id='panelOpciones-" . $SO["idsistemaoperativo"] . "'>";
            if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Editar")) {
                echo "<div class='panelOPC' onclick=editar(" . $SO["idsistemaoperativo"] . ")>Editar</div>";
            }
            if (tienePermiso($_SESSION["idusuario"], $SO["proveedor"], "Sistemas Operativos", "Eliminar")) {
                echo "<div class='panelOPC' onclick=eliminar(" . $SO["idsistemaoperativo"] . ")>Eliminar</div>";
            }
            echo "</div></div>";
            echo "<div class='eleLinTabla' style='width: 20%' title='" . $SO["nombre"] . "'>" . $SO["nombre"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 10%' title='" . $SO["tipo"] . "'>" . $SO["tipo"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 14%' title='" . $SO["proveedor"] . "'>" . $SO["proveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 10%' title='" . $SO["clasificacion"] . "'>" . $SO["clasificacion"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 20%' title='" . $SO["identificador"] . "'>" . $SO["identificador"] . "</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}

//Editar Sistemas Operativos
if ($_POST["accion"] == 59) {

    $sqlSO = "SELECT sistemaoperativo.idsistemaoperativo AS idsistemaoperativo, proveedor.nombre AS proveedor,
        proveedor.idproveedor AS idproveedor, tipoos.idtipoos AS idtipoos, tipoos.nombre AS tipo, 
        sistemaoperativo.nombre AS nombre, sistemaoperativo.identificador AS identificador,
        sistemaoperativo.clasifica AS clasificacion 
        FROM sistemaoperativo
        INNER JOIN proveedor ON sistemaoperativo.idproveedor = proveedor.idproveedor
        INNER JOIN tipoos ON sistemaoperativo.idtipoos = tipoos.idtipoos
        WHERE idsistemaoperativo = " . $_POST["idso"] . " ORDER BY idsistemaoperativo ASC;";

    $resultSO = pg_query($sqlSO) or die('La consulta fallo: ' . pg_last_error());
    $SO = pg_fetch_array($resultSO);

    echo "<div class='tituloAgregar'>Editar Sistema Operativo</div>";

    echo "<form id='editarSO' name='editarSO' method='post' action='recursos/php/acciones.php?accion=editarSO&id=" . $_POST["idso"] . "'>";
    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Nombre";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='nombreSO' name='nombreSO' type='text' class='entradaConfiguracion' value='" . $SO["nombre"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento' style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Proveedor";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<select onchange='actualizaTiposEditar()' class='selectConfiguracion' id='selectedProvEditar' name='selectedProvEditar'>";

    $query_proveedor = "SELECT * FROM proveedor ORDER BY nombre ASC;";
    $result_proveedor = pg_query($query_proveedor) or die("La consulta fallo: " . pg_last_error());

    $band = 0;
    $idtemProveedor = 0;
    while ($proveedor = pg_fetch_array($result_proveedor)) {
        if ($band == 0) {
            $idtemProveedor = $proveedor["idproveedor"];
            $band = 1;
        }
        if ($SO["idproveedor"] == $proveedor["idproveedor"]) {
            $idtemProveedor = $proveedor["idproveedor"];
            echo "<option selected value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
        } else {
            echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
        }
    }

    echo "</select>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento' style='margin-bottom: 5px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Tipo";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<select class='selectConfiguracion' id='selectedTipoEditar' name='selectedTipoEditar'>";

    $query_tipo = "SELECT tipoos.idtipoos AS idtipoos, tipoos.nombre AS nombre, 
                proveedor_tipoos.idproveedor AS idproveedor
                FROM tipoos INNER JOIN proveedor_tipoos ON tipoos.idtipoos = proveedor_tipoos.idtipoos
                WHERE idproveedor = " . $idtemProveedor . ";";
    $result_tipo = pg_query($query_tipo) or die('La consulta fallo: ' . pg_last_error());
    while ($tipo = pg_fetch_array($result_tipo)) {
        if ($SO["idtipoos"] == $tipo["idtipoos"]) {
            echo "<option selected value='" . $tipo["idtipoos"] . "'>" . $tipo["nombre"] . "</option>";
        } else {
            echo "<option value='" . $tipo["idtipoos"] . "'>" . $tipo["nombre"] . "</option>";
        }
    }

    echo "</select>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Identificador";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='identificadorSO' name='identificadorSO' type='text' class='entradaConfiguracion' value='" . $SO["identificador"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div onclick=update() class='agregarBoton' id='botonAgregar' >Editar Sistema Operativo</div>";
    echo "<div onclick=cerrarEditar() class='cerrarAgregar' id='cerrarAgregar'>[CERRAR]</div>";
    echo "</form>";
}

// <----FLAVORS---->
//Ordena Flavors
if ($_POST["accion"] == 60) {

    $sqlFlavors = "SELECT flavor.idflavor AS idflavor, flavor.nombre AS nombre, proveedor.idproveedor AS idproveedor,
                    proveedor.nombre AS proveedor, flavor.numcpu AS numcpu, flavor.memoriaram AS memoriaram,
                    flavor.tamanoosdisk AS tamanoosdisk, flavor.tamanoextradisk AS tamanoextradisk, 
                    flavor.disextra AS disextra, flavor.precio AS precio, flavor.identificador AS identificador
                    FROM flavor INNER JOIN proveedor ON flavor.idproveedor = proveedor.idproveedor
                    WHERE flavor.fecha_eliminacion IS NULL
                    ORDER BY " . $_POST["ordenado_por"] . " " . $_POST["orden"] . ";";

    $resultFlavors = pg_query($sqlFlavors) or die('La consulta fallo: ' . pg_last_error());

    echo "<div class='contenedorNumElementos'>" . pg_num_rows($resultFlavors) . " Elementos</div>";
    echo "<div class='cabeceraTabla'>";
    echo "<div class='eleCabTabla' style='width: 6%'>Acciones</div>";

    if ($_POST["ordenado_por"] == "nombre") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 11%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 11%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('nombre') class='eleCabTabla' style='width: 11%'>Nombre<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "proveedor") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('proveedor') class='eleCabTabla' style='width: 12%'>Proveedor<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "numcpu") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('numcpu') class='eleCabTabla' style='width: 6%'># CPUs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('numcpu') class='eleCabTabla' style='width: 6%'># CPUs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('numcpu') class='eleCabTabla' style='width: 6%'># CPUs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "memoriaram") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('memoriaram') class='eleCabTabla' style='width: 6%'>RAM<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('memoriaram') class='eleCabTabla' style='width: 6%'>RAM<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('memoriaram') class='eleCabTabla' style='width: 6%'>RAM<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "tamanoosdisk") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('tamanoosdisk') class='eleCabTabla' style='width: 8%'>HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('tamanoosdisk') class='eleCabTabla' style='width: 8%'>HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('tamanoosdisk') class='eleCabTabla' style='width: 8%'>HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "tamanoextradisk") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('tamanoextradisk') class='eleCabTabla' style='width: 8%'>ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('tamanoextradisk') class='eleCabTabla' style='width: 8%'>ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('tamanoextradisk') class='eleCabTabla' style='width: 8%'>ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "disextra") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('disextra') class='eleCabTabla' style='width: 10%'># ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('disextra') class='eleCabTabla' style='width: 10%'># ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('disextra') class='eleCabTabla' style='width: 10%'># ext. HDDs<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "precio") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('precio') class='eleCabTabla' style='width: 8%'>Precio<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('precio') class='eleCabTabla' style='width: 8%'>Precio<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('precio') class='eleCabTabla' style='width: 8%'>Precio<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }

    if ($_POST["ordenado_por"] == "identificador") {
        if ($_POST["orden"] == "asc") {
            echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 14%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
        } else if ($_POST["orden"] == "desc") {
            echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 14%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/upicon.png' alt='Smiley face' height='17' width='17'></div>";
        }
    } else {
        echo "<div onclick=ordena('identificador') class='eleCabTabla' style='width: 14%'>Identificador<img style='margin-left: 3px; margin-top: 5px;' src='recursos/imagenes/downicon.png' alt='Smiley face' height='17' width='17'></div>";
    }
    echo "<div id='cuerpotabla' class='cuerpotabla'>";
    while ($Flavor = pg_fetch_array($resultFlavors)) {
        if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Visualizar")) {
            echo "<div class='lineaTabla'>";
            echo "<div class='eleLinTablaOPC' style='width: 6%'><img onclick=mostrarAcciones(" . $Flavor["idflavor"] . ") style='margin-left: 10px; cursor: pointer; margin-top: 3px;' src='recursos/imagenes/opcionesicon.png' alt='Smiley face' height='25' width='25'><div onmousemove=mostrarAcciones(" . $Flavor["idflavor"] . ") onmouseout=ocultarAcciones(" . $Flavor["idflavor"] . ") class='panelOpciones' id='panelOpciones-" . $Flavor["idflavor"] . "'>";

            if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Editar")) {
                echo "<div class='panelOPC' onclick=editar(" . $Flavor["idflavor"] . ")>Editar</div>";
            }
            if (tienePermiso($_SESSION["idusuario"], $Flavor["proveedor"], "Flavors", "Eliminar")) {
                echo "<div class='panelOPC' onclick=eliminar(" . $Flavor["idflavor"] . ")>Eliminar</div>";
            }
            echo "</div></div>";
            echo "<div class='eleLinTabla' style='width: 11%' title='" . $Flavor["nombre"] . "'>" . $Flavor["nombre"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 12%' title='" . $Flavor["proveedor"] . "'>" . $Flavor["proveedor"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 6%' title='" . $Flavor["numcpu"] . "'>" . $Flavor["numcpu"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 6%' title='" . $Flavor["memoriaram"] . "'>" . $Flavor["memoriaram"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["tamanoosdisk"] . "'>" . $Flavor["tamanoosdisk"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["tamanoextradisk"] . "'>" . $Flavor["tamanoextradisk"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 10%' title='" . $Flavor["disextra"] . "'>" . $Flavor["disextra"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 8%' title='" . $Flavor["precio"] . "'>" . $Flavor["precio"] . "</div>";
            echo "<div class='eleLinTabla' style='width: 14%' title='" . $Flavor["identificador"] . "'>" . $Flavor["identificador"] . "</div>";
            echo "</div>";
        }
    }
    echo "</div>";
}

//Editar Flavors
if ($_POST["accion"] == 61) {

    $sqlFlavor = "SELECT flavor.idflavor AS idflavor, flavor.nombre AS nombre, proveedor.idproveedor AS idproveedor,
                proveedor.nombre AS proveedor, flavor.numcpu AS numcpu, flavor.memoriaram AS memoriaram,
                flavor.tamanoosdisk AS tamanoosdisk, flavor.tamanoextradisk AS tamanoextradisk, 
                flavor.disextra AS disextra, flavor.precio AS precio, flavor.identificador AS identificador
                FROM flavor INNER JOIN proveedor ON flavor.idproveedor = proveedor.idproveedor
                WHERE idflavor = " . $_POST["idflav"] . " AND flavor.fecha_eliminacion IS NULL
                ORDER BY idflavor ASC;";

    $resultFlavor = pg_query($sqlFlavor) or die('La consulta fallo: ' . pg_last_error());
    $Flavor = pg_fetch_array($resultFlavor);

    echo "<div class='tituloAgregar'>Editar Flavor</div>";

    echo "<form id='editarFlavor' name='editarFlavor' method='post' action='recursos/php/acciones.php?accion=editarFlavor&id=" . $_POST["idflav"] . "'>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Nombre";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='nombreFlavor' name='nombreFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["nombre"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento' style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Proveedor";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<select onchange='actualizaHddsExtraEditar()' class='selectConfiguracion' id='selectedProvEditar' name='selectedProvEditar'>";

    $query_proveedor = "SELECT * FROM proveedor ORDER BY nombre ASC;";
    $result_proveedor = pg_query($query_proveedor) or die("La consulta fallo: " . pg_last_error());
    while ($proveedor = pg_fetch_array($result_proveedor)) {
        if ($Flavor["idproveedor"] == $proveedor["idproveedor"]) {
            echo "<option selected value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
        } else {
            echo "<option value='" . $proveedor["idproveedor"] . "'>" . $proveedor["nombre"] . "</option>";
        }
    }

    echo "</select>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Cantidad de CPUs";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='cpusFlavor' name='cpusFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["numcpu"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Memoria RAM (MB)";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='ramFlavor' name='ramFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["memoriaram"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Espacio HDDs";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='hddFlavor' name='hddFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["tamanoosdisk"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div id='contenedorHDDsExtraEditar'>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Espacio extra HDDs";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='extraHddFlavorEditar' name='extraHddFlavorEditar' type='text' class='entradaConfiguracion' value='" . $Flavor["tamanoextradisk"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Cantidad de extra HDDs";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='numExtraHddFlavorEditar' name='numExtraHddFlavorEditar' type='text' class='entradaConfiguracion' value='" . $Flavor["disextra"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "</div>";
    ?>
    <script type="text/javascript">
        if ($("#selectedProvEditar").val() == 1) {
            $("#contenedorHDDsExtraEditar").hide();
        }
    </script>
    <?php
    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 10px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Precio";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='precioFlavor' name='precioFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["precio"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div class='ConfiguracionElemento'  style='margin-bottom: 20px;'>";
    echo "<div class='ConfiguracionElemento-titulo'>";
    echo "Identificador";
    echo "</div>";
    echo "<div class='ConfiguracionElemento-contenedor'>";
    echo "<input id='idFlavor' name='idFlavor' type='text' class='entradaConfiguracion' value='" . $Flavor["identificador"] . "'/>";
    echo "</div>";
    echo "</div>";

    echo "<div onclick=update() class='agregarBoton' id='botonAgregar' >Editar Flavor</div>";
    echo "<div onclick=cerrarEditar() class='cerrarAgregar' id='cerrarAgregar' style='margin-bottom: 15px'>[CERRAR]</div>";
    echo "</form>";
}

//Actualiza tipos
if ($_POST["accion"] == 62) {
    $query_tipo = "SELECT tipoos.idtipoos AS idtipoos, tipoos.nombre AS nombre, 
                proveedor_tipoos.idproveedor AS idproveedor
                FROM tipoos INNER JOIN proveedor_tipoos ON tipoos.idtipoos = proveedor_tipoos.idtipoos
                WHERE idproveedor = " . $_POST["proveedor"] . ";";
    $result_tipo = pg_query($query_tipo) or die('La consulta fallo: ' . pg_last_error());
    while ($tipo = pg_fetch_array($result_tipo)) {
        echo "<option value='" . $tipo["idtipoos"] . "'>" . $tipo["nombre"] . "</option>";
    }
}


pg_close($conexion);
?>