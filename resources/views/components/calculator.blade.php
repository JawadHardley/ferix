<div class="container general mb-3 card">
    <div class="row p-5">
        <h1 class="mb-5">Freight Certificate Cost Calculator || Presis Consultancy Ltd</h1> 
        <div class="col-12 col-md-4 mb-3">
            From:
        </div>
        <div class="col-12 col-md-8 mb-3">
            Ferix io Services
        </div>
        <div class="col-12 col-md-4 mb-3">
            Estimate date:
        </div>
        <div class="col-12 col-md-8 mb-3">
            {{ Carbon\Carbon::now()->format('d M Y') }}
        </div>
        <div class="col-12 col-md-4 mb-3">
            Currency:
        </div>
        <div class="col-12 col-md-8 mb-3">
            <select class="form-select" name="currency">
                <option selected value="1">USD</option>
                <option value="2">EUR</option>
                <option value="3">TZS</option>
            </select>
        </div>
        <div class="col-12 col-md-4 mb-3">
            Transport Type:
        </div>
        <div class="col-12 col-md-8 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="ttype" id="radioDefault1">
                <label class="form-check-label" for="radioDefault1">
                    Road
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="ttype" id="radioDefault1">
                <label class="form-check-label" for="radioDefault1">
                    Rail
                </label>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <!-- Currency: -->
        </div>
        <div class="col-12 col-md-8 mb-3">
            <select class="form-select" name="type">
                <option selected value="2">Continuance</option>
                <option value="1">regional</option>
            </select>
        </div>
    </div>
</div>

<div class="container freight mb-3  card fade-section">
    <div class="row p-5">
        <h1 class="mb-5">Freight Details</h1>
        <div class="col-12 col-md-4 mb-3">
            Gross weight (Kg):
        </div>
        <div class="col-12 col-md-8 mb-3">
            <div class="mb-3">
                <!-- <label for="exampleFormControlInput1" class="form-label">Email address</label> -->
                <input type="number" class="form-control" name="gross" placeholder="Eg 1000">
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            Volume (m3):
        </div>
        <div class="col-12 col-md-8 mb-3">
            <div class="mb-3">
                <!-- <label for="exampleFormControlInput1" class="form-label">Email address</label> -->
                <input type="number" class="form-control" name="volume" placeholder="Eg 1000">
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            Freight cost:
        </div>
        <div class="col-12 col-md-4 mb-3">
            <!-- <label for="exampleFormControlInput1" class="form-label">Email address</label> -->
            <input type="number" class="form-control" name="ucost" placeholder="Eg 250">
        </div>
        <div class="col-12 col-md-4 mb-3">
            <select class="form-select" name="currency2" aria-label="Default select example">
                <option selected value="1">USD</option>
            </select>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <!-- Freight cost: -->
        </div>
        <div class="col-12 col-md-8 mb-3">
            <button class="btn btn-primary call">Calculate</button>
            <button class="btn btn-danger reseter">Reset</button>
        </div>
    </div>
</div>

<div class="container answers mb-3 fade-section card">
    <div class="row p-5">
        <h1 class="mb-5">Cost Estimate</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" colspan="">Description</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Units</th>
                    <th scope="col">Line Totals</th>
                </tr>
            </thead>
            <tbody>
                <tr class="d-none regional">
                    <td>Bulk or/Ton or/Cbm VRAC 4</td>
                    <td>EUR</td>
                    <td>4.00</td>
                    <td class="callunit">1.00</td>
                    <td class="calltotal">20.00</td>
                </tr>
                <tr>
                    <td class="disc1">ADMIN-COD-Continuance</td>
                    <td class="disc1-c">USD</td>
                    <td class="codunit">20.0</td>
                    <td>1.00</td>
                    <td class="disc1-d">20.00</td>
                </tr>
                <tr>
                    <td>USD1.8% OF FREIGHT COST</td>
                    <td>USD</td>
                    <td>1.8%</td>
                    <td class="fcost">222.00</td>
                    <td class="ans1">4.00</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end h4">Total Estimated Cost</td>
                    <td class="ans2">24.00 USD</td>
                </tr>
        </table>

    </div>
</div>

<script>
window.tzRate = {{$eur}};
</script>

