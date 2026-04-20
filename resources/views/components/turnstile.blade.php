@php
  $model = $attributes->has('wire:model') ? $attributes->get('wire:model') : null;
@endphp

@if ($model)
  <div
    data-sitekey="{{ config('services.turnstile.key') }}"
    data-theme="light"
    data-size="flexible"
    data-callback="turnstileOnSuccess"
    data-response-field-name="captcha"
    {{ $attributes->twMerge('turnstile h-16.25') }}
  >
  </div>

  @script
    <script lang="js">
      window.turnstileOnSuccess = async (token) => {
        const button = document.querySelector("button[type='submit']")
        button?.removeAttribute("disabled")

        await $wire.$set("{{ $model }}", token, false)
      }
    </script>
  @endscript
@endif
