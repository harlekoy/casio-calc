<template>
  <div class="flex items-start justify-center relative min-h-screen bg-[#0d0d0f]">
    <!-- Calculator Body -->
    <div
      class="w-[340px] mt-4 bg-casio-body rounded-t-[18px] rounded-b-[24px] p-5 px-4 pb-6 border border-[#3a3a3c] relative z-10"
      style="box-shadow: 0 2px 0 0 #444, 0 4px 0 0 #222, 0 8px 30px rgba(0,0,0,0.7), inset 0 1px 0 rgba(255,255,255,0.05)"
    >
      <!-- Brand Header -->
      <div class="text-center mb-3">
        <div class="h-[3px] rounded-sm mb-2" style="background: linear-gradient(90deg, transparent, var(--color-casio-gold), transparent)"></div>
        <div class="flex items-baseline justify-center gap-2.5">
          <span class="font-display font-black text-[22px] text-casio-gold tracking-[4px]" style="text-shadow: 0 1px 2px rgba(0,0,0,0.5)">CASIO</span>
          <span class="font-sans text-[11px] text-[#888] tracking-[2px] font-light">CalcTek FX-991</span>
        </div>
        <div class="font-sans text-[8px] text-[#666] tracking-[3px] uppercase mt-0.5">SCIENTIFIC CALCULATOR</div>
      </div>

      <!-- Solar Panel -->
      <div class="flex gap-[3px] justify-center mx-auto my-2.5 mb-3.5 px-3 py-1 bg-[#1a1a1c] rounded w-fit">
        <div
          v-for="n in 4"
          :key="n"
          class="w-10 h-2.5 border border-[#0a0a1a] rounded-[1px]"
          style="background: linear-gradient(180deg, #1a1a3a 0%, #2a2a4a 40%, #1a1a3a 100%)"
        ></div>
      </div>

      <!-- LCD Screen -->
      <div class="bg-casio-bezel rounded-lg p-1.5 mx-1 mb-4 border border-[#111]" style="box-shadow: inset 0 2px 6px rgba(0,0,0,0.5)">
        <div
          class="bg-casio-screen rounded flex flex-col justify-end px-3.5 py-3 relative overflow-hidden"
          style="box-shadow: inset 0 1px 3px rgba(0,0,0,0.2)"
        >
          <!-- Screen glare -->
          <div class="absolute top-0 left-0 right-0 h-[40%] pointer-events-none z-10" style="background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, transparent 100%)"></div>

          <!-- Ticker Tape (inside screen) -->
          <transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-[200px]"
            leave-from-class="opacity-100 max-h-[200px]"
            leave-to-class="opacity-0 max-h-0"
          >
            <div v-if="showTape" class="overflow-hidden">
              <!-- Tape Header -->
              <div class="flex items-center justify-between mb-1">
                <span class="font-display text-[7px] font-bold text-casio-screen-text/50 tracking-[2px]">TAPE</span>
                <div class="flex gap-1.5 items-center">
                  <button
                    @click="clearAllHistory"
                    :disabled="!history.length"
                    class="bg-transparent border border-casio-screen-text/20 text-casio-screen-text/50 font-sans text-[7px] tracking-[1px] px-1.5 py-[1px] rounded-[2px] cursor-pointer transition-all duration-150 hover:enabled:border-casio-screen-text/50 hover:enabled:text-casio-screen-text/80 disabled:opacity-30 disabled:cursor-not-allowed"
                  >
                    CLEAR ALL
                  </button>
                  <button @click="toggleTape" class="bg-transparent border-none text-casio-screen-text/40 text-sm cursor-pointer leading-none hover:text-casio-screen-text/80">
                    &times;
                  </button>
                </div>
              </div>

              <!-- Tape Entries -->
              <div ref="tapeRoll" class="max-h-[160px] overflow-y-auto space-y-0">
                <div v-if="!history.length" class="text-center text-casio-screen-text/40 text-[10px] py-3 italic">
                  No calculations yet.
                </div>
                <div v-for="item in history" :key="item.id" class="py-0.5">
                  <div class="flex items-center justify-end gap-1.5">
                    <span class="font-mono text-[11px] text-casio-screen-text/60 text-right flex-1">{{ item.expression }} =</span>
                    <span class="font-mono text-[12px] text-casio-screen-text font-bold">{{ item.result }}</span>
                    <button
                      @click="deleteEntry(item.id)"
                      class="bg-transparent border-none text-casio-screen-text/25 text-[11px] cursor-pointer px-0 leading-none transition-colors duration-150 hover:text-casio-screen-text/70"
                    >
                      &times;
                    </button>
                  </div>
                  <div class="border-b border-dashed border-casio-screen-text/10"></div>
                </div>
              </div>

              <!-- Separator between tape and input -->
              <div class="border-b border-casio-screen-text/20 my-1.5"></div>
            </div>
          </transition>

          <!-- Current expression -->
          <div class="font-mono text-sm text-casio-screen-text opacity-70 text-right min-h-[20px] break-all leading-tight">
            {{ displayExpression || '&nbsp;' }}
          </div>
          <div class="font-mono text-[32px] text-casio-screen-text text-right leading-tight break-all">
            {{ displayResult }}
          </div>
        </div>
      </div>

      <!-- Button Grid -->
      <div class="px-1 space-y-1.5">
        <!-- Scientific toggle -->
        <button
          @click="showScientific = !showScientific"
          class="w-full h-[22px] rounded-md border-none cursor-pointer font-sans text-[9px] font-bold tracking-[2px] uppercase transition-all duration-200"
          :class="showScientific
            ? 'bg-[#2a3a4a] text-[#a0cfed] shadow-[inset_0_1px_3px_rgba(0,0,0,0.3)]'
            : 'bg-[#1e1e22] text-[#666] hover:text-[#888]'"
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
          <div v-show="showScientific" class="space-y-1.5 overflow-hidden">
            <!-- Trig functions -->
            <div class="flex gap-1.5">
              <button @click="appendToExpression('sin(')" :class="btnFn">sin</button>
              <button @click="appendToExpression('cos(')" :class="btnFn">cos</button>
              <button @click="appendToExpression('tan(')" :class="btnFn">tan</button>
              <button @click="appendToExpression('asin(')" :class="btnFn">sin<sup>-1</sup></button>
              <button @click="appendToExpression('acos(')" :class="btnFn">cos<sup>-1</sup></button>
            </div>

            <!-- Log, exp, abs -->
            <div class="flex gap-1.5">
              <button @click="appendToExpression('atan(')" :class="btnFn">tan<sup>-1</sup></button>
              <button @click="appendToExpression('ln(')" :class="btnFn">ln</button>
              <button @click="appendToExpression('log10(')" :class="btnFn">log</button>
              <button @click="appendToExpression('exp(')" :class="btnFn">e<sup>x</sup></button>
              <button @click="appendToExpression('abs(')" :class="btnFn">|x|</button>
            </div>

            <!-- Power, roots, constants -->
            <div class="flex gap-1.5">
              <button @click="appendToExpression('sqrt(')" :class="btnFn">&radic;</button>
              <button @click="appendToExpression('^')" :class="btnFn">x<sup>y</sup></button>
              <button @click="appendToExpression('!')" :class="btnFn">x!</button>
              <button @click="appendToExpression('pi')" :class="btnFn">&pi;</button>
              <button @click="appendToExpression('e')" :class="btnFn">e</button>
            </div>

            <!-- Parens, rounding -->
            <div class="flex gap-1.5">
              <button @click="appendToExpression('(')" :class="btnFn">(</button>
              <button @click="appendToExpression(')')" :class="btnFn">)</button>
              <button @click="appendToExpression('floor(')" :class="btnFn">&lfloor;x&rfloor;</button>
              <button @click="appendToExpression('ceil(')" :class="btnFn">&lceil;x&rceil;</button>
              <button @click="clearEntry" :class="btnClear">CE</button>
            </div>
          </div>
        </transition>

        <!-- Numeric rows -->
        <div class="flex gap-1.5">
          <button @click="appendToExpression('7')" :class="btnNum">7</button>
          <button @click="appendToExpression('8')" :class="btnNum">8</button>
          <button @click="appendToExpression('9')" :class="btnNum">9</button>
          <button @click="appendToExpression('/')" :class="btnOp">&divide;</button>
          <button @click="allClear" :class="btnClear">AC</button>
        </div>

        <div class="flex gap-1.5">
          <button @click="appendToExpression('4')" :class="btnNum">4</button>
          <button @click="appendToExpression('5')" :class="btnNum">5</button>
          <button @click="appendToExpression('6')" :class="btnNum">6</button>
          <button @click="appendToExpression('*')" :class="btnOp">&times;</button>
          <button @click="backspace" :class="btnDel">DEL</button>
        </div>

        <div class="flex gap-1.5">
          <button @click="appendToExpression('1')" :class="btnNum">1</button>
          <button @click="appendToExpression('2')" :class="btnNum">2</button>
          <button @click="appendToExpression('3')" :class="btnNum">3</button>
          <button @click="appendToExpression('-')" :class="btnOp">&minus;</button>
          <button @click="calculate" :disabled="!expression" :class="btnEq">=</button>
        </div>

        <div class="flex gap-1.5">
          <button @click="appendToExpression('0')" :class="[...btnNum, 'flex-[2.1]']">0</button>
          <button @click="appendToExpression('.')" :class="btnNum">.</button>
          <button @click="appendToExpression('+')" :class="btnOp">+</button>
          <button @click="toggleTape" :class="btnTape">TAPE</button>
        </div>
      </div>

      <!-- Error message -->
      <div v-if="error" class="bg-casio-clear-shadow text-red-200 text-center text-xs px-2 py-1.5 rounded mt-2 font-mono">
        {{ error }}
      </div>
    </div>

    <!-- Floating Bottom Sheet -->
    <div class="fixed bottom-0 left-0 right-0 z-50 transition-transform duration-300 ease-out" :class="showPanel ? 'translate-y-0' : 'translate-y-full'">
      <div class="max-w-2xl mx-auto bg-[#131315] border border-b-0 border-[#2a2a2c] rounded-t-xl shadow-[0_-4px_30px_rgba(0,0,0,0.6)]">
        <!-- Drag handle -->
        <div class="flex justify-center pt-2 pb-1">
          <div class="w-10 h-1 rounded-full bg-[#333]"></div>
        </div>

        <!-- Tabs + Close -->
        <div class="flex items-center justify-between px-5 pb-2">
          <div class="flex gap-4">
            <button
              @click="panelTab = 'guide'"
              class="bg-transparent border-none text-[10px] font-bold tracking-[2px] uppercase cursor-pointer transition-colors duration-150 pb-1"
              :class="panelTab === 'guide' ? 'text-casio-gold border-b border-casio-gold' : 'text-[#555] hover:text-[#888]'"
            >How to Use</button>
            <button
              @click="panelTab = 'cheatsheet'"
              class="bg-transparent border-none text-[10px] font-bold tracking-[2px] uppercase cursor-pointer transition-colors duration-150 pb-1"
              :class="panelTab === 'cheatsheet' ? 'text-casio-gold border-b border-casio-gold' : 'text-[#555] hover:text-[#888]'"
            >Cheatsheet</button>
          </div>
          <button
            @click="showPanel = false"
            class="bg-transparent border-none text-[#555] text-lg cursor-pointer leading-none hover:text-[#aaa] transition-colors duration-150"
          >&times;</button>
        </div>

        <!-- Tab Content -->
        <div class="px-5 pb-4 max-h-[50vh] overflow-y-auto">

          <!-- Guide Tab -->
          <div v-if="panelTab === 'guide'" class="space-y-3">
            <div v-for="tip in tips" :key="tip.title">
              <div class="flex items-start gap-2.5 py-1.5">
                <span class="text-base leading-none mt-0.5">{{ tip.icon }}</span>
                <div>
                  <div class="text-[11px] font-bold text-[#ccc]">{{ tip.title }}</div>
                  <div class="text-[10px] text-[#777] mt-0.5 leading-relaxed">{{ tip.desc }}</div>
                  <div v-if="tip.keys" class="flex flex-wrap gap-1 mt-1.5">
                    <span
                      v-for="key in tip.keys"
                      :key="key"
                      class="text-[9px] font-mono bg-[#1e1e22] text-[#999] border border-[#333] rounded px-1.5 py-0.5"
                    >{{ key }}</span>
                  </div>
                </div>
              </div>
              <div class="border-b border-[#1e1e22]"></div>
            </div>
          </div>

          <!-- Cheatsheet Tab -->
          <div v-if="panelTab === 'cheatsheet'" class="space-y-3">
            <div v-for="section in cheatsheet" :key="section.title">
              <div class="text-[9px] font-bold tracking-[2px] uppercase mb-1.5" :class="section.color">{{ section.title }}</div>
              <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
                <div v-for="item in section.items" :key="item.expr" class="flex justify-between font-mono text-[11px]">
                  <span
                    class="text-[#999] cursor-pointer hover:text-white transition-colors duration-150"
                    @click="pasteExpression(item.expr)"
                    :title="'Click to paste'"
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
      @click="showPanel = !showPanel"
      class="fixed bottom-4 right-4 z-40 w-11 h-11 rounded-full border border-[#3a3a3c] bg-[#1e1e22] text-casio-gold text-lg cursor-pointer shadow-[0_2px_12px_rgba(0,0,0,0.5)] transition-all duration-200 hover:bg-[#2a2a2e] hover:scale-105 active:scale-95"
      :title="showPanel ? 'Hide panel' : 'Help & Cheatsheet'"
    >?</button>

  </div>
