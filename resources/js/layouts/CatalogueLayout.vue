<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import SiteFooter from '@/components/SiteFooter.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import { home } from '@/routes';

defineProps<{ title: string }>();
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-archive-background text-archive-foreground selection:bg-amber-400 selection:text-archive-background"
    >
        <Head :title="title" />
        <SiteHeader />
        <main
            id="main"
            class="mx-auto flex w-full max-w-7xl min-w-0 flex-1 flex-col gap-8 px-6 py-10 lg:px-10 lg:py-14"
        >
            <header class="flex flex-col gap-5 border-b border-white/10 pb-8">
                <nav
                    aria-label="Breadcrumb"
                    class="flex flex-wrap items-center gap-3 text-sm text-white/45"
                >
                    <Link
                        :href="home()"
                        class="rounded-sm hover:text-amber-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                        >Home</Link
                    >
                    <span aria-hidden="true">/</span>
                    <span aria-current="page" class="text-white/65">{{
                        title
                    }}</span>
                </nav>
                <p
                    class="flex items-center gap-3 text-xs font-semibold tracking-[0.2em] text-amber-300 uppercase"
                >
                    <span
                        class="h-px w-8 bg-amber-300"
                        aria-hidden="true"
                    ></span
                    >The New Theatre archive
                </p>
                <h1
                    class="text-4xl leading-tight font-semibold tracking-tight wrap-anywhere sm:text-5xl"
                >
                    {{ title }}
                </h1>
            </header>
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>
