<template>
  <div
    class="bg-casio-bezel rounded-lg p-1.5 mx-1 mb-4 border border-[#111]"
    style="box-shadow: inset 0 2px 6px rgba(0,0,0,0.5)"
  >
    <div
      class="bg-casio-screen rounded flex flex-col justify-end px-3.5 py-3 relative overflow-hidden"
      style="box-shadow: inset 0 1px 3px rgba(0,0,0,0.2)"
    >
      <!-- Screen glare -->
      <div
        class="absolute top-0 left-0 right-0 h-[40%] pointer-events-none z-10"
        style="background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, transparent 100%)"
      />

      <!-- Ticker Tape (inside screen) -->
      <transition
        enter-active-class="transition-all duration-300 ease-out"
        leave-active-class="transition-all duration-200 ease-in"
        enter-from-class="opacity-0 max-h-0"
        enter-to-class="opacity-100 max-h-[200px]"
        leave-from-class="opacity-100 max-h-[200px]"
        leave-to-class="opacity-0 max-h-0"
      >
        <div
          v-if="showTape"
          class="overflow-hidden"
        >
          <!-- Tape Header -->
          <div class="flex items-center justify-between mb-1">
            <span class="font-display text-[7px] font-bold text-casio-screen-text/50 tracking-[2px]">TAPE</span>
            <div class="flex gap-1.5 items-center">
              <button
                :disabled="!history.length"
                class="bg-transparent border border-casio-screen-text/20 text-casio-screen-text/50 font-sans text-[7px] tracking-[1px] px-1.5 py-[1px] rounded-[2px] cursor-pointer transition-all duration-150 hover:enabled:border-casio-screen-text/50 hover:enabled:text-casio-screen-text/80 disabled:opacity-30 disabled:cursor-not-allowed"
                @click="$emit('clear-all')"
              >
                CLEAR ALL
              </button>
              <button
                class="bg-transparent border-none text-casio-screen-text/40 text-sm cursor-pointer leading-none hover:text-casio-screen-text/80"
                @click="$emit('toggle-tape')"
              >
                &times;
              </button>
            </div>
          </div>

          <!-- Tape Entries -->
          <div
            ref="tapeRoll"
            class="max-h-[160px] overflow-y-auto space-y-0"
          >
            <div
              v-if="!history.length"
              class="text-center text-casio-screen-text/40 text-[10px] py-3 italic"
            >
              No calculations yet.
            </div>
            <div
              v-for="item in history"
              :key="item.id"
              class="py-0.5"
            >
              <div class="flex items-center justify-end gap-1.5">
                <span class="font-mono text-[11px] text-casio-screen-text/60 text-right flex-1">{{ item.expression }} =</span>
                <span class="font-mono text-[12px] text-casio-screen-text font-bold">{{ item.result }}</span>
                <button
                  class="bg-transparent border-none text-casio-screen-text/25 text-[11px] cursor-pointer px-0 leading-none transition-colors duration-150 hover:text-casio-screen-text/70"
                  @click="$emit('delete-entry', item.id)"
                >
                  &times;
                </button>
              </div>
              <div class="border-b border-dashed border-casio-screen-text/10" />
            </div>
          </div>

          <!-- Separator between tape and input -->
          <div class="border-b border-casio-screen-text/20 my-1.5" />
        </div>
      </transition>

      <!-- Current expression -->
      <div class="font-mono text-sm text-casio-screen-text opacity-70 text-right min-h-[20px] break-all leading-tight">
        {{ displayExpression || '&nbsp;' }}
      </div>
      <div class="font-mono text-[32px] text-casio-screen-text text-right leading-tight break-all">
        {{ result }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  expression: { type: String, required: true },
  result: { type: String, required: true },
  history: { type: Array, required: true },
  showTape: { type: Boolean, required: true },
})

defineEmits(['delete-entry', 'clear-all', 'toggle-tape'])

const displayExpression = computed(() =>
  props.expression
    .replace(/\*/g, '\u00D7')
    .replace(/\//g, '\u00F7')
    .replace(/sqrt\(/g, '\u221A(')
    .replace(/pi/g, '\u03C0')
    .replace(/log10\(/g, 'log\u2081\u2080(')
)
</script>
