<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/playwrights';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ playwrights: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Playwrights">
        <CatalogueList
            :records="playwrights"
            :record-url="(record) => show.url(record.id)"
            empty-message="No playwrights have been added yet."
        />
    </CatalogueLayout>
</template>
