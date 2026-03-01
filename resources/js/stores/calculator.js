import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useClipboard } from '@vueuse/core'
import { debounce, find, some, includes } from 'lodash-es'
import calculatorApi from '../api'

export const useCalculatorStore = defineStore('calculator', () => {
  const { text: clipboardText } = useClipboard({ read: true, legacy: true })

  const expression = ref('')
  const result = ref('0')
  const history = ref([])
  const showTape = ref(false)
  const showScientific = ref(false)
  const showPanel = ref(false)
  const error = ref(null)
  const loading = ref(false)
  const keyBuffer = ref('')

  function appendToExpression(val) {
    error.value = null
    const operators = ['+', '-', '*', '/', '^']
    if (expression.value === '' && result.value !== '0' && includes(operators, val)) {
      expression.value = result.value
    }
    expression.value += val
  }

  function backspace() {
    error.value = null
    expression.value = expression.value.slice(0, -1)
  }

  function clearEntry() {
    error.value = null
    expression.value = ''
  }

  function allClear() {
    error.value = null
    expression.value = ''
    result.value = '0'
  }

  async function calculate() {
    if (!expression.value || loading.value) return
    loading.value = true
    error.value = null

    try {
      const response = await calculatorApi.calculate(expression.value)
      result.value = response.data.result
      expression.value = ''
      history.value.unshift(response.data)
    } catch (err) {
      error.value = err?.response?.data?.error || 'Calculation error'
    } finally {
      loading.value = false
    }
  }

  async function fetchHistory() {
    try {
      const response = await calculatorApi.getCalculations()
      history.value = response.data
    } catch {
      // silent fail on load
    }
  }

  async function deleteEntry(id) {
    try {
      await calculatorApi.deleteCalculation(id)
      history.value = history.value.filter((h) => h.id !== id)
    } catch {
      error.value = 'Failed to delete'
    }
  }

  async function clearAllHistory() {
    try {
      await calculatorApi.clearAll()
      history.value = []
    } catch {
      error.value = 'Failed to clear history'
    }
  }

  function pasteExpression(expr) {
    error.value = null
    expression.value = expr
  }

  function toggleTape() {
    showTape.value = !showTape.value
  }

  function handlePaste(e) {
    e.preventDefault()
    const text =
      clipboardText.value || (e.clipboardData || window.clipboardData)?.getData('text') || ''
    const sanitized = text.replace(/[^0-9a-z+\-*/().^!]/gi, '')
    if (sanitized) {
      error.value = null
      expression.value += sanitized
    }
  }

  function flushKeyBuffer() {
    if (keyBuffer.value) {
      expression.value += keyBuffer.value
      keyBuffer.value = ''
    }
  }

  const debouncedFlush = debounce(flushKeyBuffer, 700)

  function handleKeyboard(e) {
    const key = e.key

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
    ]

    if (key.length === 1 && /[a-z]/i.test(key)) {
      e.preventDefault()
      error.value = null
      keyBuffer.value += key.toLowerCase()

      debouncedFlush.cancel()

      const match = find(fnKeywords, (fn) => fn.word === keyBuffer.value)
      if (match) {
        keyBuffer.value = ''
        appendToExpression(match.output)
        return
      }

      const isPrefix = some(fnKeywords, (fn) => fn.word.startsWith(keyBuffer.value))
      if (isPrefix) {
        debouncedFlush()
        return
      }

      flushKeyBuffer()
      return
    }

    if (keyBuffer.value) {
      flushKeyBuffer()
      debouncedFlush.cancel()
    }

    if (includes('0123456789', key)) {
      appendToExpression(key)
    } else if (includes(['+', '-', '*', '/', '.', '(', ')', '!'], key)) {
      appendToExpression(key)
    } else if (key === '^') {
      appendToExpression('^')
    } else if (key === 'Enter') {
      e.preventDefault()
      calculate()
    } else if (key === 'Backspace') {
      backspace()
    } else if (key === 'Escape') {
      allClear()
    }
  }

  function initListeners() {
    window.addEventListener('keydown', handleKeyboard)
    window.addEventListener('paste', handlePaste)
  }

  function destroyListeners() {
    window.removeEventListener('keydown', handleKeyboard)
    window.removeEventListener('paste', handlePaste)
  }

  return {
    expression,
    result,
    history,
    showTape,
    showScientific,
    showPanel,
    error,
    loading,
    keyBuffer,

    appendToExpression,
    backspace,
    clearEntry,
    allClear,
    calculate,
    fetchHistory,
    deleteEntry,
    clearAllHistory,
    pasteExpression,
    toggleTape,
    handlePaste,
    flushKeyBuffer,
    handleKeyboard,
    initListeners,
    destroyListeners,
  }
})
