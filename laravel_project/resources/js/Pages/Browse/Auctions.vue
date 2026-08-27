<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default { layout: AppLayout };
</script>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuctionCard from '@/Components/AuctionCard.vue';
import { useInfiniteScroll } from '@/composables/useInfiniteScroll';

const props = defineProps({ auctions: Object, categories: Array, filters: Object, now: String });

const searchTerm = ref(props.filters.q || '');
const selectedCat = ref(props.filters.category || '');
const selectedStatus = ref(props.filters.status || '');
const currentSort = ref(props.filters.sort || 'bids');
const minPrice = ref(props.filters.min_price || '');
const maxPrice = ref(props.filters.max_price || '');
let searchTimer = null;
let priceTimer = null;

const sortOptions = [
    { v: 'bids', label: 'Popüler', icon: 'bi-fire' },
    { v: 'ending', label: 'Bitmek Üzere', icon: 'bi-clock' },
    { v: 'new', label: 'Yeni', icon: 'bi-stars' },
    { v: 'price', label: 'Fiyat ↓', icon: 'bi-sort-down' },
    { v: 'price_asc', label: 'Fiyat ↑', icon: 'bi-sort-up' },
];

const catName = computed(() => {
    const c = props.categories.find(x => x.slug === selectedCat.value);
    return c ? c.name : '';
});
const hasActiveFilters = computed(() =>
    !!(searchTerm.value.trim() || selectedCat.value || selectedStatus.value || minPrice.value || maxPrice.value)
);

function feedParams() {
    const params = {};
    if (searchTerm.value.trim()) params.q = searchTerm.value.trim();
    if (selectedCat.value) params.category = selectedCat.value;
    if (selectedStatus.value) params.status = selectedStatus.value;
    if (currentSort.value && currentSort.value !== 'bids') params.sort = currentSort.value;
    if (minPrice.value) params.min_price = minPrice.value;
    if (maxPrice.value) params.max_price = maxPrice.value;
    return params;
}

const { items, hasMore, loading, sentinel, reset } = useInfiniteScroll(
    () => route('browse.auctions.feed'),
    props.auctions,
    feedParams
);

watch(() => props.auctions, (val) => reset(val));

function applyFilters() {
    router.get(route('browse.auctions'), feedParams(), { preserveScroll: true, preserveState: true });
}
function setSort(v) { currentSort.value = v; applyFilters(); }
function onSearchInput() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(applyFilters, 500);
}
function onPriceInput() {
    clearTimeout(priceTimer);
    priceTimer = setTimeout(applyFilters, 600);
}
function clearAll() {
    searchTerm.value = ''; selectedCat.value = ''; selectedStatus.value = '';
    minPrice.value = ''; maxPrice.value = ''; currentSort.value = 'bids';
    router.get(route('browse.auctions'), {}, { preserveScroll: true, preserveState: true });
}
function clearCat() { selectedCat.value = ''; applyFilters(); }
function clearStatus() { selectedStatus.value = ''; applyFilters(); }
function clearPrice() { minPrice.value = ''; maxPrice.value = ''; applyFilters(); }
function clearSearch() { searchTerm.value = ''; applyFilters(); }
</script>

<template>
    <Head title="Müzayedeler" />
    <div class="py-4">
        <div class="brz-filter" data-testid="browse-filter">
            <div class="brz-filter-row">
                <div class="brz-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Artırma ara..." autocomplete="off"
                           v-model="searchTerm" @input="onSearchInput" data-testid="browse-search-input">
                </div>
                <select class="brz-select" v-model="selectedCat" @change="applyFilters" data-testid="browse-category-select">
                    <option value="">Tüm Kategoriler</option>
                    <option v-for="cat in categories" :key="cat.slug" :value="cat.slug">
                        {{ cat.depth ? '— ' + cat.name : cat.name }}<span v-if="cat.auctions_count"> ({{ cat.auctions_count }})</span>
                    </option>
                </select>
                <select class="brz-select" v-model="selectedStatus" @change="applyFilters" data-testid="browse-status-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="active">Aktif</option>
                    <option value="ended">Bitti</option>
                </select>
                <div class="brz-price">
                    <input type="number" min="0" placeholder="Min ₺" v-model="minPrice" @input="onPriceInput" data-testid="browse-min-price">
                    <span>–</span>
                    <input type="number" min="0" placeholder="Max ₺" v-model="maxPrice" @input="onPriceInput" data-testid="browse-max-price">
                </div>
            </div>

            <div class="brz-sortbar">
                <button v-for="opt in sortOptions" :key="opt.v" class="brz-sort-btn"
                        :class="{ active: currentSort === opt.v }" @click="setSort(opt.v)"
                        :data-testid="`browse-sort-${opt.v}`">
                    <i class="bi" :class="opt.icon"></i> {{ opt.label }}
                </button>
                <div class="brz-count"><strong>{{ auctions.total }}</strong> sonuç</div>
            </div>

            <div v-if="hasActiveFilters" class="brz-chips" data-testid="browse-active-chips">
                <span v-if="searchTerm.trim()" class="brz-chip">"{{ searchTerm }}" <button @click="clearSearch"><i class="bi bi-x"></i></button></span>
                <span v-if="selectedCat" class="brz-chip">{{ catName }} <button @click="clearCat"><i class="bi bi-x"></i></button></span>
                <span v-if="selectedStatus" class="brz-chip">{{ selectedStatus === 'active' ? 'Aktif' : 'Bitti' }} <button @click="clearStatus"><i class="bi bi-x"></i></button></span>
                <span v-if="minPrice || maxPrice" class="brz-chip">{{ minPrice || 0 }}₺ – {{ maxPrice || '∞' }}₺ <button @click="clearPrice"><i class="bi bi-x"></i></button></span>
                <button class="brz-chip-clear" @click="clearAll" data-testid="browse-clear-all"><i class="bi bi-arrow-counterclockwise"></i> Filtreleri Temizle</button>
            </div>
        </div>

        <div class="brz-head">
            <div class="brz-head-title"><i class="bi bi-grid"></i> Müzayedeler</div>
            <div class="brz-head-date">{{ now }} itibarıyla</div>
        </div>

        <div class="row g-3" id="auction-grid">
            <div v-for="auction in items" :key="auction.id" class="col-xl-3 col-lg-4 col-md-6 auction-item">
                <AuctionCard :auction="auction" />
            </div>
            <div v-if="!items.length" class="idx-empty">
                <i class="bi bi-inbox"></i>
                <p>Filtrelerinizle eşleşen artırma bulunamadı.</p>
            </div>
        </div>

        <div ref="sentinel" class="brz-infinite" data-testid="auctions-sentinel">
            <div v-if="loading" class="brz-loader" data-testid="auctions-loading">
                <span class="brz-spinner"></span> Yükleniyor…
            </div>
            <div v-else-if="!hasMore && items.length" class="brz-end">Tüm sonuçlar gösterildi</div>
        </div>
    </div>
</template>
