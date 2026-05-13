<label
  class="text-[0.9375rem]/4.5 relative flex w-full flex-col gap-1.5 font-medium text-[#3d3530]"
  x-data="{ showPassword: false }"
  {{ $attributes->only(['x-show']) }}
>
  {{ $label ?? '' }}

  @if ($type != 'password')
    <input
      type="{{ $type ?? 'text' }}"
      autocomplete="off"
      {{ $attributes->twMerge('rounded-xs inset-ring inset-ring-[#5e6e754d] focus:inset-ring-[#c8b96ea6] bg-[#ffffff73] px-3 py-2 text-sm/5 font-normal read-only:cursor-not-allowed read-only:bg-[#3d35301a] focus:bg-[#ffffffb3] focus:outline-none read-only:focus:bg-[#3d35301a]') }}
    />

    {{ $slot }}
  @else
    <input
      :type="showPassword ? 'text' : 'password'"
      autocomplete="off"
      {{ $attributes->twMerge('rounded-xs inset-ring inset-ring-[#5e6e754d] focus:inset-ring-[#c8b96ea6] bg-[#ffffff73] py-2 pl-3 pr-9 text-sm/5 font-normal read-only:cursor-not-allowed read-only:bg-[#3d35301a] focus:bg-[#ffffffb3] focus:outline-none read-only:focus:bg-[#3d35301a]') }}
    />

    <button
      type="button"
      class="inset-e-0 absolute top-6 flex shrink-0 cursor-pointer p-2.5"
      x-on:click="showPassword = !showPassword"
    >
      <span
        class="size-4 text-[#3d3530]"
        :class="showPassword ? 'icon-[heroicons--eye-20-solid]' : 'icon-[heroicons--eye-slash-20-solid]'"
      >
      </span>
    </button>
  @endif
</label>
