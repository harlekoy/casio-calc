<template>
  <div class="flex gap-[3px] justify-center mx-auto my-2.5 mb-3.5 px-3 py-1 bg-[#1a1a1c] rounded w-fit">
    <div
      v-for="n in 4"
      :key="n"
      class="w-10 h-2.5 border border-[#0a0a1a] rounded-[1px]"
      style="background: linear-gradient(180deg, #1a1a3a 0%, #2a2a4a 40%, #1a1a3a 100%)"
    />
  </div>
</template>
