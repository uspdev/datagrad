@isset($cursoOpcao)

    <h4>Relatório de Evasão</h4> <br>

    <form action="{{ route('graduacao.relatorio.evasao.post') }}" method="POST" class="form-group">

        @csrf

        <label for="tipo">Calcular para:</label>
        <select id="tipo" name="tipo">

            <option value='' {{ old('tipo') == '' ? 'selected' : 'selected' }}></option>
            <option value='curso' {{ old('tipo') == 'curso' ? 'selected' : '' }}>Curso</option>
            <option value='unidade' {{ old('tipo') == 'unidade' ? 'selected' : '' }}>Unidade</option>


        </select>

        <br>

        <div id=opcaoCursos style='visibility: hidden'>


            <label for="curso">Curso:</label>
            <select id="curso" name="curso">

                <option value=''></option>

                @foreach ($cursoOpcao as $curso)
                    <option value={{ $curso['cod'] }} {{ old('curso') == $curso['cod'] ? 'selected' : '' }}>
                        {{ $curso['cod'] }} - {{ $curso['nome'] }} </option>
                @endforeach

            </select>

        </div>

        <label for="anoIngresso">Ano de ingresso da turma: </label>
        <select id="anoIngresso" name="ano">

            <option value=''></option>

            @foreach (range(2015, date('Y') - 1) as $ano)
                <option value={{ $ano }} {{ old('ano') == $ano ? 'selected' : '' }}>{{ $ano }}</option>
            @endforeach

        </select>

        <br><br>

        <button type="submit" class="btn btn-sm btn-primary spinner">Cálcular</button>
    </form>

    <script>
        var opcaoTipo = document.getElementById('tipo');
        var opcaoCurso = document.getElementById('opcaoCursos');

        opcaoTipo.addEventListener('input', function() {
            var opcaoSelecionada = opcaoTipo.value;


            if (opcaoSelecionada === 'unidade' || opcaoSelecionada === '') {

                opcaoCurso.style.visibility = 'hidden'

            } else {

                opcaoCurso.style.visibility = 'visible'
            }
        })

        function verificarOpcaoTipo() {

            if (opcaoTipo.value == 'curso') {

                opcaoCurso.style.visibility = 'visible'

            }

        }

        verificarOpcaoTipo()
    </script>

@endisset
