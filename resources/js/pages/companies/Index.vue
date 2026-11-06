<script setup lang="ts">
import CatalogueList from '@/components/CatalogueList.vue';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show } from '@/routes/companies';
import type { PaginatedRecords } from '@/types/catalogue';

defineProps<{ companies: PaginatedRecords }>();
</script>

<template>
    <CatalogueLayout title="Companies">
        <CatalogueList
            :records="companies"
            :record-url="(record) => show.url(record.id)"
            empty-message="No companies have been added yet."
        />
    </CatalogueLayout>
</template>
