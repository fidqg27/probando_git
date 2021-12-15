<?php 
    session_start();
    require_once 'login.php';
    require_once 'pantalla.php';
    
    $conexion = new mysqli($hn, $un, $pw, $db);

    if ($conexion->connect_error) die ("Fatal error");
    
    $username=$_SESSION['username'];
    //Borrar registro
    if (isset($_POST['delete']) && isset($_POST['id_nota']))
    {   
        $id_nota= get_post($conexion, 'id_nota');
        $query = "DELETE FROM diario WHERE id_nota='$id_nota'";
        $result = $conexion->query($query);
        if (!$result) echo "BORRAR falló"; 
    }
    //Buscar registro
  /*  if(isset($_POST['buscar'])){
        $buscar=get_post($conexion, 'buscar');
        $busca = $_POST["palabra"];
        $query="SELECT * FROM diario WHERE titulo LIKE '%$busca%' AND username='$username'";
        $result= $conexion->query($query);
        if (!$result) die ("Falló la busqueda");       
    }*/

    if (isset($_POST['id_nota']) &&
        isset($_POST['titulo']) &&
        isset($_POST['fecha_registro']) &&
        isset($_POST['fecha_vencimiento']) &&
        isset($_POST['cuerpo']) &&
        isset($_POST['prioridad']) )
    {
        $username=$_SESSION['username'];
        $id_nota = get_post($conexion, 'id_nota');
        $titulo = get_post($conexion, 'titulo');
        $fecha_registro = get_post($conexion, 'fecha_registro');
        $fecha_vencimiento = get_post($conexion, 'fecha_vencimiento');
        $cuerpo = get_post($conexion, 'cuerpo');
        $prioridad = get_post($conexion, 'prioridad');
        $query = "INSERT INTO diario VALUE" .
            "('$username','$id_nota','$titulo', '$fecha_registro', '$fecha_vencimiento', '$cuerpo','$prioridad')";
        $result = $conexion->query($query);
        if (!$result);

    }

    $charset="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
    $cad="";
    for($i=0;$i<15;$i++){
        $cad .= substr ($charset,rand (0,61),1);
    }

    echo <<<_END
    <form action="nota.php" method="post"><pre>
        <input type="hidden" name="id_nota" value="$cad"  id="id_nota">
        TITULO: <textarea  placeholder="Titulo" name="titulo" cols="40" rows="1.5" id="titulo"></textarea>

        fecha_registro <input type="date" name="fecha_registro" id="fecha_registro">
        fecha_vencimiento <input type="date" name="fecha_vencimiento" id="fecha_vencimiento">

        <textarea placeholder="¿Qué pasó hoy?" name="cuerpo" cols="48" rows="10" id="nota"></textarea>
        <input type="checkbox"  name="prioridad" value="marcado"><label for="vehicle1"> prioridad</label>
        <input type="submit" value="Agregar">
    </pre></form>
    <form action="pantalla.php" method="post"><pre>
        <input type="submit" value="Anterior">
    </pre></form>
    _END;
    
    $query = "SELECT * FROM diario WHERE username='$username'ORDER BY fecha_vencimiento ASC";
    $result = $conexion->query($query);
    if (!$result) die ("Falló el acceso a la base de datos");

    $rows = $result->num_rows;

    for ($j = 0; $j < $rows; $j++)
    {
        $row = $result->fetch_array(MYSQLI_NUM);

        $r0 = htmlspecialchars($row[0]);
        $r1 = htmlspecialchars($row[1]);
        $r2 = htmlspecialchars($row[2]);
        $r3 = htmlspecialchars($row[3]);
        $r4 = htmlspecialchars($row[4]);
        $r5 = htmlspecialchars($row[5]);
        $r6 = htmlspecialchars($row[6]);

        echo <<<_END
        <pre>
        titulo $r2
        fecha_registro $r3
        fecha_vencimiento $r4
        cuerpo $r5
        prioridad $r6
        </pre>
          </pre>
        <form action='nota.php' method='post'>
        <input type='hidden' name='delete' value='yes'>
        <input type='hidden' name='id_nota' value='$r1'>
        <input type='submit' value='BORRAR'> 
        </form>

        <form method="post" action="editarnota.php">
        <input type="hidden" name="id_codigo" value='$r1'>
        <input type="submit" name="Editar" value="Editar">
        </form>

        
        _END;
    }
    /*<form action='editanota.php' method='post'>
        <input type='submit' value='Editar'> </form>*/
    $result->close();
    $conexion->close();

    function get_post($con, $var)
    {
        return $con->real_escape_string($_POST[$var]);
    }
?>