<template>
    <AdminLayout>
        <div class="header">
            <h1>Properties</h1>
            <Link href="/admin/properties/create" class="btn">
                Create Property
            </Link>
        </div>

        <div class="filters">
            <input v-model="filters.search" placeholder="Search name, city, address..." @input="applyFilters" class="search-input" />
            <input v-model="filters.city" placeholder="City" @input="applyFilters" />
            <select v-model="filters.type" @change="applyFilters">
                <option value="">All Types</option>
                <option value="apartment">Apartment</option>
                <option value="house">House</option>
                <option value="condo">Condo</option>
                <option value="studio">Studio</option>
            </select>
            <select v-model="filters.status" @change="applyFilters">
                <option value="">All Statuses</option>
                <option v-for="s in statuses" :key="s" :value="s">{{ capitalize(s) }}</option>
            </select>
            <select v-model="filters.bedrooms" @change="applyFilters">
                <option value="">Any Bedrooms</option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
            </select>
            <input v-model="filters.min_price" type="number" placeholder="Min Price ($)" @input="applyFilters" />
            <input v-model="filters.max_price" type="number" placeholder="Max Price ($)" @input="applyFilters" />
            <button @click="clearFilters" class="btn-clear">Clear</button>
        </div>

        <EmptyState v-if="properties.data.length === 0" title="No properties found" description="Create your first property to start managing rentals.">
            <Link href="/admin/properties/create" class="btn">Create Property</Link>
        </EmptyState>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Bedrooms</th>
                    <th>Monthly Cost</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="property in properties.data" :key="property.id">
                    <td>{{ property.name }}</td>
                    <td>{{ capitalize(property.type) }}</td>
                    <td>{{ property.city }}, {{ property.state }}</td>
                    <td>{{ property.bedrooms }}</td>
                    <td>${{ formatMoney(property.total_monthly_cost) }}</td>
                    <td>{{ capitalize(property.status) }}</td>
                    <td class="actions-cell">
                        <Link :href="`/admin/properties/${property.id}`" class="link">View</Link>
                        <Link :href="`/admin/properties/${property.id}/edit`" class="link">Edit</Link>
                        <button @click="destroy(property.id)" class="link link-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="properties.links.length > 3" class="pagination">
            <Link
                v-for="link in properties.links"
                :key="link.label"
                :href="link.url"
                :class="{ active: link.active, disabled: !link.url }"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>

<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    properties: Object,
    filters: Object,
    statuses: Array,
});

const filters = reactive({
    search: props.filters?.search || '',
    city: props.filters?.city || '',
    type: props.filters?.type || '',
    status: props.filters?.status || '',
    bedrooms: props.filters?.bedrooms || '',
    min_price: props.filters?.min_price || '',
    max_price: props.filters?.max_price || '',
});

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
const formatMoney = (cents) => (cents / 100).toFixed(2);

const destroy = (id) => {
    if (!confirm('Are you sure you want to delete this property?')) return;
    router.delete(`/admin/properties/${id}`);
};

const applyFilters = () => {
    router.get('/admin/properties', filters, { preserveState: true, replace: true });
};

const clearFilters = () => {
    Object.assign(filters, { search: '', city: '', type: '', status: '', bedrooms: '', min_price: '', max_price: '' });
    router.get('/admin/properties', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filters input,
.filters select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.search-input {
    min-width: 280px;
}

.btn-clear {
    padding: 8px 12px;
    background: #6b7280;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-clear:hover {
    background: #4b5563;
}

h1 {
    margin: 0;
}

.btn {
    padding: 8px 16px;
    background: #3490dc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
}

.btn:hover {
    background: #2779bd;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background: #f7fafc;
    font-weight: 600;
}

.link {
    color: #3490dc;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.pagination {
    display: flex;
    gap: 5px;
    margin-top: 20px;
}

.pagination a {
    padding: 8px 12px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #3490dc;
    border-radius: 4px;
}

.pagination a.active {
    background: #3490dc;
    color: white;
    border-color: #3490dc;
}

.pagination a.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.actions-cell {
    display: flex;
    gap: 12px;
    align-items: center;
}

.link-danger {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    font-size: inherit;
    font-family: inherit;
}

.link-danger:hover {
    color: #e3342f;
}
</style>