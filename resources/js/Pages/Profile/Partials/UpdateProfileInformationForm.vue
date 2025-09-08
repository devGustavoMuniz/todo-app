<script setup lang="ts">
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-6">
            <p class="text-muted-foreground">
                Atualize as informações do seu perfil e endereço de email.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <div>
                <label for="name" class="block text-sm font-medium mb-2">
                    Nome
                </label>

                <Input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{ 'border-destructive': form.errors.name }"
                />

                <p v-if="form.errors.name" class="text-destructive text-sm mt-1">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-2">
                    Email
                </label>

                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :class="{ 'border-destructive': form.errors.email }"
                />

                <p v-if="form.errors.email" class="text-destructive text-sm mt-1">
                    {{ form.errors.email }}
                </p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-muted p-4 rounded-md">
                <p class="text-sm text-muted-foreground">
                    Seu endereço de email não foi verificado.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-primary underline hover:no-underline ml-1"
                    >
                        Clique aqui para reenviar o email de verificação.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    Um novo link de verificação foi enviado para seu email.
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <Button type="submit" :disabled="form.processing" size="mobile">
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
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
                        Salvo com sucesso.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
