<x-contents>
    <x-card
        title="Update OAuth Client"
        description="Update app name and callback urls to continue authenticate with us."
    >
        <x-form
            wire:submit="updatePassportClient"
            x-data="{ callbacks: $wire.callbacks }"
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

            <x-button
                type="submit"
                class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
            >
                Update Client
            </x-button>
        </x-form>
    </x-card>

    <x-card
        title="Delete OAuth Client"
        description="Warning: This client data will be deleted immediately."
        class="flex flex-col justify-between sm:flex-row sm:items-center"
        danger-area
    >
        <x-button
            variant="danger"
            class="h-fit w-full max-w-28 text-xs/3"
            wire:click="deletePassportClient('{{ $client->id }}')"
            wire:confirm="Are you sure to delete this client? This action can't be undone!"
            wire:loading.attr="disabled"
        >
            Delete Client
        </x-button>
    </x-card>
</x-contents>
