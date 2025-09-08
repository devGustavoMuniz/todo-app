<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
          Dashboard
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
          
          <!-- Welcome Message -->
          <FadeIn>
            <Card>
          <CardHeader>
            <CardTitle class="text-xl sm:text-2xl">
              Bem-vindo de volta! 👋
            </CardTitle>
            <p class="text-muted-foreground text-sm sm:text-base">
              Aqui está um resumo das suas tarefas
            </p>
          </CardHeader>
            </Card>
          </FadeIn>

        <!-- Stats Cards -->
        <SlideUp>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
          <Card>
            <CardHeader class="pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                  Total de Tarefas
                </CardTitle>
                <div class="h-4 w-4 text-muted-foreground">📋</div>
              </div>
            </CardHeader>
            <CardContent class="pt-0">
              <div class="text-3xl font-bold">{{ stats.total }}</div>
              <p class="text-xs text-muted-foreground mt-1">
                Total criadas
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader class="pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                  Pendentes
                </CardTitle>
                <div class="h-4 w-4 text-yellow-500">⏳</div>
              </div>
            </CardHeader>
            <CardContent class="pt-0">
              <div class="text-3xl font-bold text-yellow-600">{{ stats.pending }}</div>
              <p class="text-xs text-muted-foreground mt-1">
                Aguardando início
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader class="pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                  Em Progresso
                </CardTitle>
                <div class="h-4 w-4 text-blue-500">🔄</div>
              </div>
            </CardHeader>
            <CardContent class="pt-0">
              <div class="text-3xl font-bold text-blue-600">{{ stats.in_progress }}</div>
              <p class="text-xs text-muted-foreground mt-1">
                Sendo trabalhadas
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader class="pb-3">
              <div class="flex items-center justify-between">
                <CardTitle class="text-sm font-medium text-muted-foreground">
                  Concluídas
                </CardTitle>
                <div class="h-4 w-4 text-green-500">✅</div>
              </div>
            </CardHeader>
            <CardContent class="pt-0">
              <div class="text-3xl font-bold text-green-600">{{ stats.done }}</div>
              <p class="text-xs text-muted-foreground mt-1">
                {{ stats.completion_rate }}% de conclusão
              </p>
            </CardContent>
          </Card>
          </div>
        </SlideUp>

        <!-- Progress Bar -->
        <SlideUp>
          <Card v-if="stats.total > 0">
          <CardHeader>
            <CardTitle class="text-lg">Progresso Geral</CardTitle>
            <p class="text-muted-foreground">
              Você concluiu {{ stats.done }} de {{ stats.total }} tarefas
            </p>
          </CardHeader>
          <CardContent>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div 
                class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-300 ease-in-out"
                :style="`width: ${stats.completion_rate}%`"
              ></div>
            </div>
            <div class="flex justify-between text-sm text-muted-foreground mt-2">
              <span>0%</span>
              <span class="font-medium">{{ stats.completion_rate }}%</span>
              <span>100%</span>
            </div>
          </CardContent>
          </Card>
        </SlideUp>

        <!-- Recent Tasks -->
        <SlideUp>
          <Card v-if="recentTasks.length > 0">
          <CardHeader>
            <div class="flex items-center justify-between">
              <CardTitle class="text-lg">Tarefas Recentes</CardTitle>
              <Button variant="outline" size="sm" :as="Link" :href="route('tasks.index')">
                Ver Todas
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div 
                v-for="task in recentTasks" 
                :key="task.id"
                class="flex items-center justify-between p-3 border rounded-lg hover:bg-muted/50 transition-colors"
              >
                <div class="flex-1">
                  <div class="flex items-center gap-3">
                    <h4 class="font-medium">{{ task.title }}</h4>
                    <Badge :variant="getStatusVariant(task.status)">
                      {{ task.status_label || task.status }}
                    </Badge>
                  </div>
                  <p class="text-sm text-muted-foreground mt-1">
                    {{ formatDate(task.created_at) }}
                  </p>
                </div>
                <Button variant="ghost" size="sm" :as="Link" :href="route('tasks.show', task.id)">
                  Ver
                </Button>
              </div>
            </div>
          </CardContent>
          </Card>
        </SlideUp>

        <!-- Empty State -->
        <FadeIn>
          <Card v-if="stats.total === 0">
          <CardContent class="text-center py-12">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-lg font-medium mb-2">Nenhuma tarefa ainda</h3>
            <p class="text-muted-foreground mb-4">
              Comece criando sua primeira tarefa para organizar seu trabalho
            </p>
            <Button :as="Link" :href="route('tasks.create')">
              Criar Primeira Tarefa
            </Button>
          </CardContent>
          </Card>
        </FadeIn>

        <!-- Quick Actions -->
        <SlideUp>
          <Card>
          <CardHeader>
            <CardTitle class="text-lg">Ações Rápidas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <Button :as="Link" :href="route('tasks.create')" class="h-12">
                ➕ Nova Tarefa
              </Button>
              <Button 
                variant="outline" 
                :as="Link" 
                :href="route('tasks.index', { status: 'pending' })" 
                class="h-12"
              >
                ⏳ Ver Pendentes
              </Button>
              <Button 
                variant="outline" 
                :as="Link" 
                :href="route('tasks.index', { status: 'in_progress' })" 
                class="h-12"
              >
                🔄 Ver Em Progresso
              </Button>
            </div>
          </CardContent>
          </Card>
        </SlideUp>

        </div>
      </div>
    </PageTransition>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import CardHeader from '@/Components/ui/CardHeader.vue'
import CardContent from '@/Components/ui/CardContent.vue'
import CardTitle from '@/Components/ui/CardTitle.vue'
import Badge from '@/Components/ui/Badge.vue'
import PageTransition from '@/Components/ui/PageTransition.vue'
import FadeIn from '@/Components/ui/FadeIn.vue'
import SlideUp from '@/Components/ui/SlideUp.vue'
import type { Task, TaskStats } from '@/types'

interface Props {
  stats: TaskStats
  recentTasks: Task[]
}

defineProps<Props>()

function getStatusVariant(status: string) {
  switch (status) {
    case 'pending': return 'pending'
    case 'in_progress': return 'in_progress'
    case 'done': return 'done'
    default: return 'secondary'
  }
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit', 
    year: 'numeric'
  })
}
</script>