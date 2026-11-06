<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/years';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ years: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Years">
        <CatalogueList
            :records="years"
            :record-url="(record) => show.url(record.id)"
            empty-message="No years have been added yet."
        />
    </CatalogueLayout>
</template>
