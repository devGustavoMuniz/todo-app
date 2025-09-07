<template>
  <Head title="Entrar" />

  <GuestLayout>
    <div class="mx-auto max-w-sm">
      <Card>
        <CardHeader class="text-center">
          <CardTitle class="text-2xl">Entrar</CardTitle>
          <p class="text-muted-foreground">
            Entre com suas credenciais para acessar o sistema
          </p>
        </CardHeader>
        
        <CardContent>
          <div v-if="status" class="mb-4 p-3 text-sm bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ status }}
          </div>

          <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2">
              <label for="email" class="text-sm font-medium">Email</label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="seu@email.com"
                required
                autofocus
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
                autocomplete="current-password"
                :class="{ 'border-red-500': form.errors.password }"
              />
              <div v-if="form.errors.password" class="text-sm text-red-600">
                {{ form.errors.password }}
              </div>
            </div>

            <div class="flex items-center space-x-2">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0"
              />
              <label for="remember" class="text-sm text-muted-foreground">
                Lembrar-me
              </label>
            </div>

            <Button 
              type="submit" 
              class="w-full"
              :disabled="form.processing"
            >
              <span v-if="form.processing">Entrando...</span>
              <span v-else>Entrar</span>
            </Button>

            <div class="text-center space-y-2">
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm text-primary hover:underline"
              >
                Esqueceu sua senha?
              </Link>
              
              <div class="text-sm text-muted-foreground">
                Não tem uma conta?
                <Link :href="route('register')" class="text-primary hover:underline font-medium">
                  Cadastre-se
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

defineProps<{
  canResetPassword?: boolean
  status?: string
}>()

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => {
      form.reset('password')
    },
  })
}
</script>