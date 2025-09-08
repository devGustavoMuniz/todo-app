<template>
  <Transition
    appear
    enter-active-class="transition-all duration-200 ease-out"
    enter-from-class="opacity-0 scale-95"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition-all duration-150 ease-in"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-95"
  >
    <slot />
  </Transition>
</template>

<script setup lang="ts">
// Quick scale transition for modals, dropdowns, etc.
</script>