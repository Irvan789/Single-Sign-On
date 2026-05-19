<div
  class="mt-3 flex flex-col gap-px text-[#3d3530]"
  {{ $attributes->except(['class', 'title']) }}
>
  <div class="text-lg/5.5 font-bold">
    {{ $title }}
  </div>

  @if ($slot)
    <span class="text-smd">
      {{ $slot }}
    </span>
  @endif
</div>
