<template>
  <Head title="Cadastro" />

  <GuestLayout>
    <div class="mx-auto max-w-sm">
      <Card>
        <CardHeader class="text-center">
          <CardTitle class="text-2xl">Criar Conta</CardTitle>
          <p class="text-muted-foreground">
            Preencha os dados abaixo para criar sua conta
          </p>
        </CardHeader>
        
        <CardContent>
          <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2">
              <label for="name" class="text-sm font-medium">Nome</label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Seu nome completo"
                required
                autofocus
                autocomplete="name"
                :class="{ 'border-red-500': form.errors.name }"
              />
              <div v-if="form.errors.name" class="text-sm text-red-600">
                {{ form.errors.name }}
              </div>
            </div>

            <div class="space-y-2">
              <label for="email" class="text-sm font-medium">Email</label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="seu@email.com"
                required
                autocomplete="username"
                :class="{ 'border-red-500': form.errors.email }"
              />
              <div v-if="form.errors.email" class="text-sm text-red-600">
                {{ form.errors.email }}
              </div>
            </div>

            <div class="space-y-2">
              <label for="password" class="text-sm font-medium">Senha</label>
              <Input
                id="password"
                v-model="form.password"
                type="password"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :class="{ 'border-red-500': form.errors.password }"
              />
              <div v-if="form.errors.password" class="text-sm text-red-600">
                {{ form.errors.password }}
              </div>
            </div>

            <div class="space-y-2">
              <label for="password_confirmation" class="text-sm font-medium">Confirmar Senha</label>
              <Input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                :class="{ 'border-red-500': form.errors.password_confirmation }"
              />
              <div v-if="form.errors.password_confirmation" class="text-sm text-red-600">
                {{ form.errors.password_confirmation }}
              </div>
            </div>

            <Button 
              type="submit" 
              class="w-full"
              :disabled="form.processing"
            >
              <span v-if="form.processing">Criando conta...</span>
              <span v-else>Criar Conta</span>
            </Button>

            <div class="text-center">
              <div class="text-sm text-muted-foreground">
                Já tem uma conta?
                <Link :href="route('login')" class="text-primary hover:underline font-medium">
                  Entrar
                </Link>
              </div>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Input from '@/Components/ui/Input.vue'
import Card from '@/Components/ui/Card.vue'
import CardHeader from '@/Components/ui/CardHeader.vue'
import CardContent from '@/Components/ui/CardContent.vue'
import CardTitle from '@/Components/ui/CardTitle.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => {
      form.reset('password', 'password_confirmation')
    },
  })
}
</script>