@extends('layouts.app')

@section('content')
  <div class="h4">
    Dados para reconhecimento de cursos de graduação
  </div>
  <div class="card-body">
    @cannot('user')
      <div class="font-weight-bold">
        Faça o <a href="login">login</a> com a senha única para acessar esse sistema.
      </div>
      <br />
    @endcannot
    @can('user')
      {{-- @cannot('datagrad')
        <div class="font-weight-bold">
          O acesso a esse sistema é restrito. Entre em contato com o responsável.
        </div>
        <br />
      @endcannot --}}
      {{-- @can('datagrad')
        <div>
          Utilize o menu para navegar pelo sistema.
        </div>
        <br />
      @endcan --}}
      <div>
        Utilize o menu para navegar pelo sistema.
      </div>
    @endcan

    <br />
    <div class="h5">Funcionalidades</div>
    <div>
      Este sistema permite consultar as disiciplinas oferecidas pela unidade e propor alterações.
      Cada responsável pode propor alterações em suas disciplinas e gerar documento PDF para
      tramitação nas instâncias necessárias. Uma vez aprovado, a assitência acadêmica utiliza
      as informações para implementar a nova versão da disciplina no sistema Júpiter.
    </div>
    <br>
    <div>
      Este sistema auxilia a coleta e organização de dados para o <b>reconhecimento</b> e <b>renovação de
        reconhecimento</b> de cursos de graduação junto ao Conselho Estadual de Educação de São Paulo - CEE,
      conforme deliberação CEE 171/2019 que "Dispõe sobre a regulação, supervisão e avaliação de instituições de ensino
      superior e cursos superiores de graduação vinculados ao Sistema Estadual de Ensino de São Paulo".<br />
    </div>
    <div class="ml-3 my-2">
      Em particular, o sistema fornece subsídios para o preenchimento do item 1 - III do anexo 8 - <b>Relatório
        síntese</b>, documento obrigatório do processo.<br>
      Além disso são fornecidas outras informações que podem auxiliar a elaboração de <b>Relatório com informações
        complementares</b>.
    </div>
    <div class="ml-3 my-2">
      Este sistema extrai dados da base USP como função, jornada, carga horária, turmas, etc, bem como dados do currículo
      lattes como titulação, atividade docente, etc. <br>
      Lembrando que o currículo lattes deve ser mantido atualizado pelo docente conforme item 3 do anexo 8.<br>
    </div>
    <br>
    <div>
      Outras funcionalidades:<br>
      - <b>Relatório de carga didática</b>: mostra a carga didática de docentes em determinado período;<br>
      - <b>Relatório grade horária</b>: mostra a grade horária corrente para a lista de alunos informada;<br>
      - <b>Relatório de evasão</b>: mostra gráfico e tabela de evasão por curso e por ano de ingresso;<br>
      - <b>Alteração de ementas de disicplinas</b>: Ferramenta que facilita a atualização das ementas das disciplinas.<br>
    </div>
  </div>
@endsection
