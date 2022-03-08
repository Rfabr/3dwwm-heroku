<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Designer;

class ProductSearch
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * @var Designer[]
     */
    public $designer = [];

    /**
     * @var Category[]
     */
    public $category = [];
}
