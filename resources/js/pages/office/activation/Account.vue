<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ActivationStepper from '@/components/ActivationStepper.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import account from '@/routes/activation/account';

defineOptions({ layout: AuthLayout });

const { t } = useI18n();

defineProps<{
    officeName: string;
}>();
</script>

<template>
    <Head :title="t('activation.account.title')" />

    <ActivationStepper :current-step="1" />

    <div class="mb-4 text-center text-sm text-muted-foreground">
        {{ officeName }}
    </div>

    <Form
        v-bind="account.store.form()"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="mobile_number">
                    {{ t('activation.account.mobile_number_label') }}
                </Label>
                <Input
                    id="mobile_number"
                    type="tel"
                    name="mobile_number"
                    required
                    autofocus
                    dir="ltr"
                />
                <InputError :message="errors.mobile_number" />
            </div>

            <div class="grid gap-2">
                <Label for="official_email">
                    {{ t('activation.account.official_email_label') }}
                </Label>
                <Input
                    id="official_email"
                    type="email"
                    name="official_email"
                    required
                    dir="ltr"
                />
                <InputError :message="errors.official_email" />
            </div>

            <Label
                for="terms_accepted"
                class="flex items-start gap-3 text-sm font-normal"
            >
                <Checkbox id="terms_accepted" name="terms_accepted" />
                <span>{{ t('activation.account.terms_label') }}</span>
            </Label>
            <InputError :message="errors.terms_accepted" />

            <Button type="submit" class="w-full" :disabled="processing">
                <Spinner v-if="processing" />
                {{ t('activation.account.submit') }}
            </Button>
        </div>
    </Form>
</template>
