<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { formatRelativeTime } from '@/lib/formatRelativeTime';

const props = defineProps<{
    event: string;
    createdAt: string;
}>();

const { t, te, locale } = useI18n();

// Copy per event key is filled in as the underlying screens (that create
// these events) are built — falls back to the raw event key so nothing
// silently disappears from the feed in the meantime.
const label = computed(() => {
    const key = `dashboard.activity.${props.event}`;

    return te(key) ? t(key) : props.event;
});

const relativeTime = computed(() =>
    formatRelativeTime(props.createdAt, locale.value),
);
</script>

<template>
    <li class="flex items-center justify-between gap-3 py-2 text-sm">
        <span>{{ label }}</span>
        <span class="shrink-0 text-xs text-muted-foreground">{{
            relativeTime
        }}</span>
    </li>
</template>
