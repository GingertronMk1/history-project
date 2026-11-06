<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/seasons';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ seasons: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Seasons">
        <CatalogueList
            :records="seasons"
            :record-url="(record) => show.url(record.id)"
            empty-message="No seasons have been added yet."
        />
    </CatalogueLayout>
</template>
