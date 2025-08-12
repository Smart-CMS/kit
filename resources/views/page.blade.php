<x-kit-layout>
    @section('content')
        @if ($page->layout_id && $page->layout)
            @include($page->layout->viewPath, $page->layout->getVariables($page->layout_settings))
        @endif
        @template
    @endsection
</x-kit-layout>
