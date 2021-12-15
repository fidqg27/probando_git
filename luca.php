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
