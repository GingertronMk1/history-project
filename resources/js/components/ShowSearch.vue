<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { index } from '@/routes/shows';

const props = withDefaults(
    defineProps<{ initialSearch?: string; dark?: boolean }>(),
    {
        initialSearch: '',
        dark: false,
    },
);
const query = ref(props.initialSearch);

function search() {
    router.get(
        index.url(),
        query.value.trim() ? { q: query.value.trim() } : {},
    );
}
</script>

<template>
    <form
        role="search"
        aria-label="Search shows"
        class="flex gap-2"
        @submit.prevent="search"
    >
        <input
            v-model="query"
            type="search"
            name="q"
            aria-label="Search show titles"
            placeholder="Search the shows…"
            maxlength="200"
            class="min-w-0 flex-1 rounded-sm border px-4 py-3 text-sm outline-offset-4 focus-visible:outline-2 focus-visible:outline-amber-400"
            :class="
                dark
                    ? 'border-white/20 bg-white/5 text-white placeholder:text-white/45'
                    : 'border-stone-300 bg-white text-stone-900 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-100'
            "
        />
        <button
            type="submit"
            class="rounded-sm bg-amber-400 px-5 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
        >
            Search
        </button>
    </form>
</template>
