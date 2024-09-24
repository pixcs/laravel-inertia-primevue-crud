<script setup>
  import { ref, defineProps } from 'vue';
  import Button from 'primevue/button';
  import Nav from "../Shared/Nav.vue"; 

  defineProps({
    numbersAndSymbols: Array
  });

  // Reactive variable to hold the current value
  let currentValue = ref('');

  const handleClick = (value) => {

    if (value == "Clear") {
      currentValue.value = "";
    }
    else if (value != "=") {
      currentValue.value += value;
      console.log(currentValue.value);
    }
    else {
      try {
        currentValue.value = eval(currentValue.value);
      } catch (err) {
        currentValue.value = 'Syntax Error';
      }
    }
  }

</script>

<template>
  <Nav></Nav>
  <div class="max-w-96 mx-auto mt-64 grid grid-cols-4 items-center gap-2 border-2 border-slate-700 p-4">
    <h1 class="col-span-4 !bg-zinc-800 p-4">{{ currentValue }}</h1>
    <Button 
      v-for="numAndSymbol of numbersAndSymbols" 
      :key="numAndSymbol" 
      v-text="numAndSymbol"
      @click="handleClick(numAndSymbol)" 
    ></Button>
  </div>
</template>