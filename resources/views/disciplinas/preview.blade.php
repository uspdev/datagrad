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
  <form>
    @include('disciplinas.partials.navbar-edit')
  </form>

  <div class="row">
    <div class="col-12">
      <object class="pdf" data="{{ $url }}" height="800"></object>
    </div>
  </div>
@endsection
