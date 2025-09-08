<template>
  <Transition
    appear
    enter-active-class="transition-all duration-400 ease-out"
    enter-from-class="opacity-0 translate-y-6"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition-all duration-250 ease-in"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-6"
  >
    <slot />
  </Transition>
</template>

<script setup lang="ts">
// Slide-up transition component for cards and sections
</script>