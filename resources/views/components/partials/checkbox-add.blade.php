<!-- Botão que abre o modal -->
<button type="button" class="btn btn-sm text-primary d-print-none" data-toggle="modal"
  data-target="#{{ $id }}_modalOpcoes">
  <i class="fas fa-plus"></i> Adicionar/Remover
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $id }}_modalOpcoes" tabindex="-1" role="dialog" aria-labelledby="modalOpcoesLabel"
  aria-hidden="true">
  <div class="modal-dialog {{ $modalWidth }}" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalOpcoesLabel">Escolha as opções</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{-- Checkbox de opções pré-definidas --}}
      <div class="modal-body">
        <div class="checkbox-group border rounded p-2 overflow-auto text-left" style="max-height: 18em;">
          @foreach ($model->meta[$name]['options'] as $key => $val)
            <div class="form-check">
              <input type="checkbox" id="{{ $name . '_' . $key }}" name="{{ $name }}[]"
                value="{{ $val }}" class="form-check-input" @checked(in_array((string) $val, $model->{$name}))>
              <label class="form-check-label" for="{{ $name . '_' . $key }}">
                {{ $val }}
                @if ($nameIgl)
                  | <i>{{ $model->meta[$nameIgl]['options'][$key] }}</i>
                @endif
              </label>
              @if ($nameIgl)
                <input type="checkbox" id="{{ $nameIgl . '_' . $key }}" class="d-none" name="{{ $nameIgl }}[]"
                  value="{{ $model->meta[$nameIgl]['options'][$key] }}" @checked(in_array((string) $val, $model->{$name}))>
              @endif
            </div>
          @endforeach
        </div>

        @if ($custom)
          <!-- Textarea para inserir várias linhas -->
          @php
            // Remove os valores predefinidos para ficar somente com os customs e colocar no textarea
            $preDefinidos = array_values($model->meta[$name]['options']);
            $customValues = array_filter($model->{$name}, function ($v) use ($preDefinidos) {
                return !in_array((string) $v, $preDefinidos);
            });

            $preDefinidosIgl = array_values($model->meta[$nameIgl]['options']);
            $customValuesIgl = array_filter($model->{$nameIgl}, function ($v) use ($preDefinidosIgl) {
                return !in_array((string) $v, $preDefinidosIgl);
            });
          @endphp
          <div class="custom-checkbox-div d-none">
            @foreach ($customValues as $value)
              <input type="hidden" name="{{ $name }}[]" value="{{ $value }}">
            @endforeach
          </div>

          <div class="row">
            <div class="col-6">
              <div class="form-group mt-2">
                <label for="{{ $id }}_textarea">Digite suas opções (uma por linha)</label>
                <textarea id="{{ $id }}_textarea" class="form-control" rows="4"
                  placeholder="Escreva cada opção em uma linha...">{{ implode("\n", $customValues) }}</textarea>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group mt-2">
                <label for="{{ $id }}_textarea_igl">Enter your options (one per line)</label>
                <textarea id="{{ $id }}_textarea_igl" class="form-control" rows="4">{{ implode("\n", $customValuesIgl) }}</textarea>
              </div>
            </div>
          </div>

        @endif

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary salvarOpcoes" id="salvarOpcoes">Concluir</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    const campo = document.getElementById("{{ $id }}");
    const modal = document.getElementById("{{ $id }}_modalOpcoes");
    const btnSalvar = modal.querySelector(".salvarOpcoes");
    const divSelecionados = campo.querySelector(".selecionados");

    // hiddenInputs só vai existir se $custom = true
    const hiddenInputs = campo.querySelector('.custom-checkbox-div');

    btnSalvar.addEventListener("click", function() {
      // pega checkboxes marcados dentro do modal específico e prepara para exibição
      let checkboxes = modal.querySelectorAll("input[type='checkbox'].form-check-input:checked");
      let valores = Array.from(checkboxes).map(cb => {
        const key = cb.id.replace("{{ $name }}_", "");
        const cbIgl = campo.querySelector(`#{{ $nameIgl }}_${key}`) || false;
        return cb.value + (cbIgl ? " | <i>" + cbIgl.value + "</i>" : "");
      });

      // pega o textarea específico e junta com valores
      if (hiddenInputs) {
        const textarea = modal.querySelector('#{{ $id }}_textarea');
        const textareaIgl = modal.querySelector('#{{ $id }}_textarea_igl');
        const linhas = textarea?.value
          ?.split("\n")
          .map(l => l.trim())
          .filter(l => l !== "") || [];

        const linhasIgl = textareaIgl?.value
          ?.split("\n")
          .map(l => l.trim())
          .filter(l => l !== "") || [];

        // Verifica se possuem o mesmo número de linhas
        if (linhas.length !== linhasIgl.length) {
          alert("O número de linhas no campo em português e no campo em inglês deve ser igual.");
          return;
        }

        for (let i = 0; i < linhas.length; i++) {
          const pt = linhas[i] || ''; // evita undefined
          const igl = linhasIgl[i] || '';
          valores.push(pt + (igl ? ' | <i>' + igl + '</i>' : ''));
        }

        // cria/atualiza container de hidden inputs dentro do campo específico
        hiddenInputs.innerHTML = '';
        for (let i = 0; i < linhas.length; i++) {
          if (linhas[i]) {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "{{ $name }}[]";
            hidden.value = linhas[i];
            hidden.id = "{{ $id }}_hidden_" + i;
            hiddenInputs.appendChild(hidden);
          }
          if (linhasIgl[i]) {
            const hiddenIgl = document.createElement("input");
            hiddenIgl.type = "hidden";
            hiddenIgl.name = "{{ $nameIgl }}[]";
            hiddenIgl.value = linhasIgl[i];
            hiddenIgl.id = "{{ $id }}_hidden_igl_" + i;
            hiddenInputs.appendChild(hiddenIgl);
          }
        }
      }

      // atualiza a div de selecionados
      if (valores.length > 0) {
        divSelecionados.innerHTML = valores.join("<br>");
      } else {
        divSelecionados.innerHTML = "Nenhum item selecionado.";
      }

      // fecha só este modal
      $(`#{{ $id }}_modalOpcoes`).modal("hide");
    });

    // sincroniza checkbox "igl" se existir
    var checkboxesIgl = campo.querySelectorAll("input[type='checkbox'].form-check-input");

    checkboxesIgl.forEach(cb => {
      cb.addEventListener("change", function() {
        const id = this.id.replace("{{ $name }}_", ""); // pega o key
        const cbIgl = document.getElementById("{{ $nameIgl }}_" + id);
        if (cbIgl) {
          cbIgl.checked = this.checked; // sincroniza estado
        }
      });
    });
    // ------------------------

  });
</script>
