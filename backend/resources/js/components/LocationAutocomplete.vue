<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Cari lokasi...'
    },
    id: {
        type: String,
        default: 'location-input'
    }
});

const emit = defineEmits(['update:modelValue', 'select']);

const query = ref(props.modelValue);
const results = ref([]);
const isSearching = ref(false);
const showDropdown = ref(false);
const debounceTimeout = ref(null);
const searchContainer = ref(null);

// Sync external changes
watch(() => props.modelValue, (newVal) => {
    if (newVal !== query.value) {
        query.value = newVal;
    }
});

const searchLocation = async () => {
    if (!query.value || query.value.length < 3) {
        results.value = [];
        showDropdown.value = false;
        return;
    }

    isSearching.value = true;
    showDropdown.value = true;

    try {
        const response = await axios.get('https://nominatim.openstreetmap.org/search', {
            params: {
                q: query.value,
                format: 'json',
                addressdetails: 1,
                limit: 5,
                countrycodes: 'id'
            }
        });
        
        results.value = response.data;
    } catch (error) {
        console.error('Error fetching locations:', error);
        results.value = [];
    } finally {
        isSearching.value = false;
    }
};

const handleInput = () => {
    emit('update:modelValue', query.value);
    
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value);
    }
    
    debounceTimeout.value = setTimeout(() => {
        searchLocation();
    }, 500);
};

const selectLocation = (result) => {
    query.value = result.display_name;
    showDropdown.value = false;
    
    emit('update:modelValue', query.value);
    emit('select', {
        name: result.display_name,
        lat: parseFloat(result.lat),
        lng: parseFloat(result.lon),
        raw: result
    });
};

const handleClickOutside = (event) => {
    if (searchContainer.value && !searchContainer.value.contains(event.target)) {
        showDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value);
    }
});
</script>

<template>
    <div class="relative w-full" ref="searchContainer">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-search text-sm"></i>
            </div>
            <input 
                :id="id"
                v-model="query" 
                @input="handleInput"
                @focus="query.length >= 3 && searchLocation()"
                type="text" 
                :placeholder="placeholder"
                autocomplete="off"
                class="w-full pl-10 pr-10 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" 
            />
            <div v-if="isSearching" class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-circle-notch fa-spin text-sm"></i>
            </div>
        </div>

        <!-- Dropdown -->
        <div v-if="showDropdown && (results.length > 0 || isSearching)" 
             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden max-h-60 overflow-y-auto">
            
            <div v-if="isSearching && results.length === 0" class="p-4 text-center text-sm text-gray-500">
                Mencari lokasi...
            </div>
            
            <ul v-else>
                <li v-for="(result, index) in results" :key="index" 
                    @click="selectLocation(result)"
                    class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors flex gap-3 items-start">
                    <i class="fas fa-map-marker-alt text-[#10207a] mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 line-clamp-1">{{ result.name || result.display_name.split(',')[0] }}</p>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ result.display_name }}</p>
                    </div>
                </li>
            </ul>
        </div>
        
        <div v-if="showDropdown && results.length === 0 && !isSearching && query.length >= 3" 
             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-center text-sm text-gray-500">
            Lokasi tidak ditemukan. Coba kata kunci lain.
        </div>
    </div>
</template>
