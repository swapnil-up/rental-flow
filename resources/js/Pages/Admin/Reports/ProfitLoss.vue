<template>
    <AdminLayout>
        <div class="header">
            <h1>Profit &amp; Loss</h1>
            <div class="period-nav">
                <Link :href="`/admin/reports/profit-loss?period=${prev.period}&month=${prev.month}&year=${prev.year}`" class="nav-link">&larr; Prev</Link>
                <select v-model="localPeriod" @change="navigate">
                    <option value="month">Monthly</option>
                    <option value="quarter">Quarterly</option>
                    <option value="year">Yearly</option>
                </select>
                <strong class="period-label">{{ periodLabel }}</strong>
                <Link :href="`/admin/reports/profit-loss?period=${next.period}&month=${next.month}&year=${next.year}`" class="nav-link">Next &rarr;</Link>
            </div>
        </div>

        <div class="summary-cards">
            <div class="card income">
                <span class="card-label">Income</span>
                <span class="card-value">${{ formatMoney(totals.income) }}</span>
            </div>
            <div class="card expense">
                <span class="card-label">Expenses</span>
                <span class="card-value">${{ formatMoney(totals.expenses) }}</span>
            </div>
            <div class="card" :class="totals.net >= 0 ? 'positive' : 'negative'">
                <span class="card-label">Net</span>
                <span class="card-value">${{ formatMoney(totals.net) }}</span>
            </div>
            <div class="card" :class="totals.income > 0 ? 'positive' : 'negative'">
                <span class="card-label">Margin</span>
                <span class="card-value">{{ margin }}%</span>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th class="num">Income</th>
                    <th class="num">Expenses</th>
                    <th class="num">Net</th>
                    <th class="num">Margin</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in rows" :key="r.property_id">
                    <td>{{ r.property_name }}</td>
                    <td class="num">${{ formatMoney(r.income) }}</td>
                    <td class="num">${{ formatMoney(r.expenses) }}</td>
                    <td class="num" :class="r.net >= 0 ? 'positive' : 'negative'">${{ formatMoney(r.net) }}</td>
                    <td class="num" :class="r.income > 0 ? '' : 'negative'">{{ r.income > 0 ? ((r.net / r.income) * 100).toFixed(1) : '—' }}%</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td class="num"><strong>${{ formatMoney(totals.income) }}</strong></td>
                    <td class="num"><strong>${{ formatMoney(totals.expenses) }}</strong></td>
                    <td class="num" :class="totals.net >= 0 ? 'positive' : 'negative'"><strong>${{ formatMoney(totals.net) }}</strong></td>
                    <td class="num"><strong>{{ margin }}%</strong></td>
                </tr>
            </tfoot>
        </table>

        <p v-if="rows.length === 0" class="empty">No data for this period.</p>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    rows: Array,
    totals: Object,
    period: String,
    month: Number,
    year: Number,
    periodLabel: String,
    prev: Object,
    next: Object,
});

const localPeriod = ref(props.period);

const formatMoney = (cents) => (cents / 100).toFixed(2);

const margin = computed(() => {
    if (props.totals.income <= 0) return '—';
    return ((props.totals.net / props.totals.income) * 100).toFixed(1);
});

const navigate = () => {
    router.get('/admin/reports/profit-loss', { period: localPeriod.value, month: props.month, year: props.year }, { preserveState: true, replace: true });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
h1 { margin: 0; }
.period-nav { display: flex; align-items: center; gap: 10px; }
.period-nav select { padding: 6px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.period-label { font-size: 16px; }
.nav-link { color: #3490dc; text-decoration: none; font-size: 14px; }
.nav-link:hover { text-decoration: underline; }

.summary-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 30px; }
.card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; display: flex; flex-direction: column; }
.card-label { font-size: 14px; color: #6b7280; margin-bottom: 4px; }
.card-value { font-size: 24px; font-weight: 700; }
.card.income .card-value { color: #10b981; }
.card.expense .card-value { color: #ef4444; }
.card.positive .card-value { color: #10b981; }
.card.negative .card-value { color: #ef4444; }

.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.num { text-align: right; }
.positive { color: #10b981; }
.negative { color: #ef4444; }
.total-row td { border-top: 2px solid #333; }
.empty { padding: 40px; text-align: center; color: #9ca3af; }
</style>
