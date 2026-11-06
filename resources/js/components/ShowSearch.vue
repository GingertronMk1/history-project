<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { index } from '@/routes/shows';

const props = withDefaults(defineProps<{ initialSearch?: string }>(), {
    initialSearch: '',
});
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
            class="min-w-0 flex-1 rounded-sm border border-white/20 bg-white/5 px-4 py-3 text-sm text-archive-foreground outline-offset-4 placeholder:text-white/45 focus-visible:outline-2 focus-visible:outline-amber-400"
        />
        <button
            type="submit"
            class="rounded-sm bg-amber-400 px-5 py-3 text-sm font-semibold text-archive-background transition hover:bg-amber-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
        >
            Search
        </button>
    </form>
</template>
