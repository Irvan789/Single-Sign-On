<div class="relative inline-flex gap-2 text-[#3d3530]">
  <input
    type="text"
    class="rounded-xs min-w-52 inset-ring inset-ring-[#5e6e754d] focus:inset-ring-[#c8b96ea6] w-full bg-[#ffffff73] px-2.5 py-2 text-sm/5 font-normal focus:bg-[#ffffffb3] focus:outline-none"
    autocomplete="off"
    name="search"
    {{ $attributes }}
  />

  <button
    type="submit"
    class="rounded-xs px-2.5 cursor-pointer bg-[#3d3530e6] text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
  >
    <span class="icon-[tabler--search] size-4">
    </span>
  </button>
</div>
