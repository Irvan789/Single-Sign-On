<label
  class="relative flex w-full flex-col gap-1.5 text-[0.9375rem]/5 font-medium text-[#3d3530]"
  x-data="{ showPassword: false }"
  {{ $attributes->only(['x-show']) }}
>
  {{ $label ?? '' }}

  @if ($type != 'password')
    <input
      type="{{ $type ?? 'text' }}"
      autocomplete="off"
      {{ $attributes->twMerge('rounded-xs border border-[#5e6e754d] bg-[#ffffff73] px-2.5 py-1.5 text-sm/5 focus:border-[#c8b96ea6] focus:bg-[#ffffffb3] focus:outline-none') }}
    />

    {{ $slot }}
  @else
    <input
      :type="showPassword ? 'text' : 'password'"
      autocomplete="off"
      {{ $attributes->twMerge('rounded-xs border border-[#5e6e754d] bg-[#ffffff73] pl-2.5 pr-8 py-1.5 text-sm/5 focus:border-[#c8b96ea6] focus:bg-[#ffffffb3] focus:outline-none') }}
    />

    <button
      type="button"
      class="top-6.5 inset-e-0 p-2.25 absolute flex shrink-0 cursor-pointer"
      x-on:click="showPassword = !showPassword"
    >
      <span
        class="size-4 text-zinc-700"
        :class="showPassword ? 'icon-[heroicons--eye-20-solid]' : 'icon-[heroicons--eye-slash-20-solid]'"
      >
      </span>
    </button>
  @endif
</label>
