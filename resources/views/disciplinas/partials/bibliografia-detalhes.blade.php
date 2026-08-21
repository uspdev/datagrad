{{-- esta view é rendereizada no controller no método bibliografia() para retornar via ajax --}}

<div>
  Data início: <b>{{ $dtainibbg }}</b>, data fim: <b>{{ $dtafimbbg }}</b>
</div>

<div class="mt-2">
  <b>Bibliografia</b>
  <textarea class="form-control autoexpand" readonly>{!! str_replace(["\r\n", "\r", "\n"], " ¶\n", $bbg['dscbbgdis']) !!}</textarea>
</div>

<div class="mt-2">
  <b>Bibliografia complementar</b>
  <textarea class="form-control autoexpand" readonly>{!! str_replace(["\r\n", "\r", "\n"], " ¶\n", $bbg['dscbbgdiscpl']) !!}</textarea>
</div>
