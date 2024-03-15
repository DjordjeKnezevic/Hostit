<div class="container mt-4">
    <div class="p-3 mb-4 bg-white rounded shadow filters-wrapper">
        <h4 class="mb-3" style="color: #495057;">Invoices Filters</h4>
        <div class="row">
            <div class="col-md-6 mb-3 d-flex justify-content-around">
                <select name="monthYear" onchange="this.form.submit()" hx-get="{{ route('user-invoices-update') }}"
                    hx-trigger="change" hx-target="#invoices-container">
                    @foreach ($formattedDates as $value => $label)
                        <option value="{{ $value }}" {{ $year . '-' . $month == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <select name="sortBy" onchange="this.form.submit()" hx-get="{{ route('user-invoices-update') }}"
                    hx-trigger="change" hx-target="#invoices-container" class="form-select"
                    style="border-radius: .25rem; border: 1px solid #ced4da;">
                    <option value="amount_due">Sort by Amount Due</option>
                    <option value="amount_paid">Sort by Amount Paid</option>
                </select>
            </div>
        </div>
    </div>
    <div id="invoices-container">
        @include('components.invoices-list', [
            'invoicesByLocation' => $invoicesByLocation,
            'totalDue' => $totalDue,
            'totalPaid' => $totalPaid,
            'locations' => $locations,
        ])
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.invoice-collapse-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var collapseTarget = document.querySelector(button.dataset.target);
                if (collapseTarget) {
                    collapseTarget.classList.toggle('show');
                }
            });
        });
    });
</script>


<style>
    .filters-wrapper {
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgb(0 0 0 / 5%);
        margin-bottom: 20px;
    }

    .filters-wrapper h4 {
        color: #495057;
        margin-bottom: 1rem;
    }

    .filters-wrapper .form-select {
        border-radius: .25rem;
        border: 1px solid #ced4da;
        color: #495057;
        padding: .375rem 1.75rem .375rem .75rem;
    }

    .filters-wrapper .form-select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
    }

    .invoice-block {
        display: flex;
        flex-direction: column;
        padding: 20px;
        margin-bottom: 20px;
        background-color: rgba(2, 2, 48, 1);
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgb(0 0 0 / 10%);
    }

    .invoice-wrapper {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid white;
        padding-bottom: 20px;
    }

    .invoice-info h5,
    .invoice-financials p {
        color: #c4c4c5;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .invoice-financials {
        text-align: right;
    }

    .invoice-details {
        padding-top: 20px;
    }

    .invoice-collapse-btn {
        background: none;
        color: #c4c4c5;
        padding: 5px;
        border: none;
        text-align: left;
        width: 100%;
        text-decoration: underline;
    }

    .invoice-collapse-btn:focus {
        outline: none;
    }

    .header-totals h1 {
        color: #c4c4c5;
        font-size: 1.5rem;
        /* Add other styles as needed */
    }

    .invoice-details p {
        color: #fff;
        /* Or another color that is visible on the dark background */
    }
</style>
