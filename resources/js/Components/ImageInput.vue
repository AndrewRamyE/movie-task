<script setup>
import { ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);


const input = ref(null);
</script>

<template>
 <label :for="$refs.input" class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center rounded-xl border-2 border-dashed border-blue-400 bg-white p-6 text-center">

    <input ref="input" type="file" placeholder="choose Image"
    :value="modelValue"
    @change="$emit('update:modelValue', $event.target.files[0])" />
  </label>
</template>
