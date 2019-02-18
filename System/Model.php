<?php

namespace System;

use Classes\DB;

class Model
{

    /** @var string $tableName */
    protected $tableName;

    /**
     * Model constructor
     */
    public function __construct()
    {

    }

    /**
     * Gets all records
     *
     * @param int|null      $offset     Offset of the first row to be returned
     * @param int|null      $count      Maximum number of rows to be returned
     *
     * @return array
     */
    public function getAll(
        ?int $offset = null,
        ?int $count = null
    ): array
    {
        $result = [];

        if (!$this->tableName) {
            return $result;
        }

        $statement = "SELECT * FROM `$this->tableName`";

        if (isset($count)) {
            $statement .= " LIMIT $count";
        }

        if (isset($offset)) {
            $statement .= " OFFSET $offset";
        }

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Find all records by specified columns and values of them
     *
     * @param array|null    $data       Associated array of columns and values for search records
     * @param int|null      $offset     Offset of the first row to be returned
     * @param int|null      $count      Maximum number of rows to be returned
     *
     * @return array
     */
    public function find(
        ?array $data = [],
        ?int $offset = null,
        ?int $count = null
    ): array
    {
        $result = [];

        if (!$this->tableName) {
            return $result;
        }

        $statement = "SELECT * FROM `$this->tableName`";

        if (!empty($data)) {
            $where = [];
            foreach ($data as $column => $value) {
                switch (true) {
                    case is_array($value):
                        $where[] = $column . join(' ', $value);
                        break;
                    default:
                        $where[] = "$column = '$value'";
                        break;
                }
            }

            $statement .= ' WHERE ' . join(' AND ', $where);
        }

        if (isset($count)) {
            $statement .= " LIMIT $count";
        }

        if (isset($offset)) {
            $statement .= " OFFSET $offset";
        }

        $query = DB::prepare($statement);
        $query->execute();

        if ($query->rowCount()) {
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /**
     * Gets first record by specified columns and values of them
     *
     * @param array|null    $data   Associated array of columns and values for search records
     *
     * @return array
     */
    public function getFirst(
        ?array $data = []
    ): array
    {

        return $this->find($data, 0, 1);
    }

    /**
     * Creates record by provided associated array of columns and values
     *
     * @param array     $data       Associated array of columns and values to create record
     *
     * @return bool
     */
    public function create(
        array $data
    ): bool
    {
        $result = false;

        if (!$this->tableName) {
            return $result;
        }

        $keys = array_keys($data);
        $columns = '`' . implode('`, `', $keys) . '`';
        $values = substr(str_repeat('?,', count($keys)), 0, -1);

        $statement = "INSERT INTO `$this->tableName` ($columns) VALUES ($values)";

        $query = DB::prepare($statement);
        $result = $query->execute(array_values($data));

        return $result;
    }

    /**
     * Gets first record or create a new one by specified columns and values of them
     *
     * @param array     $data   Associated array of columns and values for search or create records
     *
     * @return array
     */
    public function getOrCreate(
        array $data
    ): array
    {
        $result = [];

        if (!$this->tableName) {
            return $result;
        }

        $result = $this->find($data, 0, 1);

        if (empty($result) && $this->create($data)) {
            $result = $this->find($data, 0, 1);
        }

        $result = $result[0] ?? $result;

        return $result;
    }

}
