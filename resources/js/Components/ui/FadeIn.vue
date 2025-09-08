<template>
  <Transition
    appear
    enter-active-class="transition-all duration-500 ease-out"
    enter-from-class="opacity-0 translate-y-2"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition-all duration-300 ease-in"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 -translate-y-2"
  >
    <slot />
  </Transition>
</template>

<script setup lang="ts">
// Fade-in transition component for content elements
</script>