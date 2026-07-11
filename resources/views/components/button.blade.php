@props(['variant' => 'primary'])

<button
  {{ $attributes->merge(['type' => 'button'])->twMerge('rounded-xs px-3 py-2.5 text-sm/4 text-[#f0ede8] transition-colors duration-300', $variant == 'danger' ? 'bg-[#8b3a38e6] hover:bg-[#8b3a38] disabled:bg-[#8b3a38cc] disabled:hover:bg-[#8b3a38cc]' : ($variant == 'warning' ? 'bg-[#b8952ae6] hover:bg-[#b8952a] disabled:bg-[#b8952acc] disabled:hover:bg-[#b8952acc]' : 'bg-[#514a43] hover:bg-[#3d3530] disabled:bg-[#3d3530cc] disabled:hover:bg-[#3d3530cc]')) }}
>
  {{ $slot }}
</button>
