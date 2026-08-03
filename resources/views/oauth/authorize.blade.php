<x-layouts::auth title="Authorize">
    <div class="mt-3 flex flex-col gap-3.5">
        <div class="space-y-0.5">
            <h1 class="h-5 font-serif text-2xl/5 font-medium text-[#4a3f35]">
                Authorization Request
            </h1>

            <p class="text-[0.938rem]/4.75 font-medium text-[#8a7f70]">
                {{ $client->name }} is requesting permission to access your account.
            </p>
        </div>

        <img
            src="{{ $user->avatar }}?size=128&r=g&d=mp"
            class="absolute top-4.5 right-4.5 size-6 rounded-full md:top-6.5 md:right-6.5"
        />

        <div class="flex flex-col gap-3.5">
            @if (count($scopes) > 0)
                <div class="text-[0.938rem]/4.5 text-[#6b5a46]">
                    <div class="mb-0.75">This application will be able to:</div>

                    <ul>
                        @foreach ($scopes as $scope)
                            <li class="-ms-1 flex items-center text-sm/5">
                                <span class="icon-[weui--arrow-filled] size-4.5"></span>
                                {{ $scope->description }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-1">
                <form
                    method="POST"
                    action="{{ route('passport.authorizations.deny') }}"
                    class="contents"
                >
                    @csrf
                    @method('DELETE')

                    <input
                        type="hidden"
                        name="state"
                        value="{{ $request->state }}"
                    />

                    <input
                        type="hidden"
                        name="client_id"
                        value="{{ $client->getKey() }}"
                    />

                    <input
                        type="hidden"
                        name="auth_token"
                        value="{{ $authToken }}"
                    />

                    <x-button
                        type="submit"
                        variant="danger"
                    >
                        Cancel
                    </x-button>
                </form>

                <form
                    method="POST"
                    action="{{ route('passport.authorizations.approve') }}"
                    class="contents"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="state"
                        value="{{ $request->state }}"
                    />

                    <input
                        type="hidden"
                        name="client_id"
                        value="{{ $client->getKey() }}"
                    />

                    <input
                        type="hidden"
                        name="auth_token"
                        value="{{ $authToken }}"
                    />

                    <x-button type="submit"> Authorize </x-button>
                </form>
            </div>
        </div>

        <x-separator />

        <span class="mt-px text-center text-sm/4 font-medium">
            Not {{ $user->username }}?

            <x-form
                method="POST"
                action="{{ route('logout') }}"
                class="contents"
            >
                @csrf

                <input
                    type="hidden"
                    name="authorize_url"
                    value="{{ request()->fullUrl() }}"
                />

                <button
                    type="submit"
                    class="text-sm/4 text-[#8a7f70] hover:underline"
                >
                    Logout
                </button>
            </x-form>
        </span>
    </div>
</x-layouts::auth>
