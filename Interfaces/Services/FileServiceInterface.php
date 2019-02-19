<?php

namespace Interfaces\Services;

use App\DTO\ReportFields;

interface FileServiceInterface
{

    /**
     * Gets row from file
     *
     * @return ReportFields|null
     */
    public function getRow(): ?ReportFields;

}
