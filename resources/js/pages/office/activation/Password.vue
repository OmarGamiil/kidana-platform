<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ActivationStepper from '@/components/ActivationStepper.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import password from '@/routes/activation/password';

defineOptions({ layout: AuthLayout });

const { t } = useI18n();
</script>

<template>
    <Head :title="t('activation.password.title')" />

    <ActivationStepper :current-step="3" />

    <Form
        v-bind="password.store.form()"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="password">
                    {{ t('activation.password.password_label') }}
                </Label>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    autofocus
                    autocomplete="new-password"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">
                    {{ t('activation.password.password_confirmation_label') }}
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button type="submit" class="w-full" :disabled="processing">
                <Spinner v-if="processing" />
                {{ t('activation.password.submit') }}
            </Button>
        </div>
    </Form>
</template>
