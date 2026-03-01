@props(['channel'])

<span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
    style="background-color: {{ $channel->code->color() }}"
>
    <span class="w-2 h-2 mr-1.5 rounded-full bg-green-400"></span>
    {{ $channel->name }}
</span>