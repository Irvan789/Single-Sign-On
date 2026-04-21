<x-contents>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        Create Passport Client
      </div>

      <div class="mt-1 text-[0.9375rem]/4.5">
        Create a new OAuth client
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="createPassportClient"
      x-data="{ index: [Date.now()], callback: '' }"
      x-on:toastify.window="
        const event= Array.isArray($event.detail) ? $event.detail[0] : $event.detail
        if (event.type == 'success') {
          index = [Date.now()]
          callback = ''
        }
      "
    >
      <x-input-text
        label="Application Name"
        type="text"
        wire:model="name"
      />

      <div class="relative flex flex-col gap-2">
        <template
          x-for="(id, i) in index"
          :key="id"
        >
          <template x-if="i <= 0">
            <x-input-text
              label="Callback URL"
              type="url"
              x-model="callback"
              x-on:change="$wire.callback[i] = $event.target.value"
            >
              <x-button
                class="rounded-xs absolute right-0 top-px px-1.5 py-1 text-[0.625rem] leading-3"
                x-on:click="index.push(Date.now())"
              >
                Add Callback
              </x-button>
            </x-input-text>
          </template>
        </template>

        <template
          x-for="(id, i) in index"
          :key="id"
        >
          <template x-if="i > 0">
            <div class="relative">
              <x-input-text
                type="url"
                class="pr-11.5"
                x-on:change="$wire.callback[i] = $event.target.value"
              />

              <x-button
                class="inset-e-0 rounded-r-xs absolute inset-y-0 flex rounded-l-none bg-[#B85450] p-2 text-xs hover:bg-[#8B3A38]"
                x-on:click="index = index.filter(v => v != id)"
              >
                <span class="icon-[mingcute--delete-2-line] size-4"></span>
              </x-button>
            </div>
          </template>
        </template>
      </div>

      @if (session('passport-client'))
        <div class="rounded-xs w-full space-y-2 border border-[#c8b96e4d] px-2.5 py-2 text-sm text-[#8b3a38]">
          <div class="flex flex-row gap-1">
            <span class="icon-[mingcute--alert-line] size-4.5 mt-px"></span>
            The client secret will not be shown again, so don't
            lose it!
          </div>

          <div class="flex flex-col gap-1.5 text-sm/4 text-[#3d3530]">
            <div class="flex flex-col gap-0.5">
              Client ID:
              <span>{{ session('passport-client')['id'] }}</span>
            </div>

            <div class="flex flex-col gap-0.5">
              Client Secret:
              <span>{{ session('passport-client')['secret'] }}</span>
            </div>
          </div>
        </div>
      @endif

      <x-button
        type="submit"
        class="xs:max-w-30 ml-auto w-full text-xs/3"
      >
        Create Client
      </x-button>
    </x-form>
  </div>

</x-contents>
