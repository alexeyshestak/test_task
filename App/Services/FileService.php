<?php

namespace App\Services;

use App\DTO\ReportFields;
use Classes\Storage;
use \Exception;

class FileService
{

    /** @var mixed $file */
    private $file;

    /**
     * Model constructor
     *
     * @param string    $url    File url
     */
    public function __construct(string $url)
    {
        $this->file = $this->getFile($url);
    }

    /**
     * Downloads file and returns a file pointer resource
     *
     * @param string    $url    File url
     *
     * @return mixed
     */
    private function getFile(string $url)
    {

        return Storage::getFile($url);
    }

    /**
     * Validates file to ensure that all required fields are present
     *
     * @param array     $fields     Array of required fields
     *
     * @return bool
     */
    public function validate(array $fields): bool
    {
        $result = false;

        fseek($this->file, 0, SEEK_SET);

        if ($row = fgetcsv($this->file)) {
            $fields = array_values($fields);
            $result = count(array_intersect($fields, $row)) === count($fields);
        }

        return $result;
    }

    /**
     * Gets columns' mapping by file
     *
     * @param array     $fields     Array of required fields
     *
     * @return array
     */
    public function getColumnMapping(array $fields): array
    {
        $result = [];

        fseek($this->file, 0, SEEK_SET);

        if ($row = fgetcsv($this->file)) {
            foreach ($fields as $column => $field) {
                $result[$column] = array_search($field, $row);
            }
        }

        return $result;
    }

    /**
     * Gets row from file
     *
     * @param array     $mappingRules   Array of rules for mapping fields
     *
     * @return ReportFields
     */
    public function getRow(array $mappingRules): ReportFields
    {
        $result = new ReportFields();

        if ($row = fgetcsv($this->file)) {
            foreach ($mappingRules as $column => $field) {
                $result->$column = $row[$field];
            }
        }

        return $result;
    }

}
