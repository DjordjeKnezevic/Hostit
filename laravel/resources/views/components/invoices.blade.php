<div>
    <h3>Invoices for {{ Carbon\Carbon::create(null, $month)->monthName }} {{ $year }}</h3>
    @foreach ($invoicesByLocation as $locationId => $data)
        @php
            $location = App\Models\Location::find($locationId);
        @endphp
        <div class="card">
            <div class="card-header" id="heading{{ $locationId }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                        data-target="#collapse{{ $locationId }}" aria-expanded="true"
                        aria-controls="collapse{{ $locationId }}">
                        {{ $location->name }} - Invoice ${{ $data['total_amount_due'] }}
                    </button>
                </h2>
            </div>

            <div id="collapse{{ $locationId }}" class="collapse" aria-labelledby="heading{{ $locationId }}"
                data-parent="#accordionExample">
                <div class="card-body">
                    @foreach ($data['invoices'] as $invoice)
                        @php
                            $server = $invoice->subscription->service; // Assuming 'service' returns the server
                        @endphp
                        <p>{{ $server->name }} - ${{ $invoice->amount_due }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        $('.collapse').collapse();
    });
</script>
