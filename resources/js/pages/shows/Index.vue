<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ShowSearch from '@/components/ShowSearch.vue';
import { index } from '@/routes/shows';
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/shows';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ shows: PaginatedRecords; search: string }>();
</script>

<template>
    <CatalogueLayout title="Shows">
        <ShowSearch :key="search" :initial-search="search" />
        <p v-if="search" class="text-sm text-stone-600 dark:text-stone-400">
            {{ shows.total }} {{ shows.total === 1 ? 'result' : 'results' }} for
            “{{ search }}”.
            <Link :href="index()" class="underline">Clear search</Link>
        </p>
        <CatalogueList
            :records="shows"
            :record-url="(record) => show.url(record.id)"
            :empty-message="
                search
                    ? 'No shows match your search. Try another title.'
                    : 'No shows have been added yet.'
            "
        />
    </CatalogueLayout>
</template>
