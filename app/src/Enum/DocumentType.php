<?php

namespace App\Enum;

enum DocumentType: string
{
    case HTML = 'twig.html';
    case CSS = 'css';
    case JS = 'js';
}