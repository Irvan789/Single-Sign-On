<x-layouts::error title="{{ $exception->getMessage() ?: 'Forbidden' }}">
  <div class="pr-2">403</div>
  <div class="pl-2">
    {{ $exception->getMessage() ?: 'Forbidden' }}
  </div>
</x-layouts::error>
