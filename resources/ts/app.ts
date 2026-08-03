import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.csp.esm"

import { decode } from "decode-formdata"
import { ofetch } from "ofetch"

import { OverlayScrollbars, PartialOptions as OverlayScrollbarOptions } from "overlayscrollbars"

declare global {
    interface Window {
        Livewire?: typeof Livewire
        form2Json: typeof decode
        ofetch: typeof ofetch
        overlayScrollbars: OverlayScrollbars
        turnstile: {
            remove: (e: Element | string) => void
            render: (e: Element | string) => string
            reset: (e: Element | string) => void
        }
    }
}

const scrollbarOptions: OverlayScrollbarOptions = {
    scrollbars: {
        visibility: "hidden"
    }
}

const routeWithTurnstile = ["/login", "/register", "/forgot-password"]

let turnstileWidgetId: string | null = null
let turnstileTimeout: ReturnType<typeof setTimeout> | null = null

function initOverlayScrollbars(
    elements: HTMLCollectionOf<Element>,
    config: OverlayScrollbarOptions
): void {
    Array.from(elements).forEach((element) => {
        window.overlayScrollbars = OverlayScrollbars(element as HTMLElement, config)
    })
}

function renderTurnstile(): void {
    const turnstile = document.getElementsByClassName("turnstile")
    turnstileTimeout = setTimeout(() => {
        turnstileWidgetId = window.turnstile.render(`#${turnstile[0].getAttribute("id")}`)
    }, 1000)
}

function removeTurnstile(): void {
    if (turnstileWidgetId) {
        window.turnstile.remove(turnstileWidgetId)
        turnstileWidgetId = null
    }
}

function resetTurnstile(): void {
    removeTurnstile()
    renderTurnstile()
}

function livewireTurnstileEventHandler(): void {
    if (turnstileTimeout) clearTimeout(turnstileTimeout)

    if (routeWithTurnstile.includes(location.pathname)) {
        turnstileWidgetId ? resetTurnstile() : renderTurnstile()

        return
    }

    if (turnstileWidgetId) {
        removeTurnstile()

        return
    }
}

document.addEventListener("livewire:initialized", () => {
    window.Livewire?.on("notify", livewireTurnstileEventHandler)

    window.ofetch = ofetch
    window.form2Json = decode
})

document.addEventListener("livewire:navigated", () => {
    const overlayScrollbarElements = document.querySelectorAll("[data-overlayscrollbars-viewport]")

    if (overlayScrollbarElements.length == 0) {
        const elements = document.getElementsByClassName("overlay-scrollbars")
        initOverlayScrollbars(elements, scrollbarOptions)
    }

    livewireTurnstileEventHandler()
})
