<x-contents>
    <x-card
        title="Manage OAuth Clients"
        description="Manage your oauth client application here."
        class="sm:grid sm:grid-cols-[minmax(0,1fr)] sm:items-center"
    >
        <x-anchor-button
            href="{{ route('oauth.create.clients') }}"
            class="h-fit w-fit py-2 text-xs/3 sm:w-full sm:max-w-30"
            wire:navigate
        >
            New Application
        </x-anchor-button>

        @if (count($clients) > 0)
            <table class="col-span-2 w-full table-auto">
                <thead>
                    <tr class="border-b border-[#c9b896]/50">
                        <th
                            class="pb-3.5 text-start text-xs/3.5 font-semibold text-[#8a7f70] uppercase"
                        >
                            Application
                        </th>
                        <th class="pb-3.5"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clients as $client)
                        <tr class="border-b border-[#c9b896]/30">
                            <td class="py-3.5">
                                <div
                                    class="flex w-full max-w-80 flex-row items-center gap-2 overflow-hidden"
                                >
                                    <div class="rounded bg-[#544636] p-2">
                                        <img
                                            src="{{ asset('assets/images/app.webp') }}"
                                            class="size-4"
                                        />
                                    </div>

                                    <div class="flex max-w-0 flex-col text-nowrap">
                                        <span class="text-sm/4 font-medium">
                                            {{ $client->name }}
                                        </span>

                                        <span class="text-xs/3.5 text-[#8a7f70]">
                                            {{ \Carbon\Carbon::parse($client->created_at)->format('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <x-anchor-button
                                    href="{{ route('oauth.update.clients', ['id' => $client->id]) }}"
                                    class="ml-auto block px-2.5 py-1.5 text-xs/3.5"
                                    wire:navigate
                                >
                                    View App
                                </x-anchor-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{ $clients->links() }}
    </x-card>
</x-contents>
