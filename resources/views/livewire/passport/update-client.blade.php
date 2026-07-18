<x-contents>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="-mt-px block space-y-0.5">
      <div class="text-lg/5.5 font-bold">
        Update Passport Client
      </div>

      <div class="text-smd">
        Update OAuth Client {{ $client->name }}
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="updatePassportClient"
      x-data="{ redirects: $wire.callbacks.map((url, i) => ({ id: i, url })) }"
    >
      <x-input-text
        label="Application Name"
        type="text"
        wire:model="name"
      />

      <div class="relative flex flex-col gap-2">
        <template
          x-for="(value, i) in redirects"
          :key="value.id"
        >
          <template x-if="i <= 0">
            <x-input-text
              label="Callback URL"
              type="url"
              x-model="$wire.callbacks[i]"
              x-on:change="$wire.callbacks[i] = $event.target.value"
            >
              <x-button
                class="rounded-xs px-1.75 absolute right-0 top-px py-1 text-[0.625rem] leading-3"
                x-on:click="redirects.push({ id: Date.now(), url: '' })"
              >
                Add Callback
              </x-button>
            </x-input-text>
          </template>
        </template>

        <template
          x-for="(value, i) in redirects"
          :key="value.id"
        >
          <template x-if="i > 0">
            <div class="relative">
              <x-input-text
                type="url"
                class="pr-11.5"
                x-model="$wire.callbacks[i]"
                x-on:change="$wire.callbacks[i] = $event.target.value"
              />

              <x-button
                class="inset-e-0 rounded-r-xs absolute inset-y-0 flex rounded-l-none bg-[#B85450] p-2.5 text-xs hover:bg-[#8B3A38]"
                x-on:click="redirects = redirects.filter(value => value.id != redirects[i].id)"
              >
                <span class="icon-[mingcute--delete-2-line] size-4"></span>
              </x-button>
            </div>
          </template>
        </template>
      </div>

      <x-button
        type="submit"
        class="xs:max-w-30 ml-auto w-full text-xs/3"
      >
        Update Client
      </x-button>
    </x-form>
  </div>
</x-contents>
