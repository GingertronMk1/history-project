<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/periods';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ periods: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Periods">
        <CatalogueList
            :records="periods"
            :record-url="(record) => show.url(record.id)"
            empty-message="No periods have been added yet."
        />
    </CatalogueLayout>
</template>
