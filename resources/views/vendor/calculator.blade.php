{{-- resources/views/your-page.blade.php --}}
@extends('layouts.admin.main')
@section('content')
    <div class="row">
        <div class="col p-4">
            <button id="download-pdf" class="btn btn-primary">Download Output</button>
        </div>
    </div>

    <div id="calculator-capture">
        <x-calculator :eur="$rates->eur->amount" :tz="$rates->tz->amount" />
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('download-pdf').addEventListener('click', async function() {
                const captureElement = document.getElementById('calculator-capture');
                if (!captureElement) {
                    alert('Calculator area not found.');
                    return;
                }

                try {
                    // Ensure the element is visible (html2canvas needs it in the viewport)
                    const canvas = await html2canvas(captureElement, {
                        scale: 2,
                        useCORS: true, // in case there are external images
                        logging: false,
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.85);
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF({
                        orientation: 'portrait',
                        unit: 'pt',
                        format: 'a4'
                    });

                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    const imgWidth = pageWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    let heightLeft = imgHeight;
                    let position = 0;

                    // Add first page
                    pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    // If the image is taller than one page, add more pages
                    while (heightLeft > 0) {
                        position = -(pageHeight * (pdf.internal.getNumberOfPages()));
                        pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    pdf.save('calculator.pdf');
                } catch (error) {
                    console.error('PDF generation failed:', error);
                    alert('Could not generate PDF. Please check the console for details.');
                }
            });
        });
    </script>
@endsection
