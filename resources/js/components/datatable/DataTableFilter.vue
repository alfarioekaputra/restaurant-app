<template>
  <div class="flex items-center space-x-2">
    <div v-if="showSearch" class="flex-1">
      <Input
        v-model="searchQuery"
        :placeholder="searchPlaceholder"
        class="w-[150px] md:w-[200px]"
        @update:modelValue="handleSearch"
      />
    </div>
    
    <Select
      v-if="options.length > 0"
      v-model="selectedValue"
      @update:modelValue="handleSelect"
    >
      <SelectTrigger :class="[className, 'w-[150px] md:w-[200px]']">
        <SelectValue :placeholder="placeholder" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem value="0">All {{ label }}s</SelectItem>
        <SelectItem
          v-for="option in options"
          :key="option.id"
          :value="String(option.id)"
        >
          {{ option.name }}
        </SelectItem>
      </SelectContent>
    </Select>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Input } from '@/components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

interface Option {
  id: number | string
  name: string
}

interface Props {
  options: Option[]
  modelValue?: string
  label?: string
  placeholder?: string
  className?: string
  showSearch?: boolean
  searchPlaceholder?: string
}

const props = withDefaults(defineProps<Props>(), {
  options: () => [],
  modelValue: '',
  label: '',
  placeholder: 'Select...',
  className: '',
  showSearch: false,
  searchPlaceholder: 'Search...',
})

const emit = defineEmits(['update:modelValue', 'filter'])

const selectedValue = ref(props.modelValue)
const searchQuery = ref('')

watch(() => props.modelValue, (newValue) => {
  selectedValue.value = newValue
})

const handleSelect = (value: string) => {
  selectedValue.value = value
  emit('update:modelValue', value)
  emit('filter', {
    value,
    type: 'select',
    search: searchQuery.value
  })
}

const handleSearch = (value: string) => {
  searchQuery.value = value
  emit('filter', {
    value: selectedValue.value,
    type: 'search',
    search: value
  })
}
</script>
