@props(['variant' => 'primary'])

<button
  {{ $attributes->merge(['type' => 'button'])->twMerge('rounded-xs px-3 py-2.5 text-sm/4 text-[#f0ede8] transition-colors duration-300', $variant == 'danger' ? 'bg-[#964f4d] hover:bg-[#8b3a38] disabled:bg-[#a26361] disabled:hover:bg-[#a26361]' : ($variant == 'warning' ? 'bg-[#bf9f41] hover:bg-[#b8952a] disabled:bg-[#c6ab56] disabled:hover:bg-[#c6ab56]' : 'bg-[#514a43] hover:bg-[#3d3530] disabled:bg-[#66605b] disabled:hover:bg-[#66605b]')) }}
>
  {{ $slot }}
</button>
