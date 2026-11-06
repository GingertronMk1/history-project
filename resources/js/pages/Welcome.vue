<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShowSearch from '@/components/ShowSearch.vue';
import SiteHeader from '@/components/SiteHeader.vue';
import SiteFooter from '@/components/SiteFooter.vue';
import { index as companies } from '@/routes/companies';
import { index as periods } from '@/routes/periods';
import { index as playwrights } from '@/routes/playwrights';
import { index as seasons } from '@/routes/seasons';
import { index as shows, show } from '@/routes/shows';
import { index as venues } from '@/routes/venues';
import { index as years } from '@/routes/years';

interface FeaturedShow {
    id: string;
    title: string;
    year: string | null;
    playwright: string | null;
    venue: string | null;
}

defineProps<{
    counts: {
        shows: number;
        years: number;
        playwrights: number;
        companies: number;
        venues: number;
        seasons: number;
        periods: number;
    };
    featuredShows: FeaturedShow[];
}>();

const archive = [
    {
        label: 'Years',
        key: 'years',
        route: years(),
        description: 'Follow the story, year by year.',
    },
    {
        label: 'Playwrights',
        key: 'playwrights',
        route: playwrights(),
        description: 'The writers behind the words.',
    },
    {
        label: 'Venues',
        key: 'venues',
        route: venues(),
        description: 'The places that became a stage.',
    },
    {
        label: 'Companies',
        key: 'companies',
        route: companies(),
        description: 'The companies that made it happen.',
    },
    {
        label: 'Seasons',
        key: 'seasons',
        route: seasons(),
        description: 'Discover each strand of the programme.',
    },
    {
        label: 'Periods',
        key: 'periods',
        route: periods(),
        description: 'Explore the theatre calendar.',
    },
] as const;
const posterColours = [
    'bg-[#e5b545] text-[#21192c]',
    'bg-[#9b344a] text-[#fff1d3]',
    'bg-[#ded8bc] text-[#284e4a]',
    'bg-[#365560] text-[#ffdf9b]',
    'bg-[#716084] text-[#fff0d4]',
    'bg-[#d67b4b] text-[#2b1c20]',
    'bg-[#a5b3a1] text-[#23362f]',
    'bg-[#312945] text-[#f2c873]',
];
const number = (value: number) => value.toLocaleString('en-GB');
</script>

