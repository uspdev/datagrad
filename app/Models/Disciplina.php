<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /** Dados dos campos do formulário de alteração de disciplina */
    public $meta = [
        'objdis' => [
            'titulo' => 'Objetivos',
        ],
        'objdisigl' => [
            'titulo' => 'Objectives',
            'class' => 'ingles',
        ],
        'pgmrsudis' => [
            'titulo' => 'Programa resumido',
            'ajuda' => 'Resumo do programa da disciplina, para impressão do catálogo de Cursos da Graduação',
        ],
        'pgmrsudisigl' => [
            'titulo' => 'Short program',
            'class' => 'ingles',
        ],
        'pgmdis' => [
            'titulo' => 'Programa completo',
        ],
        'pgmdisigl' => [
            'titulo' => 'Full program',
            'class' => 'ingles',
        ],
        'dscmtdavl' => [
            'titulo' => 'Métodos de avaliação',
        ],
        'dscmtdavligl' => [
            'titulo' => 'Evaluation methods',
            'class' => 'ingles',
        ],
        'crtavl' => [
            'titulo' => 'Critérios de avaliação',
        ],
        'crtavligl' => [
            'titulo' => 'Evaluation criterion',
            'class' => 'ingles',
        ],
        'dscnorrcp' => [
            'titulo' => 'Norma de recuperação',
        ],
        'dscnorrcpigl' => [
            'titulo' => 'Recovery standard',
            'class' => 'ingles',
        ],
        'dscbbgdis' => [
            'titulo' => 'Bibliografia / References',
        ],

        'cgahoratvext' => [
            'titulo' => 'Extensão: Carga horária (horas)',
            'ajuda' => 'Carga horária em atividade extensionista, definida em resolução 07/2018 do Conselho Nacional de Educação (CNE), que estabelece as diretrizes para a extensão na educação superior brasileira (instituições públicas e privadas) e que avaliações do Ministério da Educação (MEC) passam a considerar o currículo dos cursos com a extensão obrigatória.',
        ],
        'grpavoatvext' => [
            'titulo' => 'Extensão: Grupo social alvo',
            'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
        ],
        'grpavoatvextigl' => [
            'titulo' => 'Extensão: Target social group',
            'ajuda' => 'Descrever aqui as características do público com o qual os alunos desenvolverão as atividades',
            'class' => 'ingles',
        ],
        'objatvext' => [
            'titulo' => 'Extensão: Objetivos',
            'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
        ],
        'objatvextigl' => [
            'titulo' => 'Extensão: Objetives',
            'ajuda' => 'Detalhar aqui os resultados esperados com a realização da atividade extensionista, tanto para a formação dos discentes, quanto para o grupo social alvo da ação',
            'class' => 'ingles',
        ],
        'dscatvext' => [
            'titulo' => 'Extensão: Descrição',
            'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
        ],
        'dscatvextigl' => [
            'titulo' => 'Extensão: Description',
            'ajuda' => 'Relatar aqui o resumo das ações a serem desenvolvidas pelos alunos',
            'class' => 'ingles',
        ],
        'idcavlatvext' => [
            'titulo' => 'Extensão: Indicadores de avaliação da atividade',
            'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
        ],
        'idcavlatvextigl' => [
            'titulo' => 'Extensão: Activity evaluation indicators',
            'ajuda' => 'Sinalizar aqui como o grupo social externo à Universidade poderá avaliar a atividade realizada conjuntamente com os estudantes, durante sua realização e ao final',
            'class' => 'ingles',
        ],
    ];

    public function criadoPor() {
        return $this->belongsTo(User::class, 'criado_por_id');
    }

    public function atualizadoPor() {
        return $this->belongsTo(User::class, 'atualizado_por_id');
    }
}
