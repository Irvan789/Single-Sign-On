<button
    {{ $attributes->except('testing')->merge(['type' => 'button', 'data-test'=> app()->environment('testing') && $attributes->has('testing') ? $testing : null])->twMerge('rounded px-3 py-2.5 text-sm/4 font-medium transition-colors duration-300', $attributes->has('variant') && $variant == 'danger' ? 'text-[#a8503d] inset-ring inset-ring-[#a8503d]/40 hover:bg-[#f5e9e4]' : 'bg-[#6b5a46] text-[#f0ede8] hover:bg-[#544636] disabled:bg-[#897967] disabled:hover:bg-[#897967]') }}
>
    {{ $slot }}
</button>
