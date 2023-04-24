<form method="POST" action="">
    @csrf
    <div class="form-group">
        <label for="exampleFormControlTextarea1" class="h4">Nomes (1 por linha)</label>
        <button type="submit" class="btn btn-sm btn-primary spinner">Enviar</button>
        <textarea name="nomes" class="form-control" id="exampleFormControlTextarea1" rows="4">{{ $nomes }}</textarea>
    </div>
</form>

@if ($naoEncontrados)
    <hr>
    <div class="h4">Não encontrados</div>
    @foreach ($naoEncontrados as $nome)
        {{ $nome }}<br>
    @endforeach
@endif
