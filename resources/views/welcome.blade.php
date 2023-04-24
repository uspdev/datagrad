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
      @cannot('datagrad')
        <div class="font-weight-bold">
          O acesso a esse sistema é restrito. Entre em contato com o responsável.
        </div>
        <br />
      @endcannot
      @can('datagrad')
      <div>
        Utilize o menu para navegar pelo sistema.
      </div>
      <br />
      @endcan
    @endcan
    <div>
      Este sistema auxilia a coleta e organização de dados para o reconhecimento e renovação de reconhecimento de cursos
      de graduação.<br />


      Em particular, o sistema fornece subsídios para o preenchimento do item 5 do relatório síntese (anexo 9),
      documento obrigatório do processo.
    <br />
    Conselho Estadual de Educação de São Paulo CEE

    Além disso são fornecidas informações complementares que podem auxiliar a elaboração de relatório com informações complementares.

    Dados extraídos da base USP carga horária, turmas, etc.
    Dados extraidos principalmente do currículo lattes.

    DELIBERAÇÃO CEE N° 171/2019
Dispõe sobre a regulação, supervisão e avaliação de
instituições de ensino superior e cursos superiores de
graduação vinculados ao Sistema Estadual de Ensino de
São Paulo


    </div>
  </div>
@endsection
