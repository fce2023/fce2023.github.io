<?php

// Cargar la biblioteca de Google API Client desde la carpeta raíz con el nombre 'google'
require 'google/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];

    // Verificar que el archivo se haya subido correctamente
    if ($file["error"] === UPLOAD_ERR_OK) {
        // Ruta al archivo de credenciales JSON
        $credentialsPath = 'credenciales.json';

        // Cargar las credenciales
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        // Reemplaza 'TU_NOMBRE_DE_PROYECTO' con el nombre de tu proyecto en Google Developer Console
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes(Google_Service_Drive::DRIVE);

        // Crear el servicio de Google Drive
        $service = new Google_Service_Drive($client);

        // ID de la carpeta de destino
        $folderId = "1gate0BykXOR9-bGy8n_azxbCIsCds7Gq";

        // Buscar si el archivo ya existe en la carpeta de destino
        $fileList = $service->files->listFiles(array(
            'q' => "'{$folderId}' in parents and name='{$file['name']}'",
            'fields' => 'files(id)'
        ));

        if (count($fileList->getFiles()) > 0) {
            // El archivo ya existe en la carpeta
            echo "El archivo ya existe en la carpeta de destino.";
        } else {
            // Subir el archivo a Google Drive en la carpeta de destino
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $file['name'],
                'parents' => array($folderId)
            ));

            $result = $service->files->create($fileMetadata, array(
                'data' => file_get_contents($file['tmp_name']),
                'mimeType' => $file['type'],
                'uploadType' => 'multipart'
            ));

            // Mostrar el enlace de la foto subida
            echo "La foto se subió correctamente. Enlace: " . $result->webViewLink;
        }
    } else {
        echo "Error al subir la foto.";
    }
}
?>
