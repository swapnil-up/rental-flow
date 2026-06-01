<template>
    <AdminLayout>
        <h1>Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Properties</span>
                <span class="stat-value">{{ stats.total_properties }}</span>
                <span class="stat-sub">{{ stats.occupancy_rate }}% occupied</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Active Bookings</span>
                <span class="stat-value">{{ stats.active_bookings }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Pending Maintenance</span>
                <span class="stat-value">{{ stats.pending_maintenance }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Monthly Revenue</span>
                <span class="stat-value">${{ formatMoney(stats.monthly_revenue) }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h3>Upcoming Check-Ins</h3>
                <table v-if="upcomingCheckIns.length > 0" class="table">
                    <thead>
                        <tr><th>Property</th><th>Tenant</th><th>Date</th><th></th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="b in upcomingCheckIns" :key="b.id">
                            <td>{{ b.property_name }}</td>
                            <td>{{ b.tenant_name || '—' }}</td>
                            <td>{{ b.check_in }}</td>
                            <td><Link :href="`/admin/bookings/${b.id}`" class="link">View</Link></td>
                        </tr>
                    </tbody>
                </table>
                <p v-else class="empty-small">No upcoming check-ins this week.</p>
            </div>

            <div class="col">
                <h3>Upcoming Check-Outs</h3>
                <table v-if="upcomingCheckOuts.length > 0" class="table">
                    <thead>
                        <tr><th>Property</th><th>Tenant</th><th>Date</th><th></th></tr>
                    </thead>
                    <tbody>
                        <tr v-for="b in upcomingCheckOuts" :key="b.id">
                            <td>{{ b.property_name }}</td>
                            <td>{{ b.tenant_name || '—' }}</td>
                            <td>{{ b.check_out }}</td>
                            <td><Link :href="`/admin/bookings/${b.id}`" class="link">View</Link></td>
                        </tr>
                    </tbody>
                </table>
                <p v-else class="empty-small">No upcoming check-outs this week.</p>
            </div>
        </div>

        <div class="section">
            <h3>Recent Payments</h3>
            <table v-if="recentPayments.length > 0" class="table">
                <thead>
                    <tr><th>Tenant</th><th>Amount</th><th>Type</th><th>Status</th><th>Due</th><th></th></tr>
                </thead>
                <tbody>
                    <tr v-for="p in recentPayments" :key="p.id">
                        <td>{{ p.tenant_name || '—' }}</td>
                        <td>${{ formatMoney(p.amount) }}</td>
                        <td>{{ capitalize(p.type) }}</td>
                        <td>
                            <span class="badge" :style="{ backgroundColor: getColor(p.status_color) }">{{ p.status_label }}</span>
                        </td>
                        <td>{{ p.due_date }}</td>
                        <td><Link :href="`/admin/payments/${p.id}`" class="link">View</Link></td>
                    </tr>
                </tbody>
            </table>
            <p v-else class="empty-small">No payments yet.</p>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ stats: Object, upcomingCheckIns: Array, upcomingCheckOuts: Array, recentPayments: Array });

const formatMoney = (c) => (c / 100).toFixed(2);
const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const getColor = (c) => ({ orange: '#f59e0b', green: '#10b981', red: '#ef4444', gray: '#6b7280', blue: '#3b82f6' }[c] || '#6b7280');
</script>

<style scoped>
h1 { margin: 0 0 20px; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px; }
.stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; display: flex; flex-direction: column; }
.stat-label { font-size: 14px; color: #6b7280; margin-bottom: 4px; }
.stat-value { font-size: 28px; font-weight: 700; }
.stat-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }
.row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
.section { margin-bottom: 30px; }
h3 { margin: 0 0 10px; font-size: 16px; }
.table { width: 100%; border-collapse: collapse; font-size: 14px; }
.table th, .table td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
.table th { background: #f9fafb; font-weight: 600; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; color: white; }
.empty-small { color: #9ca3af; font-size: 14px; padding: 20px 0; }
</style>
