<a href="{{ route('pessoas.show', $pessoa['codpes']) }}" class="showPessoaModal">{{ $pessoa['nome'] }}</a>

@include('blocos.modal-pessoa')
