<?php
require 'google/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
    $file = $_FILES["excelFile"];

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
        $client->setScopes([Google_Service_Drive::DRIVE]);

        // Crear el servicio de Google Drive
        $service = new Google_Service_Drive($client);

        // ID de la carpeta de destino en Google Drive (1O2TZ6qmIiX7eH1nVVRll4NA3kn3TIHth)
        $folderId = "1O2TZ6qmIiX7eH1nVVRll4NA3kn3TIHth";

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

        // Mostrar el enlace del archivo subido
        echo "El archivo se subió correctamente. Enlace: " . $result->webViewLink;
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
