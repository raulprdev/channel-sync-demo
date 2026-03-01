@props(['label', 'value', 'color' => 'gray'])

<div class="bg-white rounded-lg shadow px-4 py-5">
    <dt class="text-sm font-medium text-gray-500 truncate">{{ $label }}</dt>
    <dd class="mt-1 text-3xl font-semibold text-{{ $color }}-600">{{ $value }}</dd>
</div>