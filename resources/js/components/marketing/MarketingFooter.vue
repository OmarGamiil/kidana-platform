<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Globe2, Mail, MessageCircle, Phone } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { access, home } from '@/routes';

const { t } = useI18n();

const groups = [
    { key: 'platform', items: ['home', 'services', 'readiness'] },
    { key: 'season', items: ['planning', 'compliance', 'reports'] },
    { key: 'links', items: ['knowledge', 'support', 'contact'] },
];
</script>

<template>
    <footer class="bg-marketing-teal-dark text-white">
        <div
            class="mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-[1.25fr_2fr] xl:px-8"
        >
            <div>
                <Link
                    :href="home()"
                    class="inline-block rounded-lg bg-white p-2 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marketing-gold"
                >
                    <img
                        src="/images/kidana-logo.jpg"
                        :alt="t('marketing.brand_alt')"
                        class="h-11 w-auto object-contain mix-blend-multiply"
                    />
                </Link>
                <p class="mt-5 max-w-md text-sm leading-7 text-white/60">
                    {{ t('marketing.footer.tagline') }}
                </p>
                <p class="mt-3 text-xs text-marketing-gold-soft">
                    {{ t('marketing.footer.season_line') }}
                </p>
                <div class="mt-6 flex gap-2">
                    <span
                        v-for="icon in [Globe2, MessageCircle, Mail, Phone]"
                        :key="icon.name"
                        class="flex size-9 items-center justify-center rounded-full border border-white/15 text-white/65"
                        ><component :is="icon" class="size-4"
                    /></span>
                </div>
            </div>
            <nav
                class="grid grid-cols-3 gap-8"
                :aria-label="t('marketing.footer.label')"
            >
                <div v-for="group in groups" :key="group.key">
                    <h3 class="font-bold text-white">
                        {{ t(`marketing.footer.groups.${group.key}.title`) }}
                    </h3>
                    <ul class="mt-5 grid gap-3 text-sm text-white/55">
                        <li v-for="item in group.items" :key="item">
                            <Link
                                v-if="item === 'home'"
                                :href="home()"
                                class="hover:text-marketing-gold"
                                >{{
                                    t(
                                        `marketing.footer.groups.${group.key}.${item}`,
                                    )
                                }}</Link
                            >
                            <Link
                                v-else-if="item === 'readiness'"
                                :href="access()"
                                class="hover:text-marketing-gold"
                                >{{
                                    t(
                                        `marketing.footer.groups.${group.key}.${item}`,
                                    )
                                }}</Link
                            >
                            <span v-else>{{
                                t(
                                    `marketing.footer.groups.${group.key}.${item}`,
                                )
                            }}</span>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="border-t border-white/10">
            <div
                class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-6 py-5 text-xs text-white/45 xl:px-8"
            >
                <span>{{ t('marketing.footer.copyright') }}</span>
                <span>{{ t('marketing.footer.legal') }}</span>
            </div>
        </div>
    </footer>
</template>
