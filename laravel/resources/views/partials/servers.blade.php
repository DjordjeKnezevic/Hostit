@if ($servers)
    <option value="">Select a Server</option>
    @foreach ($servers as $server)
        <option value="{{ $server->id }}">
            {{ $server->serverType->name }}
        </option>
    @endforeach
@else
    <option value="">Please select a location first</option>
@endif
