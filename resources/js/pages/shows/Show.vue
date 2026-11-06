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
import { show as yearShow } from '@/routes/years';
import type { ShowRecord } from '@/types/catalogue';

const props = defineProps<{ show: ShowRecord }>();
const relationships = computed(() => [
    { label: 'Year', record: props.show.year, route: yearShow },
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
        <Link
            :href="index()"
            class="self-start text-sm text-amber-300 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
            >All shows</Link
        >
        <p
            class="max-w-4xl text-base leading-relaxed whitespace-pre-line text-white/65"
        >
            {{ show.description || 'No description yet.' }}
        </p>
        <dl
            class="divide-y divide-white/10 border border-white/10 bg-archive-surface"
        >
            <div
                v-for="relationship in relationships"
                :key="relationship.label"
                class="flex flex-col gap-2 px-5 py-5 sm:flex-row sm:gap-8 sm:px-7"
            >
                <dt class="shrink-0 text-sm text-white/45 sm:w-36">
                    {{ relationship.label }}
                </dt>
                <dd class="min-w-0 text-sm leading-relaxed wrap-anywhere">
                    <Link
                        v-if="relationship.record"
                        :href="relationship.route(relationship.record.id)"
                        class="text-amber-300 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                        >{{ relationship.record.name }}</Link
                    >
                    <span v-else>Not recorded</span>
                </dd>
            </div>
            <div
                class="flex flex-col gap-2 px-5 py-5 sm:flex-row sm:gap-8 sm:px-7"
            >
                <dt class="shrink-0 text-sm text-white/45 sm:w-36">
                    Performance dates
                </dt>
                <dd class="min-w-0 text-sm leading-relaxed wrap-anywhere">
                    {{ show.date_start || 'Not recorded'
                    }}<template v-if="show.date_end">
                        – {{ show.date_end }}</template
                    >
                </dd>
            </div>
        </dl>
    </CatalogueLayout>
</template>
