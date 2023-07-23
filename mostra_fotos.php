<?php
// Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'
require 'google/vendor/autoload.php';

// Verificar que el archivo de credenciales exista y cargar las credenciales
$credentialsPath = 'credenciales.json';
if (!file_exists($credentialsPath)) {
    die("El archivo de credenciales no existe.");
}
$credentials = json_decode(file_get_contents($credentialsPath), true);

// Reemplazar 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes(Google_Service_Drive::DRIVE);

// Crear el servicio de Google Drive
$service = new Google_Service_Drive($client);

// ID de la carpeta de destino
$folderId = "1gate0BykXOR9-bGy8n_azxbCIsCds7Gq";

// Obtener la lista de archivos en la carpeta de destino
$files = $service->files->listFiles(array(
    'q' => "'" . $folderId . "' in parents and trashed=false",
    'fields' => 'files(id, name, thumbnailLink)'
));

// Mostrar los enlaces o miniaturas de las fotos
if (empty($files->files)) {
    echo "No hay fotos en la carpeta.";
} else {
    foreach ($files->files as $file) {
        echo '<a href="https://drive.google.com/uc?id=' . $file->id . '" target="_blank">';
        echo '<img src="' . $file->thumbnailLink . '" alt="' . $file->name . '">';
        echo '</a><br>';
    }
}
?>
