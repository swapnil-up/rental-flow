<template>
    <AdminLayout>
        <div class="header">
            <h1>Maintenance Requests</h1>
        </div>

        <div class="filters">
            <select v-model="filters.status" @change="applyFilters">
                <option value="">All Statuses</option>
                <option v-for="s in statuses" :key="s.value" :value="s.value">{{ capitalize(s.value) }}</option>
            </select>
            <select v-model="filters.priority" @change="applyFilters">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="emergency">Emergency</option>
            </select>
            <select v-model="filters.property_id" @change="applyFilters">
                <option value="">All Properties</option>
                <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <button @click="clearFilters" class="btn-clear">Clear</button>
        </div>

        <div v-if="requests.data.length === 0" class="empty-state">
            <p>No maintenance requests.</p>
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Property</th>
                    <th>Tenant</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in requests.data" :key="r.id">
                    <td>{{ r.title }}</td>
                    <td>{{ r.property_name }}</td>
                    <td>{{ r.tenant_name || '—' }}</td>
                    <td><span class="priority" :class="r.priority">{{ capitalize(r.priority) }}</span></td>
                    <td>
                        <span class="badge" :style="{ backgroundColor: getColor(r.status_color) }">
                            {{ r.status_label }}
                        </span>
                    </td>
                    <td>{{ r.created_at }}</td>
                    <td><Link :href="`/admin/maintenance/${r.id}`" class="link">View</Link></td>
                </tr>
            </tbody>
        </table>

        <div v-if="requests.links.length > 3" class="pagination">
            <Link v-for="link in requests.links" :key="link.label" :href="link.url"
                  :class="{ active: link.active, disabled: !link.url }" v-html="link.label" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({ requests: Object, filters: Object, statuses: Array, properties: Array });

const filters = reactive({
    status: props.filters?.status || '',
    priority: props.filters?.priority || '',
    property_id: props.filters?.property_id || '',
});

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const getColor = (c) => ({ red: '#ef4444', orange: '#f59e0b', blue: '#3b82f6', green: '#10b981' }[c] || '#6b7280');

const applyFilters = () => router.get('/admin/maintenance', filters, { preserveState: true, replace: true });
const clearFilters = () => {
    Object.assign(filters, { status: '', priority: '', property_id: '' });
    router.get('/admin/maintenance', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.filters select, .filters input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.btn-clear { padding: 8px 12px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-clear:hover { background: #4b5563; }
.empty-state { padding: 40px; text-align: center; background: #f7fafc; border-radius: 8px; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.priority { font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
.priority.low { background: #d1fae5; color: #065f46; }
.priority.medium { background: #fef3c7; color: #92400e; }
.priority.high { background: #fee2e2; color: #991b1b; }
.priority.emergency { background: #fce4ec; color: #b71c1c; font-weight: 700; }
.pagination { display: flex; gap: 5px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #3490dc; border-radius: 4px; }
.pagination a.active { background: #3490dc; color: white; border-color: #3490dc; }
.pagination a.disabled { opacity: .5; pointer-events: none; }
</style>
