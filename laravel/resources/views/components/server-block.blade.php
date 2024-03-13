<div class="server-block mb-4 p-3 d-flex flex-column">
    <div class="d-flex justify-content-between align-items-start server-wrapper">
        <!-- Server Info -->
        <div class="server-info">
            <h3>{{ $subscription->service->name }}</h3>
            <p>Location: {{ $subscription->service->location->city }}</p>
            <p>Network Zone: {{ $subscription->service->location->network_zone }}</p>
            <p>Server Type: {{ $subscription->service->serverType->name }}</p>
            <p>Subscription Start: {{ $subscription->start_date }}</p>
            <p>Subscription End: {{ $subscription->end_date ? $subscription->end_date : 'N/A' }}</p>
            <p>Subscription type: {{ $subscription->pricing->period }}</p>
        </div>
        <!-- Server Specs -->
        <div class="server-specs">
            <h5>Specs:</h5>
            <p>CPU Cores: {{ $subscription->service->serverType->cpu_cores }}</p>
            <p>RAM: {{ $subscription->service->serverType->ram }} GB</p>
            <p>Storage: {{ $subscription->service->serverType->storage }} GB</p>
            <p>Network Speed: {{ $subscription->service->serverType->network_speed }} Gbps</p>
        </div>
    </div>
    <!-- Server Status -->
    <div class="server-status d-flex flex-row justify-content-between row" id="status-{{ $subscription->id }}">
        @include('partials.server-status', ['subscription' => $subscription])
    </div>
</div>
