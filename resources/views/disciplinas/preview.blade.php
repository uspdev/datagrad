@extends('layouts.app')

{{-- @section('menu')
@endsection --}}

@section('skin_footer')
@endsection

@section('styles')
  @parent
  <style>
    .pdf {
      width: 100%;
      /* aspect-ratio: 4 / 3; */
    }
  </style>
@endsection

@section('content')
  @include('disciplinas.partials.navbar-preview')

  <div class="row">
    <div class="col-12">
      <embed src="{{ $url }}" type="application/pdf" width="100%" height="800px">
      <a href="{{ $url }}" target="_blank">Abrir PDF</a>
      {{-- <object class="pdf" type="application/pdf" data="{{ $url }}" height="800">
      Alt: <a href="disciplina.pdf">disciplina.pdf</a>
      </object> --}}

      {{-- veio do mercurioweb --}}
      {{-- <object data="blob:https://portalservicos.usp.br/db028cea-e3fe-48d9-b3b2-1f7bb311968b" type="application/pdf"
        class="w-100 h-100" style="min-height: 500px;">
        <p>Não foi possível exibir o documento. Utilize o botão abaixo para fazer download:</p>
        <p class="text-center"><a href="blob:https://portalservicos.usp.br/db028cea-e3fe-48d9-b3b2-1f7bb311968b"
            download="documento.pdf" class="btn btn-sm btn-primary"> Download do documento </a></p>
      </object> --}}
    </div>
  </div>
@endsection
