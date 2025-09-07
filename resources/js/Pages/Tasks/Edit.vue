<template>
  <Head title="Editar Tarefa" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-foreground leading-tight">
        Editar Tarefa
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle>Editar: {{ task.title }}</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="title" class="block text-sm font-medium mb-2">
                  Título *
                </label>
                <Input
                  id="title"
                  v-model="form.title"
                  type="text"
                  required
                  :class="{ 'border-destructive': form.errors.title }"
                />
                <p v-if="form.errors.title" class="text-destructive text-sm mt-1">
                  {{ form.errors.title }}
                </p>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium mb-2">
                  Descrição
                </label>
                <Textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  :class="{ 'border-destructive': form.errors.description }"
                />
                <p v-if="form.errors.description" class="text-destructive text-sm mt-1">
                  {{ form.errors.description }}
                </p>
              </div>

              <div>
                <label for="status" class="block text-sm font-medium mb-2">
                  Status *
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  required
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                  :class="{ 'border-destructive': form.errors.status }"
                >
                  <option value="pending">Pendente</option>
                  <option value="in_progress">Em Progresso</option>
                  <option value="done">Concluída</option>
                </select>
                <p v-if="form.errors.status" class="text-destructive text-sm mt-1">
                  {{ form.errors.status }}
                </p>
              </div>

              <div class="flex items-center gap-4">
                <Button type="submit" :disabled="form.processing">
                  {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                </Button>
                <Button variant="outline" as="Link" :href="route('tasks.index')">
                  Cancelar
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import CardHeader from '@/Components/ui/CardHeader.vue'
import CardContent from '@/Components/ui/CardContent.vue'
import CardTitle from '@/Components/ui/CardTitle.vue'
import Input from '@/Components/ui/Input.vue'
import Textarea from '@/Components/ui/Textarea.vue'
import type { Task } from '@/types'

interface Props {
  task: Task
}

const props = defineProps<Props>()

const form = useForm({
  title: props.task.title,
  description: props.task.description || '',
  status: props.task.status
})

function submit() {
  form.put(route('tasks.update', props.task.id))
}
</script>