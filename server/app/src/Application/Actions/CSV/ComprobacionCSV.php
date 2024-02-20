<?php

namespace App\Application\Actions\CSV;

use App\Application\Actions\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ComprobacionCSV extends Controller
{
    public function uploadFiles(Request $request, Response $response)
    {
        // Obtenemos los archivos subidos
        $uploadedFiles = $request->getUploadedFiles();

        // Verificamos que se haya enviado un archivo y es un CSV
        if (isset($uploadedFiles['csvFile']) && $uploadedFiles['csvFile']->getError() === UPLOAD_ERR_OK) {
            if ($this->esArchivoCSV($uploadedFiles['csvFile'])) {
                // Procesa el archivo csv
                return $this->returnResponse($response, ["success" => "Formato de archivo valido"], 200);
            } else {
                return $this->returnResponse($response, ["error" => "Formato de archivo incorrecto"], 400);
            }
        } else {
            return $this->returnResponse($response, ["error" => "No se ha enviado ningún archivo o hay un error en la carga"], 400);
        }

        return $response;
    }

    private function esArchivoCSV($uploadedFile): bool
    {
        // Verifica la extensión del archivo
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        return strtolower($extension) === 'csv';
    }
}