<div class="container mt-4">
    <div class="p-3 mb-4 bg-white rounded shadow">
        <h4 class="mb-3" style="color: #495057;">Server Filters</h4>
        <form id="filterForm" hx-get="{{ route('filter-servers') }}" hx-target="#serversContainer"
            hx-trigger="change from:select" class="row">
            <div class="col-md-4 mb-3 d-flex justify-content-around">
                <select name="state" class="form-select" style="border-radius: .25rem; border: 1px solid #ced4da;">
                    <option value="">Select State</option>
                    <option value="good">Good</option>
                    <option value="pending">Pending</option>
                    <option value="down">Down</option>
                    <option value="stopped">Stopped</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="location" class="form-select" style="border-radius: .25rem; border: 1px solid #ced4da;">
                    <option value="">Select Location</option>
                    @foreach (App\Models\Location::all() as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="sort" class="form-select" style="border-radius: .25rem; border: 1px solid #ced4da;">
                    <option value="desc" selected="selected">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>
            </div>
        </form>
    </div>


    <!-- Server list container -->
    <div id="serversContainer">
        @include('components.servers-list', ['servers' => $servers])
    </div>

    <!-- Pagination links with dynamic rendering -->


</div>
