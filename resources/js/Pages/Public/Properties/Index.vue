<template>
    <PublicLayout>
        <div class="page-header">
            <h1>Available Properties</h1>
            <input v-model="search" placeholder="Search by name or city..." @input="applySearch" class="search-input" />
        </div>

        <EmptyState v-if="properties.data.length === 0" title="No properties available" description="Check back soon for new listings!" />

        <div v-else class="grid">
            <div v-for="p in properties.data" :key="p.id" class="card">
                <div class="card-body">
                    <h3>{{ p.name }}</h3>
                    <p class="type">{{ capitalize(p.type) }} &middot; {{ p.city }}, {{ p.state }}</p>
                    <p class="details">{{ p.bedrooms }} bed &middot; {{ p.bathrooms }} bath &middot; {{ p.square_feet }} sq ft</p>
                    <p class="price">${{ formatMoney(p.monthly_rent) }}/mo</p>
                    <Link :href="`/properties/${p.id}`" class="btn">View Details</Link>
                </div>
            </div>
        </div>

        <div v-if="properties.links && properties.links.length > 3" class="pagination">
            <Link v-for="link in properties.links" :key="link.label" :href="link.url" :class="{ active: link.active, disabled: !link.url }" v-html="link.label" />
        </div>
    </PublicLayout>
</template>

<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import { ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ properties: Object, filters: Object });

const search = ref(props.filters?.search || '');

const applySearch = () => {
    router.get('/properties', { search: search.value }, { preserveState: true, replace: true });
};

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatMoney = (c) => (c / 100).toFixed(2);
</script>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 10px; }
h1 { margin: 0; font-size: 24px; }
.search-input { padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-width: 260px; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
.card { background: white; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: box-shadow 0.2s; }
.card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.card-body { padding: 20px; }
.card-body h3 { margin: 0 0 6px; font-size: 16px; }
.type { color: #6b7280; font-size: 13px; margin: 0 0 8px; }
.details { color: #374151; font-size: 13px; margin: 0 0 12px; }
.price { font-size: 20px; font-weight: 700; color: #059669; margin: 0 0 16px; }
.btn { display: inline-block; padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; }
.btn:hover { background: #2779bd; }
.pagination { display: flex; gap: 5px; margin-top: 30px; justify-content: center; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #3490dc; border-radius: 4px; }
.pagination a.active { background: #3490dc; color: white; border-color: #3490dc; }
.pagination a.disabled { opacity: 0.5; pointer-events: none; }
</style>
