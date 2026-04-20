<div
  x-data="{ toasts: [], remove(key) { setTimeout(() => { this.toasts = this.toasts.filter((toast) => toast.key != key) }, 1000) } }"
  x-on:toastify.window="
    const event = Array.isArray($event.detail) ? $event.detail[0] : $event.detail
    const toast = { key: Date.now().toString(), type: event.type, message: event.message }
      
    toasts.push(toast)
  "
  class="inset-e-1/2 md:inset-e-0 fixed top-2.5 z-50 flex w-full max-w-xs translate-x-1/2 flex-col space-y-1.5 px-2 md:translate-x-0"
>

  <template
    x-for="toast in toasts"
    :key="toast.key"
  >
    <div
      x-data="{ showToast: false }"
      x-init="$nextTick(() => {
          showToast = true;
          setTimeout(() => {
              show = false;
              remove(toast.key);
          }, 3000)
      })"
      x-show="showToast"
      x-transition:enter="transform ease-out duration-300 transition"
      x-transition:enter-start="-translate-y-2 md:translate-x-2 md:translate-y-0"
      x-transition:enter-end="translate-y-0 md:translate-x-0"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="rounded-xs wrap-anywhere pointer-events-auto relative ml-auto inline-flex w-full max-w-xs items-center gap-2 bg-neutral-50 px-2.5 py-2 shadow-sm"
      :class="{
          'text-teal-900': toast.type == 'success',
          'text-rose-800': toast.type == 'error'
      }"
    >
      <div
        class="mt-px size-5 shrink-0"
        :class="{
            'icon-[mingcute--check-circle-line]': toast.type == 'success',
            'icon-[mingcute--alert-line]': toast.type == 'error'
        }"
      >
      </div>
      <div
        class="text-sm/4"
        x-text="toast.message"
      ></div>
    </div>
  </template>

</div>
