<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as companies } from '@/routes/companies';
import { index as periods } from '@/routes/periods';
import { index as playwrights } from '@/routes/playwrights';
import { index as seasons } from '@/routes/seasons';
import { index as shows } from '@/routes/shows';
import { index as venues } from '@/routes/venues';
import { index as years } from '@/routes/years';

defineProps<{ title: string }>();

const navigation = [
    { label: 'Shows', route: shows() },
    { label: 'Years', route: years() },
    { label: 'Companies', route: companies() },
    { label: 'Seasons', route: seasons() },
    { label: 'Periods', route: periods() },
    { label: 'Playwrights', route: playwrights() },
    { label: 'Venues', route: venues() },
];
</script>

<template>
    <div
        class="min-h-screen bg-stone-50 text-stone-900 dark:bg-stone-950 dark:text-stone-100"
    >
        <Head :title="title" />
        <header class="border-b border-stone-200 dark:border-stone-800">
            <div class="mx-auto flex max-w-6xl flex-col gap-5 px-6 py-6">
                <Link :href="shows()" class="text-xl font-semibold"
                    >The History Project</Link
                >
                <nav aria-label="Archive" class="flex flex-wrap gap-5 text-sm">
                    <Link
                        v-for="item in navigation"
                        :key="item.label"
                        :href="item.route"
                        class="hover:underline focus-visible:underline"
                        >{{ item.label }}</Link
                    >
                </nav>
            </div>
        </header>
        <main class="mx-auto flex max-w-6xl flex-col gap-8 px-6 py-10">
            <h1 class="text-3xl font-semibold tracking-tight">{{ title }}</h1>
            <slot />
        </main>
    </div>
</template>
