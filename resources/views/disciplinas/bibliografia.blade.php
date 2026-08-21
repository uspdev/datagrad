@extends('layouts.app')

@push('styles')
  <style>
    .w-70px {
      width: 70px !important;
    }
  </style>
@endpush

@section('content')
  @include('disciplinas.partials.index-navbar')
  @include('disciplinas.partials.bibliografia-filtros')
  @include('disciplinas.partials.bibliografia-explicacao')

  <table class="table table-striped table-bordered table-sm datatable-simples dt-fixed-header dt-buttons dt-state-save">
    <thead>
      <tr>
        <th>Período</th>
        <th>Disciplina</th>
        <th class="w-70px">Data ini</th>
        <th class="w-70px">Data fim</th>
        <th>Tipo</th>
        <th>Obra</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($bbg as $b)
        <tr>
          <td>{{ $b['periodo'] }}</td>
          <td>
            <a href="{{ route('disciplinas.bibliografia', ['coddis' => $b['coddis'], 'dtainibbg' => $b['dtaini'], 'dtafimbbg' => $b['dtafim']]) }}"
              class="abrir-disciplina"
              data-url-disciplina="{{ route('disciplinas.show', ['coddis' => $b['coddis']]) }}#bibliografia">
              {{ $b['coddis'] }}
            </a>
          </td>
          <td>{{ $b['dtaini'] }}</td>
          <td>{{ $b['dtafim'] }}</td>
          <td>{{ $b['tipo'] }}</td>
          <td>{{ $b['obra'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@push('scripts')
  <div class="modal fade" id="modal-disciplina" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">
            Disciplina <span class="modal-disciplina-titulo"></span>
            <a href="#" class="modal-disciplina-link" target="_blank" title="Abrir disciplina">
              <i class="fas fa-external-link-alt"></i>
            </a>
          </h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body modal-disciplina-conteudo">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="sr-only">Carregando...</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    $(document).on('click', '.abrir-disciplina', function(e) {
      e.preventDefault();

      const modal = $('#modal-disciplina');
      const conteudo = modal.find('.modal-disciplina-conteudo');

      const url = $(this).attr('href');
      const urlDisciplina = $(this).data('url-disciplina');
      const coddis = new URL(url, window.location.origin).searchParams.get('coddis');
      modal.find('.modal-disciplina-titulo').text(coddis);
      modal.find('.modal-disciplina-link').attr('href', urlDisciplina);
      modal.modal('show');

      $.ajax({
        url: url,
        type: 'GET',
        success: function(html) {
          conteudo.html(html);
          modal.one('shown.bs.modal', function() {
            autoExpandAll(conteudo[0]);
          });
        },
        error: function() {
          modal.find('.modal-disciplina-conteudo').html(
            '<div class="alert alert-danger">Não foi possível carregar as informações.</div>'
          );
        }
      });
    });
  </script>
@endpush
