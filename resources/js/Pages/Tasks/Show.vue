<template>
  <Head :title="task.title" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
          Detalhes da Tarefa
        </h2>
        <div class="flex gap-2">
          <Button variant="outline" as="Link" :href="route('tasks.edit', task.id)">
            Editar
          </Button>
          <Button variant="outline" as="Link" :href="route('tasks.index')">
            Voltar
          </Button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <CardTitle class="text-2xl">{{ task.title }}</CardTitle>
              <Badge :variant="getStatusVariant(task.status)">
                {{ task.status_label }}
              </Badge>
            </div>
          </CardHeader>
          <CardContent class="space-y-6">
            <div v-if="task.description">
              <h3 class="text-lg font-medium mb-2">Descrição</h3>
              <p class="text-muted-foreground whitespace-pre-line">{{ task.description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <h4 class="font-medium mb-1">Status</h4>
                <Badge :variant="getStatusVariant(task.status)">
                  {{ task.status_label }}
                </Badge>
              </div>
              
              <div>
                <h4 class="font-medium mb-1">Criada em</h4>
                <p class="text-muted-foreground">{{ formatDate(task.created_at) }}</p>
              </div>
              
              <div>
                <h4 class="font-medium mb-1">Última atualização</h4>
                <p class="text-muted-foreground">{{ formatDate(task.updated_at) }}</p>
              </div>
            </div>

            <div class="border-t pt-6">
              <div class="flex items-center gap-4">
                <Button as="Link" :href="route('tasks.edit', task.id)">
                  Editar Tarefa
                </Button>
                <Button 
                  variant="destructive"
                  @click="deleteTask"
                >
                  Excluir Tarefa
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import CardHeader from '@/Components/ui/CardHeader.vue'
import CardContent from '@/Components/ui/CardContent.vue'
import CardTitle from '@/Components/ui/CardTitle.vue'
import Badge from '@/Components/ui/Badge.vue'
import type { Task } from '@/types'

interface Props {
  task: Task
}

const props = defineProps<Props>()

function deleteTask() {
  if (confirm(`Deseja realmente excluir a tarefa "${props.task.title}"?`)) {
    router.delete(route('tasks.destroy', props.task.id))
  }
}

function getStatusVariant(status: string) {
  switch (status) {
    case 'pending': return 'secondary'
    case 'in_progress': return 'default'
    case 'done': return 'outline'
    default: return 'secondary'
  }
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>