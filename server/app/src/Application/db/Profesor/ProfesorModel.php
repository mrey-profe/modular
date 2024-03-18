<?php

declare(strict_types=1);

namespace App\Application\db\Profesor;

use App\Application\db\DatabaseConnection;
use App\Application\Models\Profesor;

class ProfesorModel
{

    private static $profesorModel = ["id", "nombre", "fecha_inicio", "instituto", "id_especializacion", "id_instituto"];

    public static function listarProfesor(string $id = ""): array
    {
        $sql = "SELECT p.id, p.nombre, e.tipo as especializacion, (SELECT tipo FROM profesor_regimen JOIN regimen as r ON id_regimen = r.id WHERE p.id = id_profesor) as id_regimen
        FROM Modular.profesor AS p
        JOIN especialidad AS e ON p.id_especialidad = e.id";
        $dbInstance = DatabaseConnection::getInstance();
        if ($id === "") {
            $sql .= " order by p.fecha_inicio";
            try {
                $stmt = $dbInstance->execQuery($sql);
                $result = self::addProfesor($stmt);
                if ($result !== null) return $result;
                return 404;
            } catch (\Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }

        $sql .= " WHERE p.id_departamento = ?";

        try {
            $sql .= " order by p.fecha_inicio";
            $stmt = $dbInstance->execQuery($sql, [$id]);
            $result = self::addProfesor($stmt);
            if ($result !== null) return $result;
            return 404;
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    private static function addProfesor($stmt) {
        $sql = "SELECT e.tipo as afin FROM Modular.afin as a JOIN especialidad AS e ON (a.id_especialidad = e.id) where id_profesor = ?;";
        while ($row = $stmt->fetch()) {
            $dbInstance = DatabaseConnection::getInstance();
            $stmt2 = $dbInstance->execQuery($sql, [$row['id']]);
            $afin = [];
            while ($row2 = $stmt2->fetch()) {
                $afin[] = $row2['afin'];
            }
            $regimen = $row["id_regimen"] === null ? "" : $row["id_regimen"];
            $profesor = new Profesor($row["id"], $row["nombre"], $row["especializacion"], $afin, $regimen);
            $result[] = $profesor->getData();
        }
        return $result;
    }
}
