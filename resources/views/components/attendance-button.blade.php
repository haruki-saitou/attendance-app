{{-- 1. レイアウト（外枠）を使う --}}
@props(['text', 'action', 'color', 'disabled' => false])

<form action="{{ $action }}" method="POST" class="flex-1">
    @csrf
    <button type="submit" {{ $disabled ? 'disabled' : '' }}
        class="w-full py-10 text-xl font-bold rounded-lg shadow-md transition ...">
        {{ $text }}
    </button>
</form>
