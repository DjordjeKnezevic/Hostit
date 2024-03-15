<div class="container mt-4">
    <div class="p-3 mb-4 bg-white rounded shadow">
        <h4 class="mb-3" style="color: #495057;">Server Filters</h4>
        <form id="filterForm" hx-get="{{ route('filter-servers') }}" hx-target="#serversContainer"
            hx-trigger="change from:select" class="row">
            <div class="col-md-4 mb-3 d-flex justify-content-around">
                <select name="state" class="form-select">
                    <option value="">Select State</option>
                    @foreach ($states as $state)
                        <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="location" class="form-select">
                    <option value="">Select Location</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="sort" class="form-select">
                    <option value="desc" selected="selected">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>
            </div>
            <div class="col-12">
                <a href="{{ route('filter-servers') }}" hx-get="{{ route('filter-servers') }}"
                    hx-target="#serversContainer" hx-trigger="click" class="btn btn-secondary mt-3"
                    id="clearFilters">Clear Filters</a>
            </div>
        </form>
    </div>
    <div id="serversContainer">
        @include('components.servers-list', ['servers' => $servers])
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('clearFilters').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
        });
    });
</script>
