<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { CatalogueRecord, PaginatedRecords } from '@/types/catalogue';

defineProps<{
    records: PaginatedRecords;
    recordUrl: (record: CatalogueRecord) => string;
    emptyMessage: string;
}>();
</script>

<template>
    <div class="flex flex-col gap-6">
        <p
            v-if="records.data.length === 0"
            class="text-stone-500 dark:text-stone-400"
        >
            {{ emptyMessage }}
        </p>
        <ul v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li
                v-for="record in records.data"
                :key="record.id"
                class="rounded-lg border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900"
            >
                <Link
                    :href="recordUrl(record)"
                    class="font-semibold underline-offset-4 hover:underline"
                    >{{ record.title ?? record.name }}</Link
                >
                <p
                    v-if="record.description"
                    class="mt-2 line-clamp-3 text-sm text-stone-600 dark:text-stone-400"
                >
                    {{ record.description }}
                </p>
                <p
                    v-if="record.shows_count !== undefined"
                    class="mt-3 text-sm text-stone-500 dark:text-stone-400"
                >
                    {{ record.shows_count }}
                    {{ record.shows_count === 1 ? 'show' : 'shows' }}
                </p>
            </li>
        </ul>
        <nav
            v-if="records.last_page > 1"
            aria-label="Pagination"
            class="flex items-center gap-5 text-sm"
        >
            <Link
                v-if="records.prev_page_url"
                :href="records.prev_page_url"
                class="underline"
                >Previous</Link
            >
            <span
                >Page {{ records.current_page }} of
                {{ records.last_page }}</span
            >
            <Link
                v-if="records.next_page_url"
                :href="records.next_page_url"
                class="underline"
                >Next</Link
            >
        </nav>
    </div>
</template>
