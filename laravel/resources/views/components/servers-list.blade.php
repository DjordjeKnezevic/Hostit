<div class="container mt-4">
    @foreach ($servers as $subscription)
        @include('components.server-block', ['subscription' => $subscription])
    @endforeach
    <div class="d-flex justify-content-between my-4">
        @if ($servers->onFirstPage())
            <span class="btn btn-outline-primary disabled">Previous</span>
        @else
            <a href="#" hx-get="{{ $servers->previousPageUrl() }}" hx-target="#serversContainer"
                class="btn btn-outline-primary">Previous</a>
        @endif

        @if ($servers->hasMorePages())
            <a href="#" hx-get="{{ $servers->nextPageUrl() }}" hx-target="#serversContainer"
                class="btn btn-outline-primary">Next</a>
        @else
            <span class="btn btn-outline-primary disabled">Next</span>
        @endif
    </div>
</div>
