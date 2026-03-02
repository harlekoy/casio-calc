<template>
  <div class="flex items-start justify-center relative min-h-screen bg-[#0d0d0f]">
    <!-- Calculator Body -->
    <div
      class="w-[340px] mt-4 bg-casio-body rounded-t-[18px] rounded-b-[24px] p-5 px-4 pb-6 border border-[#3a3a3c] relative z-10"
      style="box-shadow: 0 2px 0 0 #444, 0 4px 0 0 #222, 0 8px 30px rgba(0,0,0,0.7), inset 0 1px 0 rgba(255,255,255,0.05)"
    >
      <BrandHeader />
      <SolarPanel />

      <LcdScreen
        :expression="store.expression"
        :result="store.result"
        :history="store.history"
        :show-tape="store.showTape"
        @delete-entry="store.deleteEntry"
        @clear-all="store.clearAllHistory"
        @toggle-tape="store.toggleTape"
      />

      <ButtonGrid
        v-model:show-scientific="store.showScientific"
        :expression="store.expression"
        @append="store.appendToExpression"
        @calculate="store.calculate"
        @clear-entry="store.clearEntry"
        @all-clear="store.allClear"
        @backspace="store.backspace"
        @toggle-tape="store.toggleTape"
      />

      <!-- Error message -->
      <div
        v-if="store.error"
        class="bg-casio-clear-shadow text-red-200 text-center text-xs px-2 py-1.5 rounded mt-2 font-mono"
      >
        {{ store.error }}
      </div>
    </div>

    <BottomSheet
      v-model:show="store.showPanel"
      @paste-expression="store.pasteExpression"
    />
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from 'vue'
import { useCalculatorStore } from './stores/calculator'

import BrandHeader from './components/BrandHeader.vue'
import SolarPanel from './components/SolarPanel.vue'
import LcdScreen from './components/LcdScreen.vue'
import ButtonGrid from './components/ButtonGrid.vue'
import BottomSheet from './components/BottomSheet.vue'

const store = useCalculatorStore()

onMounted(() => {
  store.fetchHistory()
  store.initListeners()
})

onBeforeUnmount(() => {
  store.destroyListeners()
})
</script>
