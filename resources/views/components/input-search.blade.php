<div class="relative inline-flex gap-2 text-[#3d3530]">
  <input
    type="text"
    class="rounded-xs inset-ring inset-ring-[#cfd2d0] focus:inset-ring-[#dbd19f] w-full min-w-52 bg-[#fffcf6] px-2.5 py-2 text-sm/5 font-normal focus:bg-[#fffefa] focus:outline-none"
    autocomplete="off"
    name="search"
    {{ $attributes }}
  />

  <button
    type="submit"
    class="rounded-xs cursor-pointer bg-[#514a43] px-2.5 text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
  >
    <span class="icon-[tabler--search] size-4">
    </span>
  </button>
</div>
