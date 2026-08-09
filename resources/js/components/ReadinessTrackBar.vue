<script setup lang="ts">
import { Progress } from '@/components/ui/progress';

defineProps<{
    label: string;
    percentage: number;
    byCity: { city: string; percentage: number }[];
}>();
</script>

<template>
    <div class="space-y-2">
        <div class="flex items-center justify-between text-sm">
            <span class="font-medium">{{ label }}</span>
            <span class="text-muted-foreground">{{ percentage }}%</span>
        </div>
        <Progress :model-value="percentage" />
        <p v-if="byCity.length" class="text-xs text-muted-foreground">
            <span v-for="(city, index) in byCity" :key="city.city">
                <template v-if="index > 0">· </template>{{ city.city }}
                {{ city.percentage }}%
            </span>
        </p>
    </div>
</template>
