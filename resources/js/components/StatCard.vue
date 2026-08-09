<script setup lang="ts">
import type { Component } from 'vue';
import { Card, CardContent } from '@/components/ui/card';

defineProps<{
    icon: Component;
    value: string | number;
    label: string;
}>();
</script>

<template>
    <Card>
        <CardContent class="flex items-center gap-4 p-4">
            <div
                class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
            >
                <component :is="icon" class="size-5" />
            </div>
            <div>
                <div class="text-2xl font-bold">{{ value }}</div>
                <div class="text-sm text-muted-foreground">{{ label }}</div>
            </div>
        </CardContent>
    </Card>
</template>
