<script setup lang="ts">
import { Languages } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useDirection } from '@/composables/useDirection';
import type { Locale } from '@/plugins/i18n';

const { t } = useI18n();
const { locale, setLocale } = useDirection();

function choose(value: Locale) {
    if (value !== locale.value) {
        setLocale(value);
    }
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                size="sm"
                :aria-label="t('common.language')"
            >
                <Languages class="size-4" />
                {{ locale === 'ar' ? t('common.arabic') : t('common.english') }}
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem @click="choose('ar')">
                {{ t('common.arabic') }}
            </DropdownMenuItem>
            <DropdownMenuItem @click="choose('en')">
                {{ t('common.english') }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
