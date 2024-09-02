<button type="button" class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#ajudaModal" title="Ajuda">
  Ajuda <i class="fas fa-question"></i>
</button>

@section('javascripts_bottom')
  <!-- Modal -->
  <div class="modal fade" id="ajudaModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        {{-- <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Ajuda para disciplinas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div> --}}
        <div class="modal-body">
          {!! md2html('disciplinas.md') !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  @parent
@endsection
