<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <!--
        Placeholder brand mark (stylized "K" for Kidana) built from the app's
        own teal/gold tokens — NOT pulled from the real Figma logo asset,
        since the Figma MCP connection for this project is rate-limited to
        6 reads/month (Starter plan) and already exhausted. Swap this file's
        markup for the real exported logo whenever it's available; every
        other usage (sidebar, marketing header/footer, auth pages) references
        this one component, so nothing else needs to change.
    -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        :class="className"
        v-bind="$attrs"
    >
        <path
            d="M11 7 L11 33 M11 20 L29 6 M14 23 L29 34"
            style="fill: none"
            stroke="currentColor"
            stroke-width="5"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <circle cx="29" cy="6" r="3.2" style="fill: #d6a419" />
    </svg>
</template>
