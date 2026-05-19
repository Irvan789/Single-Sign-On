import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.csp.esm"

import {
  OverlayScrollbars,
  PartialOptions as OverlayScrollbarOptions
} from "overlayscrollbars"

import { ofetch } from "ofetch"

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

const initOverlayScrollbars = (
  elements: HTMLCollectionOf<Element>,
  config: OverlayScrollbarOptions
) => {
  Array.from(elements).forEach((element) => {
    window.osInstance = OverlayScrollbars(element as HTMLElement, config)
  })
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

const handleTurnstileResetOrOnNavigation = () => {
  if (turnstileTimeout) clearTimeout(turnstileTimeout)

  if (authenticationRoutes.includes(location.pathname)) {
    turnstileWidgetId ? resetTurnstile() : renderTurnstile()

    return
  }

  if (turnstileWidgetId) {
    removeTurnstile()
    turnstileWidgetId = null

    return
  }
}

document.addEventListener("livewire:initialized", () => {
  window.ofetch = ofetch

  window.Livewire?.on("toastify", async () => {
    handleTurnstileResetOrOnNavigation()
  })
})

document.addEventListener("livewire:navigated", () => {
  const overlayScrollbarElements = document.querySelectorAll(
    "[data-overlayscrollbars-viewport]"
  )

  if (overlayScrollbarElements.length == 0) {
    const elements = document.getElementsByClassName("overlay-scrollbars")
    initOverlayScrollbars(elements, scrollbarOptions)
  }

  handleTurnstileResetOrOnNavigation()
})
