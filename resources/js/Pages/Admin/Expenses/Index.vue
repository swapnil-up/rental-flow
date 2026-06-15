<template>
    <AdminLayout>
        <div class="header">
            <h1>Expenses</h1>
            <Link href="/admin/expenses/create" class="btn">Record Expense</Link>
        </div>

        <div class="filters">
            <select v-model="filters.property_id" @change="applyFilters">
                <option value="">All Properties</option>
                <option v-for="p in properties" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <select v-model="filters.category" @change="applyFilters">
                <option value="">All Categories</option>
                <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.label }}</option>
            </select>
            <input v-model="filters.from" type="date" @change="applyFilters" />
            <input v-model="filters.to" type="date" @change="applyFilters" />
            <button @click="clearFilters" class="btn-clear">Clear</button>
        </div>

        <EmptyState v-if="expenses.data.length === 0" title="No expenses recorded" description="Record your first expense to start tracking costs." />

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Property</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="e in expenses.data" :key="e.id">
                    <td>{{ e.date }}</td>
                    <td>{{ e.property_name }}</td>
                    <td><span class="badge">{{ e.category_label }}</span></td>
                    <td>{{ e.description || '—' }}</td>
                    <td>${{ formatMoney(e.amount) }}</td>
                    <td class="actions-cell">
                        <Link :href="`/admin/expenses/${e.id}/edit`" class="link">Edit</Link>
                        <button @click="destroy(e.id)" class="link link-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>${{ formatMoney(total) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div v-if="expenses.links && expenses.links.length > 3" class="pagination">
            <Link v-for="link in expenses.links" :key="link.label" :href="link.url" :class="{ active: link.active, disabled: !link.url }" v-html="link.label" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import EmptyState from '@/Components/EmptyState.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    expenses: Object,
    filters: Object,
    categories: Array,
    properties: Array,
});

const filters = reactive({
    property_id: props.filters?.property_id || '',
    category: props.filters?.category || '',
    from: props.filters?.from || '',
    to: props.filters?.to || '',
});

const total = computed(() => props.expenses.data.reduce((sum, e) => sum + e.amount, 0));

const formatMoney = (cents) => (cents / 100).toFixed(2);

const destroy = (id) => {
    if (!confirm('Delete this expense?')) return;
    router.delete(`/admin/expenses/${id}`);
};

const applyFilters = () => {
    router.get('/admin/expenses', filters, { preserveState: true, replace: true });
};

const clearFilters = () => {
    Object.assign(filters, { property_id: '', category: '', from: '', to: '' });
    router.get('/admin/expenses', {}, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; }
.btn { padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; border-radius: 4px; }
.btn:hover { background: #2779bd; }
.filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.filters input, .filters select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.btn-clear { padding: 8px 12px; background: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn-clear:hover { background: #4b5563; }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.total-row td { border-top: 2px solid #333; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; background: #e5e7eb; color: #374151; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.link-danger { background: none; border: none; cursor: pointer; padding: 0; font-size: inherit; font-family: inherit; color: #3490dc; }
.link-danger:hover { color: #e3342f; }
.actions-cell { display: flex; gap: 12px; align-items: center; }
.pagination { display: flex; gap: 5px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #3490dc; border-radius: 4px; }
.pagination a.active { background: #3490dc; color: white; border-color: #3490dc; }
.pagination a.disabled { opacity: 0.5; pointer-events: none; }
</style>
