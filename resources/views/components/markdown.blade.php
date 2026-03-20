{{-- 
Componente que mostra o conteúdo de um aqruivo no formato markdown da pasta docs.
Exemplo de uso:
<x-markdown file="disciplinas.md" />

Parâmetros:
- file: nome do arquivo markdown na pasta docs (obrigatório)
- style: nome do arquivo CSS de destaque de sintaxe na pasta vendor/scrivo/highlight.php/styles (opcional, padrão: github.css)

Dependências:
- spatie/commonmark-highlighter

Autor:
- Masaki K neto, em 6/10/2025
--}}

@props([
    'file',
    'style' => 'github.css',
])

@php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

$markdownPath = base_path('docs/' . $file);
$stylePath = base_path('vendor/scrivo/highlight.php/styles/' . $style);

$html = '';

// adiciona CSS de destaque, se existir
if (is_file($stylePath)) {
    $html .= '<style>' . file_get_contents($stylePath) . '</style>';
}

// lê o arquivo Markdown
if (is_file($markdownPath)) {
    $markdownContent = file_get_contents($markdownPath);

    $environment = new Environment();
    $environment->addExtension(new GithubFlavoredMarkdownExtension());
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());
    $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer());

    $converter = new MarkdownConverter($environment);
    $html .= $converter->convert($markdownContent)->getContent();
} else {
    $html .= '<p><em>Arquivo não encontrado: ' . e($file) . '</em></p>';
}
@endphp

{!! $html !!}
