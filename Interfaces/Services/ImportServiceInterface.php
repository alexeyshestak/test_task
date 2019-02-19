<?php

namespace Interfaces\Services;

interface ImportServiceInterface
{

    /**
     * Imports rows to database
     *
     * @return void
     */
    public function import(): void;

}
