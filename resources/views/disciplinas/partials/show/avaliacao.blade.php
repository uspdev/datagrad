@section('styles')
  @parent
  <style>
    #card-avaliacao {
      border: 1px solid BurlyWood;
      border-top: 3px solid BurlyWood;
    }
  </style>
@endsection

<div class="card" id="card-avaliacao">
  <div class="card-header">
    Instrumentos e critérios de avaliação
    <span class="badge badge-info">
      a partir de @date($dr['dtainiifmavl'])
      @if ($dr['dtafimifmavl'] ?? null)
        até @date($dr['dtafimifmavl'])
      @endif
    </span>
  </div>
  <div class="card-body">
    <x-disciplina-textarea-show name='dscmtdavl' :dr="$dr"></x-disciplina-textarea-show>
    <x-disciplina-textarea-show class="mt-3" name='crtavl' :dr="$dr"></x-disciplina-textarea-show>
    <x-disciplina-textarea-show class="mt-3" name='dscnorrcp' :dr="$dr"></x-disciplina-textarea-show>
  </div>
</div>
