@extends('layouts.app')

@section('content')
  @auth
    <div>Ei <b>{{ Auth()->user()->name }}!</b></div>
    <div>Você não tem acesos a este recurso!</div>
  @else
    <div><b>Faça o <a href="login">Login</a> com a senha única para acessar esse sistema.</b></div>
  @endauth

  <br />
  <br /><br />
@endsection
