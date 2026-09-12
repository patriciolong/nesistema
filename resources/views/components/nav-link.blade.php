@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2.5 text-base font-extrabold text-indigo-700 bg-indigo-50 border-b-2 border-indigo-600 rounded-xl transition duration-150 ease-in-out shadow-sm'
            : 'inline-flex items-center px-4 py-2.5 text-base font-bold text-gray-700 hover:text-indigo-600 hover:bg-slate-100 rounded-xl transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
