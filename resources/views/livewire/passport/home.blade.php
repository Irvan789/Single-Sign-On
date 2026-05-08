<x-contents>
  <div class="xs:flex-row xs:items-center flex flex-col justify-between gap-4">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        Passport Client
      </div>

      <div class="text-[0.9375rem]/4.5 mt-1">
        Manage your passport client here.
      </div>
    </div>

    <x-anchor-button
      href="{{ route('passport.create.client') }}"
      class="xs:max-w-30 w-full text-xs/3"
      wire:navigate
    >

      Create Client
    </x-anchor-button>
  </div>

  @if (count($clients) > 0)
    <div class="grid h-fit grid-flow-row gap-2">
      @foreach ($clients as $client)
        <div class="rounded-xs inline-flex items-center justify-between gap-2.5 border border-[#c8b96e4d] p-2.5">
          <div class="flex flex-col">
            <div class="text-base/5 font-bold">
              {{ $client->name }}
            </div>

            <span class="text-xs/4 font-medium">
              {{ \Carbon\Carbon::parse($client->created_at)->format('d F Y') }}
            </span>
          </div>

          <div class="xs:grid-cols-2 grid grid-cols-1 gap-1">
            <x-button
              variant="warning"
              class="px-3 py-2 text-xs/3"
              wire:click="deletePassportClientToken('{{ $client->id }}')"
              wire:confirm="Are you sure you want to clear all &quot;{{ $client->name }}&quot; token?"
            >
              Clear Tokens
            </x-button>

            <x-button
              variant="danger"
              class="px-3 py-2 text-xs/3"
              wire:click="deletePassportClient('{{ $client->id }}')"
              wire:confirm="Are you sure you want to delete client &quot;{{ $client->name }}&quot;?"
            >
              Delete
            </x-button>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="rounded-xs flex h-40 items-center justify-center border border-[#c8b96e4d] text-center text-sm/4">
      There is Nothing Here!
    </div>
  @endif

  {{ $clients->links() }}
</x-contents>
