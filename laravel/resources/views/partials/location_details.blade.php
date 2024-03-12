@if (isset($location))
    <ul class="list-group" id="locationList">
        <h5>Location information:</h5>
        <li class="list-group-item location-name">Name: {{ $location->name }}</li>
        <li class="list-group-item">Network Zone: {{ $location->network_zone }}</li>
        <li class="list-group-item">City: {{ $location->city }}</li>
    </ul>
@else
    <ul class="list-group" id="locationList">
    </ul>
@endif
