<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { home } from '@/routes';
import { index as companies } from '@/routes/companies';
import { index as periods } from '@/routes/periods';
import { index as playwrights } from '@/routes/playwrights';
import { index as seasons } from '@/routes/seasons';
import { index as shows } from '@/routes/shows';
import { index as venues } from '@/routes/venues';
import { index as years } from '@/routes/years';

const page = usePage();
const navigation = [
    { label: 'Shows', route: shows() },
    { label: 'Years', route: years() },
    { label: 'Playwrights', route: playwrights() },
    { label: 'Venues', route: venues() },
    { label: 'Companies', route: companies() },
    { label: 'Seasons', route: seasons() },
    { label: 'Periods', route: periods() },
];
</script>

<template>
    <header class="border-b border-white/10 bg-archive-header">
        <a
            href="#main"
            class="sr-only z-50 bg-amber-400 p-4 text-archive-background focus:not-sr-only focus:absolute"
            >Skip to content</a
        >
        <div
            class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-6 py-7 lg:px-10"
        >
            <Link
                :href="home()"
                class="flex items-center gap-4 rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                aria-label="New Theatre History Project home"
            >
                <svg
                    viewBox="0 0 64 64"
                    class="size-12 shrink-0"
                    aria-hidden="true"
                >
                    <rect
                        x="0"
                        y="22"
                        width="42"
                        height="42"
                        class="fill-brand-purple"
                    />
                    <rect
                        x="42"
                        y="0"
                        width="22"
                        height="22"
                        class="fill-brand-orange"
                    />
                </svg>
                <span class="flex flex-col gap-1">
                    <span
                        class="text-[10px] font-semibold tracking-[0.19em] uppercase sm:text-xs"
                        >The Nottingham New Theatre</span
                    >
                    <span
                        class="text-2xl font-semibold tracking-tight sm:text-3xl"
                        >History Project</span
                    >
                </span>
            </Link>
            <nav
                aria-label="Main navigation"
                class="flex flex-wrap gap-x-6 gap-y-3 text-sm"
            >
                <Link
                    v-for="item in navigation"
                    :key="item.label"
                    :href="item.route"
                    :aria-current="
                        page.url.split('?')[0].startsWith(item.route.url)
                            ? 'page'
                            : undefined
                    "
                    class="rounded-sm transition hover:text-amber-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                    :class="
                        page.url.split('?')[0].startsWith(item.route.url)
                            ? 'text-amber-300'
                            : 'text-white/65'
                    "
                >
                    {{ item.label }}
                </Link>
            </nav>
        </div>
    </header>
</template>
