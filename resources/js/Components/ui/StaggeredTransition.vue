<template>
  <TransitionGroup
    tag="div"
    :class="containerClass"
    enter-active-class="transition-all duration-300 ease-out"
    enter-from-class="opacity-0 translate-y-6"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition-all duration-200 ease-in"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-6"
    @before-enter="onBeforeEnter"
    @enter="onEnter"
  >
    <slot />
  </TransitionGroup>
</template>

<script setup lang="ts">
interface Props {
  containerClass?: string
  delay?: number
}

const props = withDefaults(defineProps<Props>(), {
  containerClass: '',
  delay: 100
})

function onBeforeEnter(el: Element) {
  (el as HTMLElement).style.opacity = '0'
  ;(el as HTMLElement).style.transform = 'translateY(24px)'
}

function onEnter(el: Element, done: () => void) {
  const delay = Array.from(el.parentNode?.children || []).indexOf(el) * props.delay
  setTimeout(() => {
    ;(el as HTMLElement).style.transition = 'all 0.4s ease-out'
    ;(el as HTMLElement).style.opacity = '1'
    ;(el as HTMLElement).style.transform = 'translateY(0px)'
    setTimeout(done, 400)
  }, delay)
}
</script>