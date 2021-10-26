<?php declare(strict_types = 1);

namespace Budget\Core;

interface Renderer
{
    public function render($template, $data = []) : string;
}