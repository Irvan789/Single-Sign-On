<x-contents>
    <x-card
        title="Manage Users"
        description="Update their account information and details here."
    >
        <x-input-search
            placeholder="Search User..."
            wire:model.live.debounce.300ms="search"
        />

        @if (count($users) > 0)
            <table class="w-full table-auto">
                <thead>
                    <tr class="border-b border-[#c9b896]/50">
                        <th
                            class="pb-3.5 text-start text-xs/3.5 font-semibold text-[#8a7f70] uppercase"
                        >
                            User Account
                        </th>
                        <th
                            class="hidden pb-3.5 text-center text-xs/3.5 font-semibold text-[#8a7f70] uppercase sm:table-cell"
                        >
                            Role
                        </th>
                        <th class="pb-3.5"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-[#c9b896]/30">
                            <td class="py-3.5">
                                <div
                                    class="flex w-full max-w-80 flex-row items-center gap-2 overflow-hidden"
                                >
                                    <img
                                        src="{{ $user->avatar }}?size=128&r=g&d=mp"
                                        class="size-10 rounded-full"
                                    />

                                    <div class="flex max-w-0 flex-col text-nowrap">
                                        <span class="text-base/4.5 font-medium">
                                            {{ $user->name }}
                                        </span>

                                        <span class="text-xs/4 text-[#8a7f70]">
                                            {{ $user->email }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden p-3.5 sm:table-cell">
                                <span
                                    class="mx-auto flex w-fit rounded-2xl bg-[#f4eee2] px-2.5 py-1 text-xs/3 text-[#6b5a46] capitalize"
                                >
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-3.5">
                                <x-anchor-button
                                    href="{{ route('users.update.accounts', ['id'=>$user->id]) }}"
                                    class="ml-auto block px-2.5 py-1.5 text-xs/3.5"
                                    wire:navigate
                                >
                                    View User
                                </x-anchor-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="flex h-40 items-center justify-center text-center text-sm/4">
                There is nothing here!
            </div>
        @endif

        {{ $users->links() }}
    </x-card>
</x-contents>
