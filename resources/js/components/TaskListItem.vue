<script setup lang="ts">
defineProps<{
    title: string;
    dueAt: string;
}>();
</script>

<template>
    <li class="flex items-center gap-3 py-2">
        <span class="size-2 shrink-0 rounded-full bg-warning" />
        <span class="flex-1 text-sm">{{ title }}</span>
        <span class="text-xs text-muted-foreground">{{ dueAt }}</span>
    </li>
</template>