</template>

<script>
import calculatorApi from './api';
import { useClipboard } from '@vueuse/core';
import { debounce, find, some, filter, includes, get } from 'lodash-es';

const btnBase = [
  'flex-1', 'h-[46px]', 'border-none', 'rounded-md', 'font-sans', 'font-bold',
  'cursor-pointer', 'relative', 'select-none', 'active:translate-y-[2px]',
  'transition-transform', 'duration-75',
];

export default {
  name: 'CasioCalculator',
  setup() {
    const { text: clipboardText, isSupported: clipboardSupported } = useClipboard({ read: true, legacy: true });
    return { clipboardText, clipboardSupported };
  },
  data() {
    return {
      expression: '',
      result: '0',
      history: [],
      showTape: false,
      showScientific: false,
      showPanel: false,
      panelTab: 'guide',
      error: null,
      loading: false,
      keyBuffer: '',
      tips: [
        {
          icon: '\u2328\uFE0F',
          title: 'Keyboard Input',
          desc: 'Type numbers and operators directly. Use Enter to calculate, Backspace to delete, and Escape to clear all.',
          keys: ['0-9', '+ - * / .', 'Enter', 'Backspace', 'Esc'],
        },
        {
          icon: '\uD83D\uDCCB',
          title: 'Paste Expressions',
          desc: 'Paste any math expression with Ctrl+V (or Cmd+V on Mac). Supports complex expressions like sqrt((9*9)/12).',
          keys: ['Ctrl+V', 'Cmd+V'],
        },
        {
          icon: '\uD83D\uDD24',
          title: 'Type Function Names',
          desc: 'Type function names directly on your keyboard — sqrt, sin, cos, tan, ln, exp, abs, log10, floor, ceil, pi, and more. Recognized within 700ms.',
          keys: ['sqrt', 'sin', 'cos', 'tan', 'ln', 'exp', 'abs', 'pi'],
        },
        {
          icon: '\uD83D\uDD2C',
          title: 'Scientific Mode',
          desc: 'Toggle the SCIENTIFIC bar to access trig, logarithms, exponents, constants, rounding, and more.',
        },
        {
          icon: '\uD83D\uDCDC',
          title: 'Calculation Tape',
          desc: 'Press TAPE to view your full calculation history. Delete individual entries or clear all at once.',
        },
        {
          icon: '\uD83C\uDF10',
          title: 'Session per Browser',
          desc: 'Each browser keeps its own session automatically. Your history stays separate across different browsers.',
        },
        {
          icon: '\u21A9\uFE0F',
          title: 'Chain from Result',
          desc: 'After a calculation, press any operator (+, -, etc.) to continue from the previous result.',
        },
      ],
      cheatsheet: [
        {
          title: 'Trigonometry',
          color: 'text-[#a0cfed]',
          items: [
            { expr: 'sin(pi/2)', result: '= 1' },
            { expr: 'cos(pi)', result: '= -1' },
            { expr: 'tan(pi/4)', result: '= 1' },
            { expr: 'asin(1)', result: '= 1.5708' },
          ],
        },
        {
          title: 'Logarithms & Exponentials',
          color: 'text-[#c8e6c2]',
          items: [
            { expr: 'ln(e)', result: '= 1' },
            { expr: 'log10(1000)', result: '= 3' },
            { expr: 'exp(2)', result: '= 7.389' },
            { expr: 'ln(100)/ln(10)', result: '= 2' },
          ],
        },
        {
          title: 'Power & Roots',
          color: 'text-[#f0d890]',
          items: [
            { expr: 'sqrt(144)', result: '= 12' },
            { expr: '2^8', result: '= 256' },
            { expr: '5!', result: '= 120' },
            { expr: 'sqrt(2)^2', result: '= 2' },
          ],
        },
        {
          title: 'Rounding & Misc',
          color: 'text-[#d4c0f0]',
          items: [
            { expr: 'abs(-99.5)', result: '= 99.5' },
            { expr: 'floor(9.9)', result: '= 9' },
            { expr: 'ceil(0.1)', result: '= 1' },
            { expr: 'ceil(pi)*2', result: '= 8' },
          ],
        },
        {
          title: 'Combined',
          color: 'text-casio-gold',
          items: [
            { expr: 'sin(pi/6)+cos(pi/3)', result: '= 1' },
            { expr: 'abs(floor(-3.7))', result: '= 4' },
            { expr: 'exp(ln(5))+3!', result: '= 11' },
            { expr: 'log10(2^10)', result: '= 3.01' },
          ],
        },
      ],
      btnNum: [
        ...btnBase, 'text-base', 'text-[#e5e5e7]',
        'bg-gradient-to-b', 'from-casio-num-top', 'to-casio-num',
        'shadow-[0_3px_0_var(--color-casio-num-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-num-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
      btnOp: [
        ...btnBase, 'text-xl', 'text-[#c8e6c2]',
        'bg-gradient-to-b', 'from-casio-op-top', 'to-casio-op',
        'shadow-[0_3px_0_var(--color-casio-op-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-op-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
      btnEq: [
        ...btnBase, 'text-xl', 'text-white',
        'bg-gradient-to-b', 'from-casio-eq-top', 'to-casio-eq',
        'shadow-[0_3px_0_var(--color-casio-eq-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-eq-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
        'disabled:opacity-50', 'disabled:cursor-not-allowed',
      ],
      btnClear: [
        ...btnBase, 'text-[13px]', 'text-red-200',
        'bg-gradient-to-b', 'from-casio-clear-top', 'to-casio-clear',
        'shadow-[0_3px_0_var(--color-casio-clear-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-clear-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
      btnDel: [
        ...btnBase, 'text-xs', 'text-[#f0d890]',
        'bg-gradient-to-b', 'from-casio-del-top', 'to-casio-del',
        'shadow-[0_3px_0_var(--color-casio-del-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-del-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
      btnFn: [
        ...btnBase, 'text-[11px]', 'h-[36px]', 'text-[#a0cfed]',
        'bg-gradient-to-b', 'from-casio-fn-top', 'to-casio-fn',
        'shadow-[0_3px_0_var(--color-casio-fn-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-fn-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
      btnTape: [
        ...btnBase, 'text-[11px]', 'tracking-[1px]', 'text-[#d4c0f0]',
        'bg-gradient-to-b', 'from-casio-tape-top', 'to-casio-tape',
        'shadow-[0_3px_0_var(--color-casio-tape-shadow),0_4px_6px_rgba(0,0,0,0.3)]',
        'active:shadow-[0_1px_0_var(--color-casio-tape-shadow),0_2px_3px_rgba(0,0,0,0.3)]',
      ],
    };
  },
  computed: {
    displayExpression() {
      return this.expression
        .replace(/\*/g, '\u00D7')
        .replace(/\//g, '\u00F7')
        .replace(/sqrt\(/g, '\u221A(')
        .replace(/pi/g, '\u03C0')
        .replace(/log10\(/g, 'log\u2081\u2080(');
    },
    displayResult() {
      return this.result;
    },
  },
  created() {
    this.debouncedFlush = debounce(this.flushKeyBuffer, 700);
  },
  mounted() {
    this.fetchHistory();
    window.addEventListener('keydown', this.handleKeyboard);
    window.addEventListener('paste', this.handlePaste);
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.handleKeyboard);
    window.removeEventListener('paste', this.handlePaste);
  },
  methods: {
    appendToExpression(val) {
      this.error = null;
      const operators = ['+', '-', '*', '/', '^'];
      if (this.expression === '' && this.result !== '0' && includes(operators, val)) {
        this.expression = this.result;
      }
      this.expression += val;
    },
    backspace() {
      this.error = null;
      this.expression = this.expression.slice(0, -1);
    },
    clearEntry() {
      this.error = null;
      this.expression = '';
    },
    allClear() {
      this.error = null;
      this.expression = '';
      this.result = '0';
    },
    async calculate() {
      if (!this.expression || this.loading) return;
      this.loading = true;
      this.error = null;

      try {
        const response = await calculatorApi.calculate(this.expression);
        this.result = response.data.result;
        this.expression = '';
        this.history.unshift(response.data);
      } catch (err) {
        this.error = get(err, 'response.data.error', 'Calculation error');
      } finally {
        this.loading = false;
      }
    },
    async fetchHistory() {
      try {
        const response = await calculatorApi.getCalculations();
        this.history = response.data;
      } catch {
        // silent fail on load
      }
    },
    async deleteEntry(id) {
      try {
        await calculatorApi.deleteCalculation(id);
        this.history = filter(this.history, (h) => h.id !== id);
      } catch {
        this.error = 'Failed to delete';
      }
    },
    async clearAllHistory() {
      try {
        await calculatorApi.clearAll();
        this.history = [];
      } catch {
        this.error = 'Failed to clear history';
      }
    },
    pasteExpression(expr) {
      this.error = null;
      this.expression = expr;
    },
    toggleTape() {
      this.showTape = !this.showTape;
    },
    handlePaste(e) {
      e.preventDefault();
      const text = this.clipboardText || (e.clipboardData || window.clipboardData)?.getData('text') || '';
      const sanitized = text.replace(/[^0-9a-z+\-*/().^!]/gi, '');
      if (sanitized) {
        this.error = null;
        this.expression += sanitized;
      }
    },
    flushKeyBuffer() {
      if (this.keyBuffer) {
        // Anything remaining in the buffer that wasn't matched as a function
        // gets appended as individual characters
        this.expression += this.keyBuffer;
        this.keyBuffer = '';
      }
    },
    handleKeyboard(e) {
      const key = e.key;

      // Function keywords that can be typed (longest-first for greedy match)
      const fnKeywords = [
        { word: 'log10', output: 'log10(' },
        { word: 'floor', output: 'floor(' },
        { word: 'sqrt', output: 'sqrt(' },
        { word: 'asin', output: 'asin(' },
        { word: 'acos', output: 'acos(' },
        { word: 'atan', output: 'atan(' },
        { word: 'ceil', output: 'ceil(' },
        { word: 'sinh', output: 'sinh(' },
        { word: 'cosh', output: 'cosh(' },
        { word: 'tanh', output: 'tanh(' },
        { word: 'sin', output: 'sin(' },
        { word: 'cos', output: 'cos(' },
        { word: 'tan', output: 'tan(' },
        { word: 'abs', output: 'abs(' },
        { word: 'exp', output: 'exp(' },
        { word: 'ln', output: 'ln(' },
        { word: 'pi', output: 'pi' },
      ];

      if (key.length === 1 && /[a-z]/i.test(key)) {
        e.preventDefault();
        this.error = null;
        this.keyBuffer += key.toLowerCase();

        this.debouncedFlush.cancel();

        // Check for a complete match
        const match = find(fnKeywords, fn => fn.word === this.keyBuffer);
        if (match) {
          this.keyBuffer = '';
          this.appendToExpression(match.output);
          return;
        }

        // Check if buffer is a prefix of any keyword
        const isPrefix = some(fnKeywords, fn => fn.word.startsWith(this.keyBuffer));
        if (isPrefix) {
          // Wait for more keys
          this.debouncedFlush();
          return;
        }

        // Not a prefix of anything — flush
        this.flushKeyBuffer();
        return;
      }

      // Non-letter key — flush buffer first
      if (this.keyBuffer) {
        this.flushKeyBuffer();
        this.debouncedFlush.cancel();
      }

      if (includes('0123456789', key)) {
        this.appendToExpression(key);
      } else if (includes(['+', '-', '*', '/', '.', '(', ')', '!'], key)) {
        this.appendToExpression(key);
      } else if (key === '^') {
        this.appendToExpression('^');
      } else if (key === 'Enter') {
        e.preventDefault();
        this.calculate();
      } else if (key === 'Backspace') {
        this.backspace();
      } else if (key === 'Escape') {
        this.allClear();
      }
    },
  },
};
</script>
