<x-layouts::auth title="Authorize">
  <div class="contents">
    <x-auth-header title="Authorization Request">
      {{ $client->name }} is requesting permission to access your account.
    </x-auth-header>

    <img
      src="{{ $user->avatar }}?size=128&r=g&d=mp"
      class="size-5.5 xs:right-7 xs:top-7 absolute right-4 top-4 rounded-full"
    />

    <div class="mt-4 flex flex-col gap-3.5">
      @if (count($scopes) > 0)
        <div class="text-smd">
          <div class="mb-0.5">
            This application will be able to:
          </div>

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
          >

          <input
            type="hidden"
            name="client_id"
            value="{{ $client->getKey() }}"
          >

          <input
            type="hidden"
            name="auth_token"
            value="{{ $authToken }}"
          >

          <button
            type="submit"
            class="rounded-xs w-full cursor-pointer bg-[#b85450e6] px-3 py-2.5 text-sm/4 text-neutral-100 transition-colors duration-300 hover:bg-[#b85450]"
          >
            Cancel
          </button>
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
          >

          <input
            type="hidden"
            name="client_id"
            value="{{ $client->getKey() }}"
          >

          <input
            type="hidden"
            name="auth_token"
            value="{{ $authToken }}"
          >

          <button
            type="submit"
            class="rounded-xs w-full cursor-pointer bg-[#3d3530e6] px-3 py-2.5 text-sm/4 text-neutral-100 transition-colors duration-300 hover:bg-[#3d3530]"
          >
            Authorize
          </button>
        </form>
      </div>
    </div>

    <div class="mt-4 flex items-center gap-2.5">
      <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
      <div class="h-1.25 w-1.25 rotate-45 bg-[#c8b96e]"></div>
      <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
    </div>

    <div
      class="text-smd/4.5 mt-3.5 inline-flex w-full items-center justify-center gap-1 font-medium text-[#3d3530]"
    >
      Not {{ $user->username }}?

      <form
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
          class="cursor-pointer text-[#8b7355] hover:underline"
        >
          Logout
        </button>
      </form>
    </div>
  </div>
</x-layouts::auth>
