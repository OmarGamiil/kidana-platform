<script setup lang="ts">
defineProps<{
    value: string;
    label: string;
    accent?: boolean;
}>();
</script>

<template>
    <article
        class="flex min-h-36 flex-col justify-center rounded-2xl border border-marketing-border bg-marketing-surface p-6 text-center"
    >
        <strong
            :class="[
                'text-4xl font-bold',
                accent ? 'text-marketing-gold' : 'text-marketing-teal',
            ]"
            >{{ value }}</strong
        >
        <p class="mx-auto mt-3 max-w-52 text-sm leading-6 text-marketing-muted">
            {{ label }}
        </p>
    </article>
</template>
