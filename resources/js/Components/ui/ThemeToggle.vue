<template>
  <Button 
    variant="ghost" 
    size="sm" 
    @click="toggleTheme"
    class="relative"
    :title="isDark ? 'Alternar para tema claro' : 'Alternar para tema escuro'"
  >
    <Transition 
      enter-active-class="transition-all duration-300 ease-in-out"
      leave-active-class="transition-all duration-300 ease-in-out" 
      enter-from-class="opacity-0 rotate-90 scale-0"
      enter-to-class="opacity-100 rotate-0 scale-100"
      leave-from-class="opacity-100 rotate-0 scale-100"
      leave-to-class="opacity-0 rotate-90 scale-0"
    >
      <svg
        v-if="isDark"
        key="moon"
        class="h-4 w-4"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
        />
      </svg>
      <svg
        v-else
        key="sun"
        class="h-4 w-4"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
        />
      </svg>
    </Transition>
  </Button>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Button from './Button.vue'

const isDark = ref(false)

// Detectar preferência do sistema
const getSystemPreference = () => {
  return window.matchMedia('(prefers-color-scheme: dark)').matches
}

// Aplicar tema
const applyTheme = (dark: boolean) => {
  if (dark) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  isDark.value = dark
}

// Toggle do tema
const toggleTheme = () => {
  const newTheme = !isDark.value
  applyTheme(newTheme)
  localStorage.setItem('theme', newTheme ? 'dark' : 'light')
}

// Inicializar tema
onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  let preferDark = false

  if (savedTheme === 'dark') {
    preferDark = true
  } else if (savedTheme === 'light') {
    preferDark = false
  } else {
    // Se não há preferência salva, usar a do sistema
    preferDark = getSystemPreference()
  }

  applyTheme(preferDark)

  // Escutar mudanças na preferência do sistema
  window.matchMedia('(prefers-color-scheme: dark)')
    .addEventListener('change', (e) => {
      if (!localStorage.getItem('theme')) {
        applyTheme(e.matches)
      }
    })
})
</script>