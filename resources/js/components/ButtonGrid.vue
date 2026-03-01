<template>
  <div class="px-1 space-y-1.5">
    <!-- Scientific toggle -->
    <button
      class="w-full h-[22px] rounded-md border-none cursor-pointer font-sans text-[9px] font-bold tracking-[2px] uppercase transition-all duration-200"
      :class="showScientific
        ? 'bg-[#2a3a4a] text-[#a0cfed] shadow-[inset_0_1px_3px_rgba(0,0,0,0.3)]'
        : 'bg-[#1e1e22] text-[#666] hover:text-[#888]'"
      @click="$emit('update:showScientific', !showScientific)"
    >
      {{ showScientific ? '&#9650; SCIENTIFIC' : '&#9660; SCIENTIFIC' }}
    </button>

    <!-- Scientific rows -->
    <transition
      enter-active-class="transition-all duration-250 ease-out"
      leave-active-class="transition-all duration-200 ease-in"
      enter-from-class="opacity-0 max-h-0"
      enter-to-class="opacity-100 max-h-[250px]"
      leave-from-class="opacity-100 max-h-[250px]"
      leave-to-class="opacity-0 max-h-0"
    >
      <div
        v-show="showScientific"
        class="space-y-1.5 overflow-hidden"
      >
        <div
          v-for="(row, i) in scientificRows"
          :key="i"
          class="flex gap-1.5"
        >
          <button
            v-for="btn in row"
            :key="btn.label"
            :class="styles[btn.style || 'fn']"
            @click="handleClick(btn)"
            v-html="btn.label"
          />
        </div>
      </div>
    </transition>

    <!-- Numeric rows -->
    <div
      v-for="(row, i) in numericRows"
      :key="i"
      class="flex gap-1.5"
    >
      <button
        v-for="btn in row"
        :key="btn.label"
        :class="[styles[btn.style || 'num'], btn.class]"
        :disabled="btn.emit === 'calculate' && !expression"
        @click="handleClick(btn)"
        v-html="btn.label"
      />
    </div>
  </div>
</template>

<script setup>
defineProps({
  showScientific: { type: Boolean, required: true },
  expression: { type: String, required: true },
})

const emit = defineEmits([
  'append',
  'calculate',
  'clear-entry',
  'all-clear',
  'backspace',
  'toggle-tape',
  'update:showScientific',
])

function handleClick(btn) {
  if (btn.emit) {
    emit(btn.emit)
  } else {
    emit('append', btn.value)
  }
}

const scientificRows = [
  [
    { label: 'sin', value: 'sin(' },
    { label: 'cos', value: 'cos(' },
    { label: 'tan', value: 'tan(' },
    { label: 'sin<sup>-1</sup>', value: 'asin(' },
    { label: 'cos<sup>-1</sup>', value: 'acos(' },
  ],
  [
    { label: 'tan<sup>-1</sup>', value: 'atan(' },
    { label: 'ln', value: 'ln(' },
    { label: 'log', value: 'log10(' },
    { label: 'e<sup>x</sup>', value: 'exp(' },
    { label: '|x|', value: 'abs(' },
  ],
  [
    { label: '&radic;', value: 'sqrt(' },
    { label: 'x<sup>y</sup>', value: '^' },
    { label: 'x!', value: '!' },
    { label: '&pi;', value: 'pi' },
    { label: 'e', value: 'e' },
  ],
  [
    { label: '(', value: '(' },
    { label: ')', value: ')' },
    { label: '&lfloor;x&rfloor;', value: 'floor(' },
    { label: '&lceil;x&rceil;', value: 'ceil(' },
    { label: 'CE', emit: 'clear-entry', style: 'clear' },
  ],
]

const numericRows = [
  [
    { label: '7', value: '7' },
    { label: '8', value: '8' },
    { label: '9', value: '9' },
    { label: '&divide;', value: '/', style: 'op' },
    { label: 'AC', emit: 'all-clear', style: 'clear' },
  ],
  [
    { label: '4', value: '4' },
    { label: '5', value: '5' },
    { label: '6', value: '6' },
    { label: '&times;', value: '*', style: 'op' },
    { label: 'DEL', emit: 'backspace', style: 'del' },
  ],
  [
    { label: '1', value: '1' },
    { label: '2', value: '2' },
    { label: '3', value: '3' },
    { label: '&minus;', value: '-', style: 'op' },
    { label: '=', emit: 'calculate', style: 'eq' },
  ],
  [
    { label: '0', value: '0', class: 'flex-[2.1]' },
    { label: '.', value: '.' },
    { label: '+', value: '+', style: 'op' },
    { label: 'TAPE', emit: 'toggle-tape', style: 'tape' },
  ],
]

const btnBase = [
  'flex-1', 'h-[46px]', 'border-none', 'rounded-md', 'font-sans', 'font-bold',
  'cursor-pointer', 'relative', 'select-none', 'active:translate-y-[2px]',
  'transition-transform', 'duration-75',
]

const btnNum = [
  ...btnBase, 'text-base', 'text-[#e5e5e7]',
  'bg-gradient-to-b', 'from-casio-num-top', 'to-casio-num',
  'shadow-[0_3px_0_var(--color-casio-num-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-num-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const btnOp = [
  ...btnBase, 'text-xl', 'text-[#c8e6c2]',
  'bg-gradient-to-b', 'from-casio-op-top', 'to-casio-op',
  'shadow-[0_3px_0_var(--color-casio-op-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-op-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const btnEq = [
  ...btnBase, 'text-xl', 'text-white',
  'bg-gradient-to-b', 'from-casio-eq-top', 'to-casio-eq',
  'shadow-[0_3px_0_var(--color-casio-eq-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-eq-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
  'disabled:opacity-50', 'disabled:cursor-not-allowed',
]

const btnClear = [
  ...btnBase, 'text-[13px]', 'text-red-200',
  'bg-gradient-to-b', 'from-casio-clear-top', 'to-casio-clear',
  'shadow-[0_3px_0_var(--color-casio-clear-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-clear-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const btnDel = [
  ...btnBase, 'text-xs', 'text-[#f0d890]',
  'bg-gradient-to-b', 'from-casio-del-top', 'to-casio-del',
  'shadow-[0_3px_0_var(--color-casio-del-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-del-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const btnFn = [
  ...btnBase, 'text-[11px]', 'h-[36px]', 'text-[#a0cfed]',
  'bg-gradient-to-b', 'from-casio-fn-top', 'to-casio-fn',
  'shadow-[0_3px_0_var(--color-casio-fn-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-fn-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const btnTape = [
  ...btnBase, 'text-[11px]', 'tracking-[1px]', 'text-[#d4c0f0]',
  'bg-gradient-to-b', 'from-casio-tape-top', 'to-casio-tape',
  'shadow-[0_3px_0_var(--color-casio-tape-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
  'active:shadow-[0_1px_0_var(--color-casio-tape-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
]

const styles = { num: btnNum, op: btnOp, eq: btnEq, clear: btnClear, del: btnDel, fn: btnFn, tape: btnTape }
</script>
