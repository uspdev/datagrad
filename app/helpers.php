<?php

//coloque o seu helper aqui

use Carbon\Carbon;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

if (!function_exists('md2html')) {
    /**
     * Converte markdown para html (github flavored)
     * 
     * Caso seja passado o nome do arquivo, ele irá procurar na pasta docs e carregar
     *
     * @param String $markdown String contendo markdown ou nome do arquivo em docs/
     * @param String $style Estido do CSS (default=default.css)
     * @return String
     * @author Masakik, em 16/11/2022
     */
    function md2html(string $path, string $style = 'github.css'): string
    {
        $environment = new Environment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer());

        $markdownConverter = new MarkdownConverter($environment);

        // adiciona CSS do highlight
        $html = '';
        $styleFile = base_path('vendor/scrivo/highlight.php/styles/' . $style);
        if (is_file($styleFile)) {
            $html .= '<style>' . file_get_contents($styleFile) . '</style>';
        }

        // caminho do arquivo markdown
        $file = base_path('docs/' . $path);
        if (is_file($file)) {
            $markdown = file_get_contents($file);
        } else {
            return '<p><em>Arquivo não encontrado: ' . e($path) . '</em></p>';
        }

        // converter markdown para HTML
        $html .= $markdownConverter->convert($markdown)->getContent();

        return $html;
    }
}

if (!function_exists('formatarData')) {
    function formatarData($dateString, $format = 'd/m/Y')
    {
        if (empty($dateString)) {
            return '';
        }
        return Carbon::parse($dateString)->format($format);
    }
}
