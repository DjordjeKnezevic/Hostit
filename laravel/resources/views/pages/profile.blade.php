@extends('layouts.layout')

@section('body_class', 'sub_page')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <h3>Profile</h3>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="nav-servers" hx-get="{{ route('user-servers') }}"
                                hx-target="#content" hx-swap="innerHTML">
                                Servers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="nav-invoices" hx-get="{{ route('user-invoices') }}"
                                hx-target="#content" hx-swap="innerHTML">
                                Invoices
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="content">
                <div class="pt-3">
                    @if ($servers)
                        @include('components.servers', [
                            'servers' => $servers,
                            'locations' => $locations,
                            'states' => $states,
                        ])
                    @else
                        <h2>Welcome to your profile</h2>
                        <p>Here you can see your servers and invoices</p>
                    @endif
                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('htmx:afterSwap', function() {
            // Remove 'active' class from all nav links
            document.querySelectorAll('.nav-link').forEach(function(link) {
                link.classList.remove('active');
            });

            // Add 'active' class to the clicked nav link
            let currentUrl = window.location.href;
            if (currentUrl.includes('user-servers')) {
                document.getElementById('nav-servers').classList.add('active');
            } else if (currentUrl.includes('user-invoices')) {
                document.getElementById('nav-invoices').classList.add('active');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .container-fluid {
            max-width: 100%;
        }

        .sidebar {
            z-index: 1020;
            top: 0;
            left: 0;
            width: 250px;
            /* Sidebar width */
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: #333;
        }

        .sidebar .nav-link.active {
            color: #007bff;
            background-color: transparent;
        }

        .position-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
            /* Scrollable sidebar */
        }

        .px-md-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        @media (min-width: 768px) {
            .sidebar {
                top: 0;
                bottom: 0;
                left: 0;
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            }

            .sidebar.collapse {
                display: none;
                /* Hide sidebar on smaller screens */
            }

            .sidebar.collapse.show {
                display: block;
                /* Show sidebar on medium screens and up */
            }
        }
    </style>
@endpush
