@if (isset($server))
    <ul class="list-group" id="serverList">
        <h5 class="mt-2">Server information:</h5>
        <li class="list-group-item server-name">Name: {{ $server->name }}</li>
        <li class="list-group-item">CPU Cores: {{ $server->serverType->cpu_cores }}</li>
        <li class="list-group-item">RAM: {{ $server->serverType->ram }} GB</li>
        <li class="list-group-item">Storage: {{ $server->serverType->storage }} GB</li>
        <li class="list-group-item">Network Speed: {{ $server->serverType->network_speed }} Gbps</li>
    </ul>
@else
    <ul class="list-group" id="serverList">
    </ul>
@endif
