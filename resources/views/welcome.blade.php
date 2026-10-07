@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    @cannot('user')
        <div class="alert alert-info shadow-sm mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            Faça o <a href="{{ route('login') }}" class="font-weight-bold alert-link">login</a> com a senha única para acessar esse sistema.
        </div>
    @endcannot

    @can('user')
        <div class="alert border shadow-sm mb-4" style="background-color: #f8f9fa; border-left: 4px solid #003366 !important;">
            <i class="fas fa-compass mr-2" style="color: #003366;"></i> Utilize o menu ou os blocos abaixo para navegar pelo sistema.
        </div>

        {{-- GRID DE CARDS DAS FUNCIONALIDADES --}}
        <div class="row">

            {{-- BLOCK: Cursos --}}
            @can('disciplinas')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Cursos</h5>
                        <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/cursos') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-university mr-2 fa-lg icon-eesc"></i>
                                    <span>Consultar Cursos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            
            {{-- BLOCK: Validação de curso --}}
            @can('datagrad')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Validação de curso</h5>
                        <i class="fas fa-file-signature fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            @can('datagrad')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/sintese') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-file-alt mr-2 fa-lg icon-eesc"></i>
                                    <span>Relatório síntese</span>
                                </a>
                            </li>
                            @endcan
                            @can('datagrad')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/complementar') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-file-alt mr-2 fa-lg icon-eesc"></i>
                                    <span>Relatório complementar</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            @endcan

            {{-- BLOCK: Relatórios cursos --}}
            @can('relatorio-curso')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Relatórios cursos</h5>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            @can('relatorio-cgahoralu')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/carga-acumulada') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-file-alt mr-2 fa-lg icon-eesc"></i>
                                    <span>Carga horária acumulada</span>
                                </a>
                            </li>
                            @endcan
                            @can('relatorio-curso')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/horarios') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-clock mr-2 fa-lg icon-eesc"></i>
                                    <span>Quadro horários de aula</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            @endcan

            {{-- BLOCK: Relatórios alunos --}}
            @can('datagrad')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Relatórios alunos</h5>
                        <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            @can('datagrad')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/gradehoraria') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-id-card mr-2 fa-lg icon-eesc"></i>
                                    <span>Relatórios grade horária</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            @endcan

            {{-- BLOCK: Estatísticas --}}
            @can('datagrad')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Estatísticas</h5>
                        <i class="fas fa-chart-bar fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            @can('evasao')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/evasao') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-chart-bar mr-2 fa-lg icon-eesc"></i>
                                    <span>Relatório de evasão</span>
                                </a>
                            </li>
                            @endcan
                            @can('datagrad')
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('graduacao/relatorio/turma') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="far fa-file-alt mr-2 fa-lg icon-eesc"></i>
                                    <span>Relatório de turmas</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            @endcan

            {{-- BLOCK: Disciplinas --}}
            @can('disciplinas')
            <div class="col-md-4 mb-4">
                <div class="card h-100 border shadow-sm rounded-lg overflow-hidden card-eesc">
                    <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #dbe2f0; color: #003366; border-bottom: 1px solid #c3d0e8;">
                        <h5 class="card-title mb-0 font-weight-bold">Disciplinas</h5>
                        <i class="fas fa-book fa-2x opacity-75"></i>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0 py-2">
                                <a href="{{ url('disciplinas') }}" class="card-link-item text-decoration-none d-flex align-items-center">
                                    <i class="fas fa-book-open mr-2 fa-lg icon-eesc"></i>
                                    <span>Consultar e Alterar Disciplinas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endcan

        </div>
    @endcan

    {{-- SEÇÃO INFORMATIVA COMPLETA COM AS INSTRUÇÕES ORIGINAIS --}}
    <div class="card border shadow-sm mt-3">
        <div class="card-body">
            <h5 class="font-weight-bold border-bottom pb-2 mb-3" style="color: #003366;">
                <i class="fas fa-info-circle mr-1" style="color: #003366;"></i> Dados para reconhecimento de cursos de graduação
            </h5>
            
            <div class="mb-3">
                <strong>Funcionalidades</strong><br>
                Este sistema permite consultar os dados dos cursos e as disciplinas oferecidas pela unidade, bem como propor alterações.
                Cada responsável pode propor alterações em suas disciplinas e gerar documento PDF para
                tramitação nas instâncias necessárias. Uma vez aprovado, a assistência acadêmica utiliza
                as informações para implementar a nova versão da disciplina no sistema Júpiter.
            </div>

            <div class="mb-2">
                Este sistema auxilia a coleta e organização de dados para o <b>reconhecimento</b> e <b>renovação de
                reconhecimento</b> de cursos de graduação junto ao Conselho Estadual de Educação de São Paulo - CEE,
                conforme deliberação CEE 171/2019 que "Dispõe sobre a regulação, supervisão e avaliação de instituições de ensino
                superior e cursos superiores de graduação vinculados ao Sistema Estadual de Ensino de São Paulo".
            </div>

            <div class="ml-3 my-2">
                &bull; Em particular, o sistema fornece subsídios para o preenchimento do item 1 - III do anexo 8 - <b>Relatório
                síntese</b>, documento obrigatório do processo.<br>
                Além disso são fornecidas outras informações que podem auxiliar a elaboração de <b>Relatório com informações
                complementares</b>.
            </div>

            <div class="ml-3 my-2">
                &bull; Este sistema extrai dados da base USP como função, jornada, carga horária, turmas, etc, bem como dados do currículo
                lattes como titulação, atividade docente, etc. <br>
                Lembrando que o currículo lattes deve ser mantido atualizado pelo docente conforme item 3 do anexo 8.
            </div>

            <hr class="my-3">

            <div>
                <b>Outras funcionalidades:</b><br>
                - <b>Consultar Cursos</b>: permite consultar grade e turmas dos cursos de graduação;<br>
                - <b>Consultar e Alterar Disciplinas</b>: é póssivel detalhar disciplinas e propor alterações;<br>
                - <b>Relatório grade horária</b>: mostra a grade horária corrente para a lista de alunos informada;<br>
                - <b>Relatório carga horária acumulada</b>: mostra a carga horária acumulada em carga obrigatória, optativa, estágio, AAC e AEX cumprida por cada aluno de um determinado curso e ano de ingresso selecionado ou informado na lista;<br>
                - <b>Quadro horário de aulas</b>: mostra os horários de aula de cada disciplina e suas respectivas turmas, filtrado por curso e ano/semestre;<br>        
                - <b>Relatório de evasão</b>: mostra gráfico e tabela de evasão por curso e por ano de ingresso.
            </div>
        </div>
    </div>

</div>

<style>
    .card-eesc {
        border-color: #d0d7de !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-eesc:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important;
    }
    .card-link-item {
        color: #212529;
        transition: color 0.15s ease-in-out;
    }
    .card-link-item:hover {
        color: #003366 !important;
        font-weight: 600;
    }
    .icon-eesc {
        color: #003366;
    }
    .opacity-75 {
        opacity: 0.75;
    }
</style>
@endsection