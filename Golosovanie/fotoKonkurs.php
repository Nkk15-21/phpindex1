<?php
require ('conf.php');
global $yhendus;

//update +1 punkt
if(isset($_REQUEST["lisa1punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurs SET punktid=punktid+1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

//update - comment
if(isset($_REQUEST["uus_komment"]) && !empty($_REQUEST["komment"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurs SET komentaarid=Concat(komentaarid, ?) WHERE id=?");
    $komment2=$_REQUEST["komment"]."\n";
    $paring->bind_param("si", $komment2, $_REQUEST["uus_komment"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

//update -1 punkt
if(isset($_REQUEST["minus1punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurs SET punktid=punktid-1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["minus1punkt"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

//kustuta
if(isset($_REQUEST["kustuta"])){
    $paring=$yhendus->prepare("DELETE from fotokonkurs WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}


//добавление данных в таблицу
if(isset($_REQUEST["nimetus"]) && !empty($_REQUEST["nimetus"])){
    $paring=$yhendus->prepare("INSERT INTO fotokonkurs(fotoNimetus, autor, pilt, lisamisAeg) VALUES (?,?,?,NOW())");
    $paring->bind_param("sss", $_REQUEST["nimetus"], $_REQUEST["autor"], $_REQUEST["pilt"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

?>


<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Foto Konkurss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Foto Konkurss</h1>
<form action="?" method="post">
    <h3>Foto lisamine hääletamisele</h3>
    <label for="nimetus">Foto nimetus</label>
    <input type="text" id="nimetus" name='nimetus' placeholder="Kirjuta ilus foto nimetus!">
    <br>
    <label for="autor">Foto autor</label>
    <input type="text" id="autor" name="autor" placeholder="Kirjuta autori nimi!">
    <br>
    <label for="pilt">Pildifoto</label>
    <textarea name="pilt" id="pilt" cols="30" rows="10">Kooperi kujutisse aadress!</textarea>
    <br>
    <input type="submit" value="Lisa">
</form>

<table>
    <tr>
        <th>Foto nimetus</th>
        <th>Pilt</th>
        <th>Autor</th>
        <th>Punktid</th>
        <th>Lisamise Aeg</th>
        <th>Lisa +1 Punkt</th>
        <th>Lisa -1 Punkt</th>
        <th>Kustuta</th>
        <th>Kommentaarid</th>
    </tr>

    <?php

    // отображение таблицы базы данных
    global $yhendus;
    $paring=$yhendus->prepare('SELECT id, fotoNimetus, pilt, autor, punktid, lisamisAeg, komentaarid from fotokonkurs');
    $paring->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $lisamisAeg, $komentaarid);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($fotoNimetus)."</td>";
        echo "<td><img src='$pilt' alt='fotoPilt'></td>";
        echo "<td>".$autor."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$lisamisAeg."</td>";
        echo "<td><a href='?lisa1punkt=$id'>+1</a></td>";
        echo "<td><a href='?minus1punkt=$id'>-1</a></td>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "<td>".nl2br($komentaarid)."
                <form action='?' method='POST'>
                <input type='hidden' name='uus_komment' value='$id'>
                <input type='text' name='komment'>
                <input type='submit' value='ok'>   
                </form>
            </td>";
        echo "</tr>";
    }
    ?>
</table>

<?php
$yhendus->close();
?>

</body>
</html>
