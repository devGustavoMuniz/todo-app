<script setup lang="ts">
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header class="mb-6">
            <p class="text-muted-foreground">
                Uma vez que sua conta for excluída, todos os recursos e dados serão permanentemente removidos. 
                Antes de excluir sua conta, faça o download de qualquer informação que deseje manter.
            </p>
        </header>

        <div class="bg-destructive/10 border border-destructive/20 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-destructive" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-destructive">
                        Esta ação é irreversível. Todos os seus dados serão permanentemente excluídos.
                    </p>
                </div>
            </div>
        </div>

        <Button variant="destructive" @click="confirmUserDeletion">
            Excluir Conta
        </Button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-foreground mb-4">
                    Tem certeza que deseja excluir sua conta?
                </h2>

                <p class="text-sm text-muted-foreground mb-6">
                    Uma vez que sua conta for excluída, todos os recursos e dados serão permanentemente removidos. 
                    Digite sua senha para confirmar que deseja excluir permanentemente sua conta.
                </p>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-2">
                        Senha
                    </label>

                    <Input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="Digite sua senha"
                        :class="{ 'border-destructive': form.errors.password }"
                        @keyup.enter="deleteUser"
                    />

                    <p v-if="form.errors.password" class="text-destructive text-sm mt-1">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <Button variant="outline" @click="closeModal" size="mobile">
                        Cancelar
                    </Button>

                    <Button
                        variant="destructive"
                        size="mobile"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        {{ form.processing ? 'Excluindo...' : 'Excluir Conta' }}
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
