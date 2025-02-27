<script setup lang="ts" generic="TData, TValue">
import type { ColumnDef } from '@tanstack/vue-table'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  Pagination,
  PaginationEllipsis,
  PaginationNext,
  PaginationPrev,
} from '@/components/ui/pagination'
import { Input } from '@/components/ui/input'
import { Search } from 'lucide-vue-next'
import { router } from '@inertiajs/vue3'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import DataTableFilter from './DataTableFilter.vue'

import {
  FlexRender,
  getCoreRowModel,
  useVueTable,
} from '@tanstack/vue-table'

import { ref, watch } from 'vue'
import { Button } from '@/components/ui/button'

const props = withDefaults(defineProps<{
  columns: ColumnDef<TData, TValue>[]
  meta: {
    data: TData[]
    current_page: number
    from: number
    last_page: number
    links: any[]
    path: string
    per_page: number
    to: number
    total: number
  }
  filters: {
    search: string
    per_page: number
    filter?: string
  }
  routeName: string
  showFilter?: boolean
  filterOptions?: Array<{ id: number | string; name: string }>
  filterLabel?: string
  filterPlaceholder?: string
}>(), {
  showFilter: false,
  filterOptions: () => [],
  filterLabel: '',
  filterPlaceholder: 'Select...',
})

const emit = defineEmits(['update:filters'])

const searchQuery = ref(props.filters.search)
const perPage = ref(props.filters.per_page)

// Watch for changes in search and perPage
watch([searchQuery, perPage], ([newSearch, newPerPage]) => {
  emit('update:filters', {
    search: newSearch,
    per_page: newPerPage
  })
  
  router.get(
    route(props.routeName), 
    { 
      search: newSearch, 
      per_page: newPerPage 
    }, 
    { 
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
  )
}, { deep: true })

const table = useVueTable({
  get data() { return props.meta.data },
  get columns() { return props.columns },
  getCoreRowModel: getCoreRowModel(),
})

// Handle page change
const goToPage = (page: number) => {
  router.get(
    route(props.routeName), 
    { 
      page: page,
      search: searchQuery.value, 
      per_page: perPage.value 
    }, 
    { 
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
  )
}

// Handle search
const handleSearch = () => {
  router.get(
    route(props.routeName), 
    { 
      search: searchQuery.value, 
      per_page: perPage.value 
    }, 
    { 
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
  )
}

const selectedFilter = ref('')

// Handle filter
const handleFilter = (filterData: { value: string; type: string; search: string }) => {
  const newFilters = {
    search: searchQuery.value,
    per_page: perPage.value,
    filter: selectedFilter.value
  }
  
  if (filterData.type === 'select') {
    newFilters.filter = filterData.value
  }
  
  if (filterData.type === 'search') {
    newFilters.search = filterData.search
  }
  
  // Reset to first page when filtering
  router.get(
    route(props.routeName),
    { ...newFilters, page: 1 },
    { 
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
  )
}
</script>

<template>
  <div class="space-y-4">
    <!-- Search and per page controls -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <div class="relative w-64">
          <Input
            v-model="searchQuery"
            placeholder="Search..."
            class="pl-8"
            @keyup.enter="handleSearch"
          />
          <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
        </div>
        <DataTableFilter
          v-if="showFilter"
          v-model="selectedFilter"
          :options="filterOptions"
          :label="filterLabel"
          :placeholder="filterPlaceholder"
          @filter="handleFilter"
        />
      </div>
      
      <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-500">Rows per page:</span>
        <Select v-model="perPage">
          <SelectTrigger class="w-20 h-9">
            <SelectValue :placeholder="perPage.toString()" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem :value="5">5</SelectItem>
            <SelectItem :value="10">10</SelectItem>
            <SelectItem :value="25">25</SelectItem>
            <SelectItem :value="50">50</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>
    
    <!-- Table -->
    <div class="border rounded-md">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead v-for="column in table.getAllColumns()" :key="column.id">
              {{ column.columnDef.header?.() }}
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="props.meta.data.length">
            <TableRow
              v-for="row in table.getRowModel().rows"
              :key="row.id"
            >
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender
                  :render="cell.column.columnDef.cell"
                  :props="cell.getContext()"
                />
              </TableCell>
            </TableRow>
          </template>
          <template v-else>
            <TableRow>
              <TableCell :colspan="table.getAllColumns().length" class="h-24 text-center">
                No results.
              </TableCell>
            </TableRow>
          </template>
        </TableBody>
      </Table>
      
      <!-- Pagination -->
      <div class="flex items-center justify-between p-4">
        <div class="text-sm text-gray-500">
          Showing {{ meta.from || 0 }} to {{ meta.to || 0 }} of {{ meta.total }} entries
        </div>
        <Pagination v-if="meta.last_page > 1">
          <PaginationList class="flex items-center gap-1">
            <PaginationPrev 
            :disabled="meta.current_page === 1"
            @click="meta.current_page > 1 && goToPage(meta.current_page - 1)" 
          />
            <template v-for="page in meta.links.slice(1, -1)" :key="page.label">
              <PaginationEllipsis v-if="page.label === '...'" />
              <Button 
                v-else
                class="w-9 h-9 p-0 rounded-md flex items-center justify-center"
                :class="page.active ? 'bg-primary text-primary-foreground' : 'bg-background text-white hover:bg-accent hover:text-accent-foreground'"
                @click="goToPage(page.label)"
              >
                {{ page.label }}
              </Button>
            </template>

            <PaginationNext 
            :disabled="meta.current_page === meta.last_page"
            @click="meta.current_page < meta.last_page && goToPage(meta.current_page + 1)" 
          />
            
          </PaginationList>
        </Pagination>
      </div>
    </div>
  </div>
</template>