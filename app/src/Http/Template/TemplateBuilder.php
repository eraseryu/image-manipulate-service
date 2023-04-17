<?php

namespace App\Http\Template;

class TemplateBuilder
{
    private const PUBLIC_DIR = '/app/public/';

    public function getTemplate(string $fileName, array $variables = []): string
    {
        $template = file_get_contents(self::PUBLIC_DIR . $fileName);
        foreach ($variables as $variable => $value) {
            $placeholder = '{{' . $variable . '}}';
            $template = str_replace($placeholder, $value, $template);
        }

        return $template;
    }
}
