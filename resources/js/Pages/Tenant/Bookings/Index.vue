<template>
    <TenantLayout>
        <div class="header">
            <h1>My Bookings</h1>
        </div>

        <EmptyState v-if="bookings.length === 0" title="No bookings yet" description="Your bookings will appear here once they are created." />

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="b in bookings" :key="b.id">
                    <td>{{ b.property_name }}</td>
                    <td>{{ b.check_in }}</td>
                    <td>{{ b.check_out }}</td>
                    <td>${{ formatMoney(b.total_amount) }}</td>
                    <td>
                        <span class="badge" :style="{ backgroundColor: getColor(b.status_color) }">{{ b.status_label }}</span>
                    </td>
                    <td>
                        <button v-if="b.can_cancel" @click="cancel(b.id)" class="btn-cancel">Cancel</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </TenantLayout>
</template>

<script setup>
import EmptyState from '@/Components/EmptyState.vue';
import TenantLayout from '@/Layouts/TenantLayout.vue';
import { router } from '@inertiajs/vue3';

defineProps({ bookings: Array });

const formatMoney = (c) => (c / 100).toFixed(2);

const getColor = (c) => ({ orange: '#f59e0b', blue: '#3b82f6', green: '#10b981', gray: '#6b7280', red: '#ef4444' }[c] || '#6b7280');

const cancel = (id) => {
    if (!confirm('Cancel this booking?')) return;
    router.post(`/bookings/${id}/cancel`);
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; font-size: 24px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f7fafc; font-weight: 600; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.btn-cancel { padding: 4px 12px; background: #e3342f; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
.btn-cancel:hover { background: #cc1f1a; }
</style>
