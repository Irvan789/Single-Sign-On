import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.csp.esm"

import {
  OverlayScrollbars,
  PartialOptions as OverlayScrollbarOptions
} from "overlayscrollbars"

import { ofetch } from "ofetch"
import * as zod from "zod"

// @ts-ignore
import "overlayscrollbars/overlayscrollbars.css"

declare global {
  interface Window {
    Livewire?: typeof Livewire
    turnstile: {
      remove: (e: Element | string) => void
      render: (e: Element | string) => string
      reset: (e: Element | string) => void
    }
    ofetch: typeof ofetch
    osInstance: OverlayScrollbars
    zod: typeof zod
  }
}

const scrollbarOptions: OverlayScrollbarOptions = {
  scrollbars: {
    autoHide: "scroll"
  }
}

const authenticationRoutes = ["/login", "/register", "/forgot-password"]

let turnstileWidgetId: string | null = null
let turnstileTimeout: ReturnType<typeof setTimeout> | null = null

document.addEventListener("livewire:initialized", () => {
  window.ofetch = ofetch
  window.zod = zod
})

document.addEventListener("livewire:navigated", () => {
  const overlayScrollbarElements = document.querySelectorAll(
    "[data-overlayscrollbars-viewport]"
  )

  if (overlayScrollbarElements.length == 0) {
    const elements = document.getElementsByClassName("overlay-scrollbars")
    initOverlayScrollbars(elements, scrollbarOptions)
  }

  if (turnstileTimeout) clearTimeout(turnstileTimeout)

  if (authenticationRoutes.includes(location.pathname)) {
    !turnstileWidgetId ? renderTurnstile() : resetTurnstile()
  } else {
    if (turnstileWidgetId) {
      removeTurnstile()
      turnstileWidgetId = null
    }
  }
})

if (window.Livewire) {
  window.Livewire.on("toastify", async () => {
    if (turnstileTimeout) clearTimeout(turnstileTimeout)

    if (authenticationRoutes.includes(location.pathname)) {
      resetTurnstile()
    }
  })
}

const initOverlayScrollbars = (
  elements: HTMLCollectionOf<Element>,
  config: OverlayScrollbarOptions
) => {
  for (let i in elements) {
    if (!isNaN(Number(i))) {
      const element = elements[i] as HTMLElement
      window.osInstance = OverlayScrollbars(element, config)
    }
  }
}

const renderTurnstile = () => {
  turnstileTimeout = setTimeout(() => {
    const turnstile = document.getElementsByClassName("turnstile")
    turnstileWidgetId = window.turnstile.render(
      `#${turnstile[0].getAttribute("id")}`
    )
  }, 1000)
}

const removeTurnstile = () => {
  window.turnstile.remove(turnstileWidgetId!)
}

const resetTurnstile = () => {
  removeTurnstile()
  renderTurnstile()
}
