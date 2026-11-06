<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/venues';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ venues: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Venues">
        <CatalogueList
            :records="venues"
            :record-url="(record) => show.url(record.id)"
            empty-message="No venues have been added yet."
        />
    </CatalogueLayout>
</template>