<template>
    <div
        class="min-h-screen bg-archive-background text-archive-foreground selection:bg-amber-400 selection:text-stone-950"
    >
        <Head title="The Nottingham New Theatre History Project">
            <meta
                name="description"
                content="Explore the Nottingham New Theatre archive: shows, playwrights, venues and the years of theatre that connect them."
            />
        </Head>
        <SiteHeader />

        <main id="main">
            <section
                class="overflow-hidden bg-archive-hero"
                aria-labelledby="archive-heading"
            >
                <div
                    class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-14 lg:grid-cols-[1.12fr_1fr] lg:gap-16 lg:px-10 lg:py-16"
                >
                    <div
                        v-if="featuredShows.length"
                        class="order-2 grid min-w-0 grid-cols-3 gap-3 sm:grid-cols-4 lg:order-1 lg:-rotate-3 lg:gap-4"
                        aria-label="A selection from the show archive"
                    >
                        <Link
                            v-for="(production, index) in featuredShows"
                            :key="production.id"
                            :href="show(production.id)"
                            class="group relative aspect-[3/4] flex-col justify-between overflow-hidden p-3 shadow-lg transition duration-200 hover:z-10 hover:-translate-y-1 hover:rotate-2 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400 sm:p-4"
                            :class="[
                                posterColours[index % posterColours.length],
                                index > 5 ? 'hidden sm:flex' : 'flex',
                            ]"
                            :aria-label="`View ${production.title}${production.year ? ', ' + production.year.replace('_', '/') : ''}`"
                        >
                            <span
                                class="relative z-10 text-[8px] font-semibold tracking-[0.15em] uppercase sm:text-[9px]"
                                ><span class="sm:hidden">New Theatre</span
                                ><span class="hidden sm:inline"
                                    >From the New Theatre archive</span
                                ></span
                            >
                            <span class="relative z-10 flex flex-col gap-2">
                                <span
                                    class="line-clamp-3 text-sm leading-tight font-bold tracking-tight wrap-break-word sm:line-clamp-4 sm:text-lg"
                                    >{{ production.title }}</span
                                >
                                <span
                                    v-if="production.playwright"
                                    class="hidden text-[10px] leading-snug opacity-80 sm:line-clamp-2"
                                    >{{ production.playwright }}</span
                                >
                            </span>
                            <span
                                class="relative z-10 flex justify-between border-t border-current/30 pt-2 text-[10px]"
                            >
                                <span>{{
                                    production.year?.replace('_', '/') ??
                                    'The collection'
                                }}</span>
                                <span
                                    aria-hidden="true"
                                    class="transition group-hover:translate-x-1"
                                    >↗</span
                                >
                            </span>
                            <span
                                aria-hidden="true"
                                class="pointer-events-none absolute -top-5 -right-10 size-32 rounded-full border-[18px] border-current opacity-[0.06]"
                            ></span>
                        </Link>
                    </div>
                    <div
                        v-else
                        class="order-2 flex min-h-64 items-center justify-center border border-dashed border-white/15 p-10 lg:order-1"
                    >
                        <p class="max-w-xs text-center text-lg text-white/50">
                            The next chapter starts here. Shows will appear as
                            the archive grows.
                        </p>
                    </div>
                    <div class="order-1 flex min-w-0 flex-col gap-7 lg:order-2">
                        <p
                            class="flex items-center gap-3 text-xs font-semibold tracking-[0.2em] text-amber-300 uppercase"
                        >
                            <span
                                class="h-px w-8 bg-amber-300"
                                aria-hidden="true"
                            ></span
                            >An archive, still growing
                        </p>
                        <h1
                            id="archive-heading"
                            class="max-w-lg text-5xl leading-[1.05] font-semibold tracking-tight sm:text-6xl"
                        >
                            Every show<br />has a story.
                        </h1>
                        <p
                            class="max-w-md text-lg leading-relaxed text-white/65"
                        >
                            Discover the productions, places and playwrights
                            that shaped the New Theatre. A shared history,
                            brought together one record at a time.
                        </p>
                        <dl
                            class="grid grid-cols-3 gap-4 border-y border-white/15 py-6"
                        >
                            <div>
                                <dt class="text-xs tracking-wide text-white/55">
                                    Shows
                                </dt>
                                <dd
                                    class="mt-2 text-4xl font-semibold tracking-tight text-amber-300 tabular-nums"
                                >
                                    {{ number(counts.shows) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs tracking-wide text-white/55">
                                    Years
                                </dt>
                                <dd
                                    class="mt-2 text-4xl font-semibold tracking-tight tabular-nums"
                                >
                                    {{ number(counts.years) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs tracking-wide text-white/55">
                                    Playwrights
                                </dt>
                                <dd
                                    class="mt-2 text-4xl font-semibold tracking-tight tabular-nums"
                                >
                                    {{ number(counts.playwrights) }}
                                </dd>
                            </div>
                        </dl>
                        <Link
                            :href="years()"
                            class="flex items-center justify-between rounded-sm bg-amber-400 px-5 py-4 font-semibold text-archive-background transition hover:bg-amber-300 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400"
                            >Explore the archives
                            <span aria-hidden="true">→</span></Link
                        >
                        <ShowSearch />
                    </div>
                </div>
            </section>

            <section
                id="browse"
                class="mx-auto max-w-7xl px-6 py-16 lg:px-10"
                aria-labelledby="browse-heading"
            >
                <div
                    class="mb-8 flex flex-wrap items-end justify-between gap-4"
                >
                    <div class="flex flex-col gap-3">
                        <p
                            class="text-xs tracking-[0.2em] text-amber-300 uppercase"
                        >
                            Make a discovery
                        </p>
                        <h2
                            id="browse-heading"
                            class="text-3xl font-semibold tracking-tight sm:text-4xl"
                        >
                            Find your way into the archive.
                        </h2>
                    </div>
                    <Link
                        :href="shows()"
                        class="text-sm text-amber-300 underline-offset-4 hover:underline"
                        >Browse all {{ number(counts.shows) }} shows
                        <span aria-hidden="true">↗</span></Link
                    >
                </div>
                <div
                    class="grid gap-px overflow-hidden border border-white/10 bg-white/10 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="category in archive"
                        :key="category.key"
                        :href="category.route"
                        class="group flex flex-col gap-4 bg-archive-surface p-6 transition hover:bg-archive-surface-hover focus-visible:z-10 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-amber-400"
                    >
                        <span class="flex items-center justify-between"
                            ><span
                                class="text-2xl font-semibold tracking-tight"
                                >{{ category.label }}</span
                            ><span
                                aria-hidden="true"
                                class="text-amber-300 transition group-hover:translate-x-1"
                                >→</span
                            ></span
                        >
                        <span class="text-sm leading-relaxed text-white/55">{{
                            category.description
                        }}</span>
                        <span class="text-xs tracking-wide text-amber-200/80"
                            >{{ number(counts[category.key]) }}
                            {{ category.label.toLowerCase() }} in the
                            collection</span
                        >
                    </Link>
                </div>
            </section>

            <div class="grid md:grid-cols-2">
                <section
                    class="bg-archive-teal px-6 py-14 lg:px-10"
                    aria-labelledby="alumni-heading"
                >
                    <div
                        class="ml-auto flex max-w-xl flex-col items-start gap-6 md:pr-8"
                    >
                        <p
                            class="text-xs tracking-[0.2em] text-teal-200 uppercase"
                        >
                            Then, now &amp; next
                        </p>
                        <h2
                            id="alumni-heading"
                            class="text-4xl font-semibold tracking-tight"
                        >
                            Alumni Network
                        </h2>
                        <p
                            class="max-w-md text-lg leading-relaxed text-white/75"
                        >
                            The curtain falls. The connections stay. Reconnect
                            with the people who shared your time at the theatre
                            and see what is happening today.
                        </p>
                        <a
                            href="https://newtheatre.org.uk/alumni/"
                            class="border-b border-white/60 pb-1 font-semibold transition hover:border-amber-300 hover:text-amber-300"
                            >Discover the alumni network
                            <span aria-hidden="true">↗</span></a
                        >
                    </div>
                </section>
                <section
                    class="bg-archive-burgundy px-6 py-14 lg:px-10"
                    aria-labelledby="story-heading"
                >
                    <div
                        class="flex max-w-xl flex-col items-start gap-6 md:pl-8"
                    >
                        <p
                            class="text-xs tracking-[0.2em] text-rose-200 uppercase"
                        >
                            A history we share
                        </p>
                        <h2
                            id="story-heading"
                            class="text-4xl font-semibold tracking-tight"
                        >
                            Tell Your Story
                        </h2>
                        <p
                            class="max-w-md text-lg leading-relaxed text-white/75"
                        >
                            Remember a production? Recognise a title? Find the
                            shows you were part of, then help fill in the gaps
                            with your memories.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                :href="shows()"
                                class="rounded-sm bg-black/25 px-5 py-3 font-semibold transition hover:bg-black/40"
                                >Find your shows
                                <span aria-hidden="true">→</span></Link
                            >
                            <a
                                href="mailto:history@newtheatre.org.uk"
                                class="rounded-sm border border-white/30 px-5 py-3 font-semibold transition hover:bg-white/10"
                                >Share a memory
                                <span aria-hidden="true">↗</span></a
                            >
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <SiteFooter />
    </div>
</template>
