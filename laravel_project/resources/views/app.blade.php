<!DOCTYPE html>
<html lang="tr" data-bs-theme="dark" data-image-fallback="{{ asset('assets/media/placeholder.svg') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name') }}</title>
    @php use Artesaos\SEOTools\Facades\SEOMeta; use Artesaos\SEOTools\Facades\OpenGraph; use Artesaos\SEOTools\Facades\TwitterCard; @endphp
    <meta name="description" content="{{ SEOMeta::getDescription() }}" />
    @if(SEOMeta::getCanonical())<link rel="canonical" href="{{ SEOMeta::getCanonical() }}" />@endif
    {!! OpenGraph::generate() !!}
    {!! TwitterCard::generate() !!}
    <link rel="icon" href="{{ asset('assets/media/logos/favicon.svg') }}" type="image/x-icon" />

    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            var d = document.documentElement;
            d.classList.remove('light-mode', 'dark-mode');
            d.classList.add(t + '-mode');
            d.setAttribute('data-bs-theme', t === 'dark' ? 'dark' : 'light');
            d.classList.add('loaded');
        })();
    </script>

    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme-new.css') }}?v={{ filemtime(public_path('assets/css/theme-new.css')) }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/story-upload.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/auction-show.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/profile-show.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/balance-create.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/balance-show.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/seller-live-card.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/seller-auction-show.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-support-index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-fixes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/browse.css') }}?v={{ filemtime(public_path('assets/css/browse.css')) }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        #app-loader {
            position: fixed;
            inset: 0;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            transition: opacity .45s ease, visibility .45s ease;
        }
        #app-loader.app-loader-hide { opacity: 0; visibility: hidden; }
        .app-loader-inner { display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .app-loader-spin {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            animation: app-loader-rot .8s linear infinite;
        }
        @keyframes app-loader-rot { to { transform: rotate(360deg); } }
    </style>

    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true">

    <div id="app-loader" aria-hidden="true" role="status">
        <div class="app-loader-inner">
            <div class="app-loader-spin"></div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/js/custom/theme/image-fallback.js') }}"></script>
    <script src="{{ asset('assets/js/custom/app-toast.js') }}"></script>
    <script src="{{ asset('assets/js/custom/theme/ajax-delete.js') }}"></script>

    {{-- Hikaye (story) görüntüleyici + yükleme modalı artık Vue bileşenleridir:
         resources/js/Components/StoryViewer.vue & StoryUpload.vue (AppLayout içinde, Teleport ile body altına).
         Böylece SPA gezinmede (guest→auth) her zaman yüklenir. --}}

    @inertia
</body>

</html>
