@extends('layouts.app')

@section('styles')
  @parent
  <style>
    .card {
      margin-top: 18px;
    }

    .card-header {
      font-size: 20px;
      padding-top: 6px;
      padding-bottom: 6px;
    }
  </style>
@endsection

@section('content')
  <form action="{{ route('disciplinas.update', $disc->coddis) }}" method="POST" id="disciplinas-edit-form">
    @csrf
    @method('put')
    <input type="hidden" name="id" value={{ $disc->id }}>
    <input type="hidden" name="coddis" value={{ $disc->coddis }}>

    @php
      // as várias abas são incluídas com base no id
      $abas = [
          ['id' => 'aba-disciplina', 'titulo' => 'Disciplina', 'cor' => 'bg-primary text-white'],
          ['id' => 'aba-basicas', 'titulo' => 'Ementa', 'cor' => 'bg-primary text-white'],
          ['id' => 'aba-avaliacao', 'titulo' => 'Avaliação', 'cor' => 'bg-warning text-dark'],
          ['id' => 'aba-bibliografia', 'titulo' => 'Bibliografia', 'cor' => 'bg-warning text-dark'],
          ['id' => 'aba-ods', 'titulo' => 'ODS', 'cor' => 'bg-warning text-dark'],
          ['id' => 'aba-viagem', 'titulo' => 'Viagem didática', 'cor' => 'bg-success text-white'],
          ['id' => 'aba-extensionista', 'titulo' => 'Atividade extensionista', 'cor' => 'bg-success text-white'],
          ['id' => 'aba-animais', 'titulo' => 'Atividade com animais', 'cor' => 'bg-success text-white'],
          ['id' => 'aba-cursos', 'titulo' => 'Cursos', 'cor' => 'bg-warning text-dark'],
          ['id' => 'aba-justificativa', 'titulo' => 'Justificativa', 'cor' => 'bg-warning text-dark'],
      ];
    @endphp
    <!-- Nav tabs -->
    <div class="card-header-sticky">
      @include('disciplinas.partials.edit-navbar')

      <ul class="nav nav-tabs" id="formTabs" role="tablist">
        @foreach ($abas as $i => $aba)
          <li class="nav-item">
            <a class="nav-link @if ($i === 0) active @endif " id="{{ $aba['id'] }}-tab"
              data-toggle="tab" href="#{{ $aba['id'] }}" role="tab">
              {{ $aba['titulo'] }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    <fieldset {{ $disc->isEditavel() ? '' : 'disabled' }}>
      <!-- Tab panes -->
      <div class="tab-content mt-3">
        @foreach ($abas as $i => $aba)
          <div class="tab-pane fade @if ($i === 0) show active @endif " id="{{ $aba['id'] }}"
            role="tabpanel">
            @includeIf("disciplinas.partials.{$aba['id']}")
          </div>
        @endforeach
      </div>
      <button type="submit" id="btn-next-tab" class="btn btn-primary mt-3">Salvar e continuar</button>
    </fieldset>
  </form>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      // habilitando popover
      $(function() {
        $('[data-toggle="popover"]').popover()
      })

      // restaura posição do scroll ao carregar a página
      // https://stackoverflow.com/questions/17642872/refresh-page-and-keep-scroll-position
      var scrollpos = sessionStorage.getItem('scrollpos');
      if (scrollpos) {
        window.scrollTo(0, scrollpos);
        sessionStorage.removeItem('scrollpos');
      }

      // ao submeter form vamos salvar posição do scroll
      $('form').on('submit', function() {
        sessionStorage.setItem('scrollpos', window.scrollY);
      })
    })


    $(document).ready(function() {

      // Ao mudar de aba, salva o id no localStorage
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        const tabId = $(e.target).attr('href'); // ex: "#aba1"
        localStorage.setItem('ultimaAba', tabId);
      });

      // Ao carregar a página, ativa a aba salva
      const ultimaAba = localStorage.getItem('ultimaAba');
      if (ultimaAba) {
        const link = $('a[data-toggle="tab"][href="' + ultimaAba + '"]');
        if (link.length) {
          link.tab('show');
        }
      }


      // Ao clicar em "Salvar e continuar", vai para próxima aba e salva no localStorage
      $('#btn-next-tab').on('click', function() {
        const $tabs = $('.nav-tabs .nav-link');
        const $active = $tabs.filter('.active');
        const currentIndex = $tabs.index($active);
        const $next = $tabs.eq(currentIndex + 1);

        if ($next.length) {
          // salva a próxima aba como última aba
          localStorage.setItem('ultimaAba', $next.attr('href'));

          // ativa a próxima aba
          $next.tab('show');

          // opcional: foco no primeiro input da nova aba
          $($next.attr('href')).find('input, textarea, select').first().focus();
        } else {
          // se não houver próxima aba, envia o form
          $(this).closest('form').submit();
        }
      });



    });
  </script>
@endsection
