<template>
    <TenantLayout>
        <h1>My Payments</h1>

        <EmptyState v-if="payments.length === 0" title="No payments yet" description="Your payment history will appear here." />

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Due</th>
                    <th>Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in payments" :key="p.id">
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
                </tr>
            </tbody>
        </table>
    </TenantLayout>
</template>

<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import TenantLayout from '@/Layouts/TenantLayout.vue';

defineProps({ payments: Array });

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatMoney = (c) => (c / 100).toFixed(2);
const getColor = (c) => ({ orange: '#f59e0b', green: '#10b981', red: '#ef4444', gray: '#6b7280' }[c] || '#6b7280');
</script>

<style scoped>
h1 { margin: 0 0 20px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
</style>