<script>
window.tshRate = {{$tz}};
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generalInputs = document.querySelectorAll('.general input, .general select');
    const freightDiv = document.querySelector('.freight');
    const freightInputs = document.querySelectorAll('.freight input, .freight select');
    const answersDiv = document.querySelector('.answers');
    const calcBtn = document.querySelector('.call');
    const resetBtn = document.querySelector('.reseter');
    const regionalRow = document.querySelector('.regional');

    // Helper to check if all inputs in a NodeList are filled
    function allFilled(inputs) {
        return Array.from(inputs).every(input => {
            if (input.type === 'radio') {
                const group = document.getElementsByName(input.name);
                return Array.from(group).some(r => r.checked);
            }
            return input.value && input.value.trim() !== '';
        });
    }

    // Fade helpers
    function fadeShow(el) {
        el.classList.add('show');
    }

    function fadeHide(el) {
        el.classList.remove('show');
    }

    // Hide freight, answers, and regional row initially
    fadeHide(freightDiv);
    fadeHide(answersDiv);
    if (regionalRow) regionalRow.classList.add('d-none');

    // Show/hide freight based on general inputs
    function checkGeneral() {
        if (allFilled(generalInputs)) {
            fadeShow(freightDiv);
        } else {
            fadeHide(freightDiv);
            fadeHide(answersDiv);
            if (regionalRow) regionalRow.classList.add('d-none');
        }
    }

    // Enable/disable calculate button based on all inputs
    function checkAll() {
        if (allFilled(generalInputs) && allFilled(freightInputs)) {
            calcBtn.disabled = false;
        } else {
            calcBtn.disabled = true;
            fadeHide(answersDiv);
            if (regionalRow) regionalRow.classList.add('d-none');
        }
    }

    // Attach listeners to general and freight inputs
    generalInputs.forEach(input => {
        input.addEventListener('input', function() {
            checkGeneral();
            checkAll();
        });
        if (input.type === 'radio') {
            input.addEventListener('change', function() {
                checkGeneral();
                checkAll();
            });
        }
    });
    freightInputs.forEach(input => {
        input.addEventListener('input', checkAll);
    });

    // Calculate button logic
    calcBtn.addEventListener('click', function(e) {
    e.preventDefault();
    const typeSelect = document.querySelector('.general select[name="type"]');
    const typeValue = typeSelect ? typeSelect.options[typeSelect.selectedIndex].text.toLowerCase() : '';
    const ucostInput = document.querySelector('.freight input[name="ucost"]');
    const grossInput = document.querySelector('.freight input[name="gross"]');
    const volumeInput = document.querySelector('.freight input[name="volume"]');
    const currencySelect = document.querySelector('.general select[name="currency"]');
    const currencyValue = currencySelect ? currencySelect.value : '1';
    const ucost = parseFloat(ucostInput ? ucostInput.value : '');
    const gross = parseFloat(grossInput ? grossInput.value : '');
    const volume = parseFloat(volumeInput ? volumeInput.value : '');

    // Always hide regional row first
    if (regionalRow) regionalRow.classList.add('d-none');

    let finalAns2 = 0;
    let finalCurrency = 'USD';

    if (typeValue === 'continuance' && !isNaN(ucost)) {
        document.querySelector('.fcost').textContent = ucost.toFixed(2);
        const ans1 = +(ucost * 0.018).toFixed(2);
        document.querySelector('.ans1').textContent = ans1.toFixed(2);
        finalAns2 = ans1 + 20;

        // Reset the fields to their "continuance" values
        document.querySelector('.disc1').textContent = 'ADMIN-COD-Continuance';
        document.querySelector('.disc1-c').textContent = 'USD';
        document.querySelector('.disc1-d').textContent = '20.00';
        document.querySelector('.codunit').textContent = '20.00';
        document.querySelector('.callunit').textContent = '1.00';
        document.querySelector('.calltotal').textContent = '20.00';

        fadeShow(answersDiv);
    } else if (typeValue === 'regional' && !isNaN(gross) && !isNaN(volume) && !isNaN(ucost)) {
        // Show the regional row
        if (regionalRow) regionalRow.classList.remove('d-none');
        // Step 1: m = gross / 1000
        const m = gross / 1000;
        // Step 2: use max(volume, m)
        const x = (volume >= m) ? volume : m;
        // Step 3: (x * 4 + 40) * 1.15
        const regionalResult = ((x * 4) + 40) * window.tzRate;
        // Step 4: ucost * 1.18%
        const ans1 = +(ucost * 0.018).toFixed(2);
        document.querySelector('.fcost').textContent = ucost.toFixed(2);
        document.querySelector('.ans1').textContent = ans1.toFixed(2);
        // Step 5: add regionalResult + ans1
        finalAns2 = regionalResult + ans1;

        // Set your requested fields for regional
        document.querySelector('.disc1').textContent = 'Feri/COD Admin Fee';
        document.querySelector('.disc1-c').textContent = 'EUR';
        document.querySelector('.disc1-d').textContent = '40.00';
        document.querySelector('.codunit').textContent = '40.00';
        document.querySelector('.callunit').textContent = x.toFixed(2);
        document.querySelector('.calltotal').textContent = (x * 4).toFixed(2);

        fadeShow(answersDiv);
    } else {
        fadeHide(answersDiv);
        if (regionalRow) regionalRow.classList.add('d-none');
    }

    // Currency conversion for ans2
    if (!isNaN(finalAns2) && finalAns2 > 0) {
        if (currencyValue === '2') { // EUR
            finalAns2 = finalAns2 / window.tzRate;
            finalCurrency = 'EUR';
        } else if (currencyValue === '3') { // TZS
            finalAns2 = finalAns2 * window.tshRate;
            finalCurrency = 'TZS';
        }
        document.querySelector('.ans2').textContent = finalAns2.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + finalCurrency;
    }

});

    // Reset button logic
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });
});
</script>