<x-kit-layout>
    @section('content')
        @if ($page->layout_id)
            @include($page->layout->viewPath, $page->layout->variables)
        @endif
        @template
    @endsection
</x-kit-layout>
