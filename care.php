<!DOCTYPE html>
<html>

<head>
    <title>Cargar Excel en Google Drive</title>
</head>

<body>
    <h1>Cargar Excel en Google Drive</h1>
    <form action="procesoexel.php" method="post" enctype="multipart/form-data">
        <label for="excelFile">Seleccionar archivo de Excel:</label>
        <input type="file" name="excelFile" id="excelFile">
        <input type="submit" value="Subir archivo">
    </form>
</body>

</html>
