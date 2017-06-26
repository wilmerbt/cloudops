<?php
session_start();

header('Content-Type: text/html; charset=UTF-8');
require_once("funciones.php");
$conexion = conexion();

if ($_GET["accion"] == "loginUsuario") {
    $sqlLogin = "select * from usuario where idorganizacion='" . $_GET["idorganizacion"] . "' and correo='" . $_POST["correo"] . "' and contrasena='" . md5($_POST["passw"]) . "'";
    $resultLogin = pg_query($sqlLogin) or die('La consulta fallo: ' . pg_last_error());
    if (pg_num_rows($resultLogin) > 0) {
        $login = pg_fetch_array($resultLogin);
        $_SESSION["idorganizacion"] = $_GET["idorganizacion"];
        $_SESSION["idusuario"] = $login["idusuario"];
        ?>  
        <script type="text/javascript">
            alert("Bienvenid@.");
            location.href = "../../crearmaquinavirtual.php";
        </script>
        <?php
    } else {
        $sqlOrganizacion = "select * from organizacion where idorganizacion='" . $_GET["idorganizacion"] . "'";
        $resultOrganizacion = pg_query($sqlOrganizacion) or die('La consulta fallo: ' . pg_last_error());
        $organizacion = pg_fetch_array($resultOrganizacion);
        ?>  
        <script type="text/javascript">
            alert("Credenciales Incorrectas, por favor intentelo de nuevo o pongase en contacto con el administrador del sistema.");
            location.href = "../../<?php echo $organizacion["identificador"]; ?>/index.php";
        </script>
        <?php
    }
}


