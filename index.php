<!DOCTYPE html>
<html>
<head>
    <title>Subir Fotos a Google Drive</title>
</head>
<body>
    <h1>Subir Foto</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Selecciona una foto:</label>
        <input type="file" name="file" id="file">
        <br>
        <input type="submit" name="submit" value="Subir Foto">
    </form>
</body>
</html>
