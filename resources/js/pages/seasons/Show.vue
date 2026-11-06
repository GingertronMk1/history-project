<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { index } from '@/routes/seasons';
import { show } from '@/routes/shows';
import type { CatalogueRecord, PaginatedRecords } from '@/types/catalogue';

defineProps<{ season: CatalogueRecord; shows: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout :title="season.name ?? 'Season'">
        <Link :href="index()" class="text-sm underline">All seasons</Link>
        <p class="whitespace-pre-line text-stone-600 dark:text-stone-400">
            {{ season.description || 'No description yet.' }}
        </p>
        <section class="flex flex-col gap-4">
            <h2 class="text-xl font-semibold">Shows</h2>
            <CatalogueList
                :records="shows"
                :record-url="(record) => show.url(record.id)"
                empty-message="No shows are linked to this season yet."
            />
        </section>
    </CatalogueLayout>
</template>
