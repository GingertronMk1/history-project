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
    <div class="flex min-w-0 flex-col gap-6">
        <p
            v-if="records.data.length === 0"
            class="border border-dashed border-white/15 bg-archive-surface p-8 text-white/60"
        >
            {{ emptyMessage }}
        </p>
        <ul v-else class="divide-y divide-white/10 border border-white/10">
            <li v-for="record in records.data" :key="record.id">
                <Link
                    :href="recordUrl(record)"
                    class="group flex min-w-0 items-start justify-between gap-5 bg-archive-surface p-5 transition hover:bg-archive-surface-hover focus-visible:relative focus-visible:z-10 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-amber-400 sm:gap-8 sm:px-7 sm:py-6"
                >
                    <span class="flex min-w-0 flex-1 flex-col gap-3">
                        <span
                            class="text-xl leading-snug font-semibold tracking-tight wrap-anywhere transition group-hover:text-amber-300 sm:text-2xl"
                            >{{ record.title ?? record.name }}</span
                        >
                        <span
                            v-if="record.description"
                            class="line-clamp-2 max-w-4xl text-sm leading-relaxed text-white/60"
                            >{{ record.description }}</span
                        >
                        <span
                            v-if="record.shows_count !== undefined"
                            class="text-xs tracking-wide text-amber-200/80"
                            >{{ record.shows_count.toLocaleString('en-GB') }}
                            {{
                                record.shows_count === 1 ? 'show' : 'shows'
                            }}</span
                        >
                    </span>
                    <span
                        aria-hidden="true"
                        class="shrink-0 pt-1 text-xl text-amber-300 transition group-hover:translate-x-1"
                        >→</span
                    >
                </Link>
            </li>
        </ul>
        <nav
            v-if="records.last_page > 1"
            aria-label="Pagination"
            class="flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm"
        >
            <span class="text-white/55"
                >Page {{ records.current_page }} of {{ records.last_page }}
                <span class="mx-1 text-white/25" aria-hidden="true">·</span>
                {{ records.total.toLocaleString('en-GB') }} entries</span
            >
            <span class="flex items-center gap-3">
                <Link
                    v-if="records.prev_page_url"
                    :href="records.prev_page_url"
                    rel="prev"
                    class="rounded-sm border border-white/20 px-4 py-2.5 text-amber-300 transition hover:border-amber-300 hover:bg-archive-surface focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                    >Previous</Link
                >
                <Link
                    v-if="records.next_page_url"
                    :href="records.next_page_url"
                    rel="next"
                    class="rounded-sm border border-white/20 px-4 py-2.5 text-amber-300 transition hover:border-amber-300 hover:bg-archive-surface focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                    >Next <span aria-hidden="true">→</span></Link
                >
            </span>
        </nav>
    </div>
</template>
