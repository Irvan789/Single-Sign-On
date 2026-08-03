<x-contents>
    <x-card
        title="Create OAuth Client"
        description="Enter your app name and callback urls to authenticate with us."
    >
        <x-form
            wire:submit="createPassportClient"
            x-data="{ callbacks: [''] }"
            x-on:notify.window="
                const event = Array.isArray($event.detail) ? $event.detail[0] : $event.detail
                if (event.type == 'success') callbacks = ['']
            "
        >
            <x-input-text
                label="Application Name"
                type="text"
                wire:model="name"
            />

            <div class="relative flex flex-col gap-2">
                <template
                    x-for="(value, i) in callbacks"
                    :key="i"
                >
                    <template x-if="i <= 0">
                        <x-input-text
                            label="Callback URL's"
                            type="url"
                            x-model="callbacks[i]"
                            x-on:change="$wire.callbacks[i] = $event.target.value"
                        >
                            <x-button
                                class="absolute inset-e-0 bottom-0 flex h-fit rounded-l-none rounded-r p-2.5 text-xs/3"
                                x-on:click="callbacks.push('')"
                            >
                                <span class="icon-[tabler--plus] size-4"></span>
                            </x-button>
                        </x-input-text>
                    </template>
                </template>

                <template
                    x-for="(value, i) in callbacks"
                    :key="i"
                >
                    <template x-if="i > 0">
                        <div class="relative">
                            <x-input-text
                                type="url"
                                class="pr-11.5"
                                x-model="callbacks[i]"
                                x-on:change="$wire.callbacks[i] = $event.target.value"
                            />

                            <x-button
                                class="absolute inset-y-0 inset-e-0 flex rounded-l-none rounded-r bg-[#b85450] p-2.5 text-xs/3 hover:bg-[#a8503d]"
                                x-on:click="callbacks.splice(i, 1)"
                            >
                                <span class="icon-[tabler--trash] size-4"></span>
                            </x-button>
                        </div>
                    </template>
                </template>
            </div>

            @if (session('passport-client'))
                <div class="space-y-2 rounded bg-[#fefdfb] p-3 inset-ring inset-ring-[#c8b96e]/30">
                    <span class="flex items-center gap-1 text-sm/4 font-medium text-[#a8503d]">
                        <span class="icon-[mingcute--alert-line] size-4.5"></span>
                        The client secret will not be shown again, so don't lose it!
                    </span>

                    <div class="block space-y-2 text-sm/4">
                        <div class="flex flex-col space-y-1">
                            <span class="font-medium"> Client ID: </span>
                            <span> {{ session('passport-client')['id'] }} </span>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <span class="font-medium"> Client Secret: </span>
                            <span> {{ session('passport-client')['secret'] }} </span>
                        </div>
                    </div>
                </div>
            @endif

            <x-button
                type="submit"
                class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
            >
                Create Client
            </x-button>
        </x-form>
    </x-card>
</x-contents>
