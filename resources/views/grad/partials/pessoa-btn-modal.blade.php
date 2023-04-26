<a href="{{ route('pessoas.show', $pessoa['codpes']) }}" class="showPessoaModal">{{ $pessoa['nome'] ?? $pessoa['nompes'] }}</a>

@include('blocos.modal-pessoa')
