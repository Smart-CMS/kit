<a href="{{ $url }}" target="{{ $target }}" title="{{ $title }}"
    {{ $attributes }}>{{ $slot->isNotEmpty() ? $slot : $title }}</a>
