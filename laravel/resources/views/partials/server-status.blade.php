<div class="col-6">
    <h3>Status</h3>
    @php
        $statusColor = match ($subscription->serverStatus->status ?? 'pending') {
            'good' => 'green',
            'pending' => 'yellow',
            'down' => 'red',
            'stopped' => 'gray',
            'terminated' => 'black',
            default => 'gray',
        };
    @endphp
    <div>
        <div class="status-indicator" style="background-color: {{ $statusColor }};"></div>
        <span>{{ ucfirst($subscription->serverStatus->status ?? 'pending') }}</span>
    </div>
</div>
<div class="col-6 d-flex justify-content-center flex-column align-items-center">
    <h3>Actions</h3>
    <div class="w-75 d-flex justify-content-around">
        @if ($subscription->serverStatus->status === 'terminated')
            <p>No actions available for this server</p>
        @else
            @if (in_array($subscription->serverStatus->status, ['good', 'pending']))
                <button hx-put="{{ route('server-restart', ['server' => $subscription->id]) }}"
                    hx-target="#status-{{ $subscription->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                    class="btn btn-warning w-25">Restart</button>
                <button hx-put="{{ route('server-stop', ['server' => $subscription->id]) }}"
                    hx-target="#status-{{ $subscription->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                    class="btn btn-danger w-25">Stop</button>
            @elseif($subscription->serverStatus->status === 'stopped')
                <button hx-put="{{ route('server-start', ['server' => $subscription->id]) }}"
                    hx-target="#status-{{ $subscription->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                    class="btn btn-success">Start</button>
            @endif
            <button hx-post="{{ route('server-terminate', ['server' => $subscription->id]) }}"
                hx-target="#status-{{ $subscription->id }}" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                class="btn btn-danger w-25">Terminate</button>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.body.addEventListener('htmx:afterSwap', (event) => {
            const noActionsText = "No actions available for this server";
            if (event.detail.target.innerHTML.includes(noActionsText)) {
                toastr.success('Server terminated successfully');
            }
        });
    </script>
@endpush
