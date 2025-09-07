import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  
  const isAuthenticated = computed(() => user.value !== null)
  
  function setUser(userData: User | null) {
    user.value = userData
  }
  
  function logout() {
    user.value = null
  }
  
  return {
    user,
    isAuthenticated,
    setUser,
    logout
  }
})