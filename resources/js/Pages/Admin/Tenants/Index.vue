<template>
    <AdminLayout>
        <div class="header">
            <h1>Tenants</h1>
            <Link href="/admin/tenants/create" class="btn">Create Tenant</Link>
        </div>

        <div class="filters">
            <input v-model="filters.search" placeholder="Search by name or email..." @input="applyFilters" />
            <button @click="clearFilters" class="btn-clear">Clear</button>
        </div>

        <EmptyState v-if="tenants.data.length === 0" title="No tenants yet" description="Add your first tenant to start managing leases.">
            <Link href="/admin/tenants/create" class="btn">Create Tenant</Link>
        </EmptyState>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Bookings</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tenant in tenants.data" :key="tenant.id">
                    <td>{{ tenant.name }}</td>
                    <td>{{ tenant.email }}</td>
                    <td>{{ tenant.phone || '—' }}</td>
                    <td>{{ tenant.bookings_count ?? 0 }}</td>
                    <td class="actions-cell">
                        <Link :href="`/admin/tenants/${tenant.id}`" class="link">View</Link>
                        <Link :href="`/admin/tenants/${tenant.id}/edit`" class="link">Edit</Link>
                        <button @click="destroy(tenant.id)" class="link link-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="tenants.links.length > 3" class="pagination">
            <Link
                v-for="link in tenants.links"
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
    tenants: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
});

const destroy = (id) => {
    if (!confirm('Are you sure you want to delete this tenant?')) return;
    router.delete(`/admin/tenants/${id}`);
};

const applyFilters = () => {
    router.get('/admin/tenants', filters, { preserveState: true, replace: true });
};

const clearFilters = () => {
    filters.search = '';
    router.get('/admin/tenants', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.btn { padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; border-radius: 4px; display: inline-block; border: none; cursor: pointer; font-size: 14px; }
.btn:hover { background: #2779bd; }
.filters { display: flex; gap: 10px; margin-bottom: 20px; }
.filters input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; min-width: 250px; }
.btn-clear { padding: 8px 12px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-clear:hover { background: #4b5563; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.actions-cell { display: flex; gap: 12px; align-items: center; }
.link-danger { background: none; border: none; cursor: pointer; padding: 0; font-size: inherit; font-family: inherit; }
.link-danger:hover { color: #e3342f; }
.pagination { display: flex; gap: 5px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #3490dc; border-radius: 4px; }
.pagination a.active { background: #3490dc; color: white; border-color: #3490dc; }
.pagination a.disabled { opacity: 0.5; pointer-events: none; }
</style>
