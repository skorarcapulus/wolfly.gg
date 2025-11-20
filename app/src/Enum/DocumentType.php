<?php

namespace App\Enum;

enum DocumentType: string
{
    case HTML = 'html';
    case CSS = 'css';
    case JS = 'js';
}