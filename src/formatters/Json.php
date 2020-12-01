<?php

namespace App\Formatters\Json;

function render($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}
