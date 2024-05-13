@extends('layouts.app')

@section('styles')
  <style>
    .hover:hover {
      background-color: gainsboro;
    }

    .hide {
      display: none;
    }

    .hover:hover .hide {
      display: inline;
      color: red;
    }
  </style>
@endsection

@section('content')
  <div class="navbar navbar-light card-header-sticky justify-content-between mb-3 pb-1">
    <div class="h5">
      Funções
    </div>
    <div class="form-inline">
    </div>
  </div>

  <div class="h5">Comissão de graduação
    <button class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-plus"></i></button>
  </div>
  <div class="ml-3">
    @foreach ($roleCG->users as $user)
      <div class="hover"><span>{{ $user->name }}</span> <button class="btn btn-sm py-0"><i class="hide fas fa-trash"></i></button></div>
    @endforeach
  </div>

  <br>
  <div class="h5">Departamentos</div>
  @foreach ($departamentos as $role)
    <b>{{ $role->name }}</b><br>
    @foreach ($role->users as $user)
      {{ $user->name }} - {{ $user->permissions->pluck('name') }}<br>
    @endforeach
  @endforeach

  <br>
  <div class="h5">Cursos</div>
  {{-- @foreach ($roles as $role)
    <b>{{ $role->name }}</b><br>
    @foreach ($role->users as $user)
      {{ $user->name }}<br>
    @endforeach
  @endforeach --}}
@endsection
