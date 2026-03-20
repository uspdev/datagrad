<button id="btnSalvarPDF" class="btn btn-sm btn-danger d-print-none">Mudar para Em aprovação</button>

@section('javascripts_bottom')
  @parent
  <!-- html2canvas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <!-- jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
  const { jsPDF } = window.jspdf;

  const btn = document.getElementById('btnSalvarPDF');
  if (!btn) return;

  btn.addEventListener('click', async () => {
    const el = document.body; // ou outro container específico
    const modified = [];

    // ocultar nav e d-print-none
    document.querySelectorAll('nav, .d-print-none').forEach(el => {
      const prev = el.style.getPropertyValue('display');
      const prevPriority = el.style.getPropertyPriority('display');
      modified.push({ el, prev, prevPriority });
      el.style.setProperty('display', 'none', 'important');
    });

    const doc = new jsPDF('p', 'mm', 'a4');
    await doc.html(el, {
      callback: (doc) => {
        doc.save('pagina.pdf');
        // restaurar elementos
        modified.forEach(item => {
          if (item.prev) {
            item.el.style.setProperty('display', item.prev, item.prevPriority);
          } else {
            item.el.style.removeProperty('display');
          }
        });
      },
      x: 10,
      y: 10,
      html2canvas: { scale: 1.2, useCORS: true }
    });
  });
});

  </script>
@endsection
