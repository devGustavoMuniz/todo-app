<script setup lang="ts">
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <p class="text-muted-foreground">
                Certifique-se de que sua conta está usando uma senha longa e aleatória para manter-se seguro.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium mb-2">
                    Senha Atual
                </label>

                <Input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    :class="{ 'border-destructive': form.errors.current_password }"
                />

                <p v-if="form.errors.current_password" class="text-destructive text-sm mt-1">
                    {{ form.errors.current_password }}
                </p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">
                    Nova Senha
                </label>

                <Input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    :class="{ 'border-destructive': form.errors.password }"
                />

                <p v-if="form.errors.password" class="text-destructive text-sm mt-1">
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-2">
                    Confirmar Nova Senha
                </label>

                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    :class="{ 'border-destructive': form.errors.password_confirmation }"
                />

                <p v-if="form.errors.password_confirmation" class="text-destructive text-sm mt-1">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <Button type="submit" :disabled="form.processing" size="mobile">
                    {{ form.processing ? 'Salvando...' : 'Alterar Senha' }}
                </Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600"
                    >
                        Senha alterada com sucesso.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
