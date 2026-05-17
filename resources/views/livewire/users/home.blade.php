<x-contents>
  <div class="xs:flex-row xs:items-center flex flex-col justify-between gap-4">
    <div class="-mt-px block space-y-0.5">
      <div class="text-lg/5.5 font-bold">
        Manage Users
      </div>

      <div class="text-smd">
        Update their account information and settings here
      </div>
    </div>

    <form
      class="contents"
      wire:submit="searchUser"
    >
      <x-input-search
        placeholder="Search User"
        wire:model="search"
      />
    </form>
  </div>

  @if (count($users) > 0)
    <div class="flex flex-col gap-2">
      @foreach ($users as $user)
        <div class="rounded-xs inline-flex items-center justify-between gap-2.5 border border-[#c8b96e4d] p-2.5">
          <div class="flex w-full max-w-80 flex-row items-center gap-2 overflow-hidden">
            <img
              src="{{ $user->avatar }}?size=128&r=g&d=mp"
              class="size-10 rounded-full"
            />

            <div class="flex max-w-0 flex-col text-nowrap">
              <span class="text-base/4.5 font-semibold">
                {{ $user->name }}
              </span>
              <span class="text-sm/4 font-normal">
                {{ $user->email }}
              </span>
            </div>
          </div>

          <x-anchor-button
            href="{{ route('users.profile', ['id' => $user->id]) }}"
            class="ml-auto text-nowrap px-3 py-2 text-xs/3"
            wire:navigate
          >
            View User
          </x-anchor-button>
        </div>
      @endforeach
    </div>
  @else
    <div class="rounded-xs flex h-40 items-center justify-center border border-[#c8b96e4d] text-center text-sm/4">
      There is Nothing Here!
    </div>
  @endif

  {{ $users->links() }}
</x-contents>
