<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Component } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { RouteDefinition } from '@/wayfinder';

const props = defineProps<{
    icon: Component;
    title: string;
    description: string;
    actionLabel: string;
    badge?: string;
    href?: string | RouteDefinition<'get'>;
    disabled?: boolean;
}>();
</script>

<template>
    <Card
        :class="[
            'flex flex-col gap-4 p-6 transition-shadow',
            props.disabled ? 'opacity-60' : 'hover:shadow-md',
        ]"
    >
        <CardHeader
            class="flex flex-row items-start justify-between gap-2 space-y-0 p-0"
        >
            <div
                class="flex size-11 items-center justify-center rounded-lg bg-primary/10 text-primary"
            >
                <component :is="props.icon" class="size-6" />
            </div>
            <Badge v-if="props.badge" variant="secondary">{{
                props.badge
            }}</Badge>
        </CardHeader>
        <CardContent class="flex flex-1 flex-col gap-4 p-0">
            <div class="space-y-1">
                <CardTitle>{{ props.title }}</CardTitle>
                <p class="text-sm text-muted-foreground">
                    {{ props.description }}
                </p>
            </div>
            <Link
                v-if="!props.disabled && props.href"
                :href="props.href"
                class="mt-auto inline-flex h-9 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
            >
                {{ props.actionLabel }}
            </Link>
            <span
                v-else
                class="mt-auto inline-flex h-9 cursor-not-allowed items-center justify-center rounded-md border border-border px-4 text-sm font-medium text-muted-foreground"
            >
                {{ props.actionLabel }}
            </span>
        </CardContent>
    </Card>
</template>
