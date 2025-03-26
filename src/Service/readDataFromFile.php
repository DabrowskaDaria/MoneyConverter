<?php

namespace App\Service;

class readDataFromFile
{
    public function readDataFromFile($path): array
    {
        $data = [];
        if (file_exists($path)) {
            $file = fopen($path, "r");
            while (($row = fgetcsv($file, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($file);
        }
        return $data;
    }
}