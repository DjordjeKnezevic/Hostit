@if ($invoicesByLocation->isEmpty())
    <div class="alert alert-warning w-100" role="alert">
        No invoices found
    </div>
@else
    <h2>Total Due: ${{ number_format($totalDue, 2) }}</h2>
    <h2>Total Paid: ${{ number_format($totalPaid, 2) }}</h2>

    @foreach ($invoicesByLocation as $locationId => $data)
        <div class="invoice-block w-100">
            <div class="invoice-wrapper">
                <div class="invoice-info">
                    <h3>{{ $locations[$locationId]->name ?? 'Unknown Location' }}</h3>
                </div>
                <div class="invoice-financials">
                    <p>Total Due: ${{ number_format($data['total_amount_due'], 2) }}</p>
                    <p>Total Paid: ${{ number_format($data['total_amount_paid'], 2) }}</p>
                </div>
            </div>
            <button class="invoice-collapse-btn" type="button" data-toggle="collapse"
                data-target="#collapse{{ $locationId }}" aria-expanded="false"
                aria-controls="collapse{{ $locationId }}">
                View Servers
            </button>
            <div id="collapse{{ $locationId }}" class="collapse">
                <div class="invoice-details">
                    @foreach ($data['invoices'] as $invoice)
                        @php
                            $server = $invoice->subscription->service;
                            $serverAmountDue = $invoice->amount_due;
                            $serverAmountPaid = $invoice->amount_paid; // Assuming 'amount_paid' is a field on the Invoice model
                        @endphp
                        <div class="server-invoice-info">
                            <p>{{ $server->name }}</p>
                            <div class="server-invoice-financials">
                                <p>Due: ${{ number_format($serverAmountDue, 2) }}</p>
                                <p>Paid: ${{ number_format($serverAmountPaid, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endif


<style>
    .server-invoice-info {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #ccc;
        align-items: center;
    }

    .server-invoice-financials {
        text-align: right;
    }
</style>
