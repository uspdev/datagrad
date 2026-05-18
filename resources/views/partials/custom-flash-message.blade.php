@section('flash')

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{!! $error !!}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="flash-message fixed-top w-50 ml-auto mr-auto" style="margin-top: 60px; z-index: 9999;">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if (Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }} border border-dark rounded">{{ Session::get('alert-' . $msg) }}
          <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
        </p>
      @endif
    @endforeach
  </div>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {
      $(".flash-message").fadeTo(5000, 500).slideUp(500, function() {
        $(".flash-message").slideUp(500);
      });
    })
  </script>
@endsection
