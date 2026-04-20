<x-contents>
  <div
    class="flex flex-row items-center gap-3 md:flex-col md:gap-4"
    x-data="{
        name: '{{ $user->name }}',
        email: '{{ $user->email }}'
    }"
    x-on:profile-updated.window="
        name = $event.detail[0].name
        email = $event.detail[0].email
    "
  >
    <img
      src="{{ $user->avatar }}?size=128&r=g&d=mp"
      class="size-20 rounded-full md:size-24"
    />

    <div class="wrap-break-word flex w-full flex-col md:text-center">
      <div
        class="text-xl/6 font-bold"
        x-text="name"
      ></div>
      <div class="text-sm/5">
        <span x-text="email"></span>
      </div>
    </div>
  </div>
</x-contents>
