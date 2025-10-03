@forelse ($disc->cursos as $curso)
  <div class="card">
    <div class="card-header text-center" style="background-color: azure">
      Curso: <b>{{ $curso['codcur'] }} - {{ $curso->dr['nomcur'] }}</b>
    </div>
    <div class="card-body p-1">
      @php
        $habilidades = $disc->habilidades($curso->codcur);
        $habilidadesIgl = $disc->habilidadesIgl($curso->codcur);
      @endphp
      <table class="table table-bordered table-sm w-100">
        <tr>
          <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
            Habilidades/Skills
          </th>
        </tr>
        <tr>
          @if (!empty($habilidades))
            <td class="px-3 copy-limit" style="width:50%;">
              @include('components.partials.copiar-btn')
              <div class="textarea">
                {!! implode('<br>', $habilidades) !!}
              </div>
            </td>
            <td class="px-3 copy-limit" style="width:50%;">
              @if (!empty($habilidadesIgl))
                @include('components.partials.copiar-btn')
                <div class="textarea">
                  {!! implode('<br>', $habilidadesIgl) !!}
                </div>
              @else
                <i>Sem versão em inglês</i>
              @endif
            </td>
          @else
            <td colspan="2" class="px-3 text-center">
              Sem habilidades selecionadas!
            </td>
          @endif
        </tr>
      </table>

      @php
        $competencias = $disc->competencias($curso->codcur);
        $competenciasIgl = $disc->competenciasIgl($curso->codcur);
      @endphp
      <table class="table table-bordered table-sm">
        <tr>
          <th colspan="2" class="titulo text-center" style="background-color: aliceBlue">
            Competências/Competences
          </th>
        </tr>
        <tr>
          @if (!empty($competencias))
            <td class="px-3 copy-limit" style="width:50%;">
              @include('components.partials.copiar-btn')
              <div class="textarea">
                {!! implode('<br>', $competencias) !!}
              </div>
            </td>
            <td class="px-3 copy-limit" style="width:50%;">
              @if (!empty($competenciasIgl))
                @include('components.partials.copiar-btn')
                <div class="textarea">
                  {!! implode('<br>', $competenciasIgl) !!}
                </div>
              @else
                <i>Sem versão em inglês</i>
              @endif
            </td>
          @else
            <td colspan="2" class="px-3 text-center">
              Sem competencias selecionadas!
            </td>
          @endif
        </tr>
      </table>

      {{-- <div class="text-center border rounded p-2" style="background-color: #f8f9fa;">
        Período ideal: ? | Ciclo: Básico/Profissional | Tipo: Obrigatória/Optativa livre
      </div> --}}

    </div>
  </div>
@empty
  <div class="card">
    <div class="card-header text-center" style="background-color: azure">
      Habilidades e competências
    </div>
    <div class="card-body p-1">
      Esta disciplina não é oferecida para cursos na Unidade.
    </div>
  </div>
@endforelse
