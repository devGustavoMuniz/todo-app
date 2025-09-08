<template>
  <Head title="Tarefas" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
          Minhas Tarefas
        </h2>
        <Button :as="Link" :href="route('tasks.create')" class="w-full sm:w-auto">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Nova Tarefa
        </Button>
      </div>
    </template>

    <PageTransition>
      <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Stats Cards -->
          <FadeIn>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
          <Card>
            <CardHeader class="pb-2">
              <CardTitle class="text-sm font-medium">Total</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">{{ stats.total }}</div>
            </CardContent>
          </Card>
          
          <Card>
            <CardHeader class="pb-2">
              <CardTitle class="text-sm font-medium">Pendentes</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold text-yellow-600">{{ stats.pending }}</div>
            </CardContent>
          </Card>
          
          <Card>
            <CardHeader class="pb-2">
              <CardTitle class="text-sm font-medium">Em Progresso</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold text-blue-600">{{ stats.in_progress }}</div>
            </CardContent>
          </Card>
          
          <Card>
            <CardHeader class="pb-2">
              <CardTitle class="text-sm font-medium">Concluídas</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold text-green-600">{{ stats.done }}</div>
            </CardContent>
          </Card>
            </div>
          </FadeIn>

        <!-- Filters -->
        <SlideUp>
          <Card class="mb-6">
          <CardContent class="pt-6">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <Input
                  v-model="searchForm.search"
                  placeholder="Buscar tarefas..."
                  @input="search"
                />
              </div>
              <div class="w-full sm:w-48">
                <select
                  v-model="searchForm.status"
                  @change="search"
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                >
                  <option value="">Todos os status</option>
                  <option value="pending">Pendente</option>
                  <option value="in_progress">Em Progresso</option>
                  <option value="done">Concluída</option>
                </select>
              </div>
            </div>
          </CardContent>
          </Card>
        </SlideUp>

        <!-- Tasks List -->
        <SlideUp>
          <div class="space-y-4">
          <Card v-for="task in tasks.data" :key="task.id">
            <CardContent class="pt-6">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                    <h3 class="text-lg font-medium">{{ task.title }}</h3>
                    <Badge :variant="getStatusVariant(task.status)" class="self-start">
                      {{ task.status_label }}
                    </Badge>
                  </div>
                  <p v-if="task.description" class="text-muted-foreground mt-1">
                    {{ task.description }}
                  </p>
                  <p class="text-sm text-muted-foreground mt-2">
                    Criada em {{ formatDate(task.created_at) }}
                  </p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                  <Button variant="outline" size="sm" :as="Link" :href="route('tasks.edit', task.id)" class="justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                  </Button>
                  <Button 
                    variant="destructive" 
                    size="sm"
                    @click="deleteTask(task)"
                    class="justify-center"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Excluir
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
          
          <div v-if="tasks.data.length === 0" class="text-center py-8">
            <p class="text-muted-foreground">Nenhuma tarefa encontrada.</p>
            <Button :as="Link" :href="route('tasks.create')" class="mt-4">
              Criar primeira tarefa
            </Button>
          </div>
          </div>
        </SlideUp>

        <!-- Pagination -->
        <FadeIn>
          <div v-if="tasks.links" class="mt-6 flex justify-center">
            <nav class="flex items-center gap-1">
              <Button
                v-for="link in tasks.links"
                :key="link.label"
                variant="outline"
                size="sm"
                :disabled="!link.url"
                @click="visit(link.url)"
                v-html="link.label"
              />
            </nav>
          </div>
        </FadeIn>
        </div>
      </div>
    </PageTransition>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import CardHeader from '@/Components/ui/CardHeader.vue'
import CardContent from '@/Components/ui/CardContent.vue'
import CardTitle from '@/Components/ui/CardTitle.vue'
import Input from '@/Components/ui/Input.vue'
import Badge from '@/Components/ui/Badge.vue'
import PageTransition from '@/Components/ui/PageTransition.vue'
import FadeIn from '@/Components/ui/FadeIn.vue'
import SlideUp from '@/Components/ui/SlideUp.vue'
import type { Task, TaskStats, PaginatedData } from '@/types'

interface Props {
  tasks: PaginatedData<Task>
  stats: TaskStats
  filters: {
    search?: string
    status?: string
  }
}

defineProps<Props>()

const searchForm = reactive({
  search: '',
  status: ''
})

function search() {
  router.get(route('tasks.index'), searchForm, {
    preserveState: true,
    replace: true
  })
}

function visit(url: string | null) {
  if (url) {
    router.visit(url)
  }
}

function deleteTask(task: Task) {
  if (confirm(`Deseja realmente excluir a tarefa "${task.title}"?`)) {
    router.delete(route('tasks.destroy', task.id))
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
  return new Date(dateString).toLocaleDateString('pt-BR')
}
</script>