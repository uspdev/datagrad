@extends('layouts.app')

@section('content')
  @include('disciplinas.partials.show.navbar')

  <div class="alert alert-secondary p-4 m-2">
    <h4>Nenhuma alteração curricular em andamento</h4>
    <p>
      A disciplina <strong>{{ $disc->coddis }}</strong> não está vinculada a nenhuma proposta em edição ou em processo de
      aprovação.
    </p>
    <p class="">
      Caso seja necessário, inicie uma nova proposta de alteração curricular.
    </p>
    <p>
        <a href="{{ route('disciplinas.show', strtoupper($disc->coddis)) }}" class="btn btn-primary">Mostrar disciplina</a>
    </p>
  </div>
@endsection
