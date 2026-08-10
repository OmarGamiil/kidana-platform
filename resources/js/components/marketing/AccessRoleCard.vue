<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpLeft } from '@lucide/vue';
import type { Component } from 'vue';
import type { RouteDefinition } from '@/wayfinder';

const props = withDefaults(
    defineProps<{
        icon: Component;
        title: string;
        description: string;
        actionLabel: string;
        badge?: string;
        href?: string | RouteDefinition<'get'>;
        disabled?: boolean;
        variant?: 'primary' | 'secondary' | 'neutral';
    }>(),
    {
        badge: undefined,
        href: undefined,
        disabled: false,
        variant: 'neutral',
    },
);

const variantClasses = {
    primary: 'border-marketing-teal/55 bg-marketing-teal/8',
    secondary: 'border-marketing-gold/40 bg-marketing-gold/10',
    neutral: 'border-marketing-border bg-marketing-surface',
};
</script>

<template>
    <article
        :class="[
            'flex min-h-52 flex-col rounded-2xl border p-5',
            variantClasses[props.variant],
            props.disabled && 'cursor-not-allowed',
        ]"
    >
        <div class="flex items-start justify-between gap-4">
            <span
                :class="[
                    'flex size-11 items-center justify-center rounded-xl',
                    props.variant === 'primary'
                        ? 'bg-marketing-teal text-white'
                        : 'bg-white text-marketing-teal-dark',
                ]"
            >
                <component :is="props.icon" class="size-5" aria-hidden="true" />
            </span>
            <span
                v-if="props.badge"
                class="rounded-full border border-marketing-border/80 bg-white/70 px-3 py-1 text-[11px] font-semibold text-marketing-teal-dark"
                >{{ props.badge }}</span
            >
        </div>
        <h2 class="mt-5 text-lg font-bold text-marketing-teal-dark">
            {{ props.title }}
        </h2>
        <p class="mt-2 text-sm leading-6 text-marketing-muted">
            {{ props.description }}
        </p>
        <Link
            v-if="!props.disabled && props.href"
            :href="props.href"
            class="mt-auto flex items-center justify-between gap-3 pt-5 text-sm font-bold text-marketing-teal focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-teal"
        >
            {{ props.actionLabel }}
            <span
                class="flex size-8 items-center justify-center rounded-full bg-white text-marketing-teal shadow-sm"
                ><ArrowUpLeft
                    class="size-4 rtl:-scale-x-100"
                    aria-hidden="true"
            /></span>
        </Link>
        <button
            v-else
            type="button"
            disabled
            class="mt-auto flex cursor-not-allowed items-center justify-between gap-3 pt-5 text-sm font-bold text-marketing-muted"
            :aria-label="props.actionLabel"
        >
            {{ props.actionLabel }}
            <span
                class="flex size-8 items-center justify-center rounded-full border border-marketing-border bg-white/60"
                ><ArrowUpLeft
                    class="size-4 rtl:-scale-x-100"
                    aria-hidden="true"
            /></span>
        </button>
    </article>
</template>
