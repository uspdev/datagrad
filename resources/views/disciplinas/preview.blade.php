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
    </div>
  </div>
@endsection
