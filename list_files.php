<?php
require 'google/vendor/autoload.php';

// Ruta al archivo de credenciales JSON
$credentialsPath = 'credenciales.json';

// Cargar las credenciales
$credentials = json_decode(file_get_contents($credentialsPath), true);

// Reemplaza 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes([Google_Service_Drive::DRIVE]);

// Crear el servicio de Google Drive
$service = new Google_Service_Drive($client);

// ID de la carpeta de destino en Google Drive (1O2TZ6qmIiX7eH1nVVRll4NA3kn3TIHth)
$folderId = "1O2TZ6qmIiX7eH1nVVRll4NA3kn3TIHth";

// Obtener la lista de archivos en la carpeta
$filesList = $service->files->listFiles(array(
    'q' => "'{$folderId}' in parents",
    'fields' => 'files(id, name)'
));

$files = $filesList->getFiles();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Listar archivos en la carpeta</title>
</head>

<body>
    <h1>Archivos en la carpeta</h1>
    <?php if (empty($files)): ?>
        <p>No se encontraron archivos en la carpeta.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($files as $file): ?>
                <li><a href="read_excel.php?fileId=<?php echo $file->getId(); ?>"><?php echo $file->getName(); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>