if ($_GET["accion"] == "crearMaquina") {
    if ($_POST["aux01"] == 1) {
        $insertServidor = "insert into servidor(nombrevm,idlocation,idusuario,idorganizacion,idflavor,idproveedor,idsistemaoperativo,fecha_creacion,usuarioos,contrasenaos,status,idred,idsubred,idgruposeguridad,idkeypair) values('" . $_POST["namemaquina"] . "','" . $_POST["localizacion"] . "','" . $_SESSION["idusuario"] . "','" . $_SESSION["idorganizacion"] . "','" . $_POST["aux04"] . "','" . $_POST["aux01"] . "','" . $_POST["aux03"] . "',now(),'" . $_POST["nameusuario"] . "','" . $_POST["contrasena"] . "','Creando','" . $_POST["redvirtual"] . "','" . $_POST["subredvirtual"] . "','" . $_POST["gruposeguridad"] . "','" . $_POST["selkey"] . "')";
    }
    if ($_POST["aux01"] == 2) {
        $insertServidor = "insert into servidor(nombrevm,idlocation,idusuario,idorganizacion,idflavor,idproveedor,idsistemaoperativo,fecha_creacion,usuarioos,contrasenaos,status,idred,idsubred,idgruposeguridad) values('" . $_POST["namemaquina"] . "','" . $_POST["localizacion"] . "','" . $_SESSION["idusuario"] . "','" . $_SESSION["idorganizacion"] . "','" . $_POST["aux04"] . "','" . $_POST["aux01"] . "','" . $_POST["aux03"] . "',now(),'" . $_POST["nameusuario"] . "','" . $_POST["contrasena"] . "','Creando','" . $_POST["redvirtual"] . "','" . $_POST["subredvirtual"] . "','" . $_POST["gruposeguridad"] . "')";
    }
    $resultServidor = pg_query($insertServidor) or die('La consulta fallo: ' . pg_last_error());

    $sqlMAQUINA = "select * from servidor where nombrevm='" . $_POST["namemaquina"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    //echo $maquina["idservidor"];

    /* Amazon AWS */
    if ($_POST["aux01"] == 1) {

        $sqlUltimo = "SELECT MAX(idservidor) as ultimo FROM servidor;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $sqlOS = "select * from sistemaoperativo where idsistemaoperativo='" . $_POST["aux03"] . "'";
        $resultOS = pg_query($sqlOS) or die('La consulta fallo: ' . pg_last_error());
        $OS = pg_fetch_array($resultOS);

        $sqlFLAVOR = "select * from flavor where idflavor='" . $_POST["aux04"] . "'";
        $resultFLAVOR = pg_query($sqlFLAVOR) or die('La consulta fallo: ' . pg_last_error());
        $FLAVOR = pg_fetch_array($resultFLAVOR);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $_POST["localizacion"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $sqlGRUPO = "select * from gruposeguridad where idgruposeguridad='" . $_POST["gruposeguridad"] . "'";
        $resultGRUPO = pg_query($sqlGRUPO) or die('La consulta fallo: ' . pg_last_error());
        $grupo = pg_fetch_array($resultGRUPO);

        $sqlsubRED = "select * from subred where idsubred='" . $_POST["subredvirtual"] . "'";
        $resultsubRED = pg_query($sqlsubRED) or die('La consulta fallo: ' . pg_last_error());
        $subred = pg_fetch_array($resultsubRED);

        $sqlKeyPair = "select * from keypair where idkeypair='" . $_POST["selkey"] . "'";
        $resultKeyPair = pg_query($sqlKeyPair) or die('La consulta fallo: ' . pg_last_error());
        $keypair = pg_fetch_array($resultKeyPair);

        $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
        $configuracion = pg_fetch_array($resultConfiguracion);


        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid b7b1bf5e-73e4-4115-b07b-55abad78d3ad -rn deploy_' . $_POST["namemaquina"] . ' -in "nombrevm=' . $_POST["namemaquina"] . '" -in "keypair=' . $keypair["nombre"] . '" -in "localizacion=' . $LOCALIZACION["identificador"] . '" -in "flavor=' . $FLAVOR["identificador"] . '" -in "gruposeguridad=' . $grupo["idreal"] . '" -in "subred=' . $subred["idreal"] . '" -in "imagen=' . $OS["identificador"] . '" -in "idservidor=' . $Ultimo["ultimo"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }

    /* Windows Azure */
    if ($_POST["aux01"] == 2) {
        $sqlOS = "select * from sistemaoperativo where idsistemaoperativo='" . $_POST["aux03"] . "'";
        $resultOS = pg_query($sqlOS) or die('La consulta fallo: ' . pg_last_error());
        $OS = pg_fetch_array($resultOS);

        $sqlFLAVOR = "select * from flavor where idflavor='" . $_POST["aux04"] . "'";
        $resultFLAVOR = pg_query($sqlFLAVOR) or die('La consulta fallo: ' . pg_last_error());
        $FLAVOR = pg_fetch_array($resultFLAVOR);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $_POST["localizacion"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $_POST["localizacion"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $sqlGRUPO = "select * from gruposeguridad where idgruposeguridad='" . $_POST["gruposeguridad"] . "'";
        $resultGRUPO = pg_query($sqlGRUPO) or die('La consulta fallo: ' . pg_last_error());
        $grupo = pg_fetch_array($resultGRUPO);

        $sqlRED = "select * from red where idred='" . $_POST["redvirtual"] . "'";
        $resultRED = pg_query($sqlRED) or die('La consulta fallo: ' . pg_last_error());
        $red = pg_fetch_array($resultRED);

        $sqlsubRED = "select * from subred where idsubred='" . $_POST["subredvirtual"] . "'";
        $resultsubRED = pg_query($sqlsubRED) or die('La consulta fallo: ' . pg_last_error());
        $subred = pg_fetch_array($resultsubRED);

        $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
        $configuracion = pg_fetch_array($resultConfiguracion);

        $auxImagen = explode("_", $OS["identificador"]);
        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid c607bf9a-c33c-4311-aa4b-bb4bd6424070 -rn deploy_' . $_POST["namemaquina"] . ' -in "nombrevm=' . $_POST["namemaquina"] . '" -in "localizacion=' . $LOCALIZACION["identificador"] . '" -in "localizacionesp=' . $LOCALIZACION["identificadorespecial"] . '" -in "flavor=' . $FLAVOR["identificador"] . '" -in "ospublica=' . $auxImagen[0] . '" -in "osoferta=' . $auxImagen[1] . '" -in "ossku=' . $auxImagen[2] . '" -in "osversion=' . $auxImagen[3] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "tipoOS=' . $OS["clasifica"] . '" -in "usuarioOS=' . $_POST["nameusuario"] . '" -in "passwordOS=' . $_POST["contrasena"] . '" -in "idmaquina=' . $maquina["idservidor"] . '" -in "cuentaalmacenamiento=' . $Suscripcion["estorageacount"] . '" -in "contenedor=' . $Suscripcion["contenedorstorage"] . '" -in "nameSecurityGroup=' . $grupo["idreal"] . '" -in "nameRed=' . $red["idreal"] . '" -in "nameSubRed=' . $subred["idreal"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "apagarMaquina") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["id"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlUpdate = "update servidor set status='Apagando' where idservidor='" . $_GET["id"] . "'";
    $resultUpdate = pg_query($sqlUpdate) or die('La consulta fallo: ' . pg_last_error());

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {
        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid e69939d5-ecbc-4c59-a795-f06d46ba720a -rn poweroff_' . $maquina["nombrevm"] . ' -in "IdentificadorVM=' . $maquina["vmid"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idservidor=' . $maquina["idservidor"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
        $configuracion = pg_fetch_array($resultConfiguracion);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid f0cd76f8-7b8a-456f-b689-6c0df6af206e -rn poweroff_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '"  -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "encenderMaquina") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["id"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlUpdate = "update servidor set status='Encendiendo' where idservidor='" . $_GET["id"] . "'";
    $resultUpdate = pg_query($sqlUpdate) or die('La consulta fallo: ' . pg_last_error());

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {
        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 404bd698-702f-4080-811f-e7c1e8ae587f -rn poweron_' . $maquina["nombrevm"] . ' -in "IdentificadorVM=' . $maquina["vmid"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idservidor=' . $_GET["id"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 38ea4243-407e-44e3-969e-51c38bc6994f -rn poweron_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "reiniciarMaquina") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["id"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlUpdate = "update servidor set status='Reiniciando' where idservidor='" . $_GET["id"] . "'";
    $resultUpdate = pg_query($sqlUpdate) or die('La consulta fallo: ' . pg_last_error());

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {
        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 8169b07c-04b2-4b11-9ec4-f9af3ae43b98 -rn reboot_' . $maquina["nombrevm"] . ' -in "IdentificadorVM=' . $maquina["vmid"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idservidor=' . $maquina["idservidor"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid a5d397ab-59c5-4913-92c5-12179307f6f1 -rn reboot_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "eliminarMaquina") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["id"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlUpdate = "update servidor set status='Eliminando' where idservidor='" . $_GET["id"] . "'";
    $resultUpdate = pg_query($sqlUpdate) or die('La consulta fallo: ' . pg_last_error());

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {
        $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
        $configuracion = pg_fetch_array($resultConfiguracion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid c4f4bdf9-b280-4940-a026-95cdf1ba1ad7 -rn undeploy_' . $maquina["nombrevm"] . ' -in "identificadorvm=' . $maquina["vmid"] . '"  -in "idservidor=' . $_GET["id"] . '"  -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '" ');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 458c99de-d16d-4d25-8a0c-4478dc552199 -rn undeploy_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_GET["id"] . '" -in "cuentaalmacenamiento=' . $Suscripcion["estorageacount"] . '" -in "contenedor=' . $Suscripcion["contenedorstorage"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"  -in "storagekey=' . $configuracion["azustoragekey"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../maquinasvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "agregarDisco") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_POST["idservidoragrega"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {
        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlOS = "select * from sistemaoperativo where idsistemaoperativo='" . $maquina["idsistemaoperativo"] . "'";
        $resultOS = pg_query($sqlOS) or die('La consulta fallo: ' . pg_last_error());
        $OS = pg_fetch_array($resultOS);

        $sqlinsertDisco = "insert into disco(idservidor,nombre,sizegb,estatus,fecha_creacion,estatusazure,idenproveedor,puntomontaje) values('" . $_POST["idservidoragrega"] . "','" . $_POST["nombredisco"] . "'," . $_POST["tamanodisco"] . ",'Creando',now(),null,null,null);";
        $resultinsertDisco = pg_query($sqlinsertDisco) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(iddisco) as ultimo FROM disco;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid cab792b8-807a-432c-901e-d6b3f08bcd40 -rn adddisk_' . $_POST["nombredisco"] . '_' . $maquina["nombrevm"] . ' -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "zona=' . $localizacion["identificador"] . '" -in "idmaquina=' . $maquina["vmid"] . '" -in "tipoOS=' . $OS["clasifica"] . '" -in "nombrevolumen=' . $_POST["nombredisco"] . '" -in "tamanodisk=' . $_POST["tamanodisco"] . '" -in "iddisco=' . $Ultimo["ultimo"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlFLAVOR = "select * from flavor where idflavor='" . $maquina["idflavor"] . "'";
        $resultFLAVOR = pg_query($sqlFLAVOR) or die('La consulta fallo: ' . pg_last_error());
        $FLAVOR = pg_fetch_array($resultFLAVOR);

        $sqldiscos = "select * from disco where idservidor='" . $_POST["idservidoragrega"] . "' and estatus='on-line' and fecha_eliminacion is null;";
        $resultdiscos = pg_query($sqldiscos) or die('La consulta fallo: ' . pg_last_error());
        $discos = pg_fetch_array($resultdiscos);


        if (pg_num_rows($resultdiscos) < $FLAVOR["disextra"]) {
            //echo "</br>SE PUEDE CREAR";
            $siguienteLun = 0;
            $sqlSecunciaLun = "select * from disco where idservidor='" . $_POST["idservidoragrega"] . "' order by lun desc;";
            $resultSecuenciaLun = pg_query($sqlSecunciaLun) or die('La consulta fallo: ' . pg_last_error());
            if (pg_num_rows($resultSecuenciaLun) == 0) {
                $siguienteLun = 1;
            } else {
                $siguienteLUNA = pg_fetch_array($resultSecuenciaLun);
                $siguienteLun = $siguienteLUNA["lun"] + 1;
            }

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $sqlinsertDisco = "insert into disco(idservidor,nombre,lun,uri,sizegb,estatus,fecha_creacion,estatusazure,idenproveedor,puntomontaje) values('" . $_POST["idservidoragrega"] . "','" . $_POST["nombredisco"] . "'," . $siguienteLun . ",'https://" . $Suscripcion["estorageacount"] . ".blob.core.windows.net/" . $Suscripcion["contenedorstorage"] . "/" . $maquina["nombrevm"] . "-" . $_POST["nombredisco"] . ".vhd'," . $_POST["tamanodisco"] . ",'Creando',now(),'empty','" . $maquina["nombrevm"] . "-" . $_POST["nombredisco"] . ".vhd" . "',null);";
            $resultinsertDisco = pg_query($sqlinsertDisco) or die('La consulta fallo: ' . pg_last_error());

            $sqlUltimo = "SELECT MAX(iddisco) as ultimo FROM disco;";
            $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
            $Ultimo = pg_fetch_array($resultUltimo);

            $concatena = "";
            $bandera = 0;
            $sqlenvia = "select * from disco where idservidor='" . $_POST["idservidoragrega"] . "' and estatus='on-line' or estatus='Creando' and fecha_eliminacion is null;";
            $resultenvia = pg_query($sqlenvia) or die('La consulta fallo: ' . pg_last_error());
            while ($envia = pg_fetch_array($resultenvia)) {

                if ($envia["iddisco"] == $Ultimo["ultimo"]) {

                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_empty";
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_empty";
                    }
                } else {

                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                    }
                }
            }

            $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $localizacion = pg_fetch_array($resultLOCALIZACION);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 1c5eee58-cea8-4f9f-bd0e-15ff3cd7b091 -rn adddisk_' . $_POST["nombredisco"] . '_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "location=' . $localizacion["identificador"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_POST["idservidoragrega"] . '" -in "discos=' . $concatena . '" -in "iddisco=' . $Ultimo["ultimo"] . '" -in "estatusFinal=on-line" -in "estatusNoFinal=error" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../discos.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No es posible agregar el disco, ya que el flavor del servidor permite un maximo de <?php echo $FLAVOR["disextra"]; ?> discos extra y ya los tiene todos atachados");
                location.href = "../../maquinasvirtuales.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "desconectarDisco") {

    $sqlDesconecta = "update disco set estatus='Desconectando' where iddisco='" . $_GET["id"] . "'";
    $resultDesconecta = pg_query($sqlDesconecta) or die('La consulta fallo: ' . pg_last_error());
    $desconecta = pg_fetch_array($resultDesconecta);

    $sqlDisco = "select * from disco where iddisco='" . $_GET["id"] . "'";
    $resultDisco = pg_query($sqlDisco) or die('La consulta fallo: ' . pg_last_error());
    $disco = pg_fetch_array($resultDisco);

    $sqlProveedor = "select * from servidor where idservidor='" . $disco["idservidor"] . "'";
    $resultProveedor = pg_query($sqlProveedor) or die('La consulta fallo: ' . pg_last_error());
    $proveedor = pg_fetch_array($resultProveedor);

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($proveedor["idproveedor"] == 1) {

        $sqlMAQUINA = "select * from servidor where idservidor='" . $disco["idservidor"] . "'";
        $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
        $maquina = pg_fetch_array($resultMAQUINA);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 9af66090-60d9-4e41-a93b-fe70c004d1ae -rn updatedisks_' . $maquina["nombrevm"] . ' -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idmaquina=' . $maquina["vmid"] . '" -in "idvolumen=' . $disco["idenproveedor"] . '" -in "puntoMontaje=' . $disco["puntomontaje"] . '" -in "iddisco=' . $_GET["id"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }

    if ($proveedor["idproveedor"] == 2) {

        $concatena = "";
        $bandera = 0;
        $sqlenvia = "select * from disco where idservidor='" . $disco["idservidor"] . "' and estatus='on-line' or estatus='Conectando';";
        $resultenvia = pg_query($sqlenvia) or die('La consulta fallo: ' . pg_last_error());
        while ($envia = pg_fetch_array($resultenvia)) {
            if ($bandera == 0) {
                $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                $bandera = 1;
            } else {
                $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
            }
        }

        $sqlMAQUINA = "select * from servidor where idservidor='" . $disco["idservidor"] . "'";
        $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
        $maquina = pg_fetch_array($resultMAQUINA);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        if ($concatena == "") {
            $concatena = "vacio";
        }

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 1c5eee58-cea8-4f9f-bd0e-15ff3cd7b091 -rn updatedisks_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "location=' . $localizacion["identificador"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $disco["idservidor"] . '" -in "discos=' . $concatena . '" -in "iddisco=' . $_GET["id"] . '" -in "estatusFinal=off-line" -in "estatusNoFinal=on-line" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "conectarDisco") {

    $sqlDisco = "select * from disco where iddisco='" . $_GET["id"] . "'";
    $resultDisco = pg_query($sqlDisco) or die('La consulta fallo: ' . pg_last_error());
    $disco = pg_fetch_array($resultDisco);

    $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["idservidor"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {

        $sqlOS = "select * from sistemaoperativo where idsistemaoperativo='" . $maquina["idsistemaoperativo"] . "'";
        $resultOS = pg_query($sqlOS) or die('La consulta fallo: ' . pg_last_error());
        $OS = pg_fetch_array($resultOS);

        $sqlConecta = "update disco set estatus='Conectando', idservidor='" . $_GET["idservidor"] . "' where iddisco='" . $_GET["id"] . "'";
        $resultConecta = pg_query($sqlConecta) or die('La consulta fallo: ' . pg_last_error());
        $conecta = pg_fetch_array($resultConecta);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid b2ae3e94-5c12-4d40-ac2a-05080d1f4c87 -rn updatedisks_' . $maquina["nombrevm"] . ' -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idmaquina=' . $maquina["vmid"] . '" -in "idvolumen=' . $disco["idenproveedor"] . '" -in "tipoOS=' . $OS["clasifica"] . '" -in "iddisco=' . $_GET["id"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {

        $sqldiscos = "select * from disco where idservidor='" . $_GET["idservidor"] . "' and estatus='on-line' and fecha_eliminacion is null;";
        $resultdiscos = pg_query($sqldiscos) or die('La consulta fallo: ' . pg_last_error());
        $discos = pg_fetch_array($resultdiscos);

        $sqlFLAVOR = "select * from flavor where idflavor='" . $maquina["idflavor"] . "'";
        $resultFLAVOR = pg_query($sqlFLAVOR) or die('La consulta fallo: ' . pg_last_error());
        $FLAVOR = pg_fetch_array($resultFLAVOR);


        if (pg_num_rows($resultdiscos) < $FLAVOR["disextra"]) {

            $sqlDesconecta = "update disco set estatus='Conectando', estatusazure='attach', idservidor='" . $_GET["idservidor"] . "' where iddisco='" . $_GET["id"] . "'";
            $resultDesconecta = pg_query($sqlDesconecta) or die('La consulta fallo: ' . pg_last_error());
            $desconecta = pg_fetch_array($resultDesconecta);

            $concatena = "";
            $bandera = 0;
            $sqlenvia = "select * from disco where idservidor='" . $_GET["idservidor"] . "' and estatus='on-line' or estatus='Conectando';";
            $resultenvia = pg_query($sqlenvia) or die('La consulta fallo: ' . pg_last_error());
            while ($envia = pg_fetch_array($resultenvia)) {
                if ($_GET["id"] == $envia["iddisco"]) {
                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_attach";
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_attach";
                    }
                } else {
                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                    }
                }
            }

            $sqlMAQUINA = "select * from servidor where idservidor='" . $_GET["idservidor"] . "'";
            $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
            $maquina = pg_fetch_array($resultMAQUINA);

            $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $localizacion = pg_fetch_array($resultLOCALIZACION);

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 1c5eee58-cea8-4f9f-bd0e-15ff3cd7b091 -rn updatedisks_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "location=' . $localizacion["identificador"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $disco["idservidor"] . '" -in "discos=' . $concatena . '" -in "iddisco=' . $_GET["id"] . '" -in "estatusFinal=on-line" -in "estatusNoFinal=off-line" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../discos.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No es posible conectar el disco, ya que el flavor del servidor permite un maximo de <?php echo $FLAVOR["disextra"]; ?> discos extra y ya los tiene todos atachados");
                location.href = "../../discos.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "eliminarDisco") {

    $sqlPrevio = "select * from disco where iddisco='" . $_GET["id"] . "'";
    $resultPrevio = pg_query($sqlPrevio) or die('La consulta fallo: ' . pg_last_error());
    $previo = pg_fetch_array($resultPrevio);

    if ($previo["estatus"] != "error") {

        $sqlElimina = "update disco set estatus='Eliminando', estatusazure='attach' where iddisco='" . $_GET["id"] . "'";
        $resultElimina = pg_query($sqlElimina) or die('La consulta fallo: ' . pg_last_error());
        $elimina = pg_fetch_array($resultElimina);

        $sqlDisco = "select * from disco where iddisco='" . $_GET["id"] . "'";
        $resultDisco = pg_query($sqlDisco) or die('La consulta fallo: ' . pg_last_error());
        $disco = pg_fetch_array($resultDisco);

        $sqlMAQUINA = "select * from servidor where idservidor='" . $disco["idservidor"] . "'";
        $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
        $maquina = pg_fetch_array($resultMAQUINA);

        $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
        $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
        $configuracion = pg_fetch_array($resultConfiguracion);

        if ($maquina["idproveedor"] == 1) {

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 1668f14f-2b56-4d12-84d4-4dfc9135afd5 -rn updatedisks_' . $maquina["nombrevm"] . ' -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "idvolumen=' . $disco["idenproveedor"] . '" -in "iddisco=' . $_GET["id"] . '" -in "osip=' . $configuracion["osip"] . '"  -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../discos.php";
            </script>
            <?php
        }


        if ($maquina["idproveedor"] == 2) {

            $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $localizacion = pg_fetch_array($resultLOCALIZACION);

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $localizacion["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 7ff486a2-372c-4516-89e7-9ba09f4abcdb -rn deletedisk_' . $disco["nombre"] . ' -in "auxcuenta=' . $Suscripcion["estorageacount"] . '" -in "auxcontenedor=' . $Suscripcion["contenedorstorage"] . '" -in "auxblob=' . $disco["idenproveedor"] . '" -in "iddisco=' . $_GET["id"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"  -in "storagekey=' . $configuracion["azustoragekey"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../discos.php";
            </script>

            <?php
        }
    } else {
        $sqlElimina = "update disco set estatus='off-line', fecha_eliminacion=now() where iddisco='" . $_GET["id"] . "'";
        $resultElimina = pg_query($sqlElimina) or die('La consulta fallo: ' . pg_last_error());
        $elimina = pg_fetch_array($resultElimina);
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }
}


if ($_GET["accion"] == "agregarRed") {

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    /* Amazon AWS */
    if ($_POST["selproveedor"] == 1) {

        $sqlRedesActuales = "select * from red where idproveedor=1 and idorganizacion='" . $_SESSION["idorganizacion"] . "' and fecha_eliminacion is null";
        $resultRedesActuales = pg_query($sqlRedesActuales) or die('La consulta fallo: ' . pg_last_error());
        $numeroRedesActual = pg_num_rows($resultRedesActuales);

        if (($configuracion["redesenaws"] + $numeroRedesActual) < 5) {

            $query_usuario = "SELECT * FROM usuario where idusuario='" . $_SESSION["idusuario"] . "'";
            $result_usuario = pg_query($query_usuario) or die('La consulta fallo: ' . pg_last_error());
            $usuario = pg_fetch_array($result_usuario);

            $sqlLOCALIZACION = "select * from location where idlocation='" . $_POST["sellocalizacion"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

            $sqlRed = "insert into red(idproveedor,idorganizacion,idlocation,nombre,idreal,ipv4cidr,fecha_creacion,fecha_eliminacion) values(1,'" . $usuario["idorganizacion"] . "','" . $_POST["sellocalizacion"] . "','" . $_POST["nombrered"] . "',null,'" . $_POST["cidr"] . "',now(),null);";
            $resultRed = pg_query($sqlRed) or die('La consulta fallo: ' . pg_last_error());

            $sqlUltimo = "SELECT MAX(idred) as ultimo FROM red;";
            $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
            $Ultimo = pg_fetch_array($resultUltimo);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 8ea0d94a-6dd1-4afc-94f7-5489369530fa -rn addvirtualnet_' . $_POST["nombrered"] . ' -in "nombreVirtualNetwork=' . $_POST["nombrered"] . '" -in "localizacionVirtualNetwork=' . $LOCALIZACION["identificador"] . '" -in "espaciodedirecciones=' . $_POST["cidr"] . '" -in "idproveedor=' . $_POST["selproveedor"] . '"  -in "idorganizacion=' . $usuario["idorganizacion"] . '"  -in "idlocation=' . $_POST["sellocalizacion"] . '"  -in "idred=' . $Ultimo["ultimo"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../redesvirtuales.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede crear la red, dado que supera el número limite de redes permitidas en la plataforma de Amazon AWS que es de (5)");
                location.href = "../../redesvirtuales.php";
            </script>
            <?php
        }
    }

    /* Windows Azure */
    if ($_POST["selproveedor"] == 2) {

        $query_usuario = "SELECT * FROM usuario where idusuario='" . $_SESSION["idusuario"] . "'";
        $result_usuario = pg_query($query_usuario) or die('La consulta fallo: ' . pg_last_error());
        $usuario = pg_fetch_array($result_usuario);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $_POST["sellocalizacion"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $sqlRed = "insert into red(idproveedor,idorganizacion,idlocation,nombre,idreal,ipv4cidr,fecha_creacion,fecha_eliminacion) values(2,'" . $usuario["idorganizacion"] . "','" . $_POST["sellocalizacion"] . "','" . $_POST["nombrered"] . "',null,'" . $_POST["cidr"] . "',now(),null);";
        $resultRed = pg_query($sqlRed) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idred) as ultimo FROM red;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $_POST["sellocalizacion"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 2c9ef76d-294e-4c9b-ac5f-0a318c1e8e49 -rn addvirtualnet_' . $_POST["nombrered"] . ' -in "nombreVirtualNetwork=' . $_POST["nombrered"] . '" -in "grupo=' . $Suscripcion["gruporecursos"] . '" -in "localizacionVirtualNetwork=' . $LOCALIZACION["identificadorespecial"] . '" -in "espaciodedirecciones=' . $_POST["cidr"] . '" -in "idproveedor=' . $_POST["selproveedor"] . '"  -in "idorganizacion=' . $usuario["idorganizacion"] . '"  -in "idlocation=' . $_POST["sellocalizacion"] . '" -in "idred=' . $Ultimo["ultimo"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../redesvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "agregarSubRed") {

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    /* Amazon AWS */
    if ($_POST["selproveedor"] == 1) {
        $query_red = "SELECT * FROM red where idred='" . $_POST["selred"] . "'";
        $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
        $red = pg_fetch_array($result_red);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $red["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $sqlsubred = "insert into subred(idred,nombre,idreal,ipv4cidr,fecha_creacion,fecha_eliminacion) values('" . $_POST["selred"] . "','" . $_POST["nombresubred"] . "',null,'" . $_POST["cidr"] . "',now(),null);";
        $resultsubred = pg_query($sqlsubred) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idsubred) as ultimo FROM subred;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid c03feb8b-c312-4d6e-b360-a9a82f8532ca -rn addsubnet_' . $_POST["nombresubred"] . ' -in "location=' . $LOCALIZACION["identificador"] . '" -in "cidr=' . $_POST["cidr"] . '" -in "vpc=' . $red["idreal"] . '" -in "nombresubred=' . $_POST["nombresubred"] . '" -in "idred=' . $_POST["selred"] . '" -in "idsubred=' . $Ultimo["ultimo"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../subredesvirtuales.php";
        </script>
        <?php
    }

    /* Windows Azure */
    if ($_POST["selproveedor"] == 2) {
        $query_red = "SELECT * FROM red where idred='" . $_POST["selred"] . "'";
        $result_red = pg_query($query_red) or die('La consulta fallo: ' . pg_last_error());
        $red = pg_fetch_array($result_red);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $red["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $sqlsubred = "insert into subred(idred,nombre,idreal,ipv4cidr,fecha_creacion,fecha_eliminacion) values('" . $_POST["selred"] . "','" . $_POST["nombresubred"] . "',null,'" . $_POST["cidr"] . "',now(),null);";
        $resultsubred = pg_query($sqlsubred) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idsubred) as ultimo FROM subred;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $concatenaSubredes = "";
        $banderaConcatena = 0;
        $query_subredes = "SELECT * FROM subred where idred='" . $_POST["selred"] . "' and fecha_eliminacion is null";
        $result_subredes = pg_query($query_subredes) or die('La consulta fallo: ' . pg_last_error());
        while ($subred = pg_fetch_array($result_subredes)) {
            if ($banderaConcatena == 0) {
                $concatenaSubredes = $subred["nombre"] . "_" . $subred["ipv4cidr"];
                $banderaConcatena = 1;
            } else {
                $concatenaSubredes = $concatenaSubredes . "__" . $subred["nombre"] . "_" . $subred["ipv4cidr"];
            }
        }

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $red["idlocation"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid cba41f13-cbac-4096-b08b-ec14ad7ceb5e -rn addsubnet_' . $_POST["nombresubred"] . ' -in "nombreVirtualNetwork=' . $red["idreal"] . '" -in "subredes=' . $concatenaSubredes . '" -in "cidrred=' . trim($red["ipv4cidr"]) . '" -in "grupo=' . $Suscripcion["gruporecursos"] . '" -in "localizacionVirtualNetwork=' . $LOCALIZACION["identificadorespecial"] . '"  -in "addname=' . $_POST["nombresubred"] . '" -in "addcidr=' . $_POST["cidr"] . '" -in "addidred=' . $_POST["selred"] . '"  -in "idsubred=' . $Ultimo["ultimo"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../subredesvirtuales.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "eliminarRed") {
    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    $sqlred = "select * from red where idred='" . $_GET["id"] . "'";
    $resultred = pg_query($sqlred) or die('La consulta fallo 01: ' . pg_last_error());
    $redE = pg_fetch_array($resultred);

    if ($redE["idproveedor"] == 1) {
        $sqlServidores = "select * from servidor where idred='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {

            $sqlSubRedes = "select * from subred where idred='" . $_GET["id"] . "' and fecha_eliminacion is null";
            $resultSubRedes = pg_query($sqlSubRedes) or die('La consulta fallo: ' . pg_last_error());
            if (pg_num_rows($resultSubRedes) == 0) {

                $sqlGrupos = "select * from gruposeguridad where idred='" . $_GET["id"] . "' and fecha_eliminacion is null";
                $resultGrupos = pg_query($sqlGrupos) or die('La consulta fallo: ' . pg_last_error());

                if (pg_num_rows($resultGrupos) == 0) {
                    $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 22c3a418-bf1b-4181-a13f-7750747156a6 -rn deleteRed_' . $redE["nombre"] . ' -in "idvpc=' . $redE["idreal"] . '" -in "idgateway=' . $redE["internetgateway"] . '" -in "idred=' . $_GET["id"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
                    ?>  
                    <script type="text/javascript">
                        location.href = "../../redesvirtuales.php";
                    </script>
                    <?php
                } else {
                    ?>  
                    <script type="text/javascript">
                        alert("No se puede eliminar la red ya que existen grupos de seguridad vinculados a la misma.");
                        location.href = "../../redesvirtuales.php";
                    </script>
                    <?php
                }
            } else {
                ?>  
                <script type="text/javascript">
                    alert("No se puede eliminar la red ya que existen subredes vinculados a la misma.");
                    location.href = "../../redesvirtuales.php";
                </script>
                <?php
            }
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar la red ya que existen servidores vinculados a la misma.");
                location.href = "../../redesvirtuales.php";
            </script>
            <?php
        }
    }

    if ($redE["idproveedor"] == 2) {
        $sqlServidores = "select * from servidor where idred='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {

            $sqlSubRedes = "select * from subred where idred='" . $_GET["id"] . "' and fecha_eliminacion is null";
            $resultSubRedes = pg_query($sqlSubRedes) or die('La consulta fallo: ' . pg_last_error());

            if (pg_num_rows($resultSubRedes) == 0) {

                $sqlLOCALIZACION = "select * from location where idlocation='" . $redE["idlocation"] . "'";
                $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
                $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

                $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $redE["idlocation"] . "'";
                $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
                $Suscripcion = pg_fetch_array($resultSuscripcion);


                $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 98d0d146-99cf-43df-9d30-e6f35fe385ba -rn deleteRed_' . $redE["nombre"] . ' -in "grupo=' . $Suscripcion["gruporecursos"] . '" -in "redaeliminar=' . $redE["idreal"] . '" -in "idred=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
                ?>  
                <script type="text/javascript">
                    location.href = "../../redesvirtuales.php";
                </script>
                <?php
            } else {
                ?>  
                <script type="text/javascript">
                    alert("No se puede eliminar la red ya que existen subredes vinculados a la misma.");
                    location.href = "../../redesvirtuales.php";
                </script>
                <?php
            }
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar la red ya que existen servidores vinculados a la misma.");
                location.href = "../../redesvirtuales.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "eliminarSubRed") {
    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    $sqlSubred = "select * from subred where idsubred='" . $_GET["id"] . "'";
    $resultSubred = pg_query($sqlSubred) or die('La consulta fallo 01: ' . pg_last_error());
    $SubredE = pg_fetch_array($resultSubred);

    $sqlProveedor = "select * from red where idred='" . $SubredE["idred"] . "'";
    $resultProveedor = pg_query($sqlProveedor) or die('La consulta fallo 01: ' . pg_last_error());
    $Proveedor = pg_fetch_array($resultProveedor);

    if ($Proveedor["idproveedor"] == 1) {
        $sqlServidores = "select * from servidor where idsubred='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {
            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid ff5d20df-3cc5-4515-9908-7450f5133808 -rn deleteSubNet_' . $SubredE["nombre"] . '  -in "idsubnet=' . $SubredE["idreal"] . '" -in "idsubred=' . $_GET["id"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../subredesvirtuales.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar la subred ya que existen servidores vinculados a la misma.");
                location.href = "../../subredesvirtuales.php";
            </script>
            <?php
        }
    }

    if ($Proveedor["idproveedor"] == 2) {
        $sqlServidores = "select * from servidor where idsubred='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {
            $sqlsubred = "update subred set fecha_eliminacion = now() where idsubred='" . $_GET["id"] . "'";
            $resultsubred = pg_query($sqlsubred) or die('La consulta fallo: ' . pg_last_error());

            $concatenaSubredes = "";
            $banderaConcatena = 0;
            $query_subredes = "SELECT * FROM subred where idred='" . $SubredE["idred"] . "' and fecha_eliminacion is null";
            $result_subredes = pg_query($query_subredes) or die('La consulta fallo: ' . pg_last_error());
            while ($subred = pg_fetch_array($result_subredes)) {
                if ($banderaConcatena == 0) {
                    $concatenaSubredes = $subred["nombre"] . "_" . $subred["ipv4cidr"];
                    $banderaConcatena = 1;
                } else {
                    $concatenaSubredes = $concatenaSubredes . "__" . $subred["nombre"] . "_" . $subred["ipv4cidr"];
                }
            }

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $Proveedor["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $sqlLOCALIZACION = "select * from location where idlocation='" . $Proveedor["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

            if ($concatenaSubredes == "") {
                $concatenaSubredes = "vacio";
            }

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 4c0fd02b-c776-48ae-be71-7be41e278e3f -rn deletesubnet_' . $SubredE["nombre"] . ' -in "nombreVirtualNetwork=' . $Proveedor["idreal"] . '" -in "subredes=' . $concatenaSubredes . '" -in "cidrred=' . trim($Proveedor["ipv4cidr"]) . '" -in "grupo=' . $Suscripcion["gruporecursos"] . '" -in "localizacionVirtualNetwork=' . $LOCALIZACION["identificadorespecial"] . '"  -in "addname=' . $SubredE["nombre"] . '" -in "addcidr=' . $SubredE["ipv4cidr"] . '" -in "addidred=' . $SubredE["idred"] . '" -in "idsubred=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../subredesvirtuales.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar la subred ya que existen servidores vinculados a la misma.");
                location.href = "../../subredesvirtuales.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "eliminarGrupoSeguridad") {

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    $sqlGrupo = "select * from gruposeguridad where idgruposeguridad='" . $_GET["id"] . "'";
    $resultGrupo = pg_query($sqlGrupo) or die('La consulta fallo 01: ' . pg_last_error());
    $GrupoE = pg_fetch_array($resultGrupo);

    if ($GrupoE["idproveedor"] == 1) {
        $sqlServidores = "select * from servidor where idgruposeguridad='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 869e9805-1b50-44bd-91b6-d62040985c64 -rn deletesecurityGroup_' . $GrupoE["nombre"] . '  -in "idsecurity=' . $GrupoE["idreal"] . '" -in "idgruposeguridad=' . $_GET["id"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../gruposdeseguridad.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar el grupo de seguridad ya que existen servidores asociados al mismo.");
                location.href = "../../gruposdeseguridad.php";
            </script>
            <?php
        }
    }

    if ($GrupoE["idproveedor"] == 2) {

        $sqlServidores = "select * from servidor where idgruposeguridad='" . $_GET["id"] . "' and fecha_eliminacion is null";
        $resultServidores = pg_query($sqlServidores) or die('La consulta fallo: ' . pg_last_error());

        if (pg_num_rows($resultServidores) == 0) {

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $GrupoE["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $sqlLOCALIZACION = "select * from location where idlocation='" . $GrupoE["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 6d0e7240-ec45-4613-a24e-5e8a75fd70d1 -rn deletesecurityGroup_' . $GrupoE["nombre"] . ' -in "grupoaeliminar=' . $GrupoE["idreal"] . '" -in "grupo=' . $Suscripcion["gruporecursos"] . '" -in "idsecurity=' . $_GET["id"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../gruposdeseguridad.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar el grupo de seguridad ya que existen servidores asociados al mismo.");
                location.href = "../../gruposdeseguridad.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "crearGrupoSeguridad") {

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($_POST["selproveedor"] == 1) {

        $sqlGrupo = "insert into gruposeguridad(idproveedor,idlocation,nombre,idreal,fecha_creacion,fecha_eliminacion,idred,idorganizacion) values('" . $_POST["selproveedor"] . "','" . $_POST["sellocalizacion"] . "','" . $_POST["nombregrupo"] . "',null,now(),null,'" . $_POST["selred"] . "','" . $_SESSION["idorganizacion"] . "');";
        $resultGrupo = pg_query($sqlGrupo) or die('La consulta fallo 01: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idgruposeguridad) as ultimo FROM gruposeguridad;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo 02: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        //echo "--->".$_POST["selred"]."<--";
        $sqlRed = "SELECT * from red where idred='" . $_POST["selred"] . "';";
        $resultRed = pg_query($sqlRed) or die('La consulta fallo 03: ' . pg_last_error());
        $red = pg_fetch_array($resultRed);

        $reglas = explode("__", $_POST["concatenado"]);
        for ($i = 0; $i < count($reglas); $i++) {
            $detalleReglas = explode("_", $reglas[$i]);
            $prioridad = "";
            if ($detalleReglas[6] == "-") {
                $prioridad = "null";
            } else {
                $prioridad = $detalleReglas[6];
            }
            $sqlDetalleRegla = "insert into reglagrupo(idgruposeguridad,nombreregla,protocolo,puerto,accion,origen,destino,tipo,prioridad) values('" . $Ultimo["ultimo"] . "','" . $detalleReglas[0] . "','" . $detalleReglas[1] . "','" . $detalleReglas[2] . "','" . $detalleReglas[5] . "','" . $detalleReglas[3] . "','" . $detalleReglas[4] . "','" . $detalleReglas[7] . "'," . $prioridad . ");";
            $resultDetalleRegla = pg_query($sqlDetalleRegla) or die('La consulta fallo: ' . pg_last_error());
        }

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 75746688-7a91-457d-92d3-9404a8d65823 -rn addsecurityGroup_' . $_POST["nombregrupo"] . ' -in "reglas=' . $_POST["concatenado"] . '" -in "gruponombre=' . $_POST["nombregrupo"] . '" -in "idgrupo=' . $Ultimo["ultimo"] . '" -in "identificadorRED=' . $red["idreal"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '"  -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../gruposdeseguridad.php";
        </script>
        <?php
    }

    if ($_POST["selproveedor"] == 2) {
        $sqlGrupo = "insert into gruposeguridad(idproveedor,idlocation,nombre,idreal,fecha_creacion,fecha_eliminacion,idorganizacion) values('" . $_POST["selproveedor"] . "','" . $_POST["sellocalizacion"] . "','" . $_POST["nombregrupo"] . "',null,now(),null,'" . $_SESSION["idorganizacion"] . "');";
        $resultGrupo = pg_query($sqlGrupo) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idgruposeguridad) as ultimo FROM gruposeguridad;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $sqlLOCALIZACION = "select * from location where idlocation='" . $_POST["sellocalizacion"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $LOCALIZACION = pg_fetch_array($resultLOCALIZACION);

        $reglas = explode("__", $_POST["concatenado"]);
        for ($i = 0; $i < count($reglas); $i++) {
            $detalleReglas = explode("_", $reglas[$i]);
            $sqlDetalleRegla = "insert into reglagrupo(idgruposeguridad,nombreregla,protocolo,puerto,accion,origen,destino,tipo,prioridad) values('" . $Ultimo["ultimo"] . "','" . $detalleReglas[0] . "','" . $detalleReglas[1] . "','" . $detalleReglas[2] . "','" . $detalleReglas[5] . "','" . $detalleReglas[3] . "','" . $detalleReglas[4] . "','" . $detalleReglas[7] . "'," . $detalleReglas[6] . ");";
            $resultDetalleRegla = pg_query($sqlDetalleRegla) or die('La consulta fallo: ' . pg_last_error());
        }

        $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $_POST["sellocalizacion"] . "'";
        $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
        $Suscripcion = pg_fetch_array($resultSuscripcion);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 6ef5e137-3190-42bf-b88e-5763fd28c175 -rn addsecurityGroup_' . $_POST["nombregrupo"] . ' -in "reglas=' . $_POST["concatenado"] . '" -in "nombregrupo=' . $_POST["nombregrupo"] . '" -in "localizacionGrupo=' . $LOCALIZACION["identificadorespecial"] . '"  -in "grupoRecursos=' . $Suscripcion["gruporecursos"] . '" -in "idgruposeguridad=' . $Ultimo["ultimo"] . '" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../gruposdeseguridad.php";
        </script>
        <?php
    }
}



if ($_GET["accion"] == "agregarDisco2") {
    $sqlMAQUINA = "select * from servidor where idservidor='" . $_POST["selservidor2"] . "'";
    $resultMAQUINA = pg_query($sqlMAQUINA) or die('La consulta fallo: ' . pg_last_error());
    $maquina = pg_fetch_array($resultMAQUINA);

    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($maquina["idproveedor"] == 1) {

        $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
        $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
        $localizacion = pg_fetch_array($resultLOCALIZACION);

        $sqlOS = "select * from sistemaoperativo where idsistemaoperativo='" . $maquina["idsistemaoperativo"] . "'";
        $resultOS = pg_query($sqlOS) or die('La consulta fallo: ' . pg_last_error());
        $OS = pg_fetch_array($resultOS);

        $sqlinsertDisco = "insert into disco(idservidor,nombre,sizegb,estatus,fecha_creacion,estatusazure,idenproveedor,puntomontaje) values('" . $_POST["selservidor2"] . "','" . $_POST["nombredisco2"] . "'," . $_POST["tamanodisco2"] . ",'Creando',now(),null,null,null);";
        $resultinsertDisco = pg_query($sqlinsertDisco) or die('La consulta fallo: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(iddisco) as ultimo FROM disco;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid cab792b8-807a-432c-901e-d6b3f08bcd40 -rn adddisk_' . $_POST["nombredisco2"] . '_' . $maquina["nombrevm"] . ' -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '" -in "zona=' . $localizacion["identificador"] . '" -in "idmaquina=' . $maquina["vmid"] . '" -in "tipoOS=' . $OS["clasifica"] . '" -in "nombrevolumen=' . $_POST["nombredisco2"] . '" -in "tamanodisk=' . $_POST["tamanodisco2"] . '" -in "iddisco=' . $Ultimo["ultimo"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../discos.php";
        </script>
        <?php
    }

    if ($maquina["idproveedor"] == 2) {
        $sqlFLAVOR = "select * from flavor where idflavor='" . $maquina["idflavor"] . "'";
        $resultFLAVOR = pg_query($sqlFLAVOR) or die('La consulta fallo: ' . pg_last_error());
        $FLAVOR = pg_fetch_array($resultFLAVOR);

        $sqldiscos = "select * from disco where idservidor='" . $_POST["selservidor2"] . "' and estatus='on-line' and fecha_eliminacion is null;";
        $resultdiscos = pg_query($sqldiscos) or die('La consulta fallo: ' . pg_last_error());
        $discos = pg_fetch_array($resultdiscos);


        if (pg_num_rows($resultdiscos) < $FLAVOR["disextra"]) {
            //echo "</br>SE PUEDE CREAR";
            $siguienteLun = 0;
            $sqlSecunciaLun = "select * from disco where idservidor='" . $_POST["selservidor2"] . "' order by lun desc;";
            $resultSecuenciaLun = pg_query($sqlSecunciaLun) or die('La consulta fallo: ' . pg_last_error());
            if (pg_num_rows($resultSecuenciaLun) == 0) {
                $siguienteLun = 1;
            } else {
                $siguienteLUNA = pg_fetch_array($resultSecuenciaLun);
                $siguienteLun = $siguienteLUNA["lun"] + 1;
            }

            $sqlSuscripcion = "select * from organizacion_location where idorganizacion='" . $_SESSION["idorganizacion"] . "' and idlocation='" . $maquina["idlocation"] . "'";
            $resultSuscripcion = pg_query($sqlSuscripcion) or die('La consulta fallo: ' . pg_last_error());
            $Suscripcion = pg_fetch_array($resultSuscripcion);

            $sqlinsertDisco = "insert into disco(idservidor,nombre,lun,uri,sizegb,estatus,fecha_creacion,estatusazure,idenproveedor,puntomontaje) values('" . $_POST["selservidor2"] . "','" . $_POST["nombredisco2"] . "'," . $siguienteLun . ",'https://" . $Suscripcion["estorageacount"] . ".blob.core.windows.net/" . $Suscripcion["contenedorstorage"] . "/" . $maquina["nombrevm"] . "-" . $_POST["nombredisco2"] . ".vhd'," . $_POST["tamanodisco2"] . ",'Creando',now(),'empty','" . $maquina["nombrevm"] . "-" . $_POST["nombredisco2"] . ".vhd" . "',null);";
            $resultinsertDisco = pg_query($sqlinsertDisco) or die('La consulta fallo: ' . pg_last_error());

            $sqlUltimo = "SELECT MAX(iddisco) as ultimo FROM disco;";
            $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo: ' . pg_last_error());
            $Ultimo = pg_fetch_array($resultUltimo);

            $concatena = "";
            $bandera = 0;
            $sqlenvia = "select * from disco where idservidor='" . $_POST["selservidor2"] . "' and estatus='on-line' or estatus='Creando' and fecha_eliminacion is null;";
            $resultenvia = pg_query($sqlenvia) or die('La consulta fallo: ' . pg_last_error());
            while ($envia = pg_fetch_array($resultenvia)) {

                if ($envia["iddisco"] == $Ultimo["ultimo"]) {

                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_empty";
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_empty";
                    }
                } else {

                    if ($bandera == 0) {
                        $concatena = $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                        $bandera = 1;
                    } else {
                        $concatena = $concatena . "__" . $envia["lun"] . "_" . $envia["nombre"] . "_" . $envia["sizegb"] . "_" . $envia["uri"] . "_" . $envia["estatusazure"];
                    }
                }
            }

            $sqlLOCALIZACION = "select * from location where idlocation='" . $maquina["idlocation"] . "'";
            $resultLOCALIZACION = pg_query($sqlLOCALIZACION) or die('La consulta fallo: ' . pg_last_error());
            $localizacion = pg_fetch_array($resultLOCALIZACION);

            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 1c5eee58-cea8-4f9f-bd0e-15ff3cd7b091 -rn adddisk_' . $_POST["nombredisco2"] . '_' . $maquina["nombrevm"] . ' -in "nombrevm=' . $maquina["nombrevm"] . '" -in "location=' . $localizacion["identificador"] . '" -in "gruporecursos=' . $Suscripcion["gruporecursos"] . '" -in "idmaquina=' . $_POST["selservidor2"] . '" -in "discos=' . $concatena . '" -in "iddisco=' . $Ultimo["ultimo"] . '" -in "estatusFinal=on-line" -in "estatusNoFinal=error" -in "azudirectorio=' . $configuracion["azudirectoryid"] . '" -in "azuid=' . $configuracion["azuclientid"] . '" -in "azupassword=' . $configuracion["azuclientsecret"] . '" -in "idsuscripcion=' . $Suscripcion["idsuscripcion"] . '" -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../discos.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No es posible agregar el disco, ya que el flavor del servidor permite un maximo de <?php echo $FLAVOR["disextra"]; ?> discos extra y ya los tiene todos atachados");
                location.href = "../../maquinasvirtuales.php";
            </script>
            <?php
        }
    }
}

if ($_GET["accion"] == "agregarKey") {
    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "'";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    if ($_POST["selproveedor"] == 1) {
        $sqlKey = "insert into keypair(idproveedor,idorganizacion,nombre,fecha_creacion) values(1,'" . $_SESSION["idorganizacion"] . "','" . $_POST["nombrekey"] . "',now());";
        $resultKey = pg_query($sqlKey) or die('La consulta fallo 01: ' . pg_last_error());

        $sqlUltimo = "SELECT MAX(idkeypair) as ultimo FROM keypair;";
        $resultUltimo = pg_query($sqlUltimo) or die('La consulta fallo 02: ' . pg_last_error());
        $Ultimo = pg_fetch_array($resultUltimo);

        $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 61d16883-cf89-4d45-9ce8-6d18ba65b529 -rn createkeypair_' . $_POST["nombrekey"] . ' -in "nombrekey=' . $_POST["nombrekey"] . '" -in "idkeypair=' . $Ultimo["ultimo"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '"  -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
        ?>  
        <script type="text/javascript">
            location.href = "../../keypairs.php";
        </script>
        <?php
    }
}

if ($_GET["accion"] == "eliminarKeyPair") {
    $sqlConfiguracion = "select * from configuracion where idorganizacion='" . $_SESSION["idorganizacion"] . "';";
    $resultConfiguracion = pg_query($sqlConfiguracion) or die('La consulta fallo: ' . pg_last_error());
    $configuracion = pg_fetch_array($resultConfiguracion);

    $sqlKey = "select * from keypair where idkeypair='" . $_GET["id"] . "'";
    $resultKey = pg_query($sqlKey) or die('La consulta fallo 01: ' . pg_last_error());
    $keyE = pg_fetch_array($resultKey);

    if ($keyE["idproveedor"] == 1) {
        echo "Reconoce Amazon";
        $sqlenUso = "select * from servidor where idkeypair='" . $_GET["id"] . "' and fecha_eliminacion is null;";
        $resultenUso = pg_query($sqlenUso) or die('La consulta fallo 01: ' . pg_last_error());
        if (pg_num_rows($resultenUso) == 0) {
            $respuesta = shell_exec('java -jar "../../recursos/jarfiles/FlowInvoker2.jar" -h ' . $configuracion["orchesip"] . ':' . $configuracion["orchespuerto"] . ' -u ' . $configuracion["orchesusuario"] . ' -p ' . $configuracion["orchespassword"] . ' -uuid 6d270d0b-5176-4318-ad0f-1b9c01cd75e5 -rn deletekeypair_' . $keyE["nombre"] . ' -in "nombrekey=' . $keyE["nombre"] . '" -in "idkeypair=' . $_GET["id"] . '" -in "keyID=' . $configuracion["awsaccesskeyid"] . '" -in "KeySecret=' . $configuracion["awsaccesskey"] . '"  -in "osip=' . $configuracion["osip"] . '" -in "ospuerto=' . $configuracion["ospuerto"] . '"  -in "osusuario=' . $configuracion["osusuario"] . '" -in "ospassword=' . $configuracion["ospassword"] . '" -in "dbip=' . $configuracion["dbip"] . '" -in "dbpuerto=' . $configuracion["dbpuerto"] . '" -in "dbnombre=' . $configuracion["dbnombre"] . '" -in "dbusuario=' . $configuracion["dbusuario"] . '" -in "dbpassword=' . $configuracion["dbpassword"] . '"');
            ?>  
            <script type="text/javascript">
                location.href = "../../keypairs.php";
            </script>
            <?php
        } else {
            ?>  
            <script type="text/javascript">
                alert("No se puede eliminar el keypair ya que existen servidores asociados a la misma.");
                location.href = "../../keypairs.php";
            </script>
            <?php
        }
    }
}





//Usuarios
if ($_GET["accion"] == "agregarUsuario") {
    
    //Encriptacion pass
    $passEncripted = md5($_POST["passUsuario"]);
    
    //Ejecución de query
    $sqlNewUser = "insert into usuario (idorganizacion,nombre,apellido,correo,contrasena,registro,superusuario) "
            . "VALUES('" . $_POST["selectedOrg"] . "','" . $_POST["nombreUsuario"] . "','" . $_POST["apellidoUsuario"] . "','"
            . $_POST["correoUsuario"] . "','" . $passEncripted . "',now(),"
            .(isset($_POST["checkSuperUsuario"])?"1":"0").") returning idusuario; ";

    $resultNewUser = pg_query($sqlNewUser) or die('La consulta fallo: ' . pg_last_error());
    $userId = pg_fetch_array($resultNewUser);
    
    $sqlPerfiles = "";

    foreach ($_POST["checkPerfiles"] as $perfil) {
        $sqlPerfiles .= "insert into usuarios_perfiles (idperfil, idusuario) "
                . "values ('" . $perfil . "','" . $userId["idusuario"] . "'); ";
    }

    $resultPerfiles = pg_query($sqlPerfiles) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadusuarios.php";
    </script>
    <?php
}

if ($_GET["accion"] == "editarUsuario") {
    
    //Encriptacion pass
    $passEncripted = md5($_POST["passUsuario"]);

    //Ejecución de query
    $sqlEditUser = "UPDATE usuario SET idorganizacion='" . $_POST["selectedOrg"] . "',nombre='" . $_POST["nombreUsuario"] . "'"
            . ",apellido='" . $_POST["apellidoUsuario"] . "',correo='" . $_POST["correoUsuario"] . "',contrasena='" . $passEncripted . "'"
            . ", superusuario=".(isset($_POST["checkSuperUsuario"])?"1":"0")
            . " WHERE idusuario='" . $_GET["id"] . "'; ";

    $resultEditUser = pg_query($sqlEditUser) or die('La consulta fallo: ' . pg_last_error());

    $sqlEditPerfiles = "SELECT * FROM usuarios_perfiles WHERE idusuario = " . $_GET["id"] . " ORDER BY idperfil ASC;";
    $resultEditPerfiles = pg_query($sqlEditPerfiles) or die('La consulta fallo: ' . pg_last_error());

    $perfilesExistentes = array();
    $perfilesNuevos = array();

    while ($perfilExistente = pg_fetch_array($resultEditPerfiles)) {
        $perfilesExistentes[] = $perfilExistente["idperfil"];
    }

    foreach ($_POST["checkPerfiles"] as $perfilNuevo) {
        $perfilesNuevos[] = $perfilNuevo;
    }

    //echo print_r($perfilesNuevos)."</br>".print_r($perfilesExistentes);

    $perfilesAAgregar = array_diff($perfilesNuevos, $perfilesExistentes);
    $perfilesNoDeseados = array_diff($perfilesExistentes, $perfilesNuevos);

    $sqlUpdatePerfiles = "";

    foreach ($perfilesAAgregar as $perfilAAgregar) {
        $sqlUpdatePerfiles .= "INSERT INTO usuarios_perfiles (idperfil, idusuario) "
                . "VALUES ('" . $perfilAAgregar . "','" . $_GET["id"] . "'); ";
    }

    foreach ($perfilesNoDeseados as $perfilAEliminar) {
        $sqlUpdatePerfiles .= "DELETE FROM usuarios_perfiles WHERE idusuario= " . $_GET["id"] . " AND " . "idperfil= " . $perfilAEliminar . " ;";
    }

    if ($sqlUpdatePerfiles != "") {
        $resultEditPerfil = pg_query($sqlUpdatePerfiles) or die('La consulta fallo: ' . pg_last_error());
    }
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadusuarios.php";
    </script>
    <?php
}

if ($_GET["accion"] == "eliminarUsuario") {

    $sqlDeleteUser = "UPDATE usuario SET fecha_eliminacion = now() WHERE idusuario = " . $_GET["id"] . ";";
    $resultDeleteUser = pg_query($sqlDeleteUser) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadusuarios.php";
    </script>
    <?php
}

if ($_GET["accion"] == "crearPerfilSeguridad") {
    echo "llega a crear perfil de seguridad";
    $sqlAgregarPerfil = "INSERT INTO perfil (nombre) VALUES ('" . $_POST["nombrePerfil"] . "') returning idperfil;";

    $resultAgregarPerfil = pg_query($sqlAgregarPerfil) or die('La consulta fallo: ' . pg_last_error());
    $perfilId = pg_fetch_array($resultAgregarPerfil);

    $sqlAcciones = "";

    foreach ($_POST["checkAcciones"] as $accion) {
        $sqlAcciones .= "INSERT INTO perfiles_acciones (idacciones, idperfil) "
                . "values ('" . $accion . "','" . $perfilId["idperfil"] . "'); ";
    }

    $resultAcciones = pg_query($sqlAcciones) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadperfiles.php";
    </script>
    <?php
}

if ($_GET["accion"] == "editarPerfilSeguridad") {

    $sqlEditPerfil = "UPDATE perfil SET nombre ='" . $_POST["nombrePerfil"] . "' WHERE idperfil='" . $_GET["id"] . "'; ";

    $resultEditPerfil = pg_query($sqlEditPerfil) or die('La consulta fallo: ' . pg_last_error());

    $sqlEditAcciones = "SELECT * FROM perfiles_acciones WHERE idperfil = " . $_GET["id"] . " ORDER BY idacciones ASC;";
    $resultEditAcciones = pg_query($sqlEditAcciones) or die('La consulta fallo: ' . pg_last_error());

    $accionesExistentes = array();
    $accionesNuevas = array();

    while ($accionExistente = pg_fetch_array($resultEditAcciones)) {
        $accionesExistentes[] = $accionExistente["idacciones"];
    }

    foreach ($_POST["checkAcciones"] as $accionNueva) {
        $accionesNuevas[] = $accionNueva;
    }

    //echo print_r($perfilesNuevos)."</br>".print_r($perfilesExistentes);

    $accionesAAgregar = array_diff($accionesNuevas, $accionesExistentes);
    $accionesNoDeseadas = array_diff($accionesExistentes, $accionesNuevas);

    $sqlUpdateAcciones = "";

    foreach ($accionesAAgregar as $accionAAgregar) {
        $sqlUpdateAcciones .= "INSERT INTO perfiles_acciones (idacciones, idperfil) "
                . "VALUES ('" . $accionAAgregar . "','" . $_GET["id"] . "'); ";
    }

    foreach ($accionesNoDeseadas as $accionAEliminar) {
        $sqlUpdateAcciones .= "DELETE FROM perfiles_acciones WHERE idperfil= " . $_GET["id"] . " AND " . "idacciones= " . $accionAEliminar . " ;";
    }

    if ($sqlUpdateAcciones != "") {
        $resultEditAccion = pg_query($sqlUpdateAcciones) or die('La consulta fallo: ' . pg_last_error());
    }
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadperfiles.php";
    </script>
    <?php
}

if ($_GET["accion"] == "eliminarPerfil") {

    $sqlDeleteUser = "UPDATE perfil SET fecha_eliminacion = now() WHERE idperfil= " . $_GET["id"] . ";";
    $resultDeleteUser = pg_query($sqlDeleteUser) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../seguridadperfiles.php";
    </script>
    <?php
}

//Acciones Sistemas Operativos
if ($_GET["accion"] == "agregarSO") {

    function clasificacion() {
        if ($_POST["selectedTipo"] == 1) {
            return "Windows";
        } else {
            return "Linux";
        }
    }

    $sqlInsertSO = "INSERT INTO sistemaoperativo(
                    idproveedor, idtipoos, nombre, identificador, clasifica)
                    VALUES (" . $_POST["selectedProv"] . "," . $_POST["selectedTipo"] . ",'" . $_POST["nombreSO"] . "',
                        '" . $_POST["identificadorSO"] . "','" . clasificacion() . "');";

    $resultInsertSO = pg_query($sqlInsertSO) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../sistemasoperativos.php";
    </script>
    <?php
}

if ($_GET["accion"] == "editarSO") {

    function clasificacion() {
        if ($_POST["selectedTipoEditar"] == 1) {
            return "Windows";
        } else {
            return "Linux";
        }
    }

    $sqlUpdateSO = "UPDATE sistemaoperativo
                    SET idproveedor=" . $_POST["selectedProvEditar"] . ", idtipoos='" . $_POST["selectedTipoEditar"] . "',
                        nombre='" . $_POST["nombreSO"] . "', identificador='" . $_POST["identificadorSO"] . "',
                        clasifica='" . clasificacion() . "'
                    WHERE idsistemaoperativo=" . $_GET["id"] . ";";

    $resultUpdateSO = pg_query($sqlUpdateSO) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../sistemasoperativos.php";
    </script>
    <?php
}

if ($_GET["accion"] == "eliminarSO") {

    $sqlDeleteSO = "UPDATE sistemaoperativo SET fecha_eliminacion=now()
                    WHERE idsistemaoperativo=" . $_GET["id"] . ";";

    $resultDeleteSO = pg_query($sqlDeleteSO) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../sistemasoperativos.php";
    </script>
    <?php
}

//Acciones Flavors
if ($_GET["accion"] == "agregarFlavor") {

    $sqlInsertFlavor = "INSERT INTO flavor(idproveedor, nombre, identificador, numcpu,
                        memoriaram, tamanoosdisk, tamanoextradisk, disextra,
                        precio)
                            VALUES (" . $_POST["selectedProv"] . ",'" . $_POST["nombreFlavor"] . "',
                            '" . $_POST["idFlavor"] . "', " . $_POST["cpusFlavor"] . ",
                            " . $_POST["ramFlavor"] . ", " . $_POST["hddFlavor"] . ",
                            " . ($_POST["extraHddFlavor"]==""?"null":$_POST["extraHddFlavor"]) . ", 
                            " . ($_POST["numExtraHddFlavor"]==""?"null":$_POST["numExtraHddFlavor"]) . ",
                            " . $_POST["precioFlavor"] . ");";

    $resultInsertFlavor = pg_query($sqlInsertFlavor) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../flavors.php";
    </script>
    <?php
}

if ($_GET["accion"] == "editarFlavor") {

    $sqlUpdateFlavor = "UPDATE flavor
                        SET idproveedor=" . $_POST["selectedProvEditar"] . ", nombre='" . $_POST["nombreFlavor"] . "',
                            identificador='" . $_POST["idFlavor"] . "', numcpu=" . $_POST["cpusFlavor"] . ",
                            memoriaram=" . $_POST["ramFlavor"] . ", tamanoosdisk=" . $_POST["hddFlavor"] . ",
                            tamanoextradisk=" . ($_POST["extraHddFlavorEditar"]==""?"null":$_POST["extraHddFlavorEditar"]). ",
                            disextra=" . ($_POST["numExtraHddFlavorEditar"]==""?"null":$_POST["numExtraHddFlavorEditar"]) . ",
                            precio=" . $_POST["precioFlavor"] . "
                        WHERE idflavor=" . $_GET["id"] . ";";

    $resultUpdateFlavor = pg_query($sqlUpdateFlavor) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../flavors.php";
    </script>
    <?php
}

if ($_GET["accion"] == "eliminarFlavor") {

    $sqlDeleteFlavor = "UPDATE flavor SET fecha_eliminacion=now()
                    WHERE idflavor=" . $_GET["id"] . ";";

    $resultDeleteFlavor = pg_query($sqlDeleteFlavor) or die('La consulta fallo: ' . pg_last_error());
    ?>
    <script type="text/javascript">
        location.href = "../../flavors.php";
    </script>
    <?php
}

//<---Menu Usuarios--->
//Cerrar Sesion

if ($_GET["accion"] == "cerrarSesion") {
    
    session_destroy();
    
            ?>
            <script type="text/javascript">
                location.href="/cloudmarket/<?php echo $_GET["org"]?>/index.php";
            </script>
            <?php

}

pg_close($conexion);
?>               
