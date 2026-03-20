@extends('layouts.app')

@section('content')

  <h4>Relatório carga didática</h4>

  <form method="POST" action="">
    @csrf

    <div class="row d-print-none">
      <div class="col-md-6">
        <div class="form-group">
          <label for="nomesTextarea">Forneça uma lista de nomes (1 por linha)</label>
          <textarea name="nomes" class="form-control" id="nomesTextarea" rows="4">{{ old('nomes') }}</textarea>
        </div>
      </div>
      <div class="col-md-6">
        @include('grad.partials.setor-select')
        <div class="mt-3">
          <div class="form-check">
            <input type="checkbox" name="excluirTcc" id="excluirTcc" value="1" {{ $excluirTcc ? 'checked' : '' }}>
            <label class="form-check-label" for="excluirTcc">
              Não computar TCC, Projeto final de curso, etc
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3 ml-3">
      <div class="border border-primary rounded d-inline-flex py-1">
        <div class="mx-2"><b>Semestre</b>: de</div>
        <select class="border-0 input-small font-weight-bold" name="semestreIni" id="select-semestre-ini">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreIni ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        <div class="mx-2">a</div>
        <select class="border-0 input-small font-weight-bold" name="semestreFim" id="select-semestre-fim">
          @foreach ($turmaSelect as $t)
            <option {{ $t == $semestreFim ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        {{-- <div class="mx-2"></div> --}}
      </div>
      <div class="d-print-none pt-2">
        <button type="submit" class="btn btn-sm btn-primary spinner m-0">Enviar</button>
      </div>
    </div>

    <div class="my-2"></div>
  </form>

  <div class="d-print-none">
    @if ($naoEncontrados)
      <hr>
      <div class="h4">Não encontrados</div>
      @foreach ($naoEncontrados as $nome)
        {{ $nome }}<br>
      @endforeach
    @endif
  </div>

  @if ($pessoas)
    @include('grad.partials.cargadidatica-resultados')
  @endif

@endsection
