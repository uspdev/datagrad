@isset($taxaEvasao)
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <div id="chart_div", style="width: 90%; height: 500px; text-align: center;">Carregando gráfico...</div>
  <script>
    google.charts.load('current', {
      'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(desenharGrafico);

    function desenharGrafico() {
      var dadosEvasao = {!! json_encode($taxaEvasao) !!};
      var dadosGrafico = [
        ['Ano', 'Taxa de Permanencia', 'Taxa de Desistência', 'Taxa de Conclusão']
      ];
      for (let ano in dadosEvasao) {
        dadosGrafico.push([ano, dadosEvasao[ano]['txPermanencia'], dadosEvasao[ano]['txDesistenciaAcc'], dadosEvasao[ano][
          'txConclusaoAcc'
        ]]);
      }
      var data = google.visualization.arrayToDataTable(dadosGrafico);
      var options = {
        title: "Evasão da turma de {{ array_keys($taxaEvasao)[0] }} de ({{ $formRequest['codcur'] }}) {{ $formRequest['nomcur'] }}",
        width: 1300,
        height: 500,
        pointSize: 5,
        hAxis: {
          title: 'Anos'
        },
        tooltip: {
          isHtml: true
        },
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

    function saveChartAsImage() {
      var chartDiv = document.getElementById('chart_div');
      html2canvas(chartDiv, {
        width: 1200,
        height: 500,
        scale: 2,
        x: 100
      }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/png');
        var link = document.createElement('a');
        link.href = imgData;
        link.download = "Evasao-{{ $formRequest['nomcur'] }}-Turma-{{ array_keys($taxaEvasao)[0] }}.png";
        link.click();
      })
    };
  </script>
  <br>
  <button class="btn btn-sm btn-primary" onclick="saveChartAsImage()">Fazer Download do Gráfico</button>
  <br>
@endisset
