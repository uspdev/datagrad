<div class="my-1 form-inline">
  <label class="col-form-label">{{ $disc->meta['tipdis']['titulo'] }}</label>
  <div class="input-group input-group-sm ml-2">
    <div class="input-group-prepend diff d-none">
      <span class="input-group-text">{{ $disc->tipdis(true) }}</span>
    </div>
    <select class="form-control" name="tipdis" id="tipdis">
      <option value="">Selecione um ..</option>
      @foreach ($disc::$tipdis as $k => $v)
        <option value="{{ $k }}" {{ $disc->tipdis == $k ? 'selected' : '' }}>{{ $v }}</option>
      @endforeach
    </select>
  </div>
</div>
