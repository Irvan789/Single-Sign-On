<div
  class="mt-3 flex flex-col gap-0.5 text-[#3d3530]"
  {{ $attributes->except(['class', 'title']) }}
>
  <div class="text-lg/tight font-semibold">
    {{ $title }}
  </div>

  @if ($slot)
    <span class="text-[0.9375rem]/4.5">
      {{ $slot }}
    </span>
  @endif
</div>

