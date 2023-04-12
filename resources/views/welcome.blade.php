@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h4">
      Dados para reconhecimento de cursos de graduação
    </div>
    <div class="card-body">
      Este sistema auxilia a coleta e organização de dados para o reconhecimento de cursos de graduação.
      @cannot('user')
        <div class="font-weight-bold">
          Faça o <a href="login">login</a> com a senha única para acessar esse sistema.
        </div>
      @endcannot
      @cannot('datagrad')
        <div class="font-weight-bold">
          O acesso a esse sistema é restrito. Entre em contato com o responsável.
        </div>
      @endcannot
    </div>
  </div>
@endsection
