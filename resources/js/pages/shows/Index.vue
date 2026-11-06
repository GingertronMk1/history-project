<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/shows';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ shows: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Shows">
        <CatalogueList
            :records="shows"
            :record-url="(record) => show.url(record.id)"
            empty-message="No shows have been added yet."
        />
    </CatalogueLayout>
</template>
