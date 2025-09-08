<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Card from '@/Components/ui/Card.vue';
import CardHeader from '@/Components/ui/CardHeader.vue';
import CardContent from '@/Components/ui/CardContent.vue';
import CardTitle from '@/Components/ui/CardTitle.vue';
import PageTransition from '@/Components/ui/PageTransition.vue';
import FadeIn from '@/Components/ui/FadeIn.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Perfil" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                Perfil do Usuário
            </h2>
        </template>

        <PageTransition>
            <div class="py-6 sm:py-12">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                    <FadeIn>
                        <Card>
                            <CardHeader>
                                <CardTitle>Informações do Perfil</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                />
                            </CardContent>
                        </Card>
                    </FadeIn>

                    <FadeIn>
                        <Card>
                            <CardHeader>
                                <CardTitle>Alterar Senha</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <UpdatePasswordForm />
                            </CardContent>
                        </Card>
                    </FadeIn>

                    <FadeIn>
                        <Card>
                            <CardHeader>
                                <CardTitle>Excluir Conta</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <DeleteUserForm />
                            </CardContent>
                        </Card>
                    </FadeIn>
                </div>
            </div>
        </PageTransition>
    </AuthenticatedLayout>
</template>
