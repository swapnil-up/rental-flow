<template>
    <AdminLayout>
        <div class="header">
            <h1>Properties</h1>
            <Link href="/admin/properties/create" class="btn">
                Create Property
            </Link>
        </div>

        <div v-if="properties.data.length === 0" class="empty-state">
            <p>No properties yet. 
                <Link href="/admin/properties/create">Create one!</Link>
            </p>
        </div>

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
                    <td>
                        <Link 
                            :href="`/admin/properties/${property.id}`"
                            class="link"
                        >
                            View
                        </Link>
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
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    properties: Object,
});

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
const formatMoney = (cents) => (cents / 100).toFixed(2);
</script>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
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

.empty-state {
    padding: 40px;
    text-align: center;
    background: #f7fafc;
    border-radius: 8px;
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
</style>