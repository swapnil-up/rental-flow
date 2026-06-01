<template>
    <AdminLayout>
        <div class="header">
            <h1>Payments</h1>
        </div>

        <div class="filters">
            <select v-model="filters.status" @change="applyFilters">
                <option value="">All Statuses</option>
                <option v-for="s in statuses" :key="s.value" :value="s.value">{{ capitalize(s.value) }}</option>
            </select>
            <select v-model="filters.type" @change="applyFilters">
                <option value="">All Types</option>
                <option value="rent">Rent</option>
                <option value="deposit">Deposit</option>
                <option value="fee">Fee</option>
            </select>
            <select v-model="filters.tenant_id" @change="applyFilters">
                <option value="">All Tenants</option>
                <option v-for="t in tenants" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
            <button @click="clearFilters" class="btn-clear">Clear</button>
        </div>

        <div v-if="payments.data.length === 0" class="empty-state">
            <p>No payments.</p>
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Property</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th>Paid</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in payments.data" :key="p.id">
                    <td>{{ p.tenant_name || '—' }}</td>
                    <td>{{ p.property_name }}</td>
                    <td>${{ formatMoney(p.amount) }}</td>
                    <td>{{ capitalize(p.type) }}</td>
                    <td>
                        <span class="badge" :style="{ backgroundColor: getColor(p.status_color) }">
                            {{ p.status_label }}
                        </span>
                    </td>
                    <td>{{ p.due_date }}</td>
                    <td>{{ p.paid_at || '—' }}</td>
                    <td><Link :href="`/admin/payments/${p.id}`" class="link">View</Link></td>
                </tr>
            </tbody>
        </table>

        <div v-if="payments.links.length > 3" class="pagination">
            <Link v-for="link in payments.links" :key="link.label" :href="link.url"
                  :class="{ active: link.active, disabled: !link.url }" v-html="link.label" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({ payments: Object, filters: Object, statuses: Array, tenants: Array });

const filters = reactive({
    status: props.filters?.status || '',
    type: props.filters?.type || '',
    tenant_id: props.filters?.tenant_id || '',
});

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatMoney = (c) => (c / 100).toFixed(2);
const getColor = (c) => ({ orange: '#f59e0b', green: '#10b981', red: '#ef4444', gray: '#6b7280' }[c] || '#6b7280');

const applyFilters = () => router.get('/admin/payments', filters, { preserveState: true, replace: true });
const clearFilters = () => {
    Object.assign(filters, { status: '', type: '', tenant_id: '' });
    router.get('/admin/payments', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.filters select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.btn-clear { padding: 8px 12px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-clear:hover { background: #4b5563; }
.empty-state { padding: 40px; text-align: center; background: #f7fafc; border-radius: 8px; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.pagination { display: flex; gap: 5px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #3490dc; border-radius: 4px; }
.pagination a.active { background: #3490dc; color: white; border-color: #3490dc; }
.pagination a.disabled { opacity: .5; pointer-events: none; }
</style>
