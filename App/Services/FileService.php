<?php

namespace App\Services;

use App\DTO\ReportFields;
use Classes\Storage;
use Interfaces\Services\FileServiceInterface;
use \Exception;

class FileService implements FileServiceInterface
{

    /** @var mixed $file */
    protected $file;

    /** @var array $fieldsMapping */
    protected $fieldsMapping;

    /**
     * Model constructor
     *
     * @param string    $url    File url
     * @param array     $fields     Array of required fields
     */
    public function __construct(string $url, array $fields)
    {
        $this->processFile($url, $fields);
    }

    /**
     * Process file
     *
     * @param string    $url        File url
     * @param array     $fields     Array of required fields
     *
     * @return void
     */
    protected function processFile(string $url, array $fields): void
    {
        $this->file = $this->getFile($url);

        if ($this->validate($fields)) {
            $this->fieldsMapping = $this->setColumnMapping($fields);
        } else {
            throw new Exception('File is not valid. Some fields are missing.');
        }
    }

    /**
     * Downloads file and returns a file pointer resource
     *
     * @param string    $url    File url
     *
     * @return mixed
     */
    protected function getFile(string $url)
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
    protected function validate(array $fields): bool
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
     * Sets columns' mapping by file
     *
     * @param array     $fields     Array of required fields
     *
     * @return array
     */
    protected function setColumnMapping(array $fields): array
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
     * @return ReportFields|null
     */
    public function getRow(): ?ReportFields
    {
        $result = null;

        if ($row = fgetcsv($this->file)) {
            $result = new ReportFields();
            foreach ($this->fieldsMapping as $column => $field) {
                $result->$column = $row[$field];
            }
        }

        return $result;
    }

}
