<?php

namespace App\Interfaces;

interface SearchServiceInterface
{
    public function performSearch($searchTerm, $searchColumns);
}