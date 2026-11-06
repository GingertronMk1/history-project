<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import CatalogueLayout from '@/layouts/CatalogueLayout.vue';
import { show as companyShow } from '@/routes/companies';
import { show as periodShow } from '@/routes/periods';
import { show as playwrightShow } from '@/routes/playwrights';
import { show as seasonShow } from '@/routes/seasons';
import { index } from '@/routes/shows';
import { show as venueShow } from '@/routes/venues';
import type { ShowRecord } from '@/types/catalogue';

const props = defineProps<{ show: ShowRecord }>();
const relationships = computed(() => [
    { label: 'Company', record: props.show.company, route: companyShow },
    { label: 'Season', record: props.show.season, route: seasonShow },
    { label: 'Period', record: props.show.period, route: periodShow },
    {
        label: 'Playwright',
        record: props.show.playwright,
        route: playwrightShow,
    },
    { label: 'Venue', record: props.show.venue, route: venueShow },
]);
</script>

<template>
    <CatalogueLayout :title="show.title ?? 'Show'">
        <Link :href="index()" class="text-sm underline">All shows</Link>
        <p class="whitespace-pre-line text-stone-600 dark:text-stone-400">
            {{ show.description || 'No description yet.' }}
        </p>
        <dl class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="relationship in relationships"
                :key="relationship.label"
                class="flex flex-col gap-1"
            >
                <dt class="text-sm text-stone-500 dark:text-stone-400">
                    {{ relationship.label }}
                </dt>
                <dd>
                    <Link
                        v-if="relationship.record"
                        :href="relationship.route(relationship.record.id)"
                        class="underline"
                        >{{ relationship.record.name }}</Link
                    >
                    <span v-else>Not recorded</span>
                </dd>
            </div>
            <div class="flex flex-col gap-1">
                <dt class="text-sm text-stone-500 dark:text-stone-400">
                    Performance dates
                </dt>
                <dd>
                    {{ show.date_start || 'Not recorded'
                    }}<template v-if="show.date_end">
                        – {{ show.date_end }}</template
                    >
                </dd>
            </div>
        </dl>
    </CatalogueLayout>
</template>
