<template>
  <!-- Floating Bottom Sheet -->
  <div
    class="fixed bottom-0 left-0 right-0 z-50 transition-transform duration-300 ease-out"
    :class="show ? 'translate-y-0' : 'translate-y-full'"
  >
    <div class="max-w-2xl mx-auto bg-[#131315] border border-b-0 border-[#2a2a2c] rounded-t-xl shadow-[0_-4px_30px_rgba(0,0,0,0.6)]">
      <!-- Drag handle -->
      <div class="flex justify-center pt-2 pb-1">
        <div class="w-10 h-1 rounded-full bg-[#333]" />
      </div>

      <!-- Tabs + Close -->
      <div class="flex items-center justify-between px-5 pb-2">
        <div class="flex gap-4">
          <button
            class="bg-transparent border-none text-[10px] font-bold tracking-[2px] uppercase cursor-pointer transition-colors duration-150 pb-1"
            :class="panelTab === 'guide' ? 'text-casio-gold border-b border-casio-gold' : 'text-[#555] hover:text-[#888]'"
            @click="panelTab = 'guide'"
          >
            How to Use
          </button>
          <button
            class="bg-transparent border-none text-[10px] font-bold tracking-[2px] uppercase cursor-pointer transition-colors duration-150 pb-1"
            :class="panelTab === 'cheatsheet' ? 'text-casio-gold border-b border-casio-gold' : 'text-[#555] hover:text-[#888]'"
            @click="panelTab = 'cheatsheet'"
          >
            Cheatsheet
          </button>
        </div>
        <button
          class="bg-transparent border-none text-[#555] text-lg cursor-pointer leading-none hover:text-[#aaa] transition-colors duration-150"
          @click="$emit('update:show', false)"
        >
          &times;
        </button>
      </div>

      <!-- Tab Content -->
      <div class="px-5 pb-4 max-h-[50vh] overflow-y-auto">
        <!-- Guide Tab -->
        <div
          v-if="panelTab === 'guide'"
          class="space-y-3"
        >
          <div
            v-for="tip in tips"
            :key="tip.title"
          >
            <div class="flex items-start gap-2.5 py-1.5">
              <span class="text-base leading-none mt-0.5">{{ tip.icon }}</span>
              <div>
                <div class="text-[11px] font-bold text-[#ccc]">
                  {{ tip.title }}
                </div>
                <div class="text-[10px] text-[#777] mt-0.5 leading-relaxed">
                  {{ tip.desc }}
                </div>
                <div
                  v-if="tip.keys"
                  class="flex flex-wrap gap-1 mt-1.5"
                >
                  <span
                    v-for="key in tip.keys"
                    :key="key"
                    class="text-[9px] font-mono bg-[#1e1e22] text-[#999] border border-[#333] rounded px-1.5 py-0.5"
                  >{{ key }}</span>
                </div>
              </div>
            </div>
            <div class="border-b border-[#1e1e22]" />
          </div>
        </div>

        <!-- Cheatsheet Tab -->
        <div
          v-if="panelTab === 'cheatsheet'"
          class="space-y-3"
        >
          <div
            v-for="section in cheatsheet"
            :key="section.title"
          >
            <div
              class="text-[9px] font-bold tracking-[2px] uppercase mb-1.5"
              :class="section.color"
            >
              {{ section.title }}
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
              <div
                v-for="item in section.items"
                :key="item.expr"
                class="flex justify-between font-mono text-[11px]"
              >
                <span
                  class="text-[#999] cursor-pointer hover:text-white transition-colors duration-150"
                  :title="'Click to paste'"
                  @click="$emit('paste-expression', item.expr)"
                >{{ item.expr }}</span>
                <span class="text-[#555]">{{ item.result }}</span>
              </div>
            </div>
          </div>

          <div class="border-t border-[#2a2a2c] pt-2 text-[9px] text-[#555] text-center tracking-wide">
            Click any expression to paste it into the calculator
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Floating toggle button -->
  <button
    class="fixed bottom-4 right-4 z-40 w-11 h-11 rounded-full border border-[#3a3a3c] bg-[#1e1e22] text-casio-gold text-lg cursor-pointer shadow-[0_2px_12px_rgba(0,0,0,0.5)] transition-all duration-200 hover:bg-[#2a2a2e] hover:scale-105 active:scale-95"
    :title="show ? 'Hide panel' : 'Help & Cheatsheet'"
    @click="$emit('update:show', !show)"
  >
    ?
  </button>
</template>

<script setup>
import { ref } from 'vue'
import { tips, cheatsheet } from '../data/bottom-sheet.json'

defineProps({
  show: { type: Boolean, required: true },
})

defineEmits(['update:show', 'paste-expression'])

const panelTab = ref('guide')
</script>
