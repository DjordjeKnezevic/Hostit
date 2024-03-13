<div class="container mt-4">
    @foreach ($servers as $subscription)
        @include('components.server-block', ['subscription' => $subscription])
    @endforeach
</div>
